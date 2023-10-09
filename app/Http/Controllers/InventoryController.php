<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::all();
        return view('pages.inventory.index', compact('inventories'));
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
        // Untuk generate kode transaksi
        $latest = Inventory::latest()->first();
        $kode = '';
        if (!$latest) {
            $kode = 'BRG-0001';
        } else {
            $string = preg_replace("/[^0-9\.]/", '', $latest->code);
            $kode =  'BRG-' . sprintf('%04d', $string + 1);
        }
        Inventory::create([
            'code' => $kode,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => 0
        ]);

        Alert::success('Berhasil', 'Barang Berhasil DItambahkan');
        return redirect()->route('inventory.index');
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
        $inventory = Inventory::findorfail($id);
        $inventory->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        Alert::success('Berhasil', 'Barang Berhasil Diupdate');
        return redirect()->route('inventory.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Inventory::destroy($id);
        Alert::success('Berhasil', 'Barang Berhasil Dihapus');
        return redirect()->route('inventory.index');
    }
}
