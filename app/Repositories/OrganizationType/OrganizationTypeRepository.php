<?php

namespace App\Repositories\OrganizationType;

use Illuminate\Http\Request;

use App\Models\OrganizationType;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;

class OrganizationTypeRepository implements OrganizationTypeRepositoryInterface
{

    public function all(){
        return OrganizationType::orderBy('order', 'asc')->get();
    }

    /**
     * Create a new organization type
     * @param String $name
     * @param String $label
     */    
    public function create($name, $label){
        $lastOrgType = OrganizationType::orderBy('order', 'desc')->first();
        $newOrgType = OrganizationType::create([
            'name' => $name,
            'label' => $label,
            'order' => empty($lastOrgType) ? 0 : $lastOrgType->order + 1,
        ]);
        return $newOrgType;
    }

    /**
     * Update an existing organization type
     * @param Illuminate\Http\Request
     * @param App\Models\OrganizationType
     */    
    public function update(Request $request, OrganizationType $orgType){
        $orgType->name = $request->input('name');
        $orgType->label = $request->input('label');

        if($request->filled('order') && $request->input('order') !== $orgType->order){
            $otherOrgType = OrganizationType::where('order', $request->input('order'))->first();
            if(!empty($otherOrgType)){
                $otherOrgType->order = $orgType->order;
                $otherOrgType->save();
                $orgType->order = $request->input('order');
            }
        }
        $orgType->save();
        return $orgType;
    }

    /**
     * Delete an existing organization type
     * @param App\Models\OrganizationType
     */    
    public function delete(OrganizationType $orgType){
        $otherOrgTypes = OrganizationType::where('order', '>', $orgType->order)->get();
        foreach ($otherOrgTypes as $type) {
            $type->order--;
            $type->save();
        }
        $orgType->delete();
        return true;
    }

}
