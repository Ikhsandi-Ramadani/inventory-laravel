<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Inventory;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role == 'Sales') {
            $sales = Sales::where('user_id', $user->id)->get();
        } else {
            $sales = Sales::all();
        }

        $inventories = Inventory::all();
        return view('pages.sales.index', compact('sales', 'inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventories = Inventory::all();
        return view('pages.sales.create', compact('inventories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->user()->id;
        // Untuk generate kode transaksi
        $latest = Sales::where('user_id', $userId)->latest()->first();
        $inv = 'SL-';
        $kode = '';
        if (!$latest) {
            $kode = $inv . '0001';
        } else {
            $string = preg_replace("/[^0-9\.]/", '', $latest->number);
            $kode =  $inv . sprintf('%04d', $string + 1);
        }

        $sales = new Sales();
        $sales->number = $kode;
        $sales->user_id = $userId;
        $sales->date = $request->date;
        $sales->save();

        // dd($request->data);
        foreach ($request->data as $value) {
            $product = Inventory::findorfail($value['inventory_id']);
            $salesDetail = new SalesDetail();
            $salesDetail->sales_id = $sales->id;
            $salesDetail->inventory_id = $value['inventory_id'];
            $salesDetail->qty = $value['qty'];
            $salesDetail->price = $value['qty'] * $product->price;
            $salesDetail->save();

            $product->update([
                'stock' => $product->stock - $value['qty']
            ]);
        }

        Alert::success('Berhasil', 'Sales Berhasil DItambahkan');
        return redirect()->route('sales.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sales = Sales::findorfail($id);
        $inventories = Inventory::all();
        return view('pages.sales.show', compact('sales', 'inventories'));
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
        $salesDetail = SalesDetail::findorfail($id);
        // dd($salesDetail);
        $product = Inventory::findorfail($request->inventory_id);

        $product->update([
            'stock' => ($product->stock + $salesDetail->qty) - $request->qty
        ]);

        $salesDetail->sales_id = $salesDetail->sales_id;
        $salesDetail->inventory_id = $request->inventory_id;
        $salesDetail->qty = $request->qty;
        $salesDetail->price = $request->qty * $product->price;
        $salesDetail->save();

        Alert::success('Berhasil', 'Sales Berhasil Diupdate');
        return redirect()->route('sales.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salesDetail = SalesDetail::findorfail($id);
        $product = Inventory::findorfail($salesDetail->inventory_id);
        $product->update([
            'stock' => $product->stock + $salesDetail->qty
        ]);

        $salesDetail->delete();

        Alert::success('Berhasil', 'Barang Berhasil Dihapus');
        return redirect()->route('sales.index');
    }

    public function deleteSales(string $id)
    {
        $sales = Sales::findorfail($id);
        foreach ($sales->salesdetail as $sale) {
            $product = Inventory::findorfail($sale->inventory_id);
            $product->update([
                'stock' => $product->stock + $sale->qty
            ]);
        }

        $sales->delete();

        Alert::success('Berhasil', 'Sales Berhasil Dihapus');
        return redirect()->route('sales.index');
    }
}
