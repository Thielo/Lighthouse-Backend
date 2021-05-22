<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Thread;

class ThreadCollection extends ResourceCollection
{
    protected $minimal = false;

    public function minimal($value){
        $this->minimal = $value;
        return $this;
    }

    protected $deleted = false;

    public function deleted($value){
        $this->deleted = $value;
        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function(Thread $thread) {
            return (new ThreadResource($thread))->withPosts(false)->minimal($this->minimal)->deleted($this->deleted);
        });

        return parent::toArray($request);
    }
}
