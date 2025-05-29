<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function monthlyProfit(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $monthlyProfits = Sale::select(
                                DB::raw('MONTH(sale_date) as month'),
                                DB::raw('SUM(quantity * selling_price_per_unit) as total_revenue'),
                                DB::raw('SUM(cost_of_goods_sold) as total_hpp')
                            )
                            ->whereYear('sale_date', $year)
                            ->groupBy(DB::raw('MONTH(sale_date)'))
                            ->orderBy(DB::raw('MONTH(sale_date)'))
                            ->get();

        $profitData = [];
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create(null, $i, 1)->format('M');
            $months[] = $monthName;
            $profitData[$monthName] = 0; 
        }

        foreach ($monthlyProfits as $profit) {
            $monthName = Carbon::create(null, $profit->month, 1)->format('M');
            $profitData[$monthName] = $profit->total_revenue - $profit->total_hpp; 
        }

        $profitLabels = array_keys($profitData); 
        $profitValues = array_values($profitData); 

        $remainingStocks = Item::with('stocks')
                               ->get()
                               ->map(function ($item) {
                                   $totalQuantity = $item->stocks->sum('quantity');
                                   $totalValue = $item->stocks->sum(function ($stock) {
                                       return $stock->quantity * $stock->unit_price;
                                   });
                                   return [
                                       'item_name' => $item->name,
                                       'total_quantity' => $totalQuantity,
                                       'total_value' => $totalValue, 
                                   ];
                               });

        return view('reports.monthly_profit', compact('profitLabels', 'profitValues', 'remainingStocks', 'year'));
    }
}
