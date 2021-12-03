<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'posts';

    /**
     * the attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id', 'thread_id', 'post_id', 'title', 'body',
    ];

    /**
     * the attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * get the thread of the post.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsto('App\Models\Thread')->withdefault();
    }

    /**
     * get the author of the post
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * get the has of the post as attribute
     * @return Hashids
     */
    public function getHashAttribute()
    {
        return Hashids::connection('posts')->encode($this->id);
    }

    /**
     * get the date of the post as attribute
     * @param bool $readable
     * @return string
     */
    public function getDateAttribute($readable = true)
    {
        if ($readable == true) {
            return $this->created_at->format('d. F Y');
        } else {
            return $this->created_at->format('Y.m.d') . 'T' . $this->created_at->format('h:i:s') . 'Z';
        }
    }
}
