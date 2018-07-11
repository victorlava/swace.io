<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'ip_address', 'session_id', 'user_agent', 'log_in', 'log_out'
    ];

    /** @var string[] */
    protected $dates = [
        'log_in',
        'log_out',
    ];

    public function onlineTime(): string
    {
        $logout = $this->log_out ?? Carbon::now();

        return $logout->diffForHumans($this->log_in, true);
    }
}
