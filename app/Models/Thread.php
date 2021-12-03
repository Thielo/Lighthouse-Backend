<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'threads';
    protected $appends = ['hash', 'post_count'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'closed', 'sticky', 'user_id', 'lock_reason', 'locked_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at','updated_at','created_at','locked_at'];

    /**
     * Get the posts for this thread.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    /**
     * Get the post count for this thread.
     * @return integer
     */
    public function getPostCountAttribute()
    {
        return $this->posts->count();
    }

    /**
     * Get the author for this thread.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getHashAttribute()
    {
        return Hashids::connection('threads')->encode($this->id);
    }

    public function getDateAttribute($readable = true)
    {
        if($readable == true){
            return $this->created_at->format('d. F Y'); //July 17, 2008
        }else{
            return $this->created_at->format('Y-m-d').' '.$this->created_at->format('H:i:s'); //2016-06-30 09:20:00
        }
    }
}
