@extends('layouts.admin')
@section('title', 'Pesanan')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="font-family:'Poppins',sans-serif;">Manajemen Pesanan</h5>
        <p class="text-muted small mb-0">Monitor dan kelola status pesanan pelanggan</p>
    </div>
    <div class="d-flex gap-2">
        <select id="filterStatus" class="form-select rounded-3 border-0 shadow-sm" style="width:auto;">
            <option value="">Semua Status</option>
            @foreach($statusList as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" id="filterTanggal" class="form-control rounded-3 border-0 shadow-sm" style="width:auto;" value="{{ date('Y-m-d') }}">
        <button class="btn btn-outline-secondary rounded-3" onclick="$('#filterTanggal').val('');reloadTable()">Reset</button>
    </div>
</div>

<div class="card-modern p-4">
    <table id="tblPesanan" class="table table-hover align-middle w-100">
        <thead>
            <tr><th>Kode</th><th>Pelanggan</th><th>Meja</th><th>Items</th><th>Total</th><th>Waktu</th><th>Status</th><th class="text-center">Aksi</th></tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

{{-- Modal Detail Pesanan --}}
<div class="modal fade" id="modalStatusPesanan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Kode: <strong id="modalKodePesanan"></strong></p>
                <label class="form-label fw-semibold">Status Baru</label>
                <select class="form-select rounded-3" id="statusBaru">
                    @foreach($statusList as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                <button class="btn-primary-custom btn" onclick="simpanStatus()">
                    <span class="spinner-border spinner-border-sm d-none me-2" id="statusSpinner"></span>Update Status
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let statusEditId = null;
const statusModal = new bootstrap.Modal(document.getElementById('modalStatusPesanan'));

const statusBadge = {
    menunggu:'badge-menunggu', diproses:'badge-diproses',
    siap_disajikan:'badge-siap_disajikan', selesai:'badge-selesai', dibatalkan:'badge-dibatalkan'
};
const statusLabels = @json($statusList);

const pesananTable = $('#tblPesanan').DataTable({
    ajax: {
        url: '{{ route("admin.pesanan.index") }}',
        data: d => { d.status = $('#filterStatus').val(); d.tanggal = $('#filterTanggal').val(); },
        dataSrc: 'data'
    },
    columns: [
        { data: 'kode_pesanan', render: d => `<span class="badge bg-dark">${d}</span>` },
        { data: 'nama_pelanggan' },
        { data: 'nomor_meja', render: d => `<span class="fw-bold">${d ?? '-'}</span>` },
        { data: 'total_item', render: d => `${d} item` },
        { data: 'total_harga_format', className: 'fw-bold text-success' },
        { data: 'created_at', className: 'text-muted small' },
        { data: null, render: (d,t,r) => `<span class="badge px-3 py-2 ${statusBadge[r.status]}">${r.status_label}</span>` },
        { data: null, className: 'text-center', render: (d,t,r) =>
            `<a href="/admin/pesanan/${r.id}" class="btn btn-sm btn-outline-info rounded-3 me-1"><i class="bi bi-eye"></i></a>
             <button class="btn btn-sm btn-outline-primary rounded-3" onclick="bukaStatusModal(${r.id},'${r.kode_pesanan}','${r.status}')"><i class="bi bi-pencil-square"></i></button>`
        },
    ],
    order: [[5,'desc']], pageLength: 15,
    language: { search:'Cari:', info:'_START_-_END_ dari _TOTAL_', paginate:{previous:'‹',next:'›'} }
});

$('#filterStatus, #filterTanggal').on('change', () => pesananTable.ajax.reload());

function reloadTable() { pesananTable.ajax.reload(); }

function bukaStatusModal(id, kode, statusSaat) {
    statusEditId = id;
    document.getElementById('modalKodePesanan').textContent = kode;
    document.getElementById('statusBaru').value = statusSaat;
    statusModal.show();
}

function simpanStatus() {
    if (!statusEditId) return;
    $('#statusSpinner').removeClass('d-none');
    $.ajax({ url:`/admin/pesanan/${statusEditId}/status`, type:'PATCH',
        data: { status: $('#statusBaru').val() },
        success: res => {
            $('#statusSpinner').addClass('d-none');
            statusModal.hide();
            showToast('success', res.message);
            pesananTable.ajax.reload();
        },
        error: () => { $('#statusSpinner').addClass('d-none'); showToast('error', 'Gagal mengubah status.'); }
    });
}

// Auto-refresh setiap 30 detik
setInterval(() => pesananTable.ajax.reload(null, false), 30000);
</script>
@endpush
