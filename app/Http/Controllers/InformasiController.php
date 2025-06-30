<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {
        $informasi = [
            'dashboard' => [
                'judul' => 'Dashboard',
                'deskripsi' => 'Halaman dashboard menampilkan ringkasan statistik penting untuk posyandu, seperti:',
                'fitur' => [
                    'Total Kasus Stunting',
                    'Total Pengguna',
                    'Total Data Anak',
                    'Total Petugas',
                    'Dan terdapat fitur lainnya'
                ]
            ],
            'jadwal' => [
                'judul' => 'Jadwal',
                'deskripsi' => 'Halaman jadwal berisi informasi dan manajemen jadwal posyandu, meliputi:',
                'fitur' => [
                    'Jadwal imunisasi anak',
                    'Jadwal pemberian vitamin',
                    'Jadwal pemeriksaan kesehatan',
                    'Penambahan, pengubahan, dan penghapusan jadwal',
                    'Pelacakan status implementasi jadwal'
                ]
            ],
            'petugas' => [
                'judul' => 'Petugas',
                'deskripsi' => 'Halaman petugas digunakan untuk mengelola data petugas posyandu, dengan fitur:',
                'fitur' => [
                    'Tambah petugas baru',
                    'Edit data petugas',
                    'Hapus petugas (khusus kader utama)',
                    'Pembagian status kader (utama dan anggota)',
                    'Pencarian dan filter data petugas'
                ]
            ],
            'artikel' => [
                'judul' => 'Artikel',
                'deskripsi' => 'Halaman artikel untuk berbagi informasi kesehatan dan edukasi, dengan fitur:',
                'fitur' => [
                    'Tambah artikel baru',
                    'Edit artikel',
                    'Hapus artikel',
                    'Tampilan daftar artikel',
                    'Detail artikel'
                ]
            ],
            'pengguna' => [
                'judul' => 'Data Pengguna',
                'deskripsi' => 'Halaman data pengguna untuk mengelola akun pengguna sistem, mencakup:',
                'fitur' => [
                    'Daftar pengguna',
                    'Tambah pengguna baru',
                    'Edit data pengguna',
                    'Hapus pengguna',
                    'Pengaturan hak akses'
                ]
            ],
            'perkembangan_anak' => [
                'judul' => 'Perkembangan Anak',
                'deskripsi' => 'Halaman perkembangan anak digunakan untuk memantau dan mencatat pertumbuhan serta perkembangan anak, dengan fitur:',
                'fitur' => [
                    'Rekam data perkembangan anak',
                    'Grafik pertumbuhan (berat badan, tinggi badan)',
                    'Penilaian status gizi',
                    'Riwayat pemeriksaan kesehatan',
                    'Catatan perkembangan motorik dan kognitif',
                    'Filter dan pencarian data anak'
                ]
            ],
            'imunisasi' => [
                'judul' => 'Imunisasi',
                'deskripsi' => 'Halaman imunisasi untuk mengelola dan memantau proses imunisasi anak, mencakup:',
                'fitur' => [
                    'Daftar jenis imunisasi',
                    'Rekam data imunisasi anak',
                    'Jadwal imunisasi',
                    'Status kelengkapan imunisasi',
                    'Pengingat imunisasi yang akan datang',
                    'Laporan status imunisasi'
                ]
            ],
            'data_orangtua' => [
                'judul' => 'Data Orang Tua',
                'deskripsi' => 'Halaman data orang tua untuk mengelola informasi kontak dan identitas orang tua/wali, dengan:',
                'fitur' => [
                    'Tambah data orang tua',
                    'Edit informasi orang tua',
                    'Hapus data orang tua',
                    'Informasi kontak',
                    'Alamat lengkap',
                    'Pencarian dan filter data'
                ]
            ],
            'data_anak' => [
                'judul' => 'Data Anak',
                'deskripsi' => 'Halaman data anak untuk mencatat dan mengelola informasi anak, meliputi:',
                'fitur' => [
                    'Tambah data anak baru',
                    'Edit profil anak',
                    'Hapus data anak',
                    'Informasi identitas anak',
                    'Riwayat kesehatan',
                    'Pencarian dan filter data anak'
                ]
            ],
            'vitamin' => [
                'judul' => 'Vitamin',
                'deskripsi' => 'Halaman vitamin untuk mengelola pemberian vitamin dan suplemen pada anak, dengan:',
                'fitur' => [
                    'Daftar jenis vitamin',
                    'Rekam data pemberian vitamin',
                    'Jadwal pemberian vitamin',
                    'Status pemberian vitamin',
                    'Pengingat jadwal vitamin',
                    'Laporan konsumsi vitamin'
                ]
            ],
            'stunting' => [
                'judul' => 'Stunting',
                'deskripsi' => 'Halaman stunting untuk memantau dan menangani kasus stunting pada anak, mencakup:',
                'fitur' => [
                    'Penilaian status stunting',
                    'Perhitungan indeks antropometri',
                    'Riwayat pemeriksaan stunting',
                    'Rekomendasi intervensi',
                    'Grafik perkembangan status gizi',
                    'Pencatatan riwayat penanganan'
                ]
            ]
        ];

        return view('informasi', compact('informasi'));
    }
} 