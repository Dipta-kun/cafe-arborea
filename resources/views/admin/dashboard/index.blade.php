@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
    .chart-container { position: relative; height: 280px; }
    .order-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
{{-- Stats Cards --}}
<div class="row g-3 mb-4 fade-in-up">
    <div class="col-6 col-lg-3">
        <div class="stat-card green h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon green"><i class="bi bi-journal-bookmark"></i></div>
                <span class="text-muted small">Menu</span>
            </div>
            <div class="h3 fw-bold mb-0" style="font-family:'Poppins',sans-serif;">{{ $stats['total_menu'] }}</div>
            <div class="text-muted small mt-1">Total Item Menu</div>
            @if($stats['menu_habis'] > 0)
                <div class="mt-2"><span class="badge bg-danger">{{ $stats['menu_habis'] }} habis</span></div>
            @endif
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card brown h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon brown"><i class="bi bi-tags"></i></div>
                <span class="text-muted small">Kategori</span>
            </div>
            <div class="h3 fw-bold mb-0" style="font-family:'Poppins',sans-serif;">{{ $stats['total_kategori'] }}</div>
            <div class="text-muted small mt-1">Kategori Aktif</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card blue h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon blue"><i class="bi bi-receipt"></i></div>
                <span class="text-muted small">Pesanan</span>
            </div>
            <div class="h3 fw-bold mb-0" style="font-family:'Poppins',sans-serif;">{{ $stats['total_pesanan'] }}</div>
            <div class="text-muted small mt-1">Total Pesanan</div>
            @if($stats['pesanan_aktif'] > 0)
                <div class="mt-2"><span class="badge bg-warning text-dark">{{ $stats['pesanan_aktif'] }} aktif</span></div>
            @endif
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card orange h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon orange"><i class="bi bi-cash-coin"></i></div>
                <span class="text-muted small">Pendapatan</span>
            </div>
            <div class="fw-bold mb-0" style="font-family:'Poppins',sans-serif;font-size:18px;">
                Rp {{ number_format($stats['pendapatan_hari'], 0, ',', '.') }}
            </div>
            <div class="text-muted small mt-1">Hari Ini</div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-modern p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0" style="font-family:'Poppins',sans-serif;">
                    <i class="bi bi-graph-up-arrow text-success me-2"></i>Penjualan Bulanan {{ date('Y') }}
                </h6>
            </div>
            <div class="chart-container">
                <canvas id="chartPenjualan"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-modern p-4 h-100">
            <h6 class="fw-bold mb-3" style="font-family:'Poppins',sans-serif;">
                <i class="bi bi-star-fill text-warning me-2"></i>Menu Terlaris
            </h6>
            <div class="chart-container" style="height:240px;">
                <canvas id="chartMenu"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="card-modern p-4">
            <h6 class="fw-bold mb-3" style="font-family:'Poppins',sans-serif;">
                <i class="bi bi-calendar3 text-primary me-2"></i>Pesanan 7 Hari Terakhir
            </h6>
            <div class="chart-container" style="height:200px;">
                <canvas id="chartHarian"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card-modern p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0" style="font-family:'Poppins',sans-serif;">
                    <i class="bi bi-clock-history text-info me-2"></i>Pesanan Terbaru
                </h6>
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr>
                        <th>Kode</th><th>Pelanggan</th><th>Meja</th><th>Total</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                        @forelse($pesananTerbaru as $p)
                        <tr>
                            <td><span class="badge bg-light text-dark fw-bold">{{ $p->kode_pesanan }}</span></td>
                            <td>{{ $p->nama_pelanggan }}</td>
                            <td>{{ $p->meja?->nomor_meja }}</td>
                            <td class="fw-semibold">{{ $p->total_harga_format }}</td>
                            <td>
                                <span class="order-badge badge-{{ $p->status }}">{{ $p->status_label }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pesanan hari ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const primaryColor = '#1B4332';
const secondaryColor = '#8B5E3C';

// Chart Penjualan Bulanan
fetch('{{ route("admin.chart.penjualan") }}')
    .then(r => r.json()).then(d => {
        new Chart(document.getElementById('chartPenjualan'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: d.data,
                    backgroundColor: 'rgba(27,67,50,.15)',
                    borderColor: primaryColor,
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(27,67,50,.3)',
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k' } }
                }
            }
        });
    });

// Chart Menu Terlaris
fetch('{{ route("admin.chart.menu") }}')
    .then(r => r.json()).then(d => {
        const colors = ['#1B4332','#2D6A4F','#8B5E3C','#52b788','#95d5b2','#b7e4c7','#d8f3dc','#40916c'];
        new Chart(document.getElementById('chartMenu'), {
            type: 'doughnut',
            data: { labels: d.labels, datasets: [{ data: d.data, backgroundColor: colors, borderWidth: 0 }] },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                cutout: '60%'
            }
        });
    });

// Chart Pesanan Harian
fetch('{{ route("admin.chart.pesanan") }}')
    .then(r => r.json()).then(d => {
        new Chart(document.getElementById('chartHarian'), {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [{
                    label: 'Pesanan',
                    data: d.data,
                    borderColor: secondaryColor,
                    backgroundColor: 'rgba(139,94,60,.1)',
                    borderWidth: 2, fill: true,
                    tension: 0.4,
                    pointBackgroundColor: secondaryColor,
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    });
</script>
@endpush
