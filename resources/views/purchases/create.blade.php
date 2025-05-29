@extends('layouts.app')

@section('title', 'Catat Pembelian Stok')

@section('content')
<div class="container">
    <h1>Form Catat Pembelian Stok</h1>
    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="item_id" class="form-label">Nama Barang</label>
            <select class="form-select" id="item_id" name="item_id" required>
                <option value="">Pilih Barang</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('item_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Kuantitas</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            @error('quantity')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="unit_price" class="form-label">Harga Beli per Unit</label>
            <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" min="0" required>
            @error('unit_price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
            <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
            @error('purchase_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
    </form>
</div>
@endsection