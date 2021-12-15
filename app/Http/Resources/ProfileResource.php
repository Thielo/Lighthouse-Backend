<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (is_null($this->deleted_at)) {
            $profile = [
                'id' => $this->hash,
                'username' => $this->username,
                'name' => $this->name,
                'image' => $this->image,
                'roles' => $this->getRoleNames(),
            ];
        }else{
            $profile = [
                'id' => $this->hash,
                'username' => 'deleted',
                'name' => 'deleted',
                'image' => '',
                'roles' => [],
                'deleted' => true
            ];
        }

        if ($this->complete === true) {
            if ($profile['deleted'] === true) {
                $user['threads'] = (new ThreadCollection($this->threads))->minimal(true)->deleted(true);
            } else {
                $user['first_name'] = $this->first_name;
                $user['last_name'] = $this->last_name;
                $user['threads'] = (new ThreadCollection($this->threads))->minimal(true);
            }

        }

        return $profile;
    }
}
