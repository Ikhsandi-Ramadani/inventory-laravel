<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role == 'Purchase') {
            $purchases = Purchase::where('user_id', $user->id)->get();
        } else {
            $purchases = Purchase::all();
        }

        $inventories = Inventory::all();
        return view('pages.purchase.index', compact('purchases', 'inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventories = Inventory::all();
        return view('pages.purchase.create', compact('inventories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->user()->id;
        // Untuk generate kode transaksi
        $latest = Purchase::where('user_id', $userId)->latest()->first();
        $inv = 'PC-';
        $kode = '';
        if (!$latest) {
            $kode = $inv . '0001';
        } else {
            $string = preg_replace("/[^0-9\.]/", '', $latest->number);
            $kode =  $inv . sprintf('%04d', $string + 1);
        }

        $purchase = new Purchase();
        $purchase->number = $kode;
        $purchase->user_id = $userId;
        $purchase->date = $request->date;
        $purchase->save();

        // dd($request->data);
        foreach ($request->data as $value) {
            $product = Inventory::findorfail($value['inventory_id']);
            $purchaseDetail = new PurchaseDetail();
            $purchaseDetail->purchase_id = $purchase->id;
            $purchaseDetail->inventory_id = $value['inventory_id'];
            $purchaseDetail->qty = $value['qty'];
            $purchaseDetail->price = $value['qty'] * $product->price;
            $purchaseDetail->save();

            $product->update([
                'stock' => $product->stock + $value['qty']
            ]);
        }

        Alert::success('Berhasil', 'Purchase Berhasil DItambahkan');
        return redirect()->route('purchase.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::findorfail($id);
        $inventories = Inventory::all();
        return view('pages.purchase.show', compact('purchase', 'inventories'));
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
        $purchaseDetail = PurchaseDetail::findorfail($id);
        $product = Inventory::findorfail($request->inventory_id);

        $product->update([
            'stock' => ($product->stock - $purchaseDetail->qty) + $request->qty
        ]);

        $purchaseDetail->purchase_id = $purchaseDetail->purchase_id;
        $purchaseDetail->inventory_id = $request->inventory_id;
        $purchaseDetail->qty = $request->qty;
        $purchaseDetail->price = $request->qty * $product->price;
        $purchaseDetail->save();

        Alert::success('Berhasil', 'Purchase Berhasil Diupdate');
        return redirect()->route('purchase.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchaseDetail = PurchaseDetail::findorfail($id);
        $product = Inventory::findorfail($purchaseDetail->inventory_id);
        $product->update([
            'stock' => $product->stock - $purchaseDetail->qty
        ]);

        $purchaseDetail->delete();

        Alert::success('Berhasil', 'Barang Berhasil Dihapus');
        return redirect()->route('purchase.index');
    }

    public function deletePurchase(string $id)
    {
        $purchase = Purchase::findorfail($id);
        foreach ($purchase->purchasedetail as $sale) {
            $product = Inventory::findorfail($sale->inventory_id);
            $product->update([
                'stock' => $product->stock - $sale->qty
            ]);
        }

        $purchase->delete();

        Alert::success('Berhasil', 'Purchase Berhasil Dihapus');
        return redirect()->route('purchase.index');
    }
}
