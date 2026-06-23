@extends('layouts.admin')
@section('title', 'Menu')

@push('styles')
<style>
.img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 2px dashed #dee2e6; }
.menu-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="font-family:'Poppins',sans-serif;">Manajemen Menu</h5>
        <p class="text-muted small mb-0">Kelola item menu café</p>
    </div>
    <button class="btn-primary-custom btn" onclick="openMenuModal()">
        <i class="bi bi-plus-lg me-2"></i>Tambah Menu
    </button>
</div>

<div class="card-modern p-4">
    <div class="table-responsive">
        <table id="tblMenu" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>#</th><th>Foto</th><th>Nama Menu</th><th>Kategori</th>
                    <th>Harga</th><th>Stok</th><th>Terjual</th><th>Tersedia</th><th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalMenu" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="menuModalTitle">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMenu" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="menuId">
                <input type="hidden" name="_method" id="menuMethod" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select rounded-3" id="menu_kategori_id" name="kategori_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="err_kategori"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" id="menu_nama" name="nama_menu" placeholder="Contoh: Latte">
                                <div class="invalid-feedback" id="err_nama_menu"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea class="form-control rounded-3" id="menu_deskripsi" name="deskripsi" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control rounded-3" id="menu_harga" name="harga" min="0" step="500">
                                    <div class="invalid-feedback" id="err_harga"></div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control rounded-3" id="menu_stok" name="stok" min="0">
                                    <div class="invalid-feedback" id="err_stok"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Foto Menu</label>
                            <div class="text-center mb-2">
                                <img id="imgPreview" src="{{ asset('images/menu-placeholder.jpg') }}" class="img-preview d-block mx-auto mb-2">
                                <label for="menu_foto" class="btn btn-sm btn-outline-secondary rounded-3 w-100">
                                    <i class="bi bi-camera me-1"></i>Pilih Foto
                                </label>
                                <input type="file" id="menu_foto" name="foto" class="d-none" accept="image/*">
                                <div class="form-text text-center">Max 2MB. JPG, PNG, WebP</div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="menu_is_tersedia" name="is_tersedia" checked>
                                    <label class="form-check-label" for="menu_is_tersedia">Tersedia</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom btn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="menuSpinner"></span>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let menuEditId = null;
const menuModal = new bootstrap.Modal(document.getElementById('modalMenu'));

const menuTable = $('#tblMenu').DataTable({
    ajax: { url: '{{ route("admin.menu.index") }}', dataSrc: 'data' },
    columns: [
        { data: null, render: (d,t,r,m) => m.row + 1 },
        { data: 'foto_url', render: d => `<img src="${d}" class="menu-thumb">` },
        { data: 'nama_menu', className: 'fw-semibold' },
        { data: 'kategori', render: d => `<span class="badge rounded-pill" style="background:#F8F5F0;color:#1B4332;border:1px solid #1B4332;">${d ?? '-'}</span>` },
        { data: 'harga_format', className: 'fw-bold text-success' },
        { data: 'stok', render: d => `<span class="fw-bold ${d == 0 ? 'text-danger' : ''}">${d}</span>` },
        { data: 'terjual', render: d => `<span class="badge bg-warning text-dark">${d}</span>` },
        { data: 'is_tersedia', render: (d,t,r) =>
            `<div class="form-check form-switch"><input class="form-check-input" type="checkbox" ${d ? 'checked':''} onchange="toggleMenu(${r.id},this)"></div>`
        },
        { data: null, className: 'text-center', render: (d,t,r) =>
            `<button class="btn btn-sm btn-outline-primary rounded-3 me-1" onclick="editMenu(${r.id})"><i class="bi bi-pencil"></i></button>
             <button class="btn btn-sm btn-outline-danger rounded-3" onclick="hapusMenu(${r.id},'${r.nama_menu}')"><i class="bi bi-trash"></i></button>`
        },
    ],
    order: [[2,'asc']], pageLength: 10,
    language: { search:'Cari:', lengthMenu:'Tampilkan _MENU_', zeroRecords:'Tidak ada data', info:'_START_-_END_ dari _TOTAL_', paginate:{previous:'‹',next:'›'} }
});

function openMenuModal(id = null) {
    menuEditId = null;
    clearMenuErrors();
    document.getElementById('menuModalTitle').textContent = 'Tambah Menu';
    document.getElementById('formMenu').reset();
    document.getElementById('menu_is_tersedia').checked = true;
    document.getElementById('menuMethod').value = 'POST';
    document.getElementById('imgPreview').src = '{{ asset("images/menu-placeholder.jpg") }}';
    menuModal.show();
}

function editMenu(id) {
    fetch(`/admin/menu/${id}`)
        .then(r => r.json()).then(d => {
            menuEditId = id;
            const m = d.data;
            document.getElementById('menuModalTitle').textContent = 'Edit Menu';
            document.getElementById('menuId').value = id;
            document.getElementById('menuMethod').value = 'PATCH';
            document.getElementById('menu_kategori_id').value = m.kategori_id;
            document.getElementById('menu_nama').value = m.nama_menu;
            document.getElementById('menu_deskripsi').value = m.deskripsi ?? '';
            document.getElementById('menu_harga').value = m.harga;
            document.getElementById('menu_stok').value = m.stok;
            document.getElementById('menu_is_tersedia').checked = m.is_tersedia;
            document.getElementById('imgPreview').src = m.foto_url;
            menuModal.show();
        });
}

// Preview foto
$('#menu_foto').on('change', function() {
    const file = this.files[0];
    if (file) document.getElementById('imgPreview').src = URL.createObjectURL(file);
});

$('#formMenu').on('submit', function(e) {
    e.preventDefault();
    clearMenuErrors();
    const formData = new FormData(this);
    if (menuEditId) { formData.append('_method', 'PATCH'); }
    formData.set('is_tersedia', document.getElementById('menu_is_tersedia').checked ? 1 : 0);

    const url = menuEditId ? `/admin/menu/${menuEditId}` : '/admin/menu';
    $('#menuSpinner').removeClass('d-none');

    $.ajax({
        url, type: 'POST', data: formData, processData: false, contentType: false,
        success: res => {
            $('#menuSpinner').addClass('d-none');
            menuModal.hide();
            showToast('success', res.message);
            menuTable.ajax.reload();
        },
        error: err => {
            $('#menuSpinner').addClass('d-none');
            const errors = err.responseJSON?.errors;
            if (errors) {
                if (errors.kategori_id) showMenuError('menu_kategori_id','err_kategori',errors.kategori_id[0]);
                if (errors.nama_menu)   showMenuError('menu_nama','err_nama_menu',errors.nama_menu[0]);
                if (errors.harga)       showMenuError('menu_harga','err_harga',errors.harga[0]);
                if (errors.stok)        showMenuError('menu_stok','err_stok',errors.stok[0]);
            } else showToast('error', err.responseJSON?.message ?? 'Terjadi kesalahan.');
        }
    });
});

function hapusMenu(id, nama) {
    Swal.fire({ title:`Hapus "${nama}"?`, icon:'warning', showCancelButton:true,
        confirmButtonColor:'#dc3545', confirmButtonText:'Hapus', cancelButtonText:'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({ url:`/admin/menu/${id}`, type:'DELETE',
                success: res => { showToast('success', res.message); menuTable.ajax.reload(); },
                error: () => showToast('error', 'Gagal menghapus.')
            });
        }
    });
}

function toggleMenu(id, el) {
    $.ajax({ url:`/admin/menu/${id}/toggle`, type:'PATCH',
        success: res => showToast('success', res.message),
        error: () => { el.checked = !el.checked; showToast('error', 'Gagal.'); }
    });
}

function showMenuError(fId, eId, msg) { $(`#${fId}`).addClass('is-invalid'); $(`#${eId}`).text(msg); }
function clearMenuErrors() { $('.is-invalid').removeClass('is-invalid'); $('.invalid-feedback').text(''); }
</script>
@endpush
