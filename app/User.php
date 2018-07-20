<?php

namespace App;

use App\Notifications\MyResetPassword as ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public $nice_date_format = 'F j, H:i a';

    public $precise_date_format = 'Y-m-d H:i:s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timezone', 'company_code', 'company_name', 'company_vat', 'personal', 'company_address', 'company_city', 'first_name', 'last_name', 'phone', 'email', 'password', 'email_token', 'verified', 'contributed', 'kyc', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'admin'
    ];

    public function isVerified(): bool
    {
        return $this->verified === 1 ? true : false;
    }

    public function isAdmin(): bool
    {
        return $this->admin === 1 ? true : false;
    }

    // Disable input if KYC is passed
    public function disableInput(): string
    {
        return $this->kyc === 1 ? 'disabled' : '';
    }

    public function isKYC(): bool
    {
        return $this->kyc === 1 ? true : false;
    }

    public function isCompany(): bool
    {
        return $this->personal === 1 ? false : true;
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

    public function tokens()
    {
        return Order::where('user_id', $this->id)
            ->where('status_id', Order::COINGATE_STATUS_PAID)
            ->sum('tokens');
    }

    public function full_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function date_time(): string
    {
        return date('Y-m-d H:i:s');
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

    public function count_purchase_amount(): float {

      return $this->with('orders')->findOrFail($this->id)
                                  ->orders()
                                  ->where('status_id', 3) // Paid
                                  ->sum('gross');

    }


    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }


    public function logs()
    {
        return $this->hasMany('App\Log', 'user_id', 'id')->orderBy('log_in', 'desc');
    }

    public function addLogout(string $sessionId = null): void
    {
        $builder = $this->logs()->orderBy('log_in', 'desc');

        if (null !== $sessionId) {
            $builder->where('session_id', $sessionId);
        }

        $log = $builder->first();

        if ($log) {
            $log->log_out = Carbon::now();
            $log->save();
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification(request()->ip(), $token, $this->email));
    }
}
