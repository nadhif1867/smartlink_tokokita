<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create()
    {
        $items = Item::all();
        return view('sales.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'selling_price_per_unit' => 'required|numeric|min:0', 
            'sale_date' => 'required|date',
        ]);

        $item = Item::find($request->item_id);
        $requestedQuantity = $request->quantity;
        $totalCostOfGoodsSold = 0; 

        try {
            DB::transaction(function () use ($item, $requestedQuantity, &$totalCostOfGoodsSold, $request) {
                $availableStocks = Stock::where('item_id', $item->id)
                                        ->where('quantity', '>', 0)
                                        ->orderBy('date_received', 'asc')
                                        ->orderBy('id', 'asc') 
                                        ->lockForUpdate() 
                                        ->get();

                $currentStockQuantity = $availableStocks->sum('quantity');

                if ($currentStockQuantity < $requestedQuantity) {
                    throw new \Exception('Stok tidak cukup untuk ' . $item->name . '. Tersedia: ' . $currentStockQuantity . ', Diminta: ' . $requestedQuantity);
                }

                $remainingQuantityToSell = $requestedQuantity;

                foreach ($availableStocks as $stock) {
                    if ($remainingQuantityToSell <= 0) {
                        break;
                    }

                    $quantityFromBatch = min($remainingQuantityToSell, $stock->quantity);
                    $totalCostOfGoodsSold += $quantityFromBatch * $stock->unit_price;

                    $stock->quantity -= $quantityFromBatch;
                    $stock->save();

                    $remainingQuantityToSell -= $quantityFromBatch;
                }

                Sale::create([
                    'item_id' => $item->id,
                    'quantity' => $requestedQuantity,
                    'selling_price_per_unit' => $request->selling_price_per_unit,
                    'cost_of_goods_sold' => $totalCostOfGoodsSold, 
                    'sale_date' => $request->sale_date,
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('sales.create')->with('success', 'Sale recorded successfully!');
    }

    public function index()
    {
        $sales = Sale::with('item')->latest()->get();
        return view('sales.index', compact('sales'));
    }
}
