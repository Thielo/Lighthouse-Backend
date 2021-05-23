<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profiles';

    /**
     * the attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'description',
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
    public function user()
    {
        return $this->belongsto('App\Models\User')->withdefault();
    }
}
