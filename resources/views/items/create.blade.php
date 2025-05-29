@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="container">
    <h1>Tambah Barang Baru</h1>
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Barang</button>
    </form>
</div>
@endsection