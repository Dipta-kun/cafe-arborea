<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MejaRequest;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MejaController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $mejas = Meja::withCount('pesanan')->latest()->get();
            return response()->json(['data' => $mejas]);
        }
        return view('admin.meja.index');
    }

    public function store(MejaRequest $request)
    {
        $data = $request->validated();
        $meja = Meja::create($data);
        $this->generateQrCode($meja);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil ditambahkan dan QR Code sudah dibuat.',
            'data'    => $meja->fresh(),
        ]);
    }

    public function show(Meja $meja)
    {
        return response()->json(['data' => $meja]);
    }

    public function update(MejaRequest $request, Meja $meja)
    {
        $meja->update($request->validated());

        // Regenerate QR Code jika nomor meja berubah
        if ($request->nomor_meja !== $meja->getOriginal('nomor_meja')) {
            $this->generateQrCode($meja);
        }

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil diperbarui.',
            'data'    => $meja->fresh(),
        ]);
    }

    public function destroy(Meja $meja)
    {
        if ($meja->pesananAktif()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Meja tidak dapat dihapus karena masih ada pesanan aktif.',
            ], 422);
        }

        if ($meja->qr_code) {
            Storage::disk('public')->delete($meja->qr_code);
        }

        $meja->delete();
        return response()->json(['success' => true, 'message' => 'Meja berhasil dihapus.']);
    }

    public function downloadQr(Meja $meja)
    {
        $url      = url('/menu/' . $meja->nomor_meja);
        $qrCode   = QrCode::format('svg')->size(400)->margin(2)->generate($url);
        $filename = 'QR_Meja_' . $meja->nomor_meja . '.svg';

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function cetakQr(Meja $meja)
    {
        $url = url('/menu/' . $meja->nomor_meja);
        return view('admin.meja.cetak', compact('meja', 'url'));
    }

    private function generateQrCode(Meja $meja): void
    {
        $url      = url('/menu/' . $meja->nomor_meja);
        $path     = 'qrcodes/meja_' . $meja->id . '.svg';
        $qrCode   = QrCode::format('svg')->size(300)->margin(2)->generate($url);

        Storage::disk('public')->put($path, $qrCode);
        $meja->update(['qr_code' => $path]);
    }
}
