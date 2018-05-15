<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $updated_at = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token', 'created_at'
    ];
}
