@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-modern p-4">
            <h6 class="fw-bold mb-3" style="font-family:'Poppins',sans-serif;">Informasi Pesanan</h6>
            <div class="table-responsive">
                <table class="table table-borderless mb-0 small">
                    <tr><td class="text-muted">Kode</td><td><strong>{{ $pesanan->kode_pesanan }}</strong></td></tr>
                    <tr><td class="text-muted">Pelanggan</td><td>{{ $pesanan->nama_pelanggan }}</td></tr>
                    <tr><td class="text-muted">Meja</td><td>{{ $pesanan->meja?->nomor_meja }}</td></tr>
                    <tr><td class="text-muted">Total Item</td><td>{{ $pesanan->total_item }} item</td></tr>
                    <tr><td class="text-muted">Total</td><td class="fw-bold text-success">{{ $pesanan->total_harga_format }}</td></tr>
                    <tr><td class="text-muted">Waktu</td><td>{{ $pesanan->created_at->isoFormat('D MMM Y, HH:mm') }}</td></tr>
                    <tr><td class="text-muted">Status</td>
                        <td><span class="badge px-3 py-2 badge-{{ $pesanan->status }}">{{ $pesanan->status_label }}</span></td>
                    </tr>
                </table>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Update Status</h6>
            <select class="form-select rounded-3 mb-2" id="newStatus">
                @foreach(['menunggu'=>'Menunggu','diproses'=>'Diproses','siap_disajikan'=>'Siap Disajikan','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $k => $v)
                    <option value="{{ $k }}" {{ $pesanan->status === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            <button class="btn-primary-custom btn w-100" onclick="updateStatus()">Update Status</button>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-modern p-4">
            <h6 class="fw-bold mb-3" style="font-family:'Poppins',sans-serif;">Detail Item Pesanan</h6>
            @foreach($pesanan->detailPesanan as $d)
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                <img src="{{ $d->menu?->foto_url ?? asset('images/menu-placeholder.jpg') }}" style="width:64px;height:64px;border-radius:12px;object-fit:cover;">
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $d->nama_menu_snapshot }}</div>
                    <div class="text-muted small">Rp {{ number_format($d->harga_snapshot, 0, ',', '.') }} × {{ $d->jumlah }}</div>
                    @if($d->catatan_item)<div class="small text-info">📝 {{ $d->catatan_item }}</div>@endif
                </div>
                <div class="fw-bold text-success">{{ $d->subtotal_format }}</div>
            </div>
            @endforeach
            <div class="d-flex justify-content-between align-items-center pt-3">
                <span class="fw-bold">Total Pembayaran</span>
                <span class="fw-bold fs-5 text-success">{{ $pesanan->total_harga_format }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus() {
    $.ajax({ url:`/admin/pesanan/{{ $pesanan->id }}/status`, type:'PATCH',
        data: { status: $('#newStatus').val() },
        success: res => { showToast('success', res.message); setTimeout(() => location.reload(), 1000); },
        error: () => showToast('error', 'Gagal mengubah status.')
    });
}
</script>
@endpush
