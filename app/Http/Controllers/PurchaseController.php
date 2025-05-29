<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function create()
    {
        $items = Item::all();
        return view('purchases.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0', 
            'purchase_date' => 'required|date', 
        ]);

        DB::transaction(function () use ($request) {
            Purchase::create([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'purchase_date' => $request->purchase_date,
            ]);

            Stock::create([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'date_received' => $request->purchase_date,
            ]);
        });

        return redirect()->route('purchases.create')->with('success', 'Purchase recorded successfully!');
    }

    public function index()
    {
        $purchases = Purchase::with('item')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }
}
