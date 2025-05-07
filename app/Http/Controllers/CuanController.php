<?php

namespace App\Http\Controllers;

use App\Models\Cuan;
use App\Models\Struk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuanController extends Controller
{
    /**
     * Menampilkan daftar keuntungan (cuan)
     */
    public function index()
    {
        $cuanData = Cuan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $totalKeuntungan = Cuan::where('user_id', Auth::id())
            ->sum('keuntungan');

        return view('cuan.index', compact('cuanData', 'totalKeuntungan'));
    }

    /**
     * Menyimpan data keuntungan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'struk_id' => 'required|exists:struks,id',
            'harga_beli' => 'required|integer',
        ]);

        // Ambil data struk
        $struk = Struk::findOrFail($validated['struk_id']);

        // Pastikan struk milik user yang saat ini login
        if ($struk->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke struk ini.'
            ], 403);
        }

        // Ambil data produk dan tanggal dari struk
        $produk = $struk->getValue('produk') ?? 'Produk Tidak Diketahui';
        $tanggal = $struk->getValue('tanggal') ?? date('d/m/Y');

        // PERBAIKAN LOGIKA - Sesuai dengan kebutuhan bisnis yang benar:
        // Harga jual diambil dari database (struk)
        $harga_jual = (int) preg_replace('/\D/', '', $struk->getValue('harga') ?? '0');

        // Harga beli diambil dari input user
        $harga_beli = (int) $validated['harga_beli'];

        // Pastikan harga beli lebih rendah dari harga jual
        if ($harga_beli >= $harga_jual) {
            return response()->json([
                'success' => false,
                'message' => 'Harga beli harus lebih rendah dari harga jual (Rp ' . number_format($harga_jual, 0, ',', '.') . ')'
            ], 422);
        }

        // Hitung keuntungan dengan rumus yang benar
        $keuntungan = $harga_jual - $harga_beli;

        // Cek apakah struk ini sudah memiliki data cuan
        $existingCuan = Cuan::where('struk_id', $struk->id)->first();

        if ($existingCuan) {
            // Update data yang sudah ada
            $existingCuan->update([
                'harga_beli' => $harga_beli,
                'harga_jual' => $harga_jual,
                'keuntungan' => $keuntungan,
            ]);

            $cuan = $existingCuan;
            $message = 'Data keuntungan berhasil diperbarui';
            $isUpdate = true;
        } else {
            // Simpan data keuntungan baru
            $cuan = Cuan::create([
                'user_id' => Auth::id(),
                'struk_id' => $struk->id,
                'produk' => $produk,
                'tanggal' => $tanggal,
                'harga_beli' => $harga_beli, // Input dari user
                'harga_jual' => $harga_jual, // Dari database
                'keuntungan' => $keuntungan,
            ]);

            $message = 'Data keuntungan berhasil disimpan';
            $isUpdate = false;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $cuan,
            'is_update' => $isUpdate
        ]);
    }

    /**
     * Memeriksa apakah struk sudah memiliki data cuan
     */
    public function checkCuanExists($strukId)
    {
        $cuan = Cuan::where('struk_id', $strukId)->first();

        if ($cuan) {
            return response()->json([
                'exists' => true,
                'data' => $cuan
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }

    /**
     * Update data keuntungan yang sudah ada
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'struk_id' => 'required|exists:struks,id',
            'harga_beli' => 'required|integer',
        ]);

        // Ambil data struk
        $struk = Struk::findOrFail($validated['struk_id']);

        // Pastikan struk milik user yang saat ini login
        if ($struk->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke struk ini.'
            ], 403);
        }

        // Ambil data cuan yang sudah ada
        $cuan = Cuan::where('struk_id', $struk->id)->first();

        if (!$cuan) {
            return response()->json([
                'success' => false,
                'message' => 'Data cuan tidak ditemukan'
            ], 404);
        }

        // Harga jual tetap dari struk
        $harga_jual = (int) preg_replace('/\D/', '', $struk->getValue('harga') ?? '0');

        // Harga beli dari input
        $harga_beli = (int) $validated['harga_beli'];

        // Pastikan harga beli lebih rendah dari harga jual
        if ($harga_beli >= $harga_jual) {
            return response()->json([
                'success' => false,
                'message' => 'Harga beli harus lebih rendah dari harga jual (Rp ' . number_format($harga_jual, 0, ',', '.') . ')'
            ], 422);
        }

        // Hitung ulang keuntungan
        $keuntungan = $harga_jual - $harga_beli;

        // Update data
        $cuan->update([
            'harga_beli' => $harga_beli,
            'keuntungan' => $keuntungan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data keuntungan berhasil diperbarui',
            'data' => $cuan
        ]);
    }
}
