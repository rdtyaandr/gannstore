<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\StrukField;
use App\Models\StrukValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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


        // Ekstrak nomor telepon
        if (preg_match('/\b(?:0|62|\+62)?[0-9]{9,12}\b/', $text, $matches)) {
            $data['nomor_telepon'] = preg_replace('/^(0|62|\+62)/', '', $matches[0]);

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

        } elseif (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/', $text, $matches)) {
            // Format: DD/MM/YYYY
            $data['tanggal'] = sprintf('%s/%s/%s',
                $matches[1], // hari
                $matches[2], // bulan
                $matches[3]  // tahun
            );

        }

        // Ekstrak nominal
        if (preg_match('/Rp\s*([\d.,]+)/', $text, $matches)) {
            $data['nominal'] = preg_replace('/[^\d]/', '', $matches[1]);

        }

        // Ekstrak nama produk
        if (preg_match('/Produk\s*:\s*([^\n]+)/', $text, $matches)) {
            $data['nama_produk'] = trim($matches[1]);

        }

        // Ekstrak nomor struk
        if (preg_match('/No\.?\s*:?\s*([A-Z0-9\-]+)/', $text, $matches)) {
            $data['nomor_struk'] = trim($matches[1]);

        }

        // Ekstrak waktu terpisah jika belum ada dalam format tanggal
        if (!isset($data['tanggal']) || !str_contains($data['tanggal'], ':')) {
            if (preg_match('/(\d{2}):(\d{2})/', $text, $matches)) {
                $data['waktu'] = sprintf('%s:%s', $matches[1], $matches[2]);

                // Gabungkan dengan tanggal jika ada
                if (isset($data['tanggal'])) {
                    $data['tanggal'] .= ' ' . $data['waktu'];

                }
            }
        }

        // Ekstrak nama merchant
        if (preg_match('/Merchant\s*:?\s*([^\n]+)/', $text, $matches)) {
            $data['nama_merchant'] = trim($matches[1]);

        }

        // Ekstrak status transaksi
        if (preg_match('/Status\s*:?\s*([^\n]+)/', $text, $matches)) {
            $data['status'] = trim($matches[1]);

        }

        // Ekstrak keterangan
        if (preg_match('/Keterangan\s*:?\s*([^\n]+)/', $text, $matches)) {
            $data['keterangan'] = trim($matches[1]);

        }


        return $data;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Siapkan data dari form dinamis
            $formData = $request->input('data', []);

            // Decode URL encoded characters in formData dengan metode deepDecode
            $decodedFormData = $this->deepDecodeData($formData);

            // Buat record struk baru (tanpa gambar)
            $struk = Struk::create([
                'user_id' => Auth::id(),
                'data' => $decodedFormData // Simpan data asli dalam bentuk JSON
            ]);

            // Proses dan simpan field-field dinamis
            if (!empty($decodedFormData)) {
                foreach ($decodedFormData as $label => $value) {
                    // Normalisasi label untuk nama field
                    $name = Str::slug(strtolower($label), '_');

                    // Cek apakah field sudah ada, kalau belum buat field baru
                    $field = StrukField::firstOrCreate(
                        ['name' => $name],
                        [
                            'label' => $label,
                            'type' => 'text',
                            'is_required' => in_array(strtolower($name), ['tanggal', 'produk', 'harga']),
                            'order' => StrukField::max('order') + 1
                        ]
                    );

                    // Simpan nilai
                    StrukValue::create([
                        'struk_id' => $struk->id,
                        'struk_field_id' => $field->id,
                        'value' => $value // nilai sudah didecode di atas
                    ]);
                }
            }

            DB::commit();

            // Metode ini hanya untuk form input manual, tidak untuk API
            return redirect()->route('dashboard')->with('success', 'Data struk berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();



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



            // Siapkan data dari form scan
            $formData = $request->input('data', []);
            $tempPath = $request->input('tempPath');

            // Decode URL encoded characters in formData
            $decodedFormData = [];
            foreach ($formData as $key => $value) {
                $decodedFormData[$key] = urldecode($value);
            }

            // Buat record struk baru (tanpa gambar)
            $struk = Struk::create([
                'user_id' => Auth::id(),
                'data' => $decodedFormData, // Simpan data asli dalam bentuk JSON
                // Jika perlu, simpan path gambar
                'image_path' => str_replace('temp/', 'struks/', $tempPath)
            ]);

            // Proses dan simpan field-field dinamis
            if (!empty($decodedFormData)) {
                foreach ($decodedFormData as $label => $value) {
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

                    // Decode URL encoded characters like %0D%0A before saving
                    $decodedValue = urldecode($value);

                    // Simpan nilai
                    StrukValue::create([
                        'struk_id' => $struk->id,
                        'struk_field_id' => $field->id,
                        'value' => $decodedValue
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

            // Dapatkan data form
            $formData = $request->input('data', []);

            // Decoder untuk semua nilai dalam formData
            $decodedFormData = $this->deepDecodeData($formData);

            // Simpan data ke dalam JSON storage
            $struk->update([
                'data' => $decodedFormData
            ]);

            // Proses field-field
            if (!empty($decodedFormData)) {
                foreach ($decodedFormData as $label => $value) {
                    // Normalisasi label untuk nama field
                    $name = \Illuminate\Support\Str::slug(strtolower($label), '_');

                    // Cek apakah field sudah ada
                    $field = StrukField::firstOrCreate(
                        ['name' => $name],
                        [
                            'label' => $label,
                            'type' => 'text',
                            'is_required' => in_array(strtolower($name), ['tanggal', 'produk', 'harga']),
                            'order' => StrukField::max('order') + 1
                        ]
                    );

                    // Cari nilai yang sudah ada untuk field ini
                    $existingValue = StrukValue::where('struk_id', $struk->id)
                        ->where('struk_field_id', $field->id)
                        ->first();

                    if ($existingValue) {
                        $existingValue->update([
                            'value' => $value // Nilai sudah didecode di atas
                        ]);
                    } else {
                        StrukValue::create([
                            'struk_id' => $struk->id,
                            'struk_field_id' => $field->id,
                            'value' => $value // Nilai sudah didecode di atas
                        ]);
                    }
                }
            }

            DB::commit();

            // Menentukan format respons berdasarkan jenis request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Struk berhasil diperbarui',
                    'redirect_url' => route('dashboard')
                ]);
            }

            // Respons default untuk non-AJAX request
            return redirect()->route('dashboard')->with('success', 'Struk berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal memperbarui struk: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui struk: ' . $e->getMessage());
        }
    }

    /**
     * Decoder dan pembersih kunci dan nilai untuk memproses URL-encoded secara rekursif
     *
     * @param mixed $data
     * @return mixed
     */
    private function deepDecodeData($data)
    {
        if (!is_array($data)) {
            return $this->deepDecodeString($data);
        }

        // Kunci yang sudah diproses (untuk menghindari duplikasi)
        $processedKeys = [];

        // Field-field wajib yang sering terduplikasi
        $wajibFields = ['tanggal', 'produk', 'harga'];

        // Pertama decode semua kunci dan nilai
        $tempData = [];
        foreach ($data as $key => $value) {
            $decodedKey = $this->cleanFieldName($key);
            $decodedValue = $this->deepDecodeString($value);

            // Standarisasi field wajib
            foreach ($wajibFields as $wajibField) {
                if (str_contains(strtolower($decodedKey), $wajibField)) {
                    $decodedKey = ucfirst($wajibField); // Pastikan format seragam untuk field wajib
                    break;
                }
            }

            $tempData[$decodedKey] = $decodedValue;
        }

        // Hasilnya
        $result = [];

        // Dahulukan field wajib yang sudah distandarisasi
        foreach ($wajibFields as $wajibField) {
            $standardKey = ucfirst($wajibField);
            if (isset($tempData[$standardKey])) {
                $result[$standardKey] = $tempData[$standardKey];
                $processedKeys[] = strtolower($standardKey);
            }
        }

        // Tambahkan field lainnya (yang bukan field wajib atau variant-nya)
        foreach ($tempData as $key => $value) {
            $keyLower = strtolower($key);

            // Skip field wajib yang sudah diproses
            $skipKey = false;
            foreach ($wajibFields as $wajibField) {
                if (str_contains($keyLower, $wajibField)) {
                    $skipKey = true;
                    break;
                }
            }

            // Tambahkan jika belum diproses
            if (!$skipKey || !in_array($keyLower, $processedKeys)) {
                $result[$key] = $value;
                $processedKeys[] = $keyLower;
            }
        }

        return $result;
    }

    /**
     * Membersihkan nama field dari karakter spesial dan format
     *
     * @param string $fieldName
     * @return string
     */
    private function cleanFieldName($fieldName)
    {
        if (!is_string($fieldName)) {
            return $fieldName;
        }

        // Decode URL-encoded characters
        $decoded = $fieldName;
        $iterations = 0;
        $maxIterations = 10;

        while (strpos($decoded, '%') !== false && $iterations < $maxIterations) {
            $newValue = urldecode($decoded);
            if ($newValue === $decoded) {
                break;
            }
            $decoded = $newValue;
            $iterations++;
        }

        // Bersihkan spasi berlebih
        $cleaned = trim(preg_replace('/\s+/', ' ', $decoded));

        // Hapus karakter * dan spasi di akhir (untuk field seperti "Harga *")
        $cleaned = preg_replace('/[*\s]+$/', '', $cleaned);

        // Standarisasi nama field (opsional, misalnya "Id Transaksi" menjadi "ID Transaksi")
        $specialMappings = [
            'id transaksi' => 'ID Transaksi',
            'nomor hp' => 'Nomor HP',
            'sn ref' => 'SN/Ref',
        ];

        $cleanedLower = strtolower($cleaned);
        foreach ($specialMappings as $from => $to) {
            if ($cleanedLower === $from) {
                return $to;
            }
        }

        // Jika ini adalah field wajib utama, standarisasi format
        $mandatoryFields = ['tanggal', 'produk', 'harga'];
        foreach ($mandatoryFields as $field) {
            if ($cleanedLower === $field || str_contains($cleanedLower, $field)) {
                return ucfirst($field);
            }
        }

        return $cleaned;
    }

    /**
     * Decoder URL-encoded secara rekursif untuk string
     *
     * @param string $string
     * @return string
     */
    private function deepDecodeString($string)
    {
        if (!is_string($string)) {
            return $string;
        }

        // Decode nilai sampai tidak ada lagi perubahan (karakter %)
        $decodedValue = $string;
        $iterations = 0;
        $maxIterations = 10; // Batas maksimum iterasi untuk mencegah infinite loop

        while (strpos($decodedValue, '%') !== false && $iterations < $maxIterations) {
            $newValue = urldecode($decodedValue);
            // Jika tidak ada perubahan, hentikan
            if ($newValue === $decodedValue) {
                break;
            }
            $decodedValue = $newValue;
            $iterations++;
        }

        return $decodedValue;
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

                }
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Struk berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();


            return redirect()->back()->with('error', 'Gagal menghapus struk: ' . $e->getMessage());
        }
    }

    public function logOCR(Request $request)
    {
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
