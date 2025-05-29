@extends('layouts.app')

@section('title', 'Daftar Pembelian')

@section('content')
<div class="container">
    <h1>Daftar Pembelian</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Kuantitas</th>
                <th>Harga Beli per Unit</th>
                <th>Total Harga</th>
                <th>Tanggal Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->id }}</td>
                    <td>{{ $purchase->item->name }}</td>
                    <td>{{ $purchase->quantity }}</td>
                    <td>Rp {{ number_format($purchase->unit_price, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($purchase->quantity * $purchase->unit_price, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d F Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data pembelian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection