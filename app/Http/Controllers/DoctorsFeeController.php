<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorsFeeController extends Controller
{
    public function index()
    {
        $doctorsFees = \App\Models\DoctorsFee::paginate(10);
        return view('doctors_fee.index', compact('doctorsFees'));
    }


}
