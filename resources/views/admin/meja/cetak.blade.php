<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Meja {{ $meja->nomor_meja }} – Café Arborea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f5f5f5; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; gap: 24px; }
        .qr-card {
            width: 320px; border: 3px solid #1B4332;
            border-radius: 20px; overflow: hidden; text-align: center;
            box-shadow: 0 8px 40px rgba(0,0,0,.15);
            background: #fff;
        }
        .qr-header {
            background: linear-gradient(135deg, #1B4332, #2D6A4F);
            color: #fff; padding: 20px;
        }
        .qr-header h2 { font-size: 22px; font-weight: 700; }
        .qr-header p { font-size: 13px; opacity: .8; margin-top: 4px; }
        .qr-body { background: #fff; padding: 24px; }
        .qr-body svg { width: 200px !important; height: 200px !important; border-radius: 12px; display: block; margin: 0 auto; }
        .meja-badge {
            background: #8B5E3C; color: #fff;
            display: inline-block; padding: 8px 28px;
            border-radius: 30px; font-size: 16px; font-weight: 700;
            margin: 16px 0 8px;
        }
        .url-text { font-size: 11px; color: #888; word-break: break-all; margin-bottom: 12px; }
        .qr-footer { background: #F8F5F0; padding: 12px; font-size: 12px; color: #666; }
        .action-btns { display: flex; gap: 12px; }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .qr-card { box-shadow: none; border: 2px solid #1B4332; }
        }
    </style>
</head>
<body>
<div class="qr-card">
    <div class="qr-header">
        <h2>🌿 Café Arborea</h2>
        <p>Jatijajar – Scan untuk memesan</p>
    </div>
    <div class="qr-body">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->margin(1)->generate($url) !!}
        <div class="meja-badge">MEJA {{ $meja->nomor_meja }}</div>
        <div class="url-text">{{ $url }}</div>
    </div>
    <div class="qr-footer">Scan QR Code ini untuk melihat menu dan memesan</div>
</div>
<div class="action-btns no-print">
    <button onclick="window.print()" class="btn btn-dark rounded-3">🖨️ Cetak</button>
    <button onclick="window.close()" class="btn btn-outline-secondary rounded-3">Tutup</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
