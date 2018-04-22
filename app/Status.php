<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;
    
    public function class() {
        $clasName = '';

        if($this->title == 'Invalid') {
            $className = 'badge-danger';
        } elseif ($this->title == 'Pending') {
            $className = 'badge-warning';
        }
        elseif ($this->title == 'Expired') {
            $className = 'badge-secondary';
        }
        elseif ($this->title == 'Paid') {
            $className = 'badge-success';
        }
        elseif ($this->title == 'Canceled') {
            $className = 'badge-danger';
        }

        return ' ' . $className;

    }
}
