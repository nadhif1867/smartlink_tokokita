<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('selling_price_per_unit', 10, 2); // Harga jual per unit [cite: 4]
            $table->decimal('cost_of_goods_sold', 10, 2); // HPP berdasarkan FIFO [cite: 5]
            $table->date('sale_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::drop('sales');
    }
};
