<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $appends = ['hash', 'name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'email_verified_at', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Get the posts for this user.
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    /**
     * Get the threads for this user.
     */
    public function threads()
    {
        return $this->hasMany('App\Models\Thread');
    }

    public function getNameAttribute(){
        $name = '';

        if ($this->first_name === '' && $this->last_name === '') {
            $name = $this->username;
        } else {
            if($this->first_name != ''){
                $name = $this->first_name;
            }
            if($this->last_name != ''){
                if($name != ''){
                    $name .= ' '.$this->last_name;
                }else{
                    $name = $this->last_name;
                }
            }
        }

        return $name;
    }

    public function getHashAttribute()
    {
        return Hashids::connection('users')->encode($this->id);
    }

}
