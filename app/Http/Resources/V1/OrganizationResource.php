<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->tos_type === 'Parent' && $this->parent) {
            $tos_organization = $this->parent;
            if ($tos_organization->tos_type === 'Parent' && $tos_organization->parent) {
                if (!is_null($tos_organization->parent->tos_content)) {
                    $this->tos_content = $tos_organization->parent->tos_content;
                } else {
                    $this->tos_url = $tos_organization->parent->tos_url;
                }
            } else {
                if (!is_null($tos_organization->tos_content)) {
                    $this->tos_content = $tos_organization->tos_content;
                } else {
                    $this->tos_url =  $tos_organization->tos_url;
                }
            }
        }

        if ($this->privacy_policy_type === 'Parent' && $this->parent) {
            $privacy_policy_organization = $this->parent;
            if ($privacy_policy_organization->privacy_policy_type === 'Parent' && $privacy_policy_organization->parent) {
                if (!is_null($privacy_policy_organization->parent->privacy_policy_content)) {
                    $this->privacy_policy_content = $privacy_policy_organization->parent->privacy_policy_content;
                } else {
                    $this->privacy_policy_url = $privacy_policy_organization->parent->privacy_policy_url;
                }
            } else {
                if (!is_null($privacy_policy_organization->privacy_policy_content)) {
                    $this->privacy_policy_content = $privacy_policy_organization->privacy_policy_content;
                } else {
                    $this->privacy_policy_url =  $privacy_policy_organization->privacy_policy_url;
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'account_id' => $this->account_id,
            'api_key' => $this->api_key,
            'unit_path' => $this->unit_path,
            'noovo_client_id' => $this->noovo_client_id,
            'parent' =>  new OrganizationResource($this->whenLoaded('parent')),
            'projects' =>  ProjectResource::collection($this->whenLoaded('projects')),
            'children' =>  OrganizationResource::collection($this->whenLoaded('children')),
            'users' =>  UserResource::collection($this->whenLoaded('users')),
            'admins' =>  UserResource::collection($this->whenLoaded('admins')),
            'image' => $this->image,
            'favicon' => $this->favicon,
            'domain' => $this->domain,
            'self_registration' => $this->self_registration,
            'gcr_project_visibility' => $this->gcr_project_visibility,
            'gcr_playlist_visibility' => $this->gcr_playlist_visibility,
            'gcr_activity_visibility' => $this->gcr_activity_visibility,
            'organization_role' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->display_name;
            }),
            'organization_role_id' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->id;
            }),
            'projects_count' => isset($this->projects_count) ? $this->projects_count : 0,
            'suborganization_count' => isset($this->children_count) ? $this->children_count : 0,
            'users_count' => isset($this->users_count) ? $this->users_count : 0,
            'groups_count' => isset($this->groups_count) ? $this->groups_count : 0,
            'teams_count' => isset($this->teams_count) ? $this->teams_count : 0,
            'tos_type' => $this->tos_type,
            'tos_url' => $this->tos_url,
            'tos_content' => $this->tos_content,
            'privacy_policy_type' => $this->privacy_policy_type,
            'privacy_policy_url' => $this->privacy_policy_url,
            'privacy_policy_content' => $this->privacy_policy_content,
            'branding' => [
                'primary_color' => $this->primary_color,
                'secondary_color' => $this->secondary_color,
                'tertiary_color' => $this->tertiary_color,
                'primary_font_family' => $this->primary_font_family,
                'secondary_font_family' => $this->secondary_font_family,
            ],
            'allowed_visibility_type_id' => OrganizationVisibilityTypeResource::collection($this->allowedVisibilityTypes),
            'msteam_client_id' => $this->msteam_client_id,
            'msteam_secret_id' => $this->msteam_secret_id,
            'msteam_tenant_id' => $this->msteam_tenant_id,
            'msteam_secret_id_expiry' => $this->msteam_secret_id_expiry,
            'msteam_project_visibility' => $this->msteam_project_visibility,
            'msteam_playlist_visibility' => $this->msteam_playlist_visibility,
            'msteam_activity_visibility' => $this->msteam_activity_visibility
        ];
    }
}
