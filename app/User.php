<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;

    public $nice_date_format = 'F j, H:i a';

    public $precise_date_format = 'Y-m-d H:i:s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'password', 'email_token', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isVerified(): bool
    {
        return $this->verified === 1 ? true : false;
    }

    public function isAdmin(): bool
    {
        return $this->admin === 1 ? true : false;
    }

    public function isContributed(): bool
    {
        return $this->contributed === 1 ? true : false;
    }

    public function verify()
    {
        $this->verified = 1;
        $this->email_token = null;
        $this->verified_at = date('Y-m-d H:i:s');
        $this->save();
    }

    public function full_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function date_time(): string
    {
        return date('Y-m-d H:i:s', time());
    }

    public function format_date($date, string $type = 'nice'): string
    {
        $timestamp = strtotime($date);
        $nice = date($this->nice_date_format, $timestamp);
        $precise = date($this->precise_date_format, $timestamp);

        return ($type == 'nice') ? $nice : $precise;
    }

    public function registered_date(): string
    {
        return $this->format_date($this->created_at);
    }

    public function verified_date(string $type = 'nice'): string
    {
        return ($this->verified_at !== null) ? $this->format_date($this->verified_at, $type) : 'not verified';
    }

    public function last_online_date(string $type = 'nice'): string
    {
        $date = $this->logs()->orderBy('log_in', 'desc')->first();

        return ($date) ? $this->format_date($date->log_in, $type) : 'never';
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }


    public function logs()
    {
        return $this->hasMany('App\Log', 'user_id', 'id');
    }
}
