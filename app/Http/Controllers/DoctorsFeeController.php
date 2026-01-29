<?php

namespace App\Http\Controllers;

use App\Models\DoctorsFee;
use Illuminate\Http\Request;

class DoctorsFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is ADMIN
        if (!user_has_role(session('user'), 'ADMIN')) {
            return redirect()->route('dashboard')->withErrors(['access' => 'Access denied. Only ADMIN can view Doctors Fee module.']);
        }
        
        $query = DoctorsFee::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('URN', 'like', "%{$search}%")
                    ->orWhere('EpisodeNo', 'like', "%{$search}%")
                    ->orWhere('EpisodeDoctorDesc', 'like', "%{$search}%")
                    ->orWhere('InvoiceNumber', 'like', "%{$search}%");
            });
        }

        $doctorsFees = $query->orderBy('created_at', 'desc')->paginate(100);

        // Apply business logic calculations
        $doctorsFees->getCollection()->transform(function ($fee) {
            // Calculate AfterDiscount
            $fee->AfterDiscount = $fee->TotalPrice - $fee->DiscountItem;

            // Calculate PercentDoctor
            if ($fee->BillingGroupDesc === 'Consultation') {
                $fee->PercentDoctor = 90;
            } elseif ($fee->BillingGroupDesc === 'Medical Service') {
                $fee->PercentDoctor = 40;
            } elseif ($fee->BillingGroupDesc === 'Surgery & Intervention Procedure') {
                $fee->PercentDoctor = 45;
                $fee->PercentDoctorAnaesthesia = 15;
            }

            return $fee;
        });

        return view('doctors_fee.index', compact('doctorsFees'));
    }
}
