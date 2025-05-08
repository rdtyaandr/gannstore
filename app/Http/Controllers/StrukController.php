<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\StrukField;
use App\Models\StrukValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Intervention\Image\Facades\Image;
use LaraOCR\OCR;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\DB;

class StrukController extends Controller
{
    public function index()
    {
        // Field wajib yang harus ada di setiap struk
        $requiredFields = ['tanggal', 'produk', 'harga'];

        // Ambil semua struk user
        $struks = Struk::where('user_id', Auth::id())->latest()->get();

        // Filter struk yang tidak memiliki field wajib
        foreach ($struks as $key => $struk) {
            $isValid = true;
            foreach ($requiredFields as $field) {
                if (!$struk->getValue($field)) {
                    $isValid = false;
                    break;
                }
            }

            // Jika struk tidak valid, hapus
            if (!$isValid) {
                try {
                    $struk->delete();
                    $struks->forget($key);
                } catch (\Exception $e) {
                    Log::error('Error deleting invalid struk: ' . $e->getMessage());
                }
            }
        }

        // Paginate hasil yang tersisa
        $currentPage = request()->input('page', 1);
        $perPage = 10;
        $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
            $struks->forPage($currentPage, $perPage),
            $struks->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $fields = StrukField::orderBy('order')->get();

        // Ambil informasi struk mana yang sudah memiliki data cuan
        $strukIdsWithCuan = \App\Models\Cuan::where('user_id', Auth::id())
            ->pluck('struk_id')
            ->toArray();

        return view('dashboard', compact('paginatedResults', 'fields', 'strukIdsWithCuan'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Proses gambar untuk preview
            $imagePath = $request->file('screenshot')->getPathname();

            // Konversi gambar ke base64 untuk preview
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageBase64 = 'data:image/' . $request->file('screenshot')->getClientMimeType() . ';base64,' . $imageData;

            // Simpan file gambar sementara
            $tempPath = $request->file('screenshot')->store('temp');

            return view('struks.preview', compact('imageBase64', 'tempPath'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses gambar: ' . $e->getMessage());
        }
    }

    private function extractDataFromOCR($text)
    {
        $data = [];

        // Debug: tampilkan teks yang dibaca
        Log::info('OCR Text Raw:', ['text' => $text]);

        // Ekstrak nomor telepon
        if (preg_match('/\b(?:0|62|\+62)?[0-9]{9,12}\b/', $text, $matches)) {
            $data['nomor_telepon'] = preg_replace('/^(0|62|\+62)/', '', $matches[0]);
            Log::info('Nomor Telepon Ditemukan:', ['nomor' => $data['nomor_telepon']]);
        }

        // Ekstrak tanggal dan waktu dengan format yang lebih spesifik
        if (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})\s*(\d{2}):(\d{2})/', $text, $matches)) {
            // Format: DD/MM/YYYY HH:MM
            $data['tanggal'] = sprintf('%s/%s/%s %s:%s',
                $matches[1], // hari
                $matches[2], // bulan
                $matches[3], // tahun
                $matches[4], // jam
                $matches[5]  // menit
            );
            Log::info('Tanggal dan Waktu Ditemukan (Format Lengkap):', ['tanggal' => $data['tanggal']]);
        } elseif (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/', $text, $matches)) {
            // Format: DD/MM/YYYY
            $data['tanggal'] = sprintf('%s/%s/%s',
                $matches[1], // hari
                $matches[2], // bulan
                $matches[3]  // tahun
            );
            Log::info('Tanggal Ditemukan (Format Tanggal Saja):', ['tanggal' => $data['tanggal']]);
        }

        // Ekstrak nominal
        if (preg_match('/Rp\s*([\d.,]+)/', $text, $matches)) {
            $data['nominal'] = preg_replace('/[^\d]/', '', $matches[1]);
            Log::info('Nominal Ditemukan:', ['nominal' => $data['nominal']]);
        }

        // Ekstrak nama produk
        if (preg_match('/Produk\s*:\s*([^\n]+)/', $text, $matches)) {
            $data['nama_produk'] = trim($matches[1]);
            Log::info('Nama Produk Ditemukan:', ['produk' => $data['nama_produk']]);
        }

        // Ekstrak nomor struk
        if (preg_match('/No\.?\s*:?\s*([A-Z0-9\-]+)/', $text, $matches)) {
            $data['nomor_struk'] = trim($matches[1]);
            Log::info('Nomor Struk Ditemukan:', ['nomor_struk' => $data['nomor_struk']]);
        }

        // Ekstrak waktu terpisah jika belum ada dalam format tanggal
        if (!isset($data['tanggal']) || !str_contains($data['tanggal'], ':')) {
            if (preg_match('/(\d{2}):(\d{2})/', $text, $matches)) {
                $data['waktu'] = sprintf('%s:%s', $matches[1], $matches[2]);
                Log::info('Waktu Terpisah Ditemukan:', ['waktu' => $data['waktu']]);
                // Gabungkan dengan tanggal jika ada
                if (isset($data['tanggal'])) {
                    $data['tanggal'] .= ' ' . $data['waktu'];
                    Log::info('Tanggal dan Waktu Digabungkan:', ['hasil' => $data['tanggal']]);
                }
            }
        }

        // Ekstrak nama merchant
        if (preg_match('/Merchant\s*:?\s*([^\n]+)/', $text, $matches)) {
            $data['nama_merchant'] = trim($matches[1]);
            Log::info('Nama Merchant Ditemukan:', ['merchant' => $data['nama_merchant']]);
        }

        // Ekstrak status transaksi
        if (preg_match('/Status\s*:?\s*([^\n]+)/', $text, $matches)) {
            $data['status'] = trim($matches[1]);
            Log::info('Status Transaksi Ditemukan:', ['status' => $data['status']]);
        }

        // Ekstrak keterangan
        if (preg_match('/Keterangan\s*:?\s*([^\n]+)/', $text, $matches)) {
            $data['keterangan'] = trim($matches[1]);
            Log::info('Keterangan Ditemukan:', ['keterangan' => $data['keterangan']]);
        }

        // Log hasil akhir ekstraksi
        Log::info('Hasil Akhir Ekstraksi Data:', $data);

        return $data;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info('Request data:', $request->all());

            // Siapkan data dari form dinamis
            $formData = $request->input('data', []);

            // Buat record struk baru (tanpa gambar)
            $struk = Struk::create([
                'user_id' => Auth::id(),
                'data' => $formData // Simpan data asli dalam bentuk JSON
            ]);

            // Proses dan simpan field-field dinamis
            if (!empty($formData)) {
                foreach ($formData as $label => $value) {
                    // Normalisasi label untuk nama field
                    $name = Str::slug(strtolower($label), '_');

                    // Cek apakah field sudah ada, kalau belum buat field baru
                    $field = StrukField::firstOrCreate(
                        ['name' => $name],
                        [
                            'label' => $label,
                            'type' => 'text',
                            'is_required' => false,
                            'order' => StrukField::max('order') + 1
                        ]
                    );

                    // Simpan nilai
                    StrukValue::create([
                        'struk_id' => $struk->id,
                        'struk_field_id' => $field->id,
                        'value' => $value
                    ]);
                }
            }

            DB::commit();

            // Metode ini hanya untuk form input manual, tidak untuk API
            return redirect()->route('dashboard')->with('success', 'Data struk berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving struk: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->back()->with('error', 'Gagal menyimpan data struk: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan data struk dari hasil scan (API endpoint)
     * Selalu mengembalikan respons JSON
     */
    public function storeFromScan(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info('Request scan data:', $request->all());

            // Siapkan data dari form scan
            $formData = $request->input('data', []);
            $tempPath = $request->input('tempPath');

            // Buat record struk baru (tanpa gambar)
            $struk = Struk::create([
                'user_id' => Auth::id(),
                'data' => $formData, // Simpan data asli dalam bentuk JSON
                // Jika perlu, simpan path gambar
                'image_path' => str_replace('temp/', 'struks/', $tempPath)
            ]);

            // Proses dan simpan field-field dinamis
            if (!empty($formData)) {
                foreach ($formData as $label => $value) {
                    // Normalisasi label untuk nama field
                    $name = Str::slug(strtolower($label), '_');

                    // Cek apakah field sudah ada, kalau belum buat field baru
                    $field = StrukField::firstOrCreate(
                        ['name' => $name],
                        [
                            'label' => $label,
                            'type' => 'text',
                            'is_required' => false,
                            'order' => StrukField::max('order') + 1
                        ]
                    );

                    // Simpan nilai
                    StrukValue::create([
                        'struk_id' => $struk->id,
                        'struk_field_id' => $field->id,
                        'value' => $value
                    ]);
                }
            }

            // Jika ada file gambar sementara, pindahkan ke lokasi permanen
            if ($tempPath) {
                if (Storage::exists($tempPath)) {
                    // Pindahkan file dari temp ke direktori permanen
                    $permanentPath = str_replace('temp/', 'struks/', $tempPath);
                    Storage::move($tempPath, $permanentPath);
                }
            }

            DB::commit();

            // Selalu kembalikan respons JSON untuk API
            return response()->json([
                'success' => true,
                'message' => 'Data struk berhasil disimpan',
                'data' => $struk,
                'redirect_url' => route('dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving scan struk: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data struk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Struk $struk)
    {
        // Pastikan user hanya dapat melihat struk miliknya
        if ($struk->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke struk ini.');
        }

        return view('struks.show', compact('struk'));
    }

    public function edit(Struk $struk)
    {
        $fields = StrukField::orderBy('order')->get();
        return view('struks.edit', compact('struk', 'fields'));
    }

    public function update(Request $request, Struk $struk)
    {
        try {
            DB::beginTransaction();

            // Update data pada struk
            $formData = $request->input('data', []);
            $struk->data = $formData;

            // Simpan perubahan pada struk (tanpa gambar)
            $struk->save();

            // Hapus nilai lama dan buat nilai baru untuk setiap field
            $struk->values()->delete();

            // Proses dan simpan field-field dinamis
            if (!empty($formData)) {
                foreach ($formData as $label => $value) {
                    if (empty($value)) continue; // Lewati field yang kosong

                    // Normalisasi label untuk nama field
                    $name = Str::slug(strtolower($label), '_');

                    // Cek apakah field sudah ada, kalau belum buat field baru
                    $field = StrukField::firstOrCreate(
                        ['name' => $name],
                        [
                            'label' => $label,
                            'type' => 'text',
                            'is_required' => false,
                            'order' => StrukField::max('order') + 1
                        ]
                    );

                    // Simpan nilai baru
                    StrukValue::create([
                        'struk_id' => $struk->id,
                        'struk_field_id' => $field->id,
                        'value' => $value
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Struk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating struk: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui struk: ' . $e->getMessage());
        }
    }

    public function destroy(Struk $struk)
    {
        try {
            DB::beginTransaction();

            // Simpan ID field yang digunakan oleh struk ini
            $fieldIds = StrukValue::where('struk_id', $struk->id)
                ->pluck('struk_field_id')
                ->unique()
                ->toArray();

            // Hapus data cuan yang terkait dengan struk ini (jika ada)
            \App\Models\Cuan::where('struk_id', $struk->id)->delete();

            // Hapus nilai-nilai dari struk ini
            StrukValue::where('struk_id', $struk->id)->delete();

            // Hapus file struk jika ada
            if ($struk->screenshot_path) {
                Storage::disk('public')->delete($struk->screenshot_path);
            } else if ($struk->image_path) {
                Storage::delete($struk->image_path);
            }

            // Hapus data struk
            $struk->delete();

            // Periksa setiap field yang sebelumnya digunakan oleh struk ini
            // Jika tidak ada struk lain yang menggunakan field tersebut, hapus field-nya
            foreach ($fieldIds as $fieldId) {
                $valueCount = StrukValue::where('struk_field_id', $fieldId)->count();

                if ($valueCount === 0) {
                    // Tidak ada struk yang menggunakan field ini, jadi hapus field
                    StrukField::where('id', $fieldId)->delete();
                    Log::info('Field ID ' . $fieldId . ' dihapus karena tidak digunakan oleh struk manapun');
                }
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Struk berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting struk: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus struk: ' . $e->getMessage());
        }
    }

    public function logOCR(Request $request)
    {
        Log::info('Hasil OCR:', [
            'text' => $request->input('text'),
            'timestamp' => now()->toDateTimeString()
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Menampilkan detail keuangan dari struk untuk modal
     */
    public function financial(Struk $struk)
    {
        // Ambil data yang relevan dari struk
        $data = [
            'produk' => $struk->getValue('produk'),
            'harga' => $struk->getValue('harga'),
            'tanggal' => $struk->getValue('tanggal'),
            'status' => $struk->getValue('status'),
            // Tambahan data keuangan lainnya
            'nomor_hp' => $struk->getValue('nomor_hp'),
            'pembayaran' => $struk->getValue('pembayaran'),
            'id_transaksi' => $struk->getValue('id_transaksi'),
            'sn_ref' => $struk->getValue('sn_ref'),
        ];

        return response()->json($data);
    }
}
