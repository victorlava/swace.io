<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {

        $recaptcha = new \ReCaptcha\ReCaptcha(env('RECAPTCHA_SECRET_KEY'));

        return view('pages/landing');
    }
}
