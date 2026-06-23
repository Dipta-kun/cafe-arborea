@extends('layouts.admin')
@section('title', 'Kategori')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="font-family:'Poppins',sans-serif;">Manajemen Kategori</h5>
        <p class="text-muted small mb-0">Kelola kategori menu café Arborea</p>
    </div>
    <button class="btn-primary-custom btn" onclick="openModal()">
        <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
    </button>
</div>

<div class="card-modern p-4">
    <div class="table-responsive">
        <table id="tblKategori" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Icon</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Menu</th>
                    <th>Status</th>
                    <th>Urutan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah/Edit --}}
<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formKategori">
                @csrf
                <input type="hidden" id="kategoriId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="nama_kategori" placeholder="Contoh: Kopi Panas">
                        <div class="invalid-feedback" id="err_nama"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon Bootstrap</label>
                        <div class="input-group">
                            <span class="input-group-text" id="iconPreview"><i class="bi bi-grid"></i></span>
                            <input type="text" class="form-control rounded-end-3" id="icon" placeholder="bi-cup-hot" value="bi-grid">
                        </div>
                        <div class="form-text">Gunakan nama kelas Bootstrap Icons. Contoh: bi-cup-hot</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control rounded-3" id="deskripsi" rows="3" placeholder="Deskripsi kategori..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" class="form-control rounded-3" id="urutan" value="0" min="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom btn" id="btnSimpan">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="loadSpinner"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editId = null;
const modal = new bootstrap.Modal(document.getElementById('modalKategori'));

const table = $('#tblKategori').DataTable({
    ajax: { url: '{{ route("admin.kategori.index") }}', dataSrc: 'data' },
    columns: [
        { data: null, render: (d,t,r,m) => m.row + 1 },
        { data: 'nama_kategori', className: 'fw-semibold' },
        { data: 'icon', render: d => `<i class="bi ${d} fs-4 text-success"></i>` },
        { data: 'deskripsi', defaultContent: '-', render: d => d ? (d.length > 40 ? d.substr(0,40)+'…' : d) : '-' },
        { data: 'menu_count', render: d => `<span class="badge bg-primary rounded-pill">${d}</span>` },
        { data: 'is_active', render: (d,t,r) =>
            `<div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" ${d ? 'checked' : ''} onchange="toggleStatus(${r.id},this)">
            </div>`
        },
        { data: 'urutan' },
        { data: null, className: 'text-center', render: (d,t,r) =>
            `<button class="btn btn-sm btn-outline-primary rounded-3 me-1" onclick="editKategori(${r.id})"><i class="bi bi-pencil"></i></button>
             <button class="btn btn-sm btn-outline-danger rounded-3" onclick="hapusKategori(${r.id},'${r.nama_kategori}')"><i class="bi bi-trash"></i></button>`
        },
    ],
    order: [[6,'asc']], pageLength: 10, language: {
        search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
        zeroRecords: 'Data tidak ditemukan', info: 'Menampilkan _START_-_END_ dari _TOTAL_ data',
        paginate: { previous: 'Sebelum', next: 'Selanjutnya' }
    }
});

function openModal(id = null) {
    editId = id;
    clearErrors();
    if (!id) {
        document.getElementById('modalTitle').textContent = 'Tambah Kategori';
        document.getElementById('formKategori').reset();
        document.getElementById('is_active').checked = true;
        document.getElementById('icon').value = 'bi-grid';
        document.getElementById('iconPreview').innerHTML = '<i class="bi bi-grid"></i>';
    }
    modal.show();
}

function editKategori(id) {
    fetch(`/admin/kategori/${id}`)
        .then(r => r.json()).then(d => {
            editId = id;
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('nama_kategori').value = d.data.nama_kategori;
            document.getElementById('icon').value = d.data.icon;
            document.getElementById('iconPreview').innerHTML = `<i class="bi ${d.data.icon}"></i>`;
            document.getElementById('deskripsi').value = d.data.deskripsi ?? '';
            document.getElementById('urutan').value = d.data.urutan;
            document.getElementById('is_active').checked = d.data.is_active;
            modal.show();
        });
}

function hapusKategori(id, nama) {
    Swal.fire({
        title: `Hapus "${nama}"?`, text: 'Kategori tidak dapat dihapus jika masih ada menu.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({ url: `/admin/kategori/${id}`, type: 'DELETE',
                success: res => { showToast('success', res.message); table.ajax.reload(); },
                error: err => showToast('error', err.responseJSON?.message ?? 'Gagal menghapus.')
            });
        }
    });
}

function toggleStatus(id, el) {
    $.ajax({ url: `/admin/kategori/${id}/toggle`, type: 'PATCH',
        success: res => showToast('success', res.message),
        error: () => { el.checked = !el.checked; showToast('error', 'Gagal mengubah status.'); }
    });
}

$('#formKategori').on('submit', function(e) {
    e.preventDefault();
    clearErrors();
    const data = {
        nama_kategori: $('#nama_kategori').val(),
        icon: $('#icon').val(),
        deskripsi: $('#deskripsi').val(),
        urutan: $('#urutan').val(),
        is_active: $('#is_active').is(':checked') ? 1 : 0,
    };
    const url    = editId ? `/admin/kategori/${editId}` : '/admin/kategori';
    const method = editId ? 'PATCH' : 'POST';
    $('#loadSpinner').removeClass('d-none');

    $.ajax({ url, type: method, data,
        success: res => {
            $('#loadSpinner').addClass('d-none');
            modal.hide();
            showToast('success', res.message);
            table.ajax.reload();
        },
        error: err => {
            $('#loadSpinner').addClass('d-none');
            const errors = err.responseJSON?.errors;
            if (errors) {
                if (errors.nama_kategori) showError('nama_kategori', 'err_nama', errors.nama_kategori[0]);
            } else {
                showToast('error', err.responseJSON?.message ?? 'Terjadi kesalahan.');
            }
        }
    });
});

// Preview icon
$('#icon').on('input', function() {
    $('#iconPreview').html(`<i class="bi ${$(this).val()}"></i>`);
});

function showError(fieldId, errId, msg) {
    $(`#${fieldId}`).addClass('is-invalid');
    $(`#${errId}`).text(msg);
}
function clearErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}
</script>
@endpush
