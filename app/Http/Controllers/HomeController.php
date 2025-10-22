<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        if (!Session::has('token')) {
            return redirect('/login')->withErrors(['login' => 'Please Login.']);
        }

        return view('home');
    }
}
