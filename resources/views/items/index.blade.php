@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
<div class="container">
    <h1>Daftar Barang</h1>
    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Tambah Barang Baru</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada barang. Silakan <a href="{{ route('items.create') }}">tambah barang baru</a>.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection