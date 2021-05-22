<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{

    protected $complete = false;
    protected $private = false;

    public function complete($value){
        $this->complete = $value;
        return $this;
    }

    public function private($value){
        $this->private = $value;
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

        if (is_null($this->deleted_at)) {
            $profile = [
                'id' => $this->hash,
                'username' => $this->username,
                'name' => $this->name,
                'image' => $this->image,
                'roles' => $this->getRoleNames(),
                'thread_count' => count($this->threads),
                'post_count' => count($this->posts),
                'created_at' => $this->created_at,
                'verified_at' => $this->email_verified_at,
            ];
        }else{
            $profile = [
                'id' => $this->hash,
                'username' => 'deleted',
                'name' => 'deleted',
                'image' => '',
                'roles' => [],
                'deleted' => true,
                'thread_count' => count($this->threads),
                'post_count' => count($this->posts),
                'created_at' => $this->created_at,
                'verified_at' => $this->email_verified_at,
            ];
        }

        if($this->private === true) {
            // unset($profile['name']);
        }

        if ($this->complete === true) {
            if (isset($profile['deleted']) && $profile['deleted'] === true) {
                $profile['threads'] = (new ThreadCollection($this->threads))->minimal(true)->deleted(true);
            } else {
                $profile['first_name'] = $this->first_name;
                $profile['last_name'] = $this->last_name;
                $profile['threads'] = (new ThreadCollection($this->threads))->minimal(true);
            }

        }

        return $profile;
    }
}
