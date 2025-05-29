<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class FifoLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_fifo_logic_correctly_calculates_cogs_and_depletes_stock()
    {
        // 1. Setup Data
        $item = Item::factory()->create(['name' => 'Buku']);

        // Tambah stok dengan harga dan tanggal berbeda (FIFO principle)
        Stock::create([
            'item_id' => $item->id,
            'quantity' => 10,
            'unit_price' => 5000,
            'date_received' => Carbon::parse('2024-01-01')
        ]); 

        Stock::create([
            'item_id' => $item->id,
            'quantity' => 15,
            'unit_price' => 6000,
            'date_received' => Carbon::parse('2024-01-05')
        ]); 

        Stock::create([
            'item_id' => $item->id,
            'quantity' => 5,
            'unit_price' => 7000,
            'date_received' => Carbon::parse('2024-01-10')
        ]); 

        // 2. Perform Sale (sell 12 units)
        // Harusnya ambil 10 dari Batch 1 (5000) dan 2 dari Batch 2 (6000)
        $response = $this->post(route('sales.store'), [
            'item_id' => $item->id,
            'quantity' => 12,
            'selling_price_per_unit' => 10000,
            'sale_date' => Carbon::parse('2024-01-15')->format('Y-m-d')
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        // 3. Assertions
        $sale = Sale::first();
        $this->assertNotNull($sale);
        $this->assertEquals(12, $sale->quantity);
        $expectedHPP = (10 * 5000) + (2 * 6000); // 50000 + 12000 = 62000
        $this->assertEquals($expectedHPP, $sale->cost_of_goods_sold);

        // Periksa sisa stok
        $batch1 = Stock::find(1);
        $this->assertEquals(0, $batch1->quantity);

        $batch2 = Stock::find(2); 
        $this->assertEquals(13, $batch2->quantity); // 15 - 2 = 13

        $batch3 = Stock::find(3);
        $this->assertEquals(5, $batch3->quantity);
    }

    public function test_sale_rejected_if_stock_insufficient()
    {
        $item = Item::factory()->create(['name' => 'Keyboard']);
        Stock::create([
            'item_id' => $item->id,
            'quantity' => 5,
            'unit_price' => 100000,
            'date_received' => Carbon::parse('2024-02-01')
        ]);

        // Coba jual 10 unit, padahal stok hanya 5
        $response = $this->post(route('sales.store'), [
            'item_id' => $item->id,
            'quantity' => 10,
            'selling_price_per_unit' => 150000,
            'sale_date' => Carbon::parse('2024-02-05')->format('Y-m-d')
        ]);

        $response->assertSessionHas('error', 'Stok tidak cukup untuk Keyboard. Tersedia: 5, Diminta: 10');
        $this->assertDatabaseCount('sales', 0); // Pastikan tidak ada penjualan tercatat
        $this->assertEquals(5, Stock::first()->quantity); // Pastikan stok tidak berubah
    }

    public function test_happy_path_sale()
    {
        $item = Item::factory()->create(['name' => 'Mouse']);
        Stock::create([
            'item_id' => $item->id,
            'quantity' => 20,
            'unit_price' => 20000,
            'date_received' => Carbon::parse('2024-03-01')
        ]);

        $response = $this->post(route('sales.store'), [
            'item_id' => $item->id,
            'quantity' => 5,
            'selling_price_per_unit' => 30000,
            'sale_date' => Carbon::parse('2024-03-05')->format('Y-m-d')
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $sale = Sale::first();
        $this->assertNotNull($sale);
        $this->assertEquals(5, $sale->quantity);
        $this->assertEquals(5 * 20000, $sale->cost_of_goods_sold);
        $this->assertEquals(15, Stock::first()->quantity); 
    }
}