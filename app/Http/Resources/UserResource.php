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
            $data = [
                'id' => $this->id,
                'roles' => $this->getRoleNames(),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
                'permissions' => array_map(function ($permission) {
                    return $permission['name'];
                }, $this->getPermissionsViaRoles()->toArray()),
            ];
            $user = array_merge($user, $data);
        }

        return $user;
    }
}
