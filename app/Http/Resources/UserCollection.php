<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\User;

class UserCollection extends ResourceCollection
{
    protected $complete = false;

    public function complete($value){
        $this->complete = $value;
        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return (new UserResource($this->collection))->complete($this->complete);
    }
}
