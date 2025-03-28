<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'country',
        'city',
        'postal_code',
        'status',
        'role',
        'group_id',
        'added_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getGroupAdminOrFindByGroup($userId)
    {

        try {
            $user = self::find($userId);
        if ($user && $user->role === 'group_admin') {
            return $userId;
        }
        $groupUser = self::where('group_id', $user->group_id)->first();
        return $groupUser ? $groupUser->id : null;
        } catch (\Throwable $th) {
            return  null;
        }
    }
}
