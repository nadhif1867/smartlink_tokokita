<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;

class PurchaseSaleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_and_sale_flow()
    {
        // 1. Membuat item
        $item = Item::factory()->create(['name' => 'Monitor']);
        $this->assertDatabaseHas('items', ['name' => 'Monitor']);

        // 2. Mencatat penjualan (tambah ke stok)
        $purchaseResponse = $this->post(route('purchases.store'), [
            'item_id' => $item->id,
            'quantity' => 10,
            'unit_price' => 1500000,
            'purchase_date' => Carbon::parse('2024-04-01')->format('Y-m-d')
        ]);

        $purchaseResponse->assertRedirect(route('purchases.create'));
        $purchaseResponse->assertSessionHas('success', 'Purchase recorded successfully!');
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'quantity' => 10,
            'unit_price' => 1500000,
        ]);
        // meverifikasi bahwa stok ditambahkan
        $this->assertDatabaseHas('stocks', [
            'item_id' => $item->id,
            'quantity' => 10,
            'unit_price' => 1500000,
            'date_received' => Carbon::parse('2024-04-01')->format('Y-m-d')
        ]);

        $purchaseResponse2 = $this->post(route('purchases.store'), [
            'item_id' => $item->id,
            'quantity' => 5,
            'unit_price' => 1600000,
            'purchase_date' => Carbon::parse('2024-04-05')->format('Y-m-d')
        ]);

        $purchaseResponse2->assertRedirect(route('purchases.create'));
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'quantity' => 5,
            'unit_price' => 1600000,
        ]);
        $this->assertDatabaseHas('stocks', [
            'item_id' => $item->id,
            'quantity' => 5,
            'unit_price' => 1600000,
            'date_received' => Carbon::parse('2024-04-05')->format('Y-m-d')
        ]);

        // Total stok
        $this->assertEquals(15, Stock::where('item_id', $item->id)->sum('quantity'));

        $saleResponse = $this->post(route('sales.store'), [
            'item_id' => $item->id,
            'quantity' => 12,
            'selling_price_per_unit' => 2000000,
            'sale_date' => Carbon::parse('2024-04-10')->format('Y-m-d')
        ]);

        $saleResponse->assertRedirect(route('sales.create'));
        $saleResponse->assertSessionHas('success', 'Sale recorded successfully!');
        $this->assertDatabaseHas('sales', [
            'item_id' => $item->id,
            'quantity' => 12,
            'selling_price_per_unit' => 2000000,
        ]);

        // Hitung HPP: (10 * 1.5M) + (2 * 1.6M) = 15,000,000 + 3,200,000 = 18,200,000
        $this->assertEquals(18200000, Sale::first()->cost_of_goods_sold);

        // Mengecek stok
        $firstBatchStock = Stock::where('item_id', $item->id)
            ->where('unit_price', 1500000)
            ->first();
        $this->assertEquals(0, $firstBatchStock->quantity); // First batch fully depleted

        $secondBatchStock = Stock::where('item_id', $item->id)
            ->where('unit_price', 1600000)
            ->first();
        $this->assertEquals(3, $secondBatchStock->quantity); // 5 - 2 = 3 remaining

        // Total sisa stok
        $this->assertEquals(3, Stock::where('item_id', $item->id)->sum('quantity'));

        // 5. Melakukan dengan stok yang tidak cukup
        $insufficientSaleResponse = $this->post(route('sales.store'), [
            'item_id' => $item->id,
            'quantity' => 5, 
            'selling_price_per_unit' => 2000000,
            'sale_date' => Carbon::parse('2024-04-12')->format('Y-m-d')
        ]);

        $insufficientSaleResponse->assertSessionHas('error', 'Stok tidak cukup untuk Monitor. Tersedia: 3, Diminta: 5');
        $this->assertDatabaseCount('sales', 1); 
        $this->assertEquals(3, Stock::where('item_id', $item->id)->sum('quantity')); 
    }
}
