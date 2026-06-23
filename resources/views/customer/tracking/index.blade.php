@extends('layouts.customer')
@section('title', 'Lacak Pesanan #' . $pesanan->kode_pesanan)
@section('meja-info', 'Meja ' . $pesanan->meja->nomor_meja)

@push('styles')
<style>
    .tracking-card {
        background: #fff;
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        border: none;
        overflow: hidden;
    }
    
    /* Stepper Styling */
    .stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 2rem;
        padding-top: 10px;
    }
    
    .stepper::before {
        content: '';
        position: absolute;
        top: 35px;
        left: 12.5%;
        right: 12.5%;
        height: 4px;
        background: #e5e7eb;
        z-index: 1;
    }
    
    .stepper-progress {
        position: absolute;
        top: 35px;
        left: 12.5%;
        height: 4px;
        background: var(--primary);
        z-index: 2;
        transition: width 0.4s ease;
        width: 0%;
    }
    
    .step-item {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 25%;
    }
    
    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #9ca3af;
        transition: var(--transition);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .step-item.active .step-icon {
        border-color: var(--primary);
        background: var(--primary);
        color: #fff;
        transform: scale(1.1);
    }
    
    .step-item.completed .step-icon {
        border-color: var(--primary);
        background: #D1FAE5;
        color: var(--primary);
    }
    
    .step-label {
        margin-top: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-align: center;
        font-family: 'Poppins', sans-serif;
    }
    
    .step-item.active .step-label {
        color: var(--primary);
    }
    
    .step-item.completed .step-label {
        color: #111827;
    }
    
    .order-status-banner {
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        text-align: center;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
    }
    
    .order-status-banner.status-menunggu {
        background: #FEF3C7;
        color: #92400E;
    }
    .order-status-banner.status-diproses {
        background: #E0F2FE;
        color: #0369A1;
    }
    .order-status-banner.status-siap_disajikan {
        background: #DBEAFE;
        color: #1E40AF;
    }
    .order-status-banner.status-selesai {
        background: #D1FAE5;
        color: #065F46;
    }
    .order-status-banner.status-dibatalkan {
        background: #FEE2E2;
        color: #991B1B;
    }
    
    .detail-item-img {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
    }
    
    .item-placeholder-img {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: #e8e0d8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .order-summary-table td {
        vertical-align: middle;
    }
    
    /* Pulser for active stage */
    .step-item.active .step-icon {
        animation: pulse-step 2s infinite;
    }
    
    @keyframes pulse-step {
        0% {
            box-shadow: 0 0 0 0 rgba(27, 67, 50, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(27, 67, 50, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(27, 67, 50, 0);
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width: 600px;">
    
    {{-- Header Info --}}
    <div class="text-center mb-4">
        <div class="d-inline-block p-3 bg-white rounded-circle shadow-sm mb-3">
            <span style="font-size: 40px;">🍵</span>
        </div>
        <h4 class="fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Status Pesanan Anda</h4>
        <p class="text-muted mb-0">Café Arborea Jatijajar</p>
    </div>

    {{-- Tracking Card --}}
    <div class="card tracking-card p-4 mb-4">
        {{-- Order Code & Meta --}}
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
            <div>
                <span class="text-muted small d-block">Kode Pesanan</span>
                <h5 class="fw-bold text-success mb-0" style="font-family: 'Poppins', sans-serif;">{{ $pesanan->kode_pesanan }}</h5>
            </div>
            <div class="text-end">
                <span class="text-muted small d-block">Nama Pelanggan</span>
                <span class="fw-semibold text-dark">{{ $pesanan->nama_pelanggan }}</span>
            </div>
        </div>

        {{-- Dynamic Status Banner --}}
        <div id="statusBanner" class="order-status-banner status-{{ $pesanan->status }}">
            <i class="bi bi-hourglass-split me-2" id="bannerIcon"></i>
            <span id="bannerText">Status: {{ $pesanan->status_label }}</span>
        </div>

        {{-- Stepper Progress --}}
        <div id="stepperWrapper" class="stepper-container" style="display: {{ $pesanan->status === 'dibatalkan' ? 'none' : 'block' }};">
            <div class="stepper">
                <div class="stepper-progress" id="stepperProgress"></div>
                
                <div class="step-item" id="step-0">
                    <div class="step-icon"><i class="bi bi-hourglass-split"></i></div>
                    <div class="step-label">Menunggu</div>
                </div>
                <div class="step-item" id="step-1">
                    <div class="step-icon"><i class="bi bi-fire"></i></div>
                    <div class="step-label">Diproses</div>
                </div>
                <div class="step-item" id="step-2">
                    <div class="step-icon"><i class="bi bi-check2-circle"></i></div>
                    <div class="step-label">Siap Saji</div>
                </div>
                <div class="step-item" id="step-3">
                    <div class="step-icon"><i class="bi bi-emoji-smile"></i></div>
                    <div class="step-label">Selesai</div>
                </div>
            </div>
        </div>
        
        {{-- Cancelled State Information --}}
        <div id="cancelInfo" class="text-center py-4" style="display: {{ $pesanan->status === 'dibatalkan' ? 'block' : 'none' }};">
            <span style="font-size: 48px;">❌</span>
            <h5 class="fw-bold text-danger mt-2" style="font-family: 'Poppins', sans-serif;">Pesanan Dibatalkan</h5>
            <p class="text-muted small px-3">Mohon maaf, pesanan Anda telah dibatalkan oleh pihak Café Arborea Jatijajar. Silakan hubungi kasir atau waiter kami.</p>
        </div>
    </div>

    {{-- Detail Items Card --}}
    <div class="card tracking-card p-4 mb-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2" style="font-family: 'Poppins', sans-serif;">
            <i class="bi bi-list-ul me-2 text-success"></i>Rincian Pesanan
        </h6>
        
        <div class="table-responsive">
            <table class="table table-borderless order-summary-table mb-0">
                <tbody>
                    @foreach($pesanan->detailPesanan as $detail)
                        <tr>
                            <td style="width: 60px;">
                                @if($detail->menu && $detail->menu->foto && !str_contains($detail->menu->foto, 'placeholder'))
                                    <img src="{{ asset('storage/' . $detail->menu->foto) }}" class="detail-item-img" alt="{{ $detail->nama_menu_snapshot }}">
                                @else
                                    <div class="item-placeholder-img">
                                        {{ match($detail->menu->kategori->nama_kategori ?? '') {
                                            'Kopi Panas' => '☕',
                                            'Kopi Dingin' => '🧊',
                                            'Non-Kopi' => '🥤',
                                            'Makanan Berat' => '🍽️',
                                            'Snack & Camilan' => '🍪',
                                            'Dessert' => '🍰',
                                            default => '🌿'
                                        } }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $detail->nama_menu_snapshot }}</div>
                                <div class="text-muted small">{{ 'Rp ' . number_format($detail->harga_snapshot, 0, ',', '.') }} x {{ $detail->jumlah }}</div>
                            </td>
                            <td class="text-end fw-semibold text-dark">
                                {{ 'Rp ' . number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="border-top">
                        <td colspan="2" class="fw-bold text-dark pt-3">Total Pembayaran</td>
                        <td class="text-end fw-bold text-success fs-5 pt-3">
                            {{ $pesanan->total_harga_format }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Action Button --}}
    <div class="text-center">
        <a href="{{ route('customer.menu', $pesanan->meja->nomor_meja) }}" class="btn w-100 py-3 text-white fw-bold rounded-3 shadow" style="background: var(--primary);">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Menu
        </a>
        <p class="text-muted small mt-3">Halaman ini akan diperbarui secara otomatis ketika status pesanan Anda berubah.</p>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const KODE_PESANAN = '{{ $pesanan->kode_pesanan }}';
    const STATUS_URL = '{{ route("tracking.status", $pesanan->kode_pesanan) }}';
    let pollInterval;
    
    function updateTrackingUI(data) {
        // Update Banner
        const banner = $('#statusBanner');
        banner.attr('class', 'order-status-banner status-' + data.status);
        
        // Find icon for banner
        let iconClass = 'bi-hourglass-split';
        if (data.status === 'diproses') iconClass = 'bi-fire';
        else if (data.status === 'siap_disajikan') iconClass = 'bi-check2-circle';
        else if (data.status === 'selesai') iconClass = 'bi-emoji-smile';
        else if (data.status === 'dibatalkan') iconClass = 'bi-x-circle';
        
        $('#bannerIcon').attr('class', `bi ${iconClass} me-2`);
        $('#bannerText').text('Status: ' + data.status_label);
        
        // Update Stepper
        if (data.is_dibatalkan) {
            $('#stepperWrapper').hide();
            $('#cancelInfo').fadeIn();
            clearInterval(pollInterval);
        } else {
            $('#stepperWrapper').show();
            $('#cancelInfo').hide();
            
            // Set active states
            const currentIndex = data.current_index;
            
            $('.step-item').each(function(idx) {
                const step = $(this);
                step.removeClass('active completed');
                
                if (idx < currentIndex) {
                    step.addClass('completed');
                } else if (idx === currentIndex) {
                    step.addClass('active');
                }
            });
            
            // Update Line Progress
            // 0 -> 0%, 1 -> 33%, 2 -> 66%, 3 -> 100%
            let progressPercent = 0;
            if (currentIndex > 0) {
                progressPercent = (currentIndex / 3) * 100;
            }
            $('#stepperProgress').css('width', progressPercent + '%');
            
            // Play SweetAlert sound/notif if transitioning to siap_disajikan or selesai
            const lastStatus = localStorage.getItem(`status_${KODE_PESANAN}`);
            if (lastStatus && lastStatus !== data.status) {
                if (data.status === 'siap_disajikan') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pesanan Siap! 🎉',
                        text: 'Pesanan Anda siap disajikan. Silakan nikmati!',
                        confirmButtonColor: '#1B4332'
                    });
                } else if (data.status === 'selesai') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pesanan Selesai! 😊',
                        text: 'Terima kasih telah memesan di Café Arborea Jatijajar!',
                        confirmButtonColor: '#1B4332'
                    });
                }
            }
            
            // Save state for comparison next time
            localStorage.setItem(`status_${KODE_PESANAN}`, data.status);
            
            // Stop polling if completed
            if (data.is_selesai) {
                clearInterval(pollInterval);
            }
        }
    }
    
    function checkStatus() {
        $.getJSON(STATUS_URL)
            .done(function(data) {
                updateTrackingUI(data);
            })
            .fail(function() {
                console.error("Gagal memperbarui status pesanan.");
            });
    }
    
    $(document).ready(function() {
        // Initial state load
        checkStatus();
        
        // Poll status every 5 seconds
        pollInterval = setInterval(checkStatus, 5000);
    });
</script>
@endpush
