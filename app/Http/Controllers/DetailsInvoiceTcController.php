<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailsInvoiceTc;

class DetailsInvoiceTcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is ADMIN (Extra security layer)
        if (session('user')['role'] !== 'ADMIN') {
            return redirect()->route('dashboard')->withErrors(['access' => 'Access denied. Only ADMIN can view Details Invoice TC module.']);
        }

        $query = DetailsInvoiceTc::query();

        // Basic search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('URN', 'like', "%{$search}%")
                    ->orWhere('EpisodeNo', 'like', "%{$search}%")
                    ->orWhere('FirstName', 'like', "%{$search}%")
                    ->orWhere('LastName', 'like', "%{$search}%")
                    ->orWhere('InvoiceNumber', 'like', "%{$search}%");
            });
        }

        $details = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('details_invoice_tc.index', compact('details'));
    }
}
