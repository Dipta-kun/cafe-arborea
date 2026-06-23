@extends('layouts.customer')
@section('title', 'Menu – Meja ' . $meja->nomor_meja)
@section('meja-info', 'Meja ' . $meja->nomor_meja)

@section('content')
{{-- HERO BANNER --}}
<div class="hero-banner">
    <div class="container text-center position-relative">
        <div class="mb-2" style="font-size:48px;">🌿</div>
        <h1 class="fw-bold mb-2" style="font-size:26px;font-family:'Poppins',sans-serif;">Café Arborea</h1>
        <p class="mb-3" style="opacity:.85;">Jatijajar – Nikmati setiap tegukan</p>
        <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill" style="background:rgba(255,255,255,.15);">
            <i class="bi bi-geo-alt-fill"></i>
            <span class="fw-semibold">Meja {{ $meja->nomor_meja }}</span>
            @if($meja->nama_meja)<span class="opacity-75"> – {{ $meja->nama_meja }}</span>@endif
        </div>
    </div>
</div>

{{-- KATEGORI TABS --}}
<div class="kategori-tabs">
    <div class="tab-scroll">
        <button class="cat-tab active" data-id="all">🍽️ Semua</button>
        @foreach($kategori as $k)
            <button class="cat-tab" data-id="{{ $k->id }}">
                <i class="bi {{ $k->icon }} me-1"></i>{{ $k->nama_kategori }}
                <span class="ms-1 badge bg-success rounded-pill" style="font-size:10px;">{{ $k->menu_count }}</span>
            </button>
        @endforeach
    </div>
</div>

{{-- SEARCH --}}
<div class="search-wrap">
    <div class="position-relative">
        <i class="bi bi-search position-absolute" style="left:16px;top:50%;transform:translateY(-50%);color:#9ca3af;"></i>
        <input type="text" id="searchMenu" class="search-input" style="padding-left:44px;" placeholder="Cari menu...">
    </div>
</div>

{{-- MENU GRID --}}
<div id="menuGrid" class="menu-grid">
    {{-- Skeleton loading --}}
    @for($i=0;$i<8;$i++)
    <div class="menu-card">
        <div class="skeleton" style="width:100%;aspect-ratio:1/1;"></div>
        <div class="menu-card-body">
            <div class="skeleton mb-2" style="height:16px;width:80%;"></div>
            <div class="skeleton mb-2" style="height:12px;width:100%;"></div>
            <div class="skeleton" style="height:12px;width:60%;"></div>
        </div>
        <div class="menu-card-footer"><div class="skeleton" style="height:36px;border-radius:10px;"></div></div>
    </div>
    @endfor
</div>

{{-- Empty State --}}
<div id="emptyState" class="text-center py-5" style="display:none;">
    <div style="font-size:64px;">🔍</div>
    <h5 class="mt-3 fw-bold" style="font-family:'Poppins',sans-serif;">Menu tidak ditemukan</h5>
    <p class="text-muted">Coba kata kunci lain atau pilih kategori berbeda</p>
</div>
@endsection

@section('floating-cart')
{{-- FLOATING CART BUTTON --}}
<div id="floatingCart" style="display:none;">
    <div id="cartTotalMini" class="cart-total-mini" style="display:none;"></div>
    <button class="cart-btn" id="cartToggleBtn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
        <i class="bi bi-bag-check-fill"></i>
        <span class="cart-badge" id="cartBadge">0</span>
    </button>
</div>

{{-- OFFCANVAS CART --}}
<div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="offcanvasCart">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" style="font-family:'Poppins',sans-serif;">
            <i class="bi bi-bag-check text-success me-2"></i>Keranjang Saya
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <div id="cartItems" class="flex-grow-1 p-3">
            <div class="text-center py-5 text-muted" id="emptyCart">
                <div style="font-size:48px;">🛒</div>
                <p class="mt-2">Keranjang masih kosong</p>
            </div>
        </div>
        <div class="p-3 border-top bg-white" id="cartFooter" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold">Total</span>
                <span class="fw-bold fs-5 text-success" id="cartTotal">Rp 0</span>
            </div>

            {{-- Checkout Form --}}
            <div class="mb-3">
                <input type="text" class="form-control rounded-3" id="namaPelanggan"
                    placeholder="Nama Anda (wajib)" maxlength="100">
            </div>
            
            {{-- Pilihan Pembayaran --}}
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted mb-2">Metode Pembayaran</label>
                <div class="d-flex flex-column gap-2">
                    {{-- QRIS --}}
                    <label class="payment-method-card" for="payQris">
                        <input type="radio" name="metode_pembayaran" id="payQris" value="qris" checked class="d-none">
                        <div class="payment-card-body d-flex align-items-center justify-content-between p-3 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="payment-icon bg-success bg-opacity-10 text-success p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="bi bi-qr-code-scan fs-5"></i>
                                </div>
                                <div class="text-start">
                                    <div class="fw-bold text-dark" style="font-size: 13px;">QRIS (Bayar Instan)</div>
                                    <div class="text-muted" style="font-size: 10px;">Scan QR & bayar langsung dari HP</div>
                                </div>
                            </div>
                            <div class="payment-check">
                                <i class="bi bi-check-circle-fill text-success fs-5 check-icon" style="display: none;"></i>
                            </div>
                        </div>
                    </label>
                    
                    {{-- Kasir --}}
                    <label class="payment-method-card" for="payKasir">
                        <input type="radio" name="metode_pembayaran" id="payKasir" value="kasir" class="d-none">
                        <div class="payment-card-body d-flex align-items-center justify-content-between p-3 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="payment-icon bg-secondary bg-opacity-10 text-secondary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="bi bi-cash-coin fs-5"></i>
                                </div>
                                <div class="text-start">
                                    <div class="fw-bold text-dark" style="font-size: 13px;">Bayar di Kasir</div>
                                    <div class="text-muted" style="font-size: 10px;">Pesan dulu, bayar tunai/debit di kasir</div>
                                </div>
                            </div>
                            <div class="payment-check">
                                <i class="bi bi-check-circle-fill text-success fs-5 check-icon" style="display: none;"></i>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-danger rounded-3 flex-shrink-0" onclick="kosongkanKeranjang()">
                    <i class="bi bi-trash"></i>
                </button>
                <button class="btn w-100 rounded-3 text-white fw-semibold" id="btnPesan"
                    style="background:#1B4332;" onclick="checkout()">
                    Pesan Sekarang <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const MEJA_ID      = {{ $meja->id }};
const NOMOR_MEJA   = '{{ $meja->nomor_meja }}';
const DATA_URL     = '{{ route("customer.menu.data", $meja->nomor_meja) }}';
const TAMBAH_URL   = '{{ route("keranjang.tambah") }}';
const UPDATE_URL   = '{{ route("keranjang.update", "__ID__") }}';
const HAPUS_URL    = '{{ route("keranjang.hapus", "__ID__") }}';
const KOSONG_URL   = '{{ route("keranjang.kosongkan") }}';
const COUNT_URL    = '{{ route("keranjang.count") }}';
const CHECKOUT_URL = '{{ route("checkout.proses") }}';

let activeKategori = 'all';
let searchDebounce;

// Load menu
function loadMenu() {
    fetch(`${DATA_URL}?kategori_id=${activeKategori}&search=${$('#searchMenu').val()}`)
        .then(r => r.json()).then(d => renderMenu(d.data));
}

function renderMenu(items) {
    if (items.length === 0) {
        $('#menuGrid').html('');
        $('#emptyState').show();
        return;
    }
    $('#emptyState').hide();
    const html = items.map(m => `
        <div class="menu-card" data-id="${m.id}">
            ${m.foto_url && !m.foto_url.includes('placeholder')
                ? `<img src="${m.foto_url}" class="menu-card-img" alt="${m.nama_menu}" loading="lazy">`
                : `<div class="menu-card-img-placeholder">${getCategoryEmoji(m.kategori)}</div>`
            }
            <div class="menu-card-body">
                <div class="menu-card-name">${m.nama_menu}</div>
                <div class="menu-card-desc">${m.deskripsi ?? ''}</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="menu-card-price">${m.harga_format}</span>
                    <span class="${m.stok > 0 && m.is_tersedia ? 'badge-tersedia' : 'badge-habis'}">
                        ${m.stok > 0 && m.is_tersedia ? 'Tersedia' : 'Habis'}
                    </span>
                </div>
            </div>
            <div class="menu-card-footer">
                <button class="btn-add" ${!m.is_tersedia || m.stok == 0 ? 'disabled' : ''}
                    onclick="tambahKeranjang(${m.id},'${m.nama_menu}')">
                    ${m.is_tersedia && m.stok > 0
                        ? '<i class="bi bi-plus-lg me-1"></i>Tambah'
                        : '<i class="bi bi-x me-1"></i>Habis'}
                </button>
            </div>
        </div>
    `).join('');
    $('#menuGrid').html(html);
}

function getCategoryEmoji(k) {
    const map = { 'Kopi Panas':'☕','Kopi Dingin':'🧊','Non-Kopi':'🥤','Makanan Berat':'🍽️','Snack & Camilan':'🍪','Dessert':'🍰' };
    return map[k] ?? '🌿';
}

// Kategori tabs
$(document).on('click', '.cat-tab', function() {
    $('.cat-tab').removeClass('active');
    $(this).addClass('active');
    activeKategori = $(this).data('id');
    loadMenu();
});

// Search realtime
$('#searchMenu').on('input', function() {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(loadMenu, 350);
});

// Tambah ke keranjang
function tambahKeranjang(menuId, nama) {
    $.post(TAMBAH_URL, { menu_id: menuId, jumlah: 1 })
        .done(res => {
            if (res.success) {
                showToast('success', res.message);
                updateCartUI(res.count, res.total_format);
                loadCart();
            } else showToast('error', res.message);
        }).fail(() => showToast('error', 'Gagal menambahkan item.'));
}

// Load cart
function loadCart() {
    fetch('{{ route("keranjang.index") }}')
        .then(r => r.json()).then(d => {
            updateCartUI(d.count, d.total_format);
            renderCart(d.items, d.total_format);
        });
}

function updateCartUI(count, total) {
    $('#cartBadge').text(count);
    if (count > 0) {
        $('#floatingCart').show();
        $('#cartBtn').addClass('has-items');
        $('#cartTotalMini').text(total).show();
    } else {
        $('#cartTotalMini').hide();
    }
}

function renderCart(items, total) {
    if (items.length === 0) {
        $('#emptyCart').show();
        $('#cartFooter').hide();
        $('#cartItems').html('<div class="text-center py-5 text-muted" id="emptyCart"><div style="font-size:48px;">🛒</div><p class="mt-2">Keranjang masih kosong</p></div>');
        return;
    }
    $('#emptyCart').hide();
    $('#cartFooter').show();
    $('#cartTotal').text(total);

    const html = items.map(item => `
        <div class="cart-item" id="cart-item-${item.id}">
            <img src="${item.foto_url}" alt="${item.nama_menu}" onerror="this.src='/images/menu-placeholder.jpg'">
            <div class="flex-grow-1">
                <div class="fw-semibold small">${item.nama_menu}</div>
                <div class="text-muted" style="font-size:12px;">${item.harga_format}</div>
                <div class="text-success fw-bold small">Rp ${(item.subtotal).toLocaleString('id-ID')}</div>
            </div>
            <div class="d-flex flex-column align-items-center gap-1">
                <button class="qty-btn" onclick="ubahJumlah('${item.id}',${item.jumlah - 1})">−</button>
                <span class="fw-bold small">${item.jumlah}</span>
                <button class="qty-btn" onclick="ubahJumlah('${item.id}',${item.jumlah + 1})">+</button>
            </div>
            <button class="btn btn-sm text-danger border-0 p-0" onclick="hapusItem('${item.id}')">
                <i class="bi bi-x-circle-fill fs-5"></i>
            </button>
        </div>
    `).join('');
    $('#cartItems').html(html);
}

function ubahJumlah(id, jumlah) {
    $.ajax({ url: UPDATE_URL.replace('__ID__', id), type: 'PATCH', data: { jumlah },
        success: res => { updateCartUI(res.count, res.total_format); renderCart(res.items, res.total_format); }
    });
}

function hapusItem(id) {
    $.ajax({ url: HAPUS_URL.replace('__ID__', id), type: 'DELETE',
        success: res => { updateCartUI(res.count, res.total_format); renderCart(res.items, res.total_format); showToast('info', 'Item dihapus.'); }
    });
}

function kosongkanKeranjang() {
    Swal.fire({ title:'Kosongkan keranjang?', icon:'warning', showCancelButton:true,
        confirmButtonColor:'#dc3545', confirmButtonText:'Ya', cancelButtonText:'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({ url: KOSONG_URL, type: 'DELETE',
                success: () => { loadCart(); updateCartUI(0,'Rp 0'); showToast('info','Keranjang dikosongkan.'); }
            });
        }
    });
}

function checkout() {
    const nama = $('#namaPelanggan').val().trim();
    if (!nama) {
        Swal.fire({ icon:'warning', title:'Nama wajib diisi!', text:'Masukkan nama Anda sebelum memesan.', timer:2000, showConfirmButton:false });
        $('#namaPelanggan').focus();
        return;
    }

    Swal.fire({ title:'Konfirmasi Pesanan', text:`Pesan atas nama "${nama}" di Meja ${NOMOR_MEJA}?`,
        icon:'question', showCancelButton:true,
        confirmButtonColor:'#1B4332', confirmButtonText:'Ya, Pesan!', cancelButtonText:'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            const metodePembayaran = $('input[name="metode_pembayaran"]:checked').val();
            $('#btnPesan').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
            $.post(CHECKOUT_URL, { nama_pelanggan: nama, meja_id: MEJA_ID, metode_pembayaran: metodePembayaran })
                .done(res => {
                    if (res.success) {
                        Swal.fire({ icon:'success', title:'Pesanan Berhasil! 🎉',
                            html:`<p>Pesanan Anda telah diterima.</p><p class="fw-bold text-success fs-5">Kode: ${res.kode_pesanan}</p>`,
                            confirmButtonColor:'#1B4332', confirmButtonText:'Lihat Status Pesanan'
                        }).then(() => { window.location.href = res.redirect; });
                    } else showToast('error', res.message);
                }).fail(err => {
                    showToast('error', err.responseJSON?.message ?? 'Gagal memproses pesanan.');
                    $('#btnPesan').prop('disabled', false).html('Pesan Sekarang <i class="bi bi-arrow-right ms-1"></i>');
                });
        }
    });
}

// Init
$(document).ready(() => {
    loadMenu();
    loadCart();
});
</script>
@endpush

@push('styles')
<style>
    .payment-method-card {
        cursor: pointer;
        display: block;
        width: 100%;
        user-select: none;
    }
    .payment-method-card input[type="radio"]:checked + .payment-card-body {
        border-color: var(--primary) !important;
        background-color: rgba(27, 67, 50, 0.04) !important;
        box-shadow: 0 0 0 1px var(--primary);
    }
    .payment-method-card input[type="radio"]:checked + .payment-card-body .check-icon {
        display: block !important;
    }
    .payment-method-card .payment-card-body {
        border: 2px solid #e5e7eb !important;
        transition: var(--transition);
        background: #fff;
    }
    .payment-method-card:hover .payment-card-body {
        border-color: #cbd5e1 !important;
        background: #fafafa;
    }
</style>
@endpush
