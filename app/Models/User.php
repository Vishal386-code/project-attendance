<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Namu\WireChat\Traits\Chatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Chatable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'sr_no',
        'designation',
        'ref_no',
        'date_joined',
        'status',
        'wfh_hybrid',
        'date_left',
        'father_name',
        'dob',
        'address',
        'contact_number',
        'user_email',
        'on_role_date',
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
            'date_left' => 'date',
            'dob' => 'date',
            'on_role_date' => 'date',
        ];
    }

    public function canCreateChats(): bool
    {
        return true;
    }

    public function canCreateGroups(): bool
    {
        return true;
    }

    public function salary(): HasOne
    {
        return $this->hasOne(Salary::class);
    }

    public function bankDetail(): HasOne
    {
        return $this->hasOne(BankDetail::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Helper method to check role
    public function hasRole($role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }
}
