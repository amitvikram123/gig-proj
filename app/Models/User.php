<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'u_id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'firstname',
        'lastname',
        'mobile',
        'age',
        'gender',
        'city'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [ 
        'email_verified_at' => 'datetime',
    ];

    /**
     * returns JWT Key
     * @return string
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get JWT custom claims
     * @return array
     */

     public function getJWTCustomClaims()
     {
        return [];
     }

     public function book() {
         return $this->hasMany(Book::class,'user_id','u_id');
     }
}
