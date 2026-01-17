<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Margin;
use Illuminate\Support\Facades\Validator;

class MarginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Margin::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('TypeofItemCode', 'like', "%{$search}%")
                    ->orWhere('TypeofItemDesc', 'like', "%{$search}%")
                    ->orWhere('Margin', 'like', "%{$search}%")
                    ->orWhere('ARCIM_ServMateria', 'like', "%{$search}%");
            });
        }

        $margins = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('margin.index', compact('margins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('margin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TypeofItemCode' => 'nullable|string|max:255',
            'TypeofItemDesc' => 'nullable|string|max:255',
            'Margin' => 'nullable|numeric|min:0',
            'ARCIM_ServMateria' => 'nullable|string|max:255',
            'DateFrom' => 'required|date',
            'DateTo' => 'nullable|date|after_or_equal:DateFrom',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Margin::create($request->all());

        return redirect()->route('margin.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $margin = Margin::findOrFail($id);
        return view('margin.show', compact('margin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $margin = Margin::findOrFail($id);
        return view('margin.edit', compact('margin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'TypeofItemCode' => 'nullable|string|max:255',
            'TypeofItemDesc' => 'nullable|string|max:255',
            'Margin' => 'nullable|numeric|min:0',
            'ARCIM_ServMateria' => 'nullable|string|max:255',
            'DateFrom' => 'required|date',
            'DateTo' => 'nullable|date|after_or_equal:DateFrom',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $margin = Margin::findOrFail($id);
        $margin->update($request->all());

        return redirect()->route('margin.index')
            ->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $margin = Margin::findOrFail($id);
        $margin->delete();

        return redirect()->route('margin.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
