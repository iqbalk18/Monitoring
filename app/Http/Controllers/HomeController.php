<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        if (!Session::has('token')) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        $user = Session::get('user');
        return view('home', compact('user'));
    }
}
