<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamps = false;

    public function online_time(): string
    {
        $log_in = strtotime($this->log_in);
        $log_out = strtotime($this->log_out);
        $timestamp = $log_out - $log_in;
        $date = ($log_out === false) ? 'online' : date('H:i:s', $timestamp);

        return $date;
    }
}
