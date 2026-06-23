@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
<div class="mb-4">
    <h5 class="fw-bold mb-1" style="font-family:'Poppins',sans-serif;">Laporan Penjualan</h5>
    <p class="text-muted small mb-0">Filter dan export laporan transaksi</p>
</div>

{{-- Filter Card --}}
<div class="card-modern p-4 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Filter Periode</label>
            <select class="form-select rounded-3" id="filterPeriode">
                <option value="harian">Harian</option>
                <option value="bulanan" selected>Bulanan</option>
                <option value="tahunan">Tahunan</option>
            </select>
        </div>
        <div class="col-md-2" id="wrapTanggal" style="display:none;">
            <label class="form-label fw-semibold">Tanggal</label>
            <input type="date" class="form-control rounded-3" id="filterTanggal" value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-2" id="wrapBulan">
            <label class="form-label fw-semibold">Bulan</label>
            <select class="form-select rounded-3" id="filterBulan">
                @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $b)
                    <option value="{{ $i+1 }}" {{ ($i+1) == date('m') ? 'selected':'' }}>{{ $b }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Tahun</label>
            <select class="form-select rounded-3" id="filterTahun">
                @for($y = date('Y'); $y >= date('Y')-4; $y--)
                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn-primary-custom btn flex-grow-1" onclick="loadLaporan()">
                <i class="bi bi-search me-1"></i>Tampilkan
            </button>
            <button class="btn btn-success rounded-3" onclick="exportPdf()">
                <i class="bi bi-file-pdf me-1"></i>PDF
            </button>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4" id="summaryCards" style="display:none!important;">
    <div class="col-md-4">
        <div class="stat-card green text-center"><div class="h4 fw-bold text-success mb-1" id="sumPendapatan">-</div><div class="text-muted small">Total Pendapatan</div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card blue text-center"><div class="h4 fw-bold mb-1" id="sumPesanan">-</div><div class="text-muted small">Total Pesanan</div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card brown text-center"><div class="h4 fw-bold mb-1" id="sumItem">-</div><div class="text-muted small">Total Item Terjual</div></div>
    </div>
</div>

{{-- Table --}}
<div class="card-modern p-4">
    <table id="tblLaporan" class="table table-hover align-middle w-100">
        <thead>
            <tr><th>#</th><th>Kode</th><th>Pelanggan</th><th>Meja</th><th>Items</th><th>Total</th><th>Status</th><th>Waktu</th></tr>
        </thead>
        <tbody id="tblBody"><tr><td colspan="8" class="text-center text-muted py-4">Pilih periode dan klik Tampilkan</td></tr></tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$('#filterPeriode').on('change', function() {
    const v = $(this).val();
    $('#wrapTanggal').toggle(v === 'harian');
    $('#wrapBulan').toggle(v === 'bulanan');
});

function getParams() {
    return {
        filter: $('#filterPeriode').val(),
        tanggal: $('#filterTanggal').val(),
        bulan: $('#filterBulan').val(),
        tahun: $('#filterTahun').val(),
    };
}

function loadLaporan() {
    $.get('{{ route("admin.laporan.data") }}', getParams(), res => {
        $('#sumPendapatan').text(res.total_pendapatan_format);
        $('#sumPesanan').text(res.total_pesanan);
        $('#sumItem').text(res.total_item);
        $('#summaryCards').css('display','flex');

        let rows = '';
        if (res.data.length === 0) {
            rows = '<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data</td></tr>';
        } else {
            res.data.forEach((p, i) => {
                rows += `<tr>
                    <td>${i+1}</td>
                    <td><span class="badge bg-dark">${p.kode_pesanan}</span></td>
                    <td>${p.nama_pelanggan}</td>
                    <td>${p.meja?.nomor_meja ?? '-'}</td>
                    <td>${p.total_item}</td>
                    <td class="fw-bold text-success">Rp ${Number(p.total_harga).toLocaleString('id-ID')}</td>
                    <td><span class="badge badge-${p.status}">${p.status_label ?? p.status}</span></td>
                    <td class="text-muted small">${new Date(p.created_at).toLocaleString('id-ID')}</td>
                </tr>`;
            });
        }
        $('#tblBody').html(rows);
    }).fail(() => showToast('error', 'Gagal memuat data.'));
}

function exportPdf() {
    const params = new URLSearchParams(getParams());
    window.open(`{{ route('admin.laporan.pdf') }}?${params}`, '_blank');
}
</script>
@endpush
