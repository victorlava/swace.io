<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flash extends Model
{
    public static function create(string $type, string $message)
    {
        session()->flash('type', $type);
        session()->flash('message', $message);
    }
}
