<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulLaporan }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; background: #fff; }
        .header { background: #1B4332; color: #fff; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 11px; opacity: .85; margin-top: 4px; }
        .report-title { font-size: 14px; font-weight: bold; color: #1B4332; margin: 0 24px 16px; }
        .summary { display: flex; gap: 16px; margin: 0 24px 20px; }
        .sum-card { flex: 1; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; text-align: center; }
        .sum-card .val { font-size: 16px; font-weight: bold; color: #1B4332; }
        .sum-card .lbl { font-size: 10px; color: #888; margin-top: 4px; }
        table { width: calc(100% - 48px); margin: 0 24px; border-collapse: collapse; }
        thead th { background: #1B4332; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; font-size: 11px; }
        tbody tr:nth-child(even) td { background: #f9f9f9; }
        .total-row td { font-weight: bold; border-top: 2px solid #1B4332; background: #f0f9f4; }
        .footer { margin: 20px 24px 0; font-size: 10px; color: #aaa; text-align: right; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .badge-selesai { background: #D1FAE5; color: #065F46; }
        .badge-siap_disajikan { background: #DBEAFE; color: #1E40AF; }
    </style>
</head>
<body>
<div class="header">
    <h1>🌿 Café Arborea Jatijajar</h1>
    <p>Sistem Informasi Pemesanan Menu Digital</p>
</div>

<div class="report-title">{{ $judulLaporan }}</div>

<div class="summary">
    <div class="sum-card">
        <div class="val">{{ $pesanan->count() }}</div>
        <div class="lbl">Total Pesanan</div>
    </div>
    <div class="sum-card">
        <div class="val">{{ $pesanan->sum('total_item') }}</div>
        <div class="lbl">Total Item</div>
    </div>
    <div class="sum-card">
        <div class="val">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        <div class="lbl">Total Pendapatan</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Meja</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pesanan as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $p->kode_pesanan }}</strong></td>
            <td>{{ $p->nama_pelanggan }}</td>
            <td>{{ $p->meja?->nomor_meja }}</td>
            <td>{{ $p->total_item }}</td>
            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
            <td><span class="badge badge-{{ $p->status }}">{{ $p->status_label }}</span></td>
            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="5" style="text-align:right;">TOTAL</td>
            <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>

<div class="footer">
    Dicetak pada: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB
</div>
</body>
</html>
