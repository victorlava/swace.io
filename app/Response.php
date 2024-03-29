<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'response', 'order_id', 'coingate_id'
  ];

}
