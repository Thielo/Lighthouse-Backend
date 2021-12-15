<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
{
    protected $withPosts = false;
    protected $minimal = false;
    protected $deleted = false;

    public function withPosts($value){
        $this->withPosts = $value;
        return $this;
    }

    public function minimal($value){
        $this->minimal = $value;
        return $this;
    }

    public function deleted($value){
        $this->deleted = $value;
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
        $thread = [
            'id' => $this->hash,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'locked' => false,
            'post_count' => count($this->posts),
        ];

        if ($this->deleted === true) {
            $thread['title'] = 'deleted';
        }

        if ($this->minimal === false) {
            $thread['author'] = new ProfileResource($this->author);
        }

        if ($this->withPosts === true) {
            $thread['posts'] = new PostCollection($this->posts);
        }

        if (!is_null($this->locked_at)) {
            $thread['locked'] = true;
        }

        return $thread;
    }
}
