<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

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

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }
    public function isDentiste(): bool
    {
        return $this->role === 'Dentiste';
    }
    public function isSecretaire(): bool
    {
        return $this->role === 'Secretaire';
    }
    public function isPharmacist(): bool
    {
        return $this->role === 'Pharmacist';
    }
    public function dentist()
    {
        return $this->hasOne(Dentist::class);
    }

    public function sessions()
    {
        return \DB::table('sessions')->where('user_id', $this->id);
    }
    public function activeSessions()
    {
        $now = now()->timestamp;

        return \DB::table('sessions')
            ->where('user_id', $this->id)
            ->get()
            ->map(function ($session) use ($now) {
                return [
                    'session_id'   => $session->id,
                    'ip_address'   => $session->ip_address,
                    'user_agent'   => $session->user_agent,
                    'last_activity'=> Carbon\Carbon::createFromTimestamp($session->last_activity),
                    'duration'     => Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'is_active'    => ($now - $session->last_activity) <= config('session.lifetime') * 60,
                ];
            });
    }
    public function lastConnection()
    {
        $session = \DB::table('sessions')
            ->where('user_id', $this->id)
            ->orderByDesc('last_activity')
            ->first();

        return $session
            ? Carbon\Carbon::createFromTimestamp($session->last_activity)
            : null;
    }
    public function isOnline()
    {
        $sessionLifetime = config('session.lifetime') * 30;

        return \DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->timestamp - $sessionLifetime)
            ->exists();
    }
    public function onlineUsers()
    {
        $online = \DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->timestamp - config('session.lifetime') * 60)
            ->pluck('user_id')
            ->unique();

        return User::whereIn('id', $online)->get();
    }
    public function isEnabled()
    {
        return $this->is_active;
    }
    public function logoutUserSessions($userId)
    {
        DB::table('sessions')->where('user_id', $userId)->delete();
    }
    public function caisses()
    {
        return $this->hasMany(Caisse::class);
    }


}
