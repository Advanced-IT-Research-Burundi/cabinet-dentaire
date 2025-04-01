<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'role',
        'statut',
        'phone',
        'photo_url',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'secondary_phone',
        'secondary_email',
    ];

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

    public function notifications()
    {
        return $this->hasMany(Notification::class , 'recipient_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('read_at', null);
    }

    public function readNotifications()
    {
        return $this->notifications()->where('read_at', '!=', null);
    }

}
