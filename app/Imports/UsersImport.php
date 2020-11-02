<?php

namespace App\Imports;

use App\Models\OrganizationType;
use App\Rules\StrongPassword;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithBatchInserts, WithChunkReading, WithValidation, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    /**
     * @var
     */
    protected $timestamp;
    protected $organizationTypes;

    /**
     * Keep count of inserted rows
     * @var int
     */
    public $importedCount = 0;

    /**
     * UsersImport constructor.
     */
    public function __construct()
    {
        $this->timestamp = now();
        $this->organizationTypes = OrganizationType::pluck('label');
    }

    /**
     * @param array $row
     * @return Model|null
     */
    public function model(array $row)
    {
        $this->importedCount++; // increment the inserted rows count
        return new User([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'organization_name' => $row['organization_name'] ?? null,
            'organization_type' => $row['organization_type'] ?? null,
            'job_title' => $row['job_title'] ?? null,
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'remember_token' => Str::random(64),
            'email_verified_at' => $this->timestamp
        ]);
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.first_name' => 'required|string|max:255',
            '*.last_name' => 'required|string|max:255',
            '*.organization_name' => 'required|string|max:50',
            '*.organization_type' => ['required', 'string', 'max:255', Rule::in($this->organizationTypes),],
            '*.job_title' => 'required|string|max:255',
            '*.email' => 'required|email|max:255|unique:users,email',
            '*.password' => ['required', 'string', new StrongPassword],
        ];
    }
}
