<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        
        $query = Pengguna::where('role', 'admin');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }
        
        $petugas = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return view('petugas', compact('petugas', 'search'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:pengguna,nik',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:8',
            'no_telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'kader_status' => 'required|in:utama,anggota'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Pengguna::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'kader_status' => $request->kader_status
        ]);

        return redirect()->route('petugas.index')
            ->with('success', 'Data petugas berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email,' . $id . ',nik',
            'password' => 'nullable|string|min:8',
            'no_telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'kader_status' => 'required|in:utama,anggota'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $petugas = Pengguna::where('nik', $id)->firstOrFail();
        
        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'kader_status' => $request->kader_status
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $petugas->update($data);

        return redirect()->route('petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $currentUser = auth()->user();
        $petugas = Pengguna::where('nik', $id)->firstOrFail();

        if ($currentUser->isKaderUtama()) {
            if ($petugas->nik === $currentUser->nik) {
                return redirect()->route('petugas.index')
                    ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
            }

            if ($petugas->isKaderAnggota()) {
                $petugas->delete();
                return redirect()->route('petugas.index')
                    ->with('success', 'Data petugas berhasil dihapus!');
            }
        }

        return redirect()->route('petugas.index')
            ->with('error', 'Anda tidak memiliki izin untuk menghapus akun ini!');
    }
}
