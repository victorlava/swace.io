<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description'
    ];

    public function class() : string
    {
        $clasName = '';

        if ($this->title == 'Failed') {
            $className = 'badge-danger';
        } elseif ($this->title == 'Pending') {
            $className = 'badge-warning';
        } elseif ($this->title == 'Expired') {
            $className = 'badge-secondary';
        } elseif ($this->title == 'Paid') {
            $className = 'badge-success';
        } elseif ($this->title == 'Canceled') {
            $className = 'badge-danger';
        }

        return ' ' . $className;
    }
}
