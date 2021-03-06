<?php

namespace App;

use App\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait, Notifiable, SoftDeletes, ThrottlesLogins;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number', 'active', 'first_name', 'middle_name', 'last_name', 'address', 'postal', 'city', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'users';
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $dates = ['deleted_at'];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this->number));
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public static function getLastNumber()
    {
        $last = User::orderBy('number', 'desc')->first();

        return $last->number !== null ? $last->number : 10000;
    }

    public function getNameAttribute()
    {
        return $this->first_name . ($this->middle_name ? ' '.$this->middle_name : ''). ' ' . $this->last_name;
    }
}
