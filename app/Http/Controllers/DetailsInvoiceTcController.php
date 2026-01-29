<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsInvoiceTcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!user_has_role(session('user'), 'ADMIN')) {
            return redirect()->route('dashboard')->withErrors(['access' => 'Access denied. Only ADMIN can view Details Invoice TC module.']);
        }

        return view('details_invoice_tc.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
