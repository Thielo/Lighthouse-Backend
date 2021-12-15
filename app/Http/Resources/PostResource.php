<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    protected $withThread = false;

    public function withThread($value){
        $this->withThread = $value;
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
        $post = [
            'id' => $this->hash,
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at,
            'author' => new ProfileResource($this->author)
        ];
        if (!is_null($this->updated_at) && $this->updated_at !== $this->created_at) {
            $post['edited_at'] = $this->updated_at;
        }

        if($this->withThread === true) {
            $post['thread'] = new ThreadResource($this->thread);
        }

        return $post;
    }
}
