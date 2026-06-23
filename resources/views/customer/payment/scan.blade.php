<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran QRIS</title>
    <meta name="description" content="Halaman konfirmasi pembayaran QRIS Café Arborea">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #1B4332;
            --accent: #52B788;
            --bg: #F0F4F1;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0A1F14 0%, #1B4332 50%, #2D6A4F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .scan-card {
            background: #fff;
            border-radius: 32px;
            max-width: 380px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
            animation: slide-up 0.5s cubic-bezier(0.34, 1.2, 0.64, 1);
        }

        @keyframes slide-up {
            from { transform: translateY(40px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* Header */
        .scan-header {
            background: linear-gradient(135deg, var(--primary), #2D6A4F);
            padding: 28px 24px 20px;
            text-align: center;
            color: #fff;
        }

        .scan-header .app-icon {
            width: 56px;
            height: 56px;
            background: rgba(255,255,255,0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 12px;
            backdrop-filter: blur(10px);
        }

        .scan-header h1 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .scan-header p {
            font-size: 12px;
            opacity: 0.75;
        }

        /* Merchant Info */
        .merchant-info {
            background: #F9FAFB;
            border-bottom: 1px solid #F3F4F6;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .merchant-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .merchant-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .merchant-sub {
            font-size: 11px;
            color: #6B7280;
        }

        .verified-badge {
            margin-left: auto;
            background: #D1FAE5;
            color: #065F46;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 3px;
            flex-shrink: 0;
        }

        /* Amount */
        .amount-section {
            padding: 20px 24px;
            text-align: center;
            border-bottom: 1px solid #F3F4F6;
        }

        .amount-label {
            font-size: 11px;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 6px;
        }

        .order-detail-text {
            font-size: 12px;
            color: #6B7280;
        }

        /* Detail items */
        .items-section {
            padding: 16px 24px;
            border-bottom: 1px solid #F3F4F6;
            max-height: 160px;
            overflow-y: auto;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            font-size: 13px;
        }

        .item-name { color: #374151; }
        .item-price { color: var(--primary); font-weight: 600; }

        /* Balance */
        .balance-section {
            padding: 12px 24px;
            border-bottom: 1px solid #F3F4F6;
        }

        .balance-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .balance-label { color: #6B7280; }
        .balance-value { font-weight: 700; color: #111827; }

        /* Pay button */
        .pay-section {
            padding: 20px 24px 28px;
        }

        .btn-pay {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), #2D6A4F);
            color: #fff;
            border: none;
            border-radius: 16px;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            box-shadow: 0 8px 24px rgba(27,67,50,0.3);
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(27,67,50,0.4);
        }

        .btn-pay:active { transform: translateY(0); }

        .btn-pay.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 11px;
            color: #9CA3AF;
        }

        /* Processing Overlay */
        .processing-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }

        .processing-overlay.show { display: flex; }

        .processing-card {
            background: #fff;
            border-radius: 28px;
            padding: 40px 32px;
            text-align: center;
            max-width: 280px;
            width: 90%;
            animation: pop-in 0.3s ease;
        }

        @keyframes pop-in {
            from { transform: scale(0.8); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }

        .spinner-circle {
            width: 64px;
            height: 64px;
            border: 4px solid #E5E7EB;
            border-top-color: var(--primary);
            border-radius: 50%;
            margin: 0 auto 16px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Success card */
        .success-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }

        .success-overlay.show { display: flex; }

        .success-card-inner {
            background: #fff;
            border-radius: 28px;
            padding: 44px 32px;
            text-align: center;
            max-width: 300px;
            width: 90%;
            animation: pop-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .checkmark {
            font-size: 72px;
            display: block;
            animation: checkmark-pop 0.5s 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes checkmark-pop {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }

        .success-card-inner h2 {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            margin: 12px 0 8px;
        }

        .success-card-inner p {
            font-size: 13px;
            color: #6B7280;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<div class="scan-card">
    <!-- Header -->
    <div class="scan-header">
        <div class="app-icon">📱</div>
        <h1>Konfirmasi Pembayaran</h1>
        <p>QRIS · Café Arborea Jatijajar</p>
    </div>

    <!-- Merchant Info -->
    <div class="merchant-info">
        <div class="merchant-logo">🌿</div>
        <div>
            <div class="merchant-name">Café Arborea Jatijajar</div>
            <div class="merchant-sub">Meja {{ $pesanan->meja->nomor_meja }} · QRIS Verified</div>
        </div>
        <div class="verified-badge">
            <i class="bi bi-patch-check-fill"></i> Terverifikasi
        </div>
    </div>

    <!-- Amount -->
    <div class="amount-section">
        <div class="amount-label">Total Tagihan</div>
        <div class="amount-value">{{ $pesanan->total_harga_format }}</div>
        <div class="order-detail-text">Kode Pesanan: <strong>{{ $pesanan->kode_pesanan }}</strong></div>
    </div>

    <!-- Items -->
    <div class="items-section">
        @foreach($pesanan->detailPesanan as $detail)
        <div class="item-row">
            <span class="item-name">{{ $detail->nama_menu_snapshot }} ×{{ $detail->jumlah }}</span>
            <span class="item-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>

    <!-- "Saldo" display (simulasi) -->
    <div class="balance-section">
        <div class="balance-row">
            <span class="balance-label">Saldo Tersedia</span>
            <span class="balance-value">Rp 500.000</span>
        </div>
        <div class="balance-row" style="margin-top:4px;">
            <span class="balance-label" style="font-size:11px; color:#9CA3AF;">Saldo setelah bayar</span>
            <span style="font-size:11px; color:#6B7280; font-weight:600;">
                Rp {{ number_format(500000 - $pesanan->total_harga, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Pay Button -->
    <div class="pay-section">
        <button class="btn-pay" id="btnPay" onclick="konfirmasiPembayaran()">
            <i class="bi bi-qr-code-scan"></i>
            Bayar Sekarang
        </button>
        <div class="security-note">
            <i class="bi bi-shield-lock-fill"></i>
            Transaksi aman & terenkripsi
        </div>
    </div>
</div>

<!-- Processing Overlay -->
<div class="processing-overlay" id="processingOverlay">
    <div class="processing-card">
        <div class="spinner-circle"></div>
        <p style="font-weight:700; color:#111827; font-size:15px;">Memproses Pembayaran</p>
        <p style="font-size:12px; color:#9CA3AF; margin-top:6px;">Mohon jangan tutup halaman ini...</p>
    </div>
</div>

<!-- Success Overlay -->
<div class="success-overlay" id="successOverlay">
    <div class="success-card-inner">
        <span class="checkmark">✅</span>
        <h2>Pembayaran Berhasil!</h2>
        <p>Pesanan <strong>{{ $pesanan->kode_pesanan }}</strong> telah dikonfirmasi.<br>Dapur sedang menyiapkan pesanan Anda.</p>
        <p style="margin-top:12px; font-size:11px; color:#9CA3AF;">Halaman akan tertutup otomatis...</p>
    </div>
</div>

<script>
const SIMULASI_URL = '{{ route("pembayaran.simulasi", $pesanan->kode_pesanan) }}';
const CSRF         = '{{ csrf_token() }}';

function konfirmasiPembayaran() {
    // Show processing
    document.getElementById('processingOverlay').classList.add('show');
    document.getElementById('btnPay').classList.add('loading');

    // Simulate 1.5 sec processing
    setTimeout(() => {
        fetch(SIMULASI_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('processingOverlay').classList.remove('show');

            if (data.success) {
                document.getElementById('successOverlay').classList.add('show');
                // Close tab after 3 seconds (works if opened from QR scan)
                setTimeout(() => {
                    window.close();
                    // If can't close, show message
                }, 3000);
            } else {
                alert('Pembayaran gagal. Silakan coba lagi.');
                document.getElementById('btnPay').classList.remove('loading');
            }
        })
        .catch(() => {
            document.getElementById('processingOverlay').classList.remove('show');
            alert('Terjadi kesalahan. Periksa koneksi internet Anda.');
            document.getElementById('btnPay').classList.remove('loading');
        });
    }, 1500);
}
</script>
</body>
</html>
