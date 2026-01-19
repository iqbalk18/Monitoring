<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorsFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is ADMIN
        if (session('user')['role'] !== 'ADMIN') {
            return redirect()->route('dashboard')->withErrors(['access' => 'Access denied. Only ADMIN can view Doctors Fee module.']);
        }

        $query = \App\Models\DoctorsFee::query();

        // Basic search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('URN', 'like', "%{$search}%")
                    ->orWhere('EpisodeNo', 'like', "%{$search}%")
                    ->orWhere('FirstName', 'like', "%{$search}%")
                    ->orWhere('LastName', 'like', "%{$search}%")
                    ->orWhere('InvoiceNumber', 'like', "%{$search}%")
                    ->orWhere('EpisodeDoctorDesc', 'like', "%{$search}%");
            });
        }

        $doctorsFees = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('doctors_fee.index', compact('doctorsFees'));
    }
}
