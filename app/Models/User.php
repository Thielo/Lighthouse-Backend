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
    public function posts() {
        return $this->hasMany('App\Models\Post');
    }

    /**
     * Get the threads for this user.
     */
    public function threads() {
        return $this->hasMany('App\Models\Thread');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile() {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function getNameAttribute(){
        $name = '';

        if ($this->profile->first_name === '' && $this->profile->last_name === '') {
            $name = $this->username;
        } else {
            if($this->profile->first_name != ''){
                $name = $this->profile->first_name;
            }
            if($this->profile->last_name != ''){
                if($name != ''){
                    $name .= ' '.$this->profile->last_name;
                }else{
                    $name = $this->profile->last_name;
                }
            }
        }

        return $name;
    }

    public function getHashAttribute() {
        return Hashids::connection('users')->encode($this->id);
    }
}
