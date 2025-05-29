<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function create()
    {
        $items = Item::all();
        return view('stocks.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0', 
            'date_received' => 'required|date',
        ]);

        Stock::create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'date_received' => $request->date_received,
        ]);

        return redirect()->route('stocks.create')->with('success', 'Stock batch added successfully!');
    }
}
