@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('content')
<div class="container">
    <h1>Daftar Penjualan</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Kuantitas</th>
                <th>Harga Jual per Unit</th>
                <th>Total Penjualan</th>
                <th>HPP (FIFO)</th>
                <th>Laba Kotor</th>
                <th>Tanggal Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->item->name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>Rp {{ number_format($sale->selling_price_per_unit, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($sale->quantity * $sale->selling_price_per_unit, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($sale->cost_of_goods_sold, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format(($sale->quantity * $sale->selling_price_per_unit) - $sale->cost_of_goods_sold, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d F Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection