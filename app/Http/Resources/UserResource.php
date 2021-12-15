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
            $fullData = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'email_verified_at' => $this->email_verified_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->updated_at,
                'threads' => (new ThreadCollection($this->threads))->minimal(true),
            ];
            $user = array_merge($user, $fullData);
        }

        return $user;
        // return parent::toArray($request);
    }
}
