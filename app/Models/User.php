<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\mail\InvitationMail;
use App\Models\UserVerificationCode;
use App\Models\UserDetail;
use Mail;


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
        'is_admin',
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
     * Get the phone associated with the user.
     */
    public function verification()
    {
        return $this->hasOne(UserVerificationCode::class);
    }

    /**
     * Get the phone associated with the user.
     */
    public function user_details()
    {
        return $this->hasOne(UserDetail::class);
    }
}

User::created(function($user){
    $six_digit_random_number = rand(100000, 999999);
    $details = [
        'title' => 'verify email',
        'body' =>  'verification code: '.$six_digit_random_number
    ];

    Mail::to($user->email)->send(new InvitationMail($details));
    $user->verification()->create([
        'code' => $six_digit_random_number
    ]);
});
