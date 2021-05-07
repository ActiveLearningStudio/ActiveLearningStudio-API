<?php

namespace App\Repositories\Admin\User;

use App\Exceptions\GeneralException;
use App\Exports\ArrayExport;
use App\Imports\UsersImport;
use App\Repositories\Admin\BaseRepository;
use App\Repositories\Admin\Project\ProjectRepository;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     * @param ProjectRepository $projectRepository
     */
    public function __construct(User $model, ProjectRepository $projectRepository)
    {
        $this->model = $model;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $startDate = $data['start_date'] ?? null;
        $endDate = $data['end_date'] ?? null;
        $this->setDtParams($data);

        $this->query = $this->model->when($data['q'] ?? null, function ($query) use ($data) {
            $query->search(['email', 'name'], $data['q']);
            $query->name($data['q']);
            return $query;
        })->when(is_valid_date($startDate) && is_valid_date($endDate), function ($query) use ($startDate, $endDate) {
            // apply date scope if valid date string
            return $query->dateBetween([$startDate, $endDate]);
        });

        return $this->getDtPaginated();
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            $data['remember_token'] = Str::random(64);
            $data['deleted_at'] = null; // if soft deleted user registers again
            $data['email_verified_at'] = now();
            // with trashed is added so soft-deletes records are also checked before creating new one
            if ($user = $this->model->withTrashed()->updateOrCreate(['email' => $data['email']], $data)) {
                // no need to fire these events as user was not created recently, it was update request
                if ($user->wasRecentlyCreated !== true) {
                    event(new Registered($user));
                }
                $user->created_at = now();
                $user->save();
                return ['message' => 'User created successfully!', 'data' => $user];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create user, please try again later!');
    }

    /**
     * @param $id
     * @param $data
     * @param $clone_project_id
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data, $clone_project_id)
    {
        $user = $this->find($id);
        try {
            // update the user data
            if ($user->update($data) && $clone_project_id) {
                // loop through user organizations
                foreach ($user->organizations as $userOrganization) {
                    // if clone project id provided - clone the project
                    $this->projectRepository->clone($user, $clone_project_id, $userOrganization->id);
                }
                return ['message' => 'User data updated and project is being cloned in background!', 'data' => $this->find($id)];
            }
            return ['message' => 'User data updated successfully!', 'data' => $this->find($id)];
        } catch (\Exception $e) {
            Log::critical('Clone Project ID: ' . $clone_project_id);
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update user, please try again later!');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($user = $this->model->whereId($id)->with('projects')->first()) {
            return $user;
        }
        throw new GeneralException('User not found.');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        if ((int)$id === auth()->id()) {
            throw new GeneralException('You cannot delete your own user');
        }
        try {
            $this->find($id)->delete();
            return 'User Deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete user, please try again later!');
    }

    /**
     * Users basic report, projects, playlists and activities count
     * @param $data
     * @return mixed
     */
    public function reportBasic($data)
    {
        $this->setDtParams($data);
        $this->query = $this->model->select(['id', 'first_name', 'last_name', 'email'])->withCount(['projects', 'playlists', 'activities'])
            ->when($data['mode'] === 'subscribed', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('subscribed', true);
                });
            });
        return $this->getDtPaginated();
    }

    /**
     * @param $data
     * @return array
     * @throws GeneralException|\Throwable
     */
    public function bulkImport($data)
    {
        try {
            $import = new UsersImport();
            $import->import($data['import_file']);

            // if none of the records was inserted, could be empty file or bad formatted
            throw_if(!$import->importedCount && !$import->failures()->count(), new GeneralException('Empty or bad formatted file.'));

            $error = $this->bulkError($import);

            return [
                'report' => $error ? custom_url('storage/temporary/users-import-report.csv') : false,
                'message' => $error ? 'Failed to import some rows data, please download detailed error report.' : 'All users data imported successfully!'
            ];
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Empty or bad formatted file, please download sample file for proper format.');
    }

    /**
     * @param $import
     * @return bool|PendingDispatch
     */
    protected function bulkError($import)
    {
        $failures = $import->failures();
        $errors = [];
        $report = false;
        // check if any validation failures then create CSV FILE for report
        if (count($failures)) {
            foreach ($failures as $failure) {
                $row = (int)$failure->row();
                // if row values are already added then no need to override
                if (!isset($errors[$row])) {
                    $errors[$row] = $failure->values();
                    $errors[$row]['status'] = 'failed';
                }
                // append old errors by pipeline separator for same row
                $row_error = implode(' | ', $failure->errors());
                $errors[$row]['reason'] = isset($errors[$row]['reason']) ? $errors[$row]['reason'] . ' | ' . $row_error : $row_error;
            }
            $report = (new ArrayExport($errors, array_keys(reset($errors))))->store('public/temporary/users-import-report.csv');
        }
        $errors = $import->errors();
        // if any database error occurred
        if (count($errors)) {
            Log::error("USER IMPORT DATABASE ERRORS: ");
            Log::error(implode(", ", $errors));
        }
        return $report;
    }

    /**
     * @param $user
     * @param $role
     * @return string
     * @throws GeneralException
     */
    public function updateRole($user, $role): string
    {
        if ($user->id === auth()->id()) {
            throw new GeneralException('You cannot change the role of yourself.');
        }
        $role = $role ? 'admin' : null;
        $user->update(['role' => $role]);
        return "User role is changed successfully!";
    }
}
