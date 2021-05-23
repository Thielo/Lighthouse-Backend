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
            'id' => $this->hash,
            'username' => $this->username,
            'name' => $this->name,
            'image' => $this->image,
            'roles' => $this->getRoleNames(),
        ];

        if ($this->complete === true) {
            $user['first_name'] = $this->first_name;
            $user['last_name'] = $this->last_name;
            $user['email'] = $this->email;
            $user['email_verified_at'] = $this->email_verified_at;
            $user['created_at'] = $this->created_at;
            $user['updated_at'] = $this->updated_at;
            $user['deleted_at'] = $this->updated_at;
            $user['threads'] = (new ThreadCollection($this->threads))->minimal(true);
        }

        return $user;
    }
}
