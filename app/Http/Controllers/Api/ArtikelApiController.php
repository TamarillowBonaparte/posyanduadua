<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArtikelApiController extends Controller
{
    /**
     * Menampilkan daftar artikel dengan pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Artikel::orderBy('tanggal', 'desc')
                        ->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian jika ada
        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('isi_artikel', 'like', "%{$search}%");
        }

        $artikels = $query->paginate($perPage);

        // Tambahkan URL lengkap untuk gambar
        $artikels->getCollection()->transform(function ($artikel) {
            if ($artikel->gambar_artikel) {
                $artikel->gambar_url = url('storage/artikel/' . $artikel->gambar_artikel);
            }
            return $artikel;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar artikel berhasil diambil',
            'data' => $artikels
        ]);
    }

    /**
     * Menampilkan detail artikel
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $artikel = Artikel::findOrFail($id);
            
            // Tambahkan URL lengkap untuk gambar
            if ($artikel->gambar_artikel) {
                $artikel->gambar_url = url('storage/artikel/' . $artikel->gambar_artikel);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Detail artikel berhasil diambil',
                'data' => $artikel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Menyimpan artikel baru
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'gambar_artikel' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'isi_artikel' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Upload gambar
            $imagePath = null;
            if ($request->hasFile('gambar_artikel')) {
                $imagePath = $request->file('gambar_artikel')->store('artikel', 'public');
                $imagePath = basename($imagePath);
            }

            // Buat artikel baru
            $artikel = Artikel::create([
                'judul' => $request->judul,
                'gambar_artikel' => $imagePath,
                'isi_artikel' => $request->isi_artikel,
                'tanggal' => $request->tanggal,
            ]);

            // Tambahkan URL lengkap untuk gambar
            if ($artikel->gambar_artikel) {
                $artikel->gambar_url = url('storage/artikel/' . $artikel->gambar_artikel);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Artikel berhasil ditambahkan',
                'data' => $artikel
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengupdate artikel
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'gambar_artikel' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'isi_artikel' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $artikel = Artikel::findOrFail($id);

            // Upload gambar baru jika ada
            if ($request->hasFile('gambar_artikel')) {
                // Hapus gambar lama jika ada
                if ($artikel->gambar_artikel) {
                    $oldImagePath = 'public/artikel/' . $artikel->gambar_artikel;
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }

                // Upload gambar baru
                $imagePath = $request->file('gambar_artikel')->store('artikel', 'public');
                $artikel->gambar_artikel = basename($imagePath);
            }

            // Update data artikel
            $artikel->judul = $request->judul;
            $artikel->isi_artikel = $request->isi_artikel;
            $artikel->tanggal = $request->tanggal;
            $artikel->save();

            // Tambahkan URL lengkap untuk gambar
            if ($artikel->gambar_artikel) {
                $artikel->gambar_url = url('storage/artikel/' . $artikel->gambar_artikel);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Artikel berhasil diperbarui',
                'data' => $artikel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel tidak ditemukan atau gagal diperbarui',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Menghapus artikel
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $artikel = Artikel::findOrFail($id);

            // Hapus gambar jika ada
            if ($artikel->gambar_artikel) {
                $imagePath = 'public/artikel/' . $artikel->gambar_artikel;
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                }
            }

            // Hapus artikel
            $artikel->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Artikel berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel tidak ditemukan atau gagal dihapus',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    /**
     * Menampilkan artikel terbaru
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest(Request $request)
    {
        $limit = $request->input('limit', 5);
        
        $artikels = Artikel::orderBy('tanggal', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->limit($limit)
                         ->get();
        
        // Tambahkan URL lengkap untuk gambar
        $artikels->transform(function ($artikel) {
            if ($artikel->gambar_artikel) {
                $artikel->gambar_url = url('storage/artikel/' . $artikel->gambar_artikel);
            }
            return $artikel;
        });
        
        return response()->json([
            'status' => 'success',
            'message' => 'Artikel terbaru berhasil diambil',
            'data' => $artikels
        ]);
    }
}
