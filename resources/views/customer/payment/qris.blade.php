<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS – {{ $pesanan->kode_pesanan }}</title>
    <meta name="description" content="Scan QR code untuk menyelesaikan pembayaran pesanan Anda di Café Arborea Jatijajar">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #1B4332;
            --primary-light: #2D6A4F;
            --accent: #52B788;
            --bg: #F0F4F1;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(27,67,50,0.15);
            max-width: 460px;
            width: 100%;
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 32px 32px 28px;
            text-align: center;
            position: relative;
        }

        .payment-header .cafe-logo {
            font-size: 36px;
            margin-bottom: 8px;
        }

        .payment-header h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .payment-header p {
            font-size: 13px;
            opacity: 0.8;
            margin: 0;
        }

        .payment-body {
            padding: 28px 32px 32px;
        }

        /* Amount Section */
        .amount-section {
            background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            margin-bottom: 24px;
            border: 2px solid rgba(82,183,136,0.3);
        }

        .amount-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .amount-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.1;
        }

        .order-code {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        /* QR Section */
        .qr-section {
            text-align: center;
            margin-bottom: 24px;
        }

        .qr-title {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .qr-wrapper {
            position: relative;
            display: inline-block;
        }

        .qr-frame {
            background: #fff;
            border: 3px solid var(--primary);
            border-radius: 20px;
            padding: 16px;
            box-shadow: 0 8px 24px rgba(27,67,50,0.12);
            display: inline-block;
            position: relative;
        }

        .qr-frame img {
            display: block;
            width: 200px;
            height: 200px;
            border-radius: 8px;
        }

        .qr-corner {
            position: absolute;
            width: 24px;
            height: 24px;
            border-color: var(--accent);
            border-style: solid;
        }
        .qr-corner.tl { top: -3px; left: -3px; border-width: 4px 0 0 4px; border-radius: 4px 0 0 0; }
        .qr-corner.tr { top: -3px; right: -3px; border-width: 4px 4px 0 0; border-radius: 0 4px 0 0; }
        .qr-corner.bl { bottom: -3px; left: -3px; border-width: 0 0 4px 4px; border-radius: 0 0 0 4px; }
        .qr-corner.br { bottom: -3px; right: -3px; border-width: 0 4px 4px 0; border-radius: 0 0 4px 0; }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin: 16px 0;
        }

        .status-badge.waiting {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-badge.success {
            background: #D1FAE5;
            color: #065F46;
        }

        /* Pulse animation */
        .pulse-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse-dot 1.5s infinite;
            flex-shrink: 0;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
        }

        /* Instruction List */
        .instruction-list {
            list-style: none;
            margin-bottom: 24px;
        }

        .instruction-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 8px 0;
            font-size: 13px;
            color: #4B5563;
            border-bottom: 1px solid #F3F4F6;
        }

        .instruction-list li:last-child { border-bottom: none; }

        .step-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Supported Apps */
        .supported-apps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .app-chip {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 50px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #374151;
        }

        /* Timer */
        .timer-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .timer-bar-wrap {
            background: #E5E7EB;
            border-radius: 50px;
            height: 6px;
            margin: 8px 0;
            overflow: hidden;
        }

        .timer-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--accent), var(--primary));
            border-radius: 50px;
            transition: width 1s linear;
            width: 100%;
        }

        .timer-text {
            font-size: 12px;
            color: #6b7280;
        }

        /* Buttons */
        .btn-back {
            display: block;
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            border: 2px solid #E5E7EB;
            background: transparent;
            color: #374151;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-back:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #F0FDF4;
        }

        /* Success overlay */
        .success-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .success-overlay.show { display: flex; }

        .success-card {
            background: #fff;
            border-radius: 28px;
            padding: 48px 36px;
            text-align: center;
            max-width: 320px;
            width: 90%;
            animation: pop-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes pop-in {
            from { transform: scale(0.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .success-icon {
            font-size: 72px;
            margin-bottom: 16px;
            animation: bounce-icon 0.6s 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes bounce-icon {
            from { transform: scale(0) rotate(-10deg); opacity: 0; }
            to { transform: scale(1) rotate(0); opacity: 1; }
        }

        .success-card h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .success-card p {
            font-size: 14px;
            color: #6b7280;
        }

        .redirect-dots span {
            animation: blink-dot 1.4s infinite;
        }

        .redirect-dots span:nth-child(2) { animation-delay: 0.2s; }
        .redirect-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes blink-dot {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="payment-container">
    <div class="payment-header">
        <div class="cafe-logo">🌿</div>
        <h1>Pembayaran QRIS</h1>
        <p>Café Arborea Jatijajar · Meja {{ $pesanan->meja->nomor_meja }}</p>
    </div>

    <div class="payment-body">
        <!-- Amount -->
        <div class="amount-section">
            <div class="amount-label">Total Pembayaran</div>
            <div class="amount-value">{{ $pesanan->total_harga_format }}</div>
            <div class="order-code"># {{ $pesanan->kode_pesanan }} · {{ $pesanan->total_item }} item</div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-title">
                <i class="bi bi-qr-code-scan"></i>
                Scan QR Code di bawah ini
            </div>

            <div class="qr-wrapper">
                <div class="qr-frame">
                    {{-- QR code yang mengarah ke halaman scan simulator --}}
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('pembayaran.scan', $pesanan->kode_pesanan)) }}&bgcolor=ffffff&color=1B4332&qzone=2&margin=0"
                         alt="QR Code Pembayaran {{ $pesanan->kode_pesanan }}"
                         id="qrCodeImg">
                    <div class="qr-corner tl"></div>
                    <div class="qr-corner tr"></div>
                    <div class="qr-corner bl"></div>
                    <div class="qr-corner br"></div>
                </div>
            </div>

            <!-- Status Polling Badge -->
            <div id="statusBadge" class="status-badge waiting">
                <span class="pulse-dot"></span>
                <span id="statusText">Menunggu pembayaran...</span>
            </div>
        </div>

        <!-- Timer -->
        <div class="timer-section">
            <div class="timer-bar-wrap">
                <div class="timer-bar" id="timerBar"></div>
            </div>
            <div class="timer-text">Sesi pembayaran berakhir dalam <strong id="timerCountdown">10:00</strong></div>
        </div>

        <!-- Cara Bayar -->
        <ul class="instruction-list">
            <li>
                <span class="step-num">1</span>
                <span>Buka aplikasi mobile banking atau e-wallet Anda (GoPay, OVO, Dana, ShopeePay, dll)</span>
            </li>
            <li>
                <span class="step-num">2</span>
                <span>Pilih fitur <strong>Scan QR / QRIS</strong> lalu arahkan kamera ke QR di atas</span>
            </li>
            <li>
                <span class="step-num">3</span>
                <span>Konfirmasi jumlah <strong>{{ $pesanan->total_harga_format }}</strong> dan selesaikan pembayaran</span>
            </li>
            <li>
                <span class="step-num">4</span>
                <span>Halaman ini akan otomatis berubah setelah pembayaran berhasil ✅</span>
            </li>
        </ul>

        <!-- Supported Apps -->
        <div class="supported-apps">
            <span class="app-chip">GoPay</span>
            <span class="app-chip">OVO</span>
            <span class="app-chip">DANA</span>
            <span class="app-chip">ShopeePay</span>
            <span class="app-chip">LinkAja</span>
            <span class="app-chip">M-Banking</span>
        </div>

        <!-- Back Button -->
        <a href="{{ route('customer.menu', $pesanan->meja->nomor_meja) }}" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Menu
        </a>
    </div>
</div>

<!-- Success Overlay -->
<div class="success-overlay" id="successOverlay">
    <div class="success-card">
        <div class="success-icon">✅</div>
        <h2>Pembayaran Berhasil!</h2>
        <p>Pesanan Anda sedang diproses oleh dapur.<br>Mohon tunggu sebentar.</p>
        <p class="mt-3" style="color:#9ca3af; font-size:12px;">
            Mengalihkan<span class="redirect-dots"><span>.</span><span>.</span><span>.</span></span>
        </p>
    </div>
</div>

<script>
const KODE_PESANAN   = '{{ $pesanan->kode_pesanan }}';
const STATUS_URL     = '{{ route("pembayaran.status", $pesanan->kode_pesanan) }}';
const TRACKING_URL   = '{{ route("tracking.index", $pesanan->kode_pesanan) }}';
const TOTAL_SECONDS  = 600; // 10 menit

let secondsLeft = TOTAL_SECONDS;
let pollInterval;
let timerInterval;
let paid = false;

// ── Timer ──────────────────────────────────────────────
function updateTimer() {
    if (secondsLeft <= 0) {
        clearInterval(timerInterval);
        clearInterval(pollInterval);
        document.getElementById('timerCountdown').textContent = '00:00';
        document.getElementById('timerBar').style.width = '0%';
        document.getElementById('statusText').textContent = 'Sesi kedaluwarsa. Silakan pesan ulang.';
        return;
    }
    secondsLeft--;
    const m = String(Math.floor(secondsLeft / 60)).padStart(2, '0');
    const s = String(secondsLeft % 60).padStart(2, '0');
    document.getElementById('timerCountdown').textContent = `${m}:${s}`;
    document.getElementById('timerBar').style.width = (secondsLeft / TOTAL_SECONDS * 100) + '%';
}

// ── Status Polling ────────────────────────────────────
function checkPaymentStatus() {
    if (paid) return;
    fetch(STATUS_URL)
        .then(r => r.json())
        .then(data => {
            if (data.status_pembayaran === 'lunas') {
                paid = true;
                clearInterval(pollInterval);
                clearInterval(timerInterval);
                showSuccess();
            }
        })
        .catch(() => { /* silently fail */ });
}

function showSuccess() {
    const badge = document.getElementById('statusBadge');
    badge.className = 'status-badge success';
    badge.innerHTML = '<i class="bi bi-check-circle-fill"></i><span>Pembayaran Diterima!</span>';

    document.getElementById('successOverlay').classList.add('show');

    // Redirect after 3 seconds
    setTimeout(() => {
        window.location.href = TRACKING_URL;
    }, 3000);
}

// ── Init ──────────────────────────────────────────────
timerInterval = setInterval(updateTimer, 1000);
pollInterval  = setInterval(checkPaymentStatus, 3000);

// Check immediately
checkPaymentStatus();
</script>
</body>
</html>
