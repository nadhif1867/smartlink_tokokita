@extends('layouts.app')

@section('title', 'Laporan Laba Bulanan & Sisa Stok')

@section('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container">
    <h1>Laporan Laba Bulanan & Sisa Stok</h1>

    <div class="mb-4 card card-body shadow-sm">
        <h2>Laba Bulanan Tahun {{ $year }}</h2>
        <form action="{{ route('reports.monthly_profit') }}" method="GET" class="mb-3 d-flex align-items-center">
            <label for="year" class="form-label me-2 mb-0">Pilih Tahun:</label>
            <select name="year" id="year" class="form-select w-auto d-inline-block me-2">
                @for ($y = \Carbon\Carbon::now()->year; $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary">Lihat Laporan</button>
        </form>
        <div class="chart-container" style="position: relative; height:40vh; width:65vw">
            <canvas id="monthlyProfitChart"></canvas>
        </div>
    </div>

    <hr class="my-4">

    <div class="mb-4 card card-body shadow-sm">
        <h2>Sisa Stok per Item</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Total Kuantitas Sisa</th>
                    <th>Estimasi Nilai Sisa Stok (HPP)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($remainingStocks as $stock)
                    <tr>
                        <td>{{ $stock['item_name'] }}</td>
                        <td>{{ $stock['total_quantity'] }}</td>
                        <td>Rp {{ number_format($stock['total_value'], 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada sisa stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthlyProfitChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Grafik batang sederhana untuk visualisasi 
            data: {
                labels: {!! json_encode($profitLabels) !!},
                datasets: [{
                    label: 'Laba (Penjualan - HPP)',
                    data: {!! json_encode($profitValues) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Rupiah (Rp)'
                        },
                        ticks: {
                            callback: function(value, index, ticks) {
                                return 'Rp ' + value.toLocaleString('id-ID'); // Format mata uang
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection