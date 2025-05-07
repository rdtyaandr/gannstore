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

class StrukController extends Controller
{
    public function index()
    {
        $struks = Struk::where('user_id', Auth::id())->latest()->paginate(10);
        $fields = StrukField::orderBy('order')->get();
        return view('dashboard', compact('struks', 'fields'));
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
        \Log::info('OCR Text:', ['text' => $text]);

        // Ekstrak nomor telepon
        if (preg_match('/\b(?:0|62|\+62)?[0-9]{9,12}\b/', $text, $matches)) {
            $data['nomor_telepon'] = preg_replace('/^(0|62|\+62)/', '', $matches[0]);
        }

        // Ekstrak nominal
        if (preg_match('/Rp\s*([\d.,]+)/', $text, $matches)) {
            $data['nominal'] = preg_replace('/[^\d]/', '', $matches[1]);
        }

        // Ekstrak tanggal
        if (preg_match('/(\d{2}[\/\-]\d{2}[\/\-]\d{4}|\d{2}[\/\-]\d{2}[\/\-]\d{2})/', $text, $matches)) {
            $data['tanggal'] = $matches[0];
        }

        // Ekstrak nama produk
        if (preg_match('/Produk\s*:\s*([^\n]+)/', $text, $matches)) {
            $data['nama_produk'] = trim($matches[1]);
        }

        // Ekstrak nomor struk
        if (preg_match('/No\.?\s*:?\s*([A-Z0-9\-]+)/', $text, $matches)) {
            $data['nomor_struk'] = trim($matches[1]);
        }

        // Ekstrak waktu
        if (preg_match('/(\d{2}:\d{2}(?::\d{2})?)/', $text, $matches)) {
            $data['waktu'] = $matches[0];
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
            $request->validate([
                'tempPath' => 'required|string',
                'data' => 'required|array'
            ]);

            // Pindahkan file dari temp ke storage permanen
            $tempPath = $request->tempPath;
            $permanentPath = 'struks/' . basename($tempPath);
            Storage::move($tempPath, $permanentPath);

            // Simpan data ke database
            $struk = Struk::create([
                'image_path' => $permanentPath,
                'data' => $request->data
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $struk
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Struk $struk)
    {
        // Untuk menampilkan detail struk (opsional, akan ditambahkan nanti)
    }

    public function edit(Struk $struk)
    {
        $fields = StrukField::orderBy('order')->get();
        return view('struks.edit', compact('struk', 'fields'));
    }

    public function update(Request $request, Struk $struk)
    {
        // Validasi screenshot jika ada
        if ($request->hasFile('screenshot')) {
            $request->validate([
                'screenshot' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($struk->screenshot_path) {
                Storage::disk('public')->delete($struk->screenshot_path);
            }
            $struk->screenshot_path = $request->file('screenshot')->store('screenshots', 'public');
            $struk->save();
        }

        // Update field-field yang ada di database
        $fields = StrukField::all();
        foreach ($fields as $field) {
            if ($request->has($field->name)) {
                $struk->values()->updateOrCreate(
                    ['struk_field_id' => $field->id],
                    ['value' => $request->input($field->name)]
                );
            }
        }

        // Proses custom fields jika ada
        if ($request->has('custom_fields') && $request->has('custom_values')) {
            $customFields = $request->input('custom_fields');
            $customValues = $request->input('custom_values');

            foreach ($customFields as $index => $fieldName) {
                if (isset($customValues[$index]) && !empty($fieldName) && !empty($customValues[$index])) {
                    // Buat field baru jika belum ada
                    $field = StrukField::firstOrCreate(
                        ['name' => Str::slug($fieldName, '_')],
                        [
                            'label' => $fieldName,
                            'type' => 'text',
                            'is_required' => false,
                            'order' => StrukField::max('order') + 1
                        ]
                    );

                    // Update atau buat nilai
                    $struk->values()->updateOrCreate(
                        ['struk_field_id' => $field->id],
                        ['value' => $customValues[$index]]
                    );
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Struk berhasil diperbarui.');
    }

    public function destroy(Struk $struk)
    {
        if ($struk->screenshot_path) {
            Storage::disk('public')->delete($struk->screenshot_path);
        }
        $struk->delete();

        return redirect()->route('dashboard')->with('success', 'Struk berhasil dihapus.');
    }
}
