<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $complete = false;

    public function complete($value){
        $this->complete = $value;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = [
            'hash' => $this->hash,
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->complete === true) {
            print_r($this->getPermissionsViaRoles()->count());
            // $permissionsViaRole = $this->getPermissionsViaRoles()->toArray();
            // print_r(array_map(fn($permission): array => $permission->name, $permissionsViaRole[0]));
            $permissions = []; /*array_map(function($permission) {
                return $permission->name;
            }, $this->getPermissionsViaRoles()->toArray()); */
            //var_dump($this->getPermissionsViaRoles()->toArray());
            $data = [
                'id' => $this->id,
                'roles' => $this->getRoleNames(),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
                'permissions' => $permissions,
            ];
            $user = array_merge($user, $data);
        }

        return $user;
    }
}
