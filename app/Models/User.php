<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Carbon\Carbon;
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
        'referral_code',
        'google2fa_secret',
        'twoFA_enable',
        'points',
        'referrer_id',
        'last_login_at',
        'dob',
        'whatsapp_link',
        'plan_name',
        'plan_growth_name',
        'euro_per_points',
        'subscription_id',
        'growth_subscription_id',
        'two_factor_code', 'two_factor_expires_at'

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
        }else{
            return $user->group_id;
        }
        $groupUser = self::where('group_id', $user->group_id)->first();
        return $groupUser ? $groupUser->id : null;
        } catch (\Throwable $th) {
            return  null;
        }
    }
    public static function generateReferralCode()
{
    do {
        $code = strtoupper(Str::random(8));
    } while (User::where('referral_code', $code)->exists());

    return $code;
}
public function invoices()
{
    return $this->hasMany(Invoice::class, 'agent_id');
}
public function groupInvoices()
{
    return $this->hasMany(Invoice::class, 'group_id');
}

public function contracts()
{
    return $this->hasMany(Contract::class, 'agent_id');
}

public  function activeSubscriptions()
{
    $now =Carbon::now();

    return $this->hasMany(Subscription::class)->where(function ($query) use ($now) {
        $query->whereDate('start_date', '<=', $now)
              ->whereDate('end_date', '>=', $now)->where('type', 'plan');
    });
}

public function activeOrtherSubscriptions()
{
    $now =Carbon::now();

    return $this->hasMany(Subscription::class)->where(function ($query) use ($now) {
        $query->whereDate('start_date', '<=', $now)
              ->whereDate('end_date', '>=', $now)->whereNot('type', 'plan');
    });
}
 public static function getAdminIds()
    {
        return self::where('role', 'admin')->pluck('id')->toArray();
    }

}

