<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * @OA\Schema (
 *     @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="User id"
 *  ),
 *  @OA\Property(
 *      property="name",
 *      type="string",
 *      description="User name"
 *  ),
 *  @OA\Property(
 *      property="email",
 *      type="string",
 *      description="User email"
 *  ),
 *  @OA\Property(
 *      property="password",
 *      type="string",
 *      description="User password"
 *  ),
 *   @OA\Property(
 *      property="isAdmin",
 *      type=" bool",
 *
 *  ),
 *     @OA\Property(
 *      property="table",
 *      type="string",
 *     description="Table, that contains current model"
 *
 *  )
 * )
 */

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'isAdmin'
    ];

    /**
     * @var string
     */
    public $table = 'users';

    public function isAdmin(): bool
    {
        return $this->isAdmin ?? false;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
