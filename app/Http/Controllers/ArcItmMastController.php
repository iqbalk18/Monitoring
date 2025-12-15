<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArcItmMast;
use Illuminate\Support\Facades\Validator;

class ArcItmMastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ArcItmMast::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ARCIM_Code', 'like', "%{$search}%")
                  ->orWhere('ARCIM_Desc', 'like', "%{$search}%")
                  ->orWhere('ARCIC_Code', 'like', "%{$search}%")
                  ->orWhere('ORCAT_Code', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->status != '') {
            $today = now()->startOfDay();
            
            if ($request->status == 'active') {
                $query->where(function($q) use ($today) {
                    $q->whereNull('ARCIM_EffDateTo')
                      ->orWhere('ARCIM_EffDateTo', '>=', $today);
                });
            } elseif ($request->status == 'non_active') {
                $query->where('ARCIM_EffDateTo', '<', $today)
                      ->whereNotNull('ARCIM_EffDateTo');
            }
        }

        $items = $query->with('prices')->orderBy('created_at', 'desc')->paginate(15);
        
        $items->appends($request->only(['search', 'status']));

        return view('arc_itm_mast.index', compact('items'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = ArcItmMast::findOrFail($id);
        return view('arc_itm_mast.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'ARCIM_Code' => 'nullable|string|max:255',
            'ARCIM_Desc' => 'nullable|string|max:255',
            'ARCIM_ServMaterial' => 'nullable|string|max:255',
            'ARCIC_Code' => 'nullable|string|max:255',
            'ARCIC_Desc' => 'nullable|string|max:255',
            'ORCAT_Code' => 'nullable|string|max:255',
            'ORCAT_Desc' => 'nullable|string|max:255',
            'ARCSG_Code' => 'nullable|string|max:255',
            'ARCSG_Desc' => 'nullable|string|max:255',
            'ARCBG_Code' => 'nullable|string|max:255',
            'ARCBG_Desc' => 'nullable|string|max:255',
            'ARCIM_OrderOnItsOwn' => 'nullable|string|max:255',
            'ARCIM_ReorderOnItsOwn' => 'nullable|string|max:255',
            'ARCIM_EffDate' => 'nullable|date',
            'ARCIM_EffDateTo' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $item = ArcItmMast::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('arc-itm-mast.index')
            ->with('success', 'Data berhasil diupdate');
    }
}
