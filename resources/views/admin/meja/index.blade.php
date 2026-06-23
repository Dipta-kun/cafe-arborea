@extends('layouts.admin')
@section('title', 'Meja & QR Code')

@push('styles')
<style>
.qr-img { width: 60px; height: 60px; border-radius: 8px; border: 1px solid #dee2e6; padding: 4px; cursor: pointer; }
.qr-placeholder { width: 60px; height: 60px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="font-family:'Poppins',sans-serif;">Manajemen Meja & QR Code</h5>
        <p class="text-muted small mb-0">Kelola meja dan generate QR Code untuk pelanggan</p>
    </div>
    <button class="btn-primary-custom btn" onclick="openMejaModal()">
        <i class="bi bi-plus-lg me-2"></i>Tambah Meja
    </button>
</div>

<div class="card-modern p-4">
    <div class="table-responsive">
        <table id="tblMeja" class="table table-hover align-middle w-100">
            <thead>
                <tr><th>#</th><th>No. Meja</th><th>Nama</th><th>Kapasitas</th><th>Status</th><th>QR Code</th><th>Total Pesanan</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah/Edit --}}
<div class="modal fade" id="modalMeja" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="mejaModalTitle">Tambah Meja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMeja">
                @csrf
                <input type="hidden" id="mejaId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Nomor Meja <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="nomor_meja" placeholder="01">
                            <div class="invalid-feedback" id="err_nomor"></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Nama Meja</label>
                            <input type="text" class="form-control rounded-3" id="nama_meja" placeholder="Meja VIP">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control rounded-3" id="kapasitas" value="4" min="1" max="20">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select rounded-3" id="meja_status">
                                <option value="tersedia">Tersedia</option>
                                <option value="terisi">Terisi</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <input type="text" class="form-control rounded-3" id="keterangan" placeholder="Area indoor / outdoor...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom btn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="mejaSpinner"></span>Simpan & Generate QR
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal QR Preview --}}
<div class="modal fade" id="modalQrPreview" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 shadow text-center p-4">
            <h6 class="fw-bold mb-3" id="qrModalTitle">QR Code Meja</h6>
            <img id="qrImgLarge" src="" style="width:200px;height:200px;object-fit:cover;border-radius:12px;" class="mx-auto d-block mb-3">
            <p class="text-muted small mb-3" id="qrUrlText"></p>
            <div class="d-flex gap-2 justify-content-center">
                <a id="qrDownloadBtn" href="#" class="btn btn-sm btn-primary rounded-3"><i class="bi bi-download me-1"></i>Download</a>
                <a id="qrCetakBtn" href="#" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3"><i class="bi bi-printer me-1"></i>Cetak</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let mejaEditId = null;
const mejaModal   = new bootstrap.Modal(document.getElementById('modalMeja'));
const qrPreview   = new bootstrap.Modal(document.getElementById('modalQrPreview'));

const statusBadge = { tersedia:'success', terisi:'warning', tidak_aktif:'secondary' };
const statusLabel = { tersedia:'Tersedia', terisi:'Terisi', tidak_aktif:'Tidak Aktif' };

const mejaTable = $('#tblMeja').DataTable({
    ajax: { url: '{{ route("admin.meja.index") }}', dataSrc: 'data' },
    columns: [
        { data: null, render: (d,t,r,m) => m.row + 1 },
        { data: 'nomor_meja', render: d => `<span class="fw-bold fs-5">${d}</span>` },
        { data: 'nama_meja', defaultContent: '-' },
        { data: 'kapasitas', render: d => `<i class="bi bi-people"></i> ${d}` },
        { data: 'status', render: d => `<span class="badge bg-${statusBadge[d]}">${statusLabel[d]}</span>` },
        { data: null, render: (d,t,r) => 
            `<img src="/admin/meja/${r.id}/qrcode/inline" class="qr-img" onclick="previewQr(${r.id},'${r.nomor_meja}','/admin/meja/${r.id}/qrcode/inline')" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" /><div class="qr-placeholder" style="display:none;">📱</div>`
        },
        { data: 'pesanan_count', render: d => `<span class="badge bg-primary rounded-pill">${d}</span>` },
        { data: null, className: 'text-center', render: (d,t,r) =>
            `<button class="btn btn-sm btn-outline-primary rounded-3 me-1" onclick="editMeja(${r.id})"><i class="bi bi-pencil"></i></button>
             <button class="btn btn-sm btn-outline-success rounded-3 me-1" onclick="previewQr(${r.id},'${r.nomor_meja}','/admin/meja/${r.id}/qrcode/inline')"><i class="bi bi-qr-code"></i></button>
             <button class="btn btn-sm btn-outline-danger rounded-3" onclick="hapusMeja(${r.id},'${r.nomor_meja}')"><i class="bi bi-trash"></i></button>`
        },
    ],
    order: [[1,'asc']], pageLength: 15,
    language: { search:'Cari:', info:'_START_-_END_ dari _TOTAL_', paginate:{previous:'‹',next:'›'} }
});

function openMejaModal() {
    mejaEditId = null;
    document.getElementById('mejaModalTitle').textContent = 'Tambah Meja';
    document.getElementById('formMeja').reset();
    document.getElementById('kapasitas').value = 4;
    mejaModal.show();
}

function editMeja(id) {
    fetch(`/admin/meja/${id}`)
        .then(r => r.json()).then(d => {
            mejaEditId = id;
            const m = d.data;
            document.getElementById('mejaModalTitle').textContent = 'Edit Meja';
            document.getElementById('nomor_meja').value  = m.nomor_meja;
            document.getElementById('nama_meja').value   = m.nama_meja ?? '';
            document.getElementById('kapasitas').value   = m.kapasitas;
            document.getElementById('meja_status').value = m.status;
            document.getElementById('keterangan').value  = m.keterangan ?? '';
            mejaModal.show();
        });
}

function previewQr(id, nomor, qrUrl) {
    document.getElementById('qrModalTitle').textContent = `QR Code Meja ${nomor}`;
    document.getElementById('qrImgLarge').src = qrUrl;
    document.getElementById('qrUrlText').textContent = `${window.location.origin}/menu/${nomor}`;
    document.getElementById('qrDownloadBtn').href = `/admin/meja/${id}/qrcode/download`;
    document.getElementById('qrCetakBtn').href = `/admin/meja/${id}/qrcode/cetak`;
    qrPreview.show();
}

function hapusMeja(id, nomor) {
    Swal.fire({ title:`Hapus Meja ${nomor}?`, icon:'warning', showCancelButton:true,
        confirmButtonColor:'#dc3545', confirmButtonText:'Hapus', cancelButtonText:'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({ url:`/admin/meja/${id}`, type:'DELETE',
                success: res => { showToast('success', res.message); mejaTable.ajax.reload(); },
                error: err => showToast('error', err.responseJSON?.message ?? 'Gagal menghapus.')
            });
        }
    });
}

$('#formMeja').on('submit', function(e) {
    e.preventDefault();
    const data = {
        nomor_meja: $('#nomor_meja').val(),
        nama_meja: $('#nama_meja').val(),
        kapasitas: $('#kapasitas').val(),
        status: $('#meja_status').val(),
        keterangan: $('#keterangan').val(),
    };
    const url    = mejaEditId ? `/admin/meja/${mejaEditId}` : '/admin/meja';
    const method = mejaEditId ? 'PATCH' : 'POST';
    $('#mejaSpinner').removeClass('d-none');

    $.ajax({ url, type: method, data,
        success: res => {
            $('#mejaSpinner').addClass('d-none');
            mejaModal.hide();
            showToast('success', res.message);
            mejaTable.ajax.reload();
        },
        error: err => {
            $('#mejaSpinner').addClass('d-none');
            const errors = err.responseJSON?.errors;
            if (errors?.nomor_meja) { $('#nomor_meja').addClass('is-invalid'); $('#err_nomor').text(errors.nomor_meja[0]); }
            else showToast('error', err.responseJSON?.message ?? 'Terjadi kesalahan.');
        }
    });
});
</script>
@endpush
