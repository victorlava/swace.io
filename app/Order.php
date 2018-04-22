<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public function type() {
        return $this->hasOne('App\Currency', 'id', 'currency_id');
    }

    public function status() {
        return $this->hasOne('App\Status', 'id', 'status_id');
    }

}
