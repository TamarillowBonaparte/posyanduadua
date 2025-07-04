@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <!-- Add CSRF meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Imunisasi</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Imunisasi</span>
            </nav>
        </div>
    </div>
    @if(isset($action) && $action == 'create')
        <!-- Form Create -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Tambah Data Imunisasi</h3>
                <a href="{{ route('imunisasi.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('imunisasi.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <select name="anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('anak_id') border-red-500 @enderror">
                            <option value="">-Pilih Anak-</option>
                            @foreach($dataAnak as $anak)
                                <option value="{{ $anak->id }}" {{ old('anak_id') == $anak->id ? 'selected' : '' }}>{{ $anak->nama_anak }}</option>
                            @endforeach
                        </select>
                        @error('anak_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal Imunisasi*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal') }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Jenis Imunisasi*</label>
                        <select name="jenis_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('jenis_id') border-red-500 @enderror">
                            <option value="">-Pilih Jenis Imunisasi-</option>
                            @foreach($jenisImunisasi as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Status*</label>
                        <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('status') border-red-500 @enderror">
                            <option value="">-Pilih-</option>
                            <option value="Belum" {{ old('status') == 'Belum' ? 'selected' : '' }}>Belum</option>
                            <option value="Selesai Sesuai" {{ old('status') == 'Selesai Sesuai' ? 'selected' : '' }}>Selesai Sesuai</option>
                            <option value="Selesai Tidak Sesuai" {{ old('status') == 'Selesai Tidak Sesuai' ? 'selected' : '' }}>Selesai Tidak Sesuai</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan Data</button>
                </div>
            </form>
        </div>
    @elseif(isset($action) && $action == 'edit')
        <!-- Form Edit -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Edit Data Imunisasi</h3>
                <a href="{{ route('imunisasi.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('imunisasi.update', $imunisasi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <select name="anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('anak_id') border-red-500 @enderror">
                            <option value="">-Pilih Anak-</option>
                            @foreach($dataAnak as $anak)
                                <option value="{{ $anak->id }}" {{ old('anak_id', $imunisasi->anak_id) == $anak->id ? 'selected' : '' }}>{{ $anak->nama_anak }}</option>
                            @endforeach
                        </select>
                        @error('anak_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal Imunisasi*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal', $imunisasi->tanggal->format('Y-m-d')) }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Jenis Imunisasi*</label>
                        <select name="jenis_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('jenis_id') border-red-500 @enderror">
                            <option value="">-Pilih Jenis Imunisasi-</option>
                            @foreach($jenisImunisasi as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_id', $imunisasi->jenis_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Status*</label>
                        <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('status') border-red-500 @enderror">
                            <option value="">-Pilih-</option>
                            <option value="Belum" {{ old('status', $imunisasi->status) == 'Belum' ? 'selected' : '' }}>Belum</option>
                            <option value="Selesai Sesuai" {{ old('status', $imunisasi->status) == 'Selesai Sesuai' ? 'selected' : '' }}>Selesai Sesuai</option>
                            <option value="Selesai Tidak Sesuai" {{ old('status', $imunisasi->status) == 'Selesai Tidak Sesuai' ? 'selected' : '' }}>Selesai Tidak Sesuai</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui Data</button>
                </div>
            </form>
        </div>
    @else
        <!-- Index View (List) -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="bg-white/60 p-6 rounded-xl border border-white/40">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
                    <div class="flex gap-2">
                        <a href="{{ route('imunisasi.excel') }}" class="bg-gradient-to-r from-green-500 to-green-400 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Unduh Excel
                        </a>
                    </div>
                    <form action="{{ route('imunisasi.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('imunisasi.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
                @if(isset($search) && $search)
                    <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong> ({{ $imunisasi->total() }} hasil)
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="flex justify-end mb-4">
                    <form action="{{ route('imunisasi.index') }}" method="GET" class="flex items-center">
                        <input type="hidden" name="search" value="{{ $search ?? '' }}">
                        <label for="perPage" class="mr-2 text-sm">Tampilkan:</label>
                        <select name="perPage" id="perPage" class="border rounded p-1 text-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="ml-2 text-sm">data per halaman</span>
                    </form>
                </div>
                @if(isset($availableJadwal) && count($availableJadwal) > 0)
                <div class="mb-6 mt-4">
                    <h3 class="text-lg font-semibold mb-2 text-[#06B3BF]">Jadwal Imunisasi yang Tersedia</h3>
                    
                    <div class="mb-4 flex flex-col md:flex-row gap-2 justify-between">
                        <div class="flex space-x-2">
                            <button onclick="showAllJadwal()" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-lg text-sm transition">Semua</button>
                            <button onclick="collapseAllJadwal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-lg text-sm transition">Tutup Semua</button>
                        </div>
                        <div class="w-full md:w-64">
                            <input type="text" id="search-eligible-children" placeholder="Cari nama anak..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        @foreach($availableJadwal as $index => $jadwalData)
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 mb-4 rounded-lg border border-[#63BE9A]/20 jadwal-container">
                            <div class="flex justify-between items-center mb-2 cursor-pointer" onclick="toggleJadwalContent('jadwal-content-{{ $index }}')">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#06B3BF] toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <h4 class="font-bold text-black">{{ $jadwalData['jenis_imunisasi']->nama }}</h4>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs">{{ count($jadwalData['eligible_children']) }} anak</span>
                                </div>
                                <div class="text-sm">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $jadwalData['jadwal']->tanggal }}</span>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded ml-2">{{ $jadwalData['jadwal']->waktu }}</span>
                                </div>
                            </div>
                            
                            <div id="jadwal-content-{{ $index }}" class="jadwal-content mt-3 hidden">
                                <p class="text-sm text-gray-600 mb-2">Umur yang disarankan: {{ $jadwalData['jenis_imunisasi']->min_umur_hari }} - {{ $jadwalData['jenis_imunisasi']->max_umur_hari }} hari</p>
                                
                                @if(!empty($jadwalData['jenis_imunisasi']->keterangan))
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-3">
                                    <p class="text-sm text-yellow-800">{{ $jadwalData['jenis_imunisasi']->keterangan }}</p>
                                </div>
                                @endif
                                
                                <div class="mb-2 flex justify-between items-center">
                                    <h5 class="text-sm font-semibold">Anak yang memenuhi syarat ({{ count($jadwalData['eligible_children']) }}):</h5>
                                    <div class="flex space-x-2">
                                        <span class="text-xs text-gray-500">Tampilkan:</span>
                                        <select onchange="changeChildrenDisplay(this, 'children-grid-{{ $index }}')" class="text-xs border rounded px-1">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="all">Semua</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div id="children-grid-{{ $index }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 children-grid">
                                    @foreach($jadwalData['eligible_children'] as $childData)
                                    <div class="bg-white p-3 rounded border border-[#63BE9A]/30 shadow-sm child-card" data-child-name="{{ strtolower($childData['anak']->nama_anak) }}">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium">{{ $childData['anak']->nama_anak }}</p>
                                                <p class="text-xs text-gray-500">Umur: {{ $childData['umur_hari'] ?? $childData['child_age_days'] }} hari</p>
                                            </div>
                                            @if($childData['already_registered'])
                                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Sudah Terdaftar</span>
                                            @else
                                                <form action="{{ route('imunisasi.register-from-jadwal') }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="anak_id" value="{{ $childData['anak']->id }}">
                                                    <input type="hidden" name="jadwal_imunisasi_id" value="{{ $jadwalData['jadwal']->id }}">
                                                    <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition">Daftarkan</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="text-center py-2 children-pagination">
                                    <div class="inline-flex mt-2">
                                        <button onclick="prevChildrenPage('children-grid-{{ $index }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded-l text-xs">
                                            &lt; Prev
                                        </button>
                                        <span class="bg-gray-100 text-gray-800 py-1 px-3 text-xs page-info">
                                            Page <span class="current-page">1</span>
                                        </span>
                                        <button onclick="nextChildrenPage('children-grid-{{ $index }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded-r text-xs">
                                            Next &gt;
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] border-collapse border border-gray-300 text-left rounded-xl overflow-hidden">
                        <thead class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white">
                            <tr>
                                <th class="border border-gray-300 p-3 w-16">No</th>
                                <th class="border border-gray-300 p-3">Nama Anak</th>
                                <th class="border border-gray-300 p-3">Tanggal Imunisasi</th>
                                <th class="border border-gray-300 p-3">Jenis Imunisasi</th>
                                <th class="border border-gray-300 p-3">Status</th>
                                <th class="border border-gray-300 p-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($imunisasi as $index => $i)
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ ($imunisasi->currentPage() - 1) * $imunisasi->perPage() + $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">{{ $i->anak->nama_anak ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $i->tanggal->format('d F Y') }}</td>
                                    <td class="border border-gray-300 p-3">{{ $i->jenisImunisasi->nama ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $i->status }}</td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="fetchImunisasiDetail({{ $i->id }})" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</button>
                                            <button onclick="fetchImunisasiEdit({{ $i->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Edit</button>
                                            @if($i->status == 'Belum')
                                            <button onclick="showStatusOptions({{ $i->id }})" class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Selesai</button>
                                            @endif
                                            <form action="{{ route('imunisasi.destroy', $i->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-gray-300 p-3 text-center bg-white/60">
                                        @if(isset($search) && $search)
                                            Tidak ada data imunisasi yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data imunisasi
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($imunisasi) > 0 && count($imunisasi) < 4)
                                @for($i = 0; $i < 4 - count($imunisasi); $i++)
                                    <tr>
                                        <td class="border border-gray-300 p-3 h-12 bg-white/60" colspan="6"></td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $imunisasi->firstItem() ?? 0 }} sampai {{ $imunisasi->lastItem() ?? 0 }} dari {{ $imunisasi->total() }} data
                    </div>
                    <div>
                        {{ $imunisasi->appends(['search' => $search ?? '', 'perPage' => request('perPage') ?? 10])->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Modal -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Detail Imunisasi</h3>
                    <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto max-h-[70vh]">
                    <div class="space-y-4 mb-6">
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Anak</h3>
                            <p id="detail_nama_anak" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tanggal Imunisasi</h3>
                            <p id="detail_tanggal" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Jenis Imunisasi</h3>
                            <p id="detail_jenis_imunisasi" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Status</h3>
                            <p id="detail_status" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Usia Minimum</h3>
                            <p id="detail_min_umur" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Usia Maksimum</h3>
                            <p id="detail_max_umur" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Keterangan</h3>
                            <p id="detail_keterangan" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Terdaftar Pada</h3>
                            <p id="detail_terdaftar" class="text-lg text-black">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Edit Data Imunisasi</h3>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                            <select name="anak_id" id="edit_anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                                <option value="">-Pilih Anak-</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Tanggal Imunisasi*</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Jenis Imunisasi*</label>
                            <select name="jenis_id" id="edit_jenis_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                                <option value="">-Pilih Jenis Imunisasi-</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Status*</label>
                            <select name="status" id="edit_status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                                <option value="">-Pilih-</option>
                                <option value="Belum">Belum</option>
                                <option value="Selesai Sesuai">Selesai Sesuai</option>
                                <option value="Selesai Tidak Sesuai">Selesai Tidak Sesuai</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Options Modal -->
        <div id="statusOptionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Pilih Status Imunisasi</h3>
                    <button onclick="closeStatusModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 text-center">
                    <p class="mb-6 text-gray-700">Pilih status imunisasi yang sesuai:</p>
                    <div class="flex justify-center gap-4">
                        <button id="btnSelesaiSesuai" onclick="updateStatusImunisasi(this.dataset.id, 'Selesai Sesuai')" class="bg-green-500 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Selesai Sesuai</button>
                        <button id="btnSelesaiTidakSesuai" onclick="updateStatusImunisasi(this.dataset.id, 'Selesai Tidak Sesuai')" class="bg-orange-500 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Selesai Tidak Sesuai</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if(!isset($action))
<script>
function openDetailModal() {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    document.body.style.overflow = 'hidden'; // Disable scrolling
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
    document.body.style.overflow = ''; // Re-enable scrolling
}

function fetchImunisasiDetail(id) {
    // Show loading state
    document.getElementById('detail_nama_anak').textContent = 'Loading...';
    document.getElementById('detail_tanggal').textContent = 'Loading...';
    document.getElementById('detail_jenis_imunisasi').textContent = 'Loading...';
    document.getElementById('detail_status').textContent = 'Loading...';
    document.getElementById('detail_min_umur').textContent = 'Loading...';
    document.getElementById('detail_max_umur').textContent = 'Loading...';
    document.getElementById('detail_keterangan').textContent = 'Loading...';
    document.getElementById('detail_terdaftar').textContent = 'Loading...';
    
    // Open modal
    openDetailModal();
    
    // Fetch data
    fetch(`{{ url('/imunisasi') }}/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Format date
        const tanggal = new Date(data.imunisasi.tanggal);
        const formattedTanggal = tanggal.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        const createdAt = new Date(data.imunisasi.created_at);
        const formattedCreatedAt = createdAt.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Populate data
        document.getElementById('detail_nama_anak').textContent = data.anak ? data.anak.nama_anak : '-';
        document.getElementById('detail_tanggal').textContent = formattedTanggal;
        document.getElementById('detail_jenis_imunisasi').textContent = data.jenisImunisasi ? data.jenisImunisasi.nama : '-';
        document.getElementById('detail_status').textContent = data.imunisasi.status;
        document.getElementById('detail_min_umur').textContent = data.jenisImunisasi ? `${data.jenisImunisasi.min_umur_hari} hari` : '-';
        document.getElementById('detail_max_umur').textContent = data.jenisImunisasi ? `${data.jenisImunisasi.max_umur_hari} hari` : '-';
        document.getElementById('detail_keterangan').textContent = data.jenisImunisasi && data.jenisImunisasi.keterangan ? data.jenisImunisasi.keterangan : '-';
        document.getElementById('detail_terdaftar').textContent = formattedCreatedAt;
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        closeDetailModal();
        alert('Terjadi kesalahan saat mengambil data');
    });
}

// Close modal when clicking outside of it
window.addEventListener('click', function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        closeDetailModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('detailModal').classList.contains('hidden')) {
            closeDetailModal();
        }
    }
});

function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    document.body.style.overflow = '';
}

function fetchImunisasiEdit(id) {
    // Open modal
    openEditModal();
    
    // Fetch data
    fetch(`{{ url('/imunisasi') }}/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Edit data received:', data); // Log untuk debugging
        
        // Set form action
        document.getElementById('editForm').action = `{{ url('/imunisasi') }}/${id}`;
        
        // Clear existing options except the first one (placeholder)
        const anakSelect = document.getElementById('edit_anak_id');
        const jenisSelect = document.getElementById('edit_jenis_id');
        const statusSelect = document.getElementById('edit_status');
        
        // Keep only the first option
        anakSelect.innerHTML = '<option value="">-Pilih Anak-</option>';
        jenisSelect.innerHTML = '<option value="">-Pilih Jenis Imunisasi-</option>';
        
        // Populate anak options
        if (data.dataAnak && data.dataAnak.length > 0) {
            data.dataAnak.forEach(anak => {
                const option = document.createElement('option');
                option.value = anak.id;
                option.textContent = anak.nama_anak;
                if (Number(anak.id) === Number(data.imunisasi.anak_id)) {
                    option.selected = true;
                }
                anakSelect.appendChild(option);
            });
        }
        
        // Populate jenis imunisasi options
        if (data.jenisImunisasiList && data.jenisImunisasiList.length > 0) {
            data.jenisImunisasiList.forEach(jenis => {
                const option = document.createElement('option');
                option.value = jenis.id;
                option.textContent = jenis.nama;
                if (Number(jenis.id) === Number(data.imunisasi.jenis_id)) {
                    option.selected = true;
                }
                jenisSelect.appendChild(option);
            });
        }
        
        // Set tanggal
        let tanggalValue = '';
        if (data.imunisasi.tanggal) {
            // Coba parse tanggal dan format untuk input date HTML (YYYY-MM-DD)
            try {
                const tanggalParts = data.imunisasi.tanggal.split('T')[0];
                tanggalValue = tanggalParts;
                console.log('Tanggal value:', tanggalValue);
            } catch (e) {
                console.error('Error parsing tanggal:', e);
            }
        }
        document.getElementById('edit_tanggal').value = tanggalValue;
        
        // Set status
        statusSelect.value = data.imunisasi.status;
        console.log('Status value set to:', data.imunisasi.status);
        
        // Set up form submission handler
        document.getElementById('editForm').onsubmit = function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.errors) {
                    // Handle validation errors
                    alert('Terjadi kesalahan validasi data');
                    console.error(result.errors);
                } else {
                    // Success
                    closeEditModal();
                    window.location.reload(); // Reload to see updated data
                }
            })
            .catch(error => {
                console.error('Error updating data:', error);
                alert('Terjadi kesalahan saat memperbarui data');
            });
        };
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        closeEditModal();
        alert('Terjadi kesalahan saat mengambil data');
    });
}

// Close edit modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
});

// Close edit modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('editModal').classList.contains('hidden')) {
            closeEditModal();
        }
    }
});

// Toggle jadwal content visibility
function toggleJadwalContent(contentId) {
    const content = document.getElementById(contentId);
    const isHidden = content.classList.contains('hidden');
    const iconEl = content.previousElementSibling.querySelector('.toggle-icon');
    
    if (isHidden) {
        content.classList.remove('hidden');
        // Change icon to down arrow
        iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
    } else {
        content.classList.add('hidden');
        // Change icon to right arrow
        iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
    }
}

// Show all jadwal contents
function showAllJadwal() {
    document.querySelectorAll('.jadwal-content').forEach(content => {
        content.classList.remove('hidden');
        const iconEl = content.previousElementSibling.querySelector('.toggle-icon');
        iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
    });
}

// Collapse all jadwal contents
function collapseAllJadwal() {
    document.querySelectorAll('.jadwal-content').forEach(content => {
        content.classList.add('hidden');
        const iconEl = content.previousElementSibling.querySelector('.toggle-icon');
        iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
    });
}

// Search eligible children
document.getElementById('search-eligible-children').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    document.querySelectorAll('.child-card').forEach(card => {
        const childName = card.getAttribute('data-child-name');
        if (childName.includes(searchTerm)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});

// Change number of children to display
function changeChildrenDisplay(selectEl, gridId) {
    const grid = document.getElementById(gridId);
    const cards = grid.querySelectorAll('.child-card');
    const value = selectEl.value;
    
    // Reset pagination to first page
    const pageInfo = grid.parentElement.querySelector('.current-page');
    pageInfo.textContent = '1';
    
    // Show all cards first
    cards.forEach(card => card.style.display = '');
    
    if (value === 'all') {
        // Show all cards
        return;
    }
    
    const limit = parseInt(value);
    cards.forEach((card, index) => {
        if (index >= limit) {
            card.style.display = 'none';
        }
    });
    
    // Update pagination controls visibility
    updatePaginationVisibility(gridId, cards.length, limit);
}

// Update pagination visibility
function updatePaginationVisibility(gridId, totalItems, itemsPerPage) {
    const paginationEl = document.getElementById(gridId).parentElement.querySelector('.children-pagination');
    
    if (itemsPerPage === 'all' || totalItems <= itemsPerPage) {
        paginationEl.style.display = 'none';
    } else {
        paginationEl.style.display = 'block';
    }
}

// Next page for children
function nextChildrenPage(gridId) {
    const grid = document.getElementById(gridId);
    const cards = grid.querySelectorAll('.child-card');
    const selectEl = grid.parentElement.querySelector('select');
    const pageInfo = grid.parentElement.querySelector('.current-page');
    
    if (selectEl.value === 'all') return;
    
    const itemsPerPage = parseInt(selectEl.value);
    const currentPage = parseInt(pageInfo.textContent);
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    
    if (currentPage < totalPages) {
        const nextPage = currentPage + 1;
        pageInfo.textContent = nextPage;
        
        // Hide all cards first
        cards.forEach(card => card.style.display = 'none');
        
        // Show only cards for the current page
        const startIndex = (nextPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, cards.length);
        
        for (let i = startIndex; i < endIndex; i++) {
            cards[i].style.display = '';
        }
    }
}

// Previous page for children
function prevChildrenPage(gridId) {
    const grid = document.getElementById(gridId);
    const cards = grid.querySelectorAll('.child-card');
    const selectEl = grid.parentElement.querySelector('select');
    const pageInfo = grid.parentElement.querySelector('.current-page');
    
    if (selectEl.value === 'all') return;
    
    const itemsPerPage = parseInt(selectEl.value);
    const currentPage = parseInt(pageInfo.textContent);
    
    if (currentPage > 1) {
        const prevPage = currentPage - 1;
        pageInfo.textContent = prevPage;
        
        // Hide all cards first
        cards.forEach(card => card.style.display = 'none');
        
        // Show only cards for the current page
        const startIndex = (prevPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, cards.length);
        
        for (let i = startIndex; i < endIndex; i++) {
            cards[i].style.display = '';
        }
    }
}

// Initialize all grids on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.children-grid').forEach(grid => {
        const cards = grid.querySelectorAll('.child-card');
        const selectEl = grid.parentElement.querySelector('select');
        
        // Set initial display
        if (cards.length > 10) {
            cards.forEach((card, index) => {
                if (index >= 10) {
                    card.style.display = 'none';
                }
            });
            
            // Show pagination
            grid.parentElement.querySelector('.children-pagination').style.display = 'block';
        } else {
            // Hide pagination
            grid.parentElement.querySelector('.children-pagination').style.display = 'none';
        }
    });
});

// Function to update imunisasi status
function updateStatusImunisasi(id, status) {
    if (!confirm('Apakah Anda yakin ingin menandai imunisasi ini sebagai "' + status + '"?')) {
        return;
    }
    
    closeStatusModal();
    
    fetch(`{{ url('/imunisasi') }}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            status: status
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        alert('Status imunisasi berhasil diperbarui!');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error updating status:', error);
        alert('Gagal memperbarui status imunisasi. Silakan coba lagi.');
    });
}

function showStatusOptions(id) {
    // Set ID pada tombol-tombol di modal
    document.getElementById('btnSelesaiSesuai').dataset.id = id;
    document.getElementById('btnSelesaiTidakSesuai').dataset.id = id;
    
    // Tampilkan modal
    document.getElementById('statusOptionsModal').classList.remove('hidden');
    document.getElementById('statusOptionsModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeStatusModal() {
    document.getElementById('statusOptionsModal').classList.add('hidden');
    document.getElementById('statusOptionsModal').classList.remove('flex');
    document.body.style.overflow = '';
}

// Close status modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('statusOptionsModal');
    if (event.target === modal) {
        closeStatusModal();
    }
});

// Close status modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('statusOptionsModal').classList.contains('hidden')) {
            closeStatusModal();
        }
    }
});
</script>
@endif

@endsection
