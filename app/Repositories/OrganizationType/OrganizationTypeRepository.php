<?php

namespace App\Repositories\OrganizationType;

use Illuminate\Http\Request;
use App\Models\OrganizationType;
use App\Repositories\BaseRepository;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;

class OrganizationTypeRepository extends BaseRepository implements OrganizationTypeRepositoryInterface
{
    /**
     * OrganizationTypeRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(OrganizationType $model){
        parent::__construct($model);
    }

    /**
     * Get all models in storage
     *
     * @return Collection
     */
    public function all(){
        return $this->model->orderBy('order', 'asc')->get();
    }

    /**
     * Create model in storage
     *
     * @param array $attributes
     * @return Model
     */ 
    public function create($attributes){
        $lastOrgType = $this->model->orderBy('order', 'desc')->first();
        $newOrgType = $this->model->create([
            'name' => $attributes['name'],
            'label' => $attributes['label'],
            'order' => empty($lastOrgType) ? 0 : $lastOrgType->order + 1,
        ]);
        return $newOrgType;
    }


    /**
     * Update model in storage
     *
     * @param array $attributes
     * @param $id
     * @return Model
     */
    public function update($attributes, $id){
        $orgType = $this->model->find($id);
        $orgType->name = $attributes['name'];
        $orgType->label = $attributes['label'];

        if(isset($attributes['order']) && $attributes['order'] != $orgType->order){
            $otherOrgType = $this->model->where('order', $attributes['order'])->first();
            if(!empty($otherOrgType)){
                $otherOrgType->order = $orgType->order;
                $otherOrgType->save();
                $orgType->order = $attributes['order'];
            }
        }
        $orgType->save();
        return $orgType;
    }

    /**
     * Delete model in storage
     *
     * @param $id
     * @return int
     */
    public function delete($id){
        $orgType = $this->model->find($id);
        $otherOrgTypes = $this->model->where('order', '>', $orgType->order)->get();
        foreach ($otherOrgTypes as $type) {
            $type->order--;
            $type->save();
        }
        return $orgType->delete();
    }
}
