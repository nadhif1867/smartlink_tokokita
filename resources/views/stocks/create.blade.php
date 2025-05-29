@extends('layouts.app')

@section('title', 'Tambah Stok Baru')

@section('content')
<div class="container">
    <h1>Form Pembuatan Stok Baru</h1>
    <form action="{{ route('stocks.store') }}" method="POST">
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
            <label for="unit_price" class="form-label">Harga per Unit (Harga Beli Batch)</label>
            <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" min="0" required>
            @error('unit_price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="date_received" class="form-label">Tanggal Penerimaan</label>
            <input type="date" class="form-control" id="date_received" name="date_received" value="{{ old('date_received', date('Y-m-d')) }}" required>
            @error('date_received')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan Stok</button>
    </form>
</div>
@endsection