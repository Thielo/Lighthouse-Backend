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
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function(UserResource $resource) use($request){
            return $resource->complete($this->complete)->toArray($request);
        })->all();
    }
}
