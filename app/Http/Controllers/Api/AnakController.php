<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AnakController extends Controller
{
    /**
     * Mendapatkan daftar anak
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Log untuk debugging
        \Log::info("=== Get anak data ===");
        \Log::info("Headers: " . json_encode($request->headers->all()));
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        
        // Jika tidak ada user, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        // Default query
        $query = Anak::with('pengguna');
        
        if ($user->role === 'parent') {
            // Jika user adalah parent, hanya tampilkan anak miliknya
            $query->where('pengguna_id', $user->id);
            \Log::info("Filtering: only showing children for parent ID {$user->id}");
        } else if ($request->has('pengguna_id') && $user->role === 'admin') {
            // Admin bisa filter berdasarkan pengguna_id
            $query->where('pengguna_id', $request->pengguna_id);
            \Log::info("Admin filtering: showing children for parent ID {$request->pengguna_id}");
        }
        
        $anakList = $query->get();
        \Log::info("Found {$anakList->count()} children records");
        
        return response()->json([
            'status' => 'success',
            'data' => $anakList
        ]);
    }

    /**
     * Menyimpan data anak baru
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Debug headers dan user
        \Log::info("=== Store anak data ===");
        \Log::info("Headers: " . json_encode($request->headers->all()));
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Request data:", $request->all());
        
        // Jika masih tidak ada user setelah melewati middleware, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'sometimes|exists:pengguna,id',
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Tentukan pengguna_id
            $pengguna_id = null;
            
            if ($user->role === 'parent') {
                $pengguna_id = $user->id;
            } else if ($user->role === 'admin' && $request->has('pengguna_id')) {
                $pengguna_id = $request->pengguna_id;
            } else if ($request->has('pengguna_id')) {
                // Fallback: gunakan dari request
                $pengguna_id = $request->pengguna_id;
            }
            
            $anak = new Anak();
            $anak->pengguna_id = $pengguna_id;
            $anak->nama_anak = $request->nama_anak;
            $anak->tempat_lahir = $request->tempat_lahir;
            $anak->tanggal_lahir = $request->tanggal_lahir;
            $anak->jenis_kelamin = $request->jenis_kelamin;
            $anak->usia = $request->usia;
            $anak->save();
            
            // Load data pengguna
            $anak->load('pengguna');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data anak berhasil disimpan',
                'data' => $anak
            ], 201);
        } catch (\Exception $e) {
            \Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail anak
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Log untuk debugging
        \Log::info("=== Show anak data ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Anak ID: {$id}");
        
        // Jika tidak ada user, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        $anak = Anak::with('pengguna')->find($id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $anak->pengguna_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke data ini'
            ], 403);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $anak
        ]);
    }

    /**
     * Mengupdate data anak
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        // Log untuk debugging
        \Log::info("=== Update anak data ===");
        \Log::info("Headers: " . json_encode($request->headers->all()));
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Child ID: {$id}");
        \Log::info("Request data:", $request->all());
        
        // Jika tidak ada user, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        $anak = Anak::find($id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $anak->pengguna_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengubah data ini'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Admin bisa mengubah pengguna_id
            if ($user->role === 'admin' && $request->has('pengguna_id')) {
                $anak->pengguna_id = $request->pengguna_id;
            }
            
            $anak->nama_anak = $request->nama_anak;
            $anak->tempat_lahir = $request->tempat_lahir;
            $anak->tanggal_lahir = $request->tanggal_lahir;
            $anak->jenis_kelamin = $request->jenis_kelamin;
            $anak->usia = $request->usia;
            $anak->save();
            
            // Load data pengguna
            $anak->load('pengguna');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data anak berhasil diperbarui',
                'data' => $anak
            ]);
        } catch (\Exception $e) {
            \Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus data anak
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        // Log untuk debugging
        \Log::info("=== Delete anak data ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Anak ID: {$id}");
        
        // Jika tidak ada user, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        $anak = Anak::find($id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $anak->pengguna_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini'
            ], 403);
        }

        try {
            $anak->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data anak berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mencari anak berdasarkan NIK orang tua
     */
    public function findByPenggunaNik($nik)
    {
        $user = Auth::user();
        
        // Log untuk debugging
        \Log::info("=== Find anak by NIK ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Requested NIK: {$nik}");
        
        // Jika tidak ada user, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        // Verifikasi akses - hanya admin atau pemilik NIK yang boleh mengakses
        if ($user->role !== 'admin' && $user->nik !== $nik) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke data ini'
            ], 403);
        }
        
        try {
            $pengguna = Pengguna::where('nik', $nik)->first();
            
            if (!$pengguna) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna dengan NIK tersebut tidak ditemukan'
                ], 404);
            }
            
            $anakList = Anak::where('pengguna_id', $pengguna->id)->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $anakList
            ]);
        } catch (\Exception $e) {
            \Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghubungkan anak dengan orang tua
     */
    public function linkToParent(Request $request)
    {
        $user = Auth::user();
        
        // Log untuk debugging
        \Log::info("=== Link anak to parent ===");
        \Log::info("Headers: " . json_encode($request->headers->all()));
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Request data:", $request->all());
        
        // Jika tidak ada user, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'nik' => 'required|exists:pengguna,nik',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $pengguna = Pengguna::where('nik', $request->nik)->first();
            $anak = Anak::find($request->anak_id);
            
            // Verifikasi akses
            if ($user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hanya admin yang dapat menautkan data anak'
                ], 403);
            }
            
            // Verifikasi pengguna adalah parent
            if ($pengguna->role !== 'parent') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'NIK yang diberikan bukan milik orang tua'
                ], 400);
            }
            
            $anak->pengguna_id = $pengguna->id;
            $anak->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data anak berhasil dikaitkan dengan orang tua',
                'data' => [
                    'anak' => $anak,
                    'pengguna' => [
                        'id' => $pengguna->id,
                        'nik' => $pengguna->nik,
                        'nama' => $pengguna->nama
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengaitkan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mendapatkan anak berdasarkan ID pengguna
     */
    public function getAnakByPenggunaId($pengguna_id)
    {
        $user = Auth::user();
        
        // Log untuk debugging
        \Log::info("=== Get anak by pengguna ID ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Requested pengguna_id: {$pengguna_id}");
        
        // Jika tidak ada user, return error
        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengidentifikasi pengguna'
            ], 401);
        }
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $user->id != $pengguna_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke data ini'
            ], 403);
        }
        
        try {
            $anakList = Anak::where('pengguna_id', $pengguna_id)->get();
            \Log::info("Found {$anakList->count()} children");
            
            return response()->json([
                'status' => 'success',
                'data' => $anakList
            ]);
        } catch (\Exception $e) {
            \Log::error("Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}