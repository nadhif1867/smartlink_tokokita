@extends('layouts.app')

@section('title', 'Catat Penjualan')

@section('content')
<div class="container">
    <h1>Form Catat Penjualan</h1>
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="item_id" class="form-label">Nama Barang</label>
            <select class="form-select" id="item_id" name="item_id" required>
                <option value="">Pilih Barang</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-total-stock="{{ $item->stocks->sum('quantity') }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('item_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Kuantitas Terjual</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            <small class="text-muted" id="stock-info"></small>
            @error('quantity')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="selling_price_per_unit" class="form-label">Harga Jual per Unit</label>
            <input type="number" class="form-control" id="selling_price_per_unit" name="selling_price_per_unit" step="0.01" min="0" required>
            @error('selling_price_per_unit')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="sale_date" class="form-label">Tanggal Penjualan</label>
            <input type="date" class="form-control" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
            @error('sale_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary" id="submit-sale">Simpan Penjualan</button>
    </form>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemSelect = document.getElementById('item_id');
        const quantityInput = document.getElementById('quantity');
        const stockInfo = document.getElementById('stock-info');
        const submitButton = document.getElementById('submit-sale');

        function updateStockInfo() {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const totalStock = selectedOption.dataset.totalStock;
            if (totalStock !== undefined) {
                stockInfo.textContent = `Stok tersedia: ${totalStock}`;
                const requestedQuantity = parseInt(quantityInput.value);
                if (requestedQuantity > parseInt(totalStock)) {
                    stockInfo.classList.remove('text-muted');
                    stockInfo.classList.add('text-danger');
                    submitButton.disabled = true;
                } else {
                    stockInfo.classList.remove('text-danger');
                    stockInfo.classList.add('text-muted');
                    submitButton.disabled = false;
                }
            } else {
                stockInfo.textContent = '';
                submitButton.disabled = false;
            }
        }

        itemSelect.addEventListener('change', updateStockInfo);
        quantityInput.addEventListener('input', updateStockInfo);

        // Initial update in case of old input or pre-selected item
        updateStockInfo();
    });
</script>
@endsection
@endsection