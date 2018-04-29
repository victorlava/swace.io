<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;

    public $code = 0;

    public function set(string $status): int
    {
        $status = strtolower($status);
        $code = 0;

        switch ($status) {
            case 'failed':
                $code = 1;
                break;

            case 'pending':
                $code = 2;
                break;

            case 'expired':
                $code = 3;
                break;

            case 'paid':
                $code = 4;
                break;

            case 'canceled':
                $code = 5;
                break;

        }

        // $this

        return $this;
    }

    public function class() : string
    {
        $clasName = '';

        if ($this->title == 'Invalid') {
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
