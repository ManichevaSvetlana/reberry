<?php

namespace App\Models;

use Carbon\Carbon;
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
        'name',
        'email',
        'password',
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

    /**
     * Refresh the token.
     *
     * @param bool $createNew
     * @param string|null $device
     * @return mixed
     */
    public function refreshToken(bool $createNew = true, string $device = null)
    {
        $device = $device ?? 'web';
        $this->tokens()->where('name', $device)->delete();
        return $createNew ? $this->createToken($device)->plainTextToken : true;
    }

    /**
     * Check if token needs to be refreshed.
     *
     * @return mixed
     */
    public function checkTokenForRefresh(): mixed
    {
        $token = $this->currentAccessToken();
        $expiration = config('sanctum.expiration');

        // Refresh the token 30 min before the expiration [if the session is active]
        if(Carbon::now() >= Carbon::make($token->created_at)->addMinutes($expiration - 30)) return $this->refreshToken();

        return false;
    }
}
