@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <!-- Add CSRF meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Perkembangan Anak</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Perkembangan Anak</span>
            </nav>
        </div>
    </div>
    
    @if(isset($action) && $action == 'create')
        <!-- Form Create -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Tambah Data Perkembangan Anak</h3>
                <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('perkembangan.store') }}" method="POST">
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
                        <label class="block text-sm font-semibold text-black">Tanggal*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal') }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                        <input type="number" name="berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('berat_badan') border-red-500 @enderror" placeholder="Berat Badan" value="{{ old('berat_badan') }}">
                        @error('berat_badan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                        <input type="number" name="tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tinggi_badan') border-red-500 @enderror" placeholder="Tinggi Badan" value="{{ old('tinggi_badan') }}">
                        @error('tinggi_badan')
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
                <h3 class="text-2xl font-bold text-black">Edit Data Perkembangan Anak</h3>
                @if(isset($return_to_riwayat))
                    <a href="{{ route('perkembangan.riwayat', $return_to_riwayat) }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
                @else
                    <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
                @endif
            </div>
            <form action="{{ route('perkembangan.update', $perkembangan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="_method" value="PUT">
                @if(isset($return_to_riwayat))
                    <input type="hidden" name="return_to_riwayat" value="{{ $return_to_riwayat }}">
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <select name="anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('anak_id') border-red-500 @enderror">
                            <option value="">-Pilih Anak-</option>
                            @foreach($dataAnak as $anak)
                                <option value="{{ $anak->id }}" {{ old('anak_id', $perkembangan->anak_id) == $anak->id ? 'selected' : '' }}>{{ $anak->nama_anak }}</option>
                            @endforeach
                        </select>
                        @error('anak_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal', $perkembangan->tanggal->format('Y-m-d')) }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                        <input type="number" name="berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('berat_badan') border-red-500 @enderror" placeholder="Berat Badan" value="{{ old('berat_badan', $perkembangan->berat_badan) }}">
                        @error('berat_badan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                        <input type="number" name="tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tinggi_badan') border-red-500 @enderror" placeholder="Tinggi Badan" value="{{ old('tinggi_badan', $perkembangan->tinggi_badan) }}">
                        @error('tinggi_badan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui Data</button>
                </div>
            </form>
        </div>
    @elseif(isset($action) && $action == 'show')
        <!-- Detail View -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Detail Data Perkembangan Anak</h3>
                <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm mb-4">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Anak</h3>
                        <p class="text-lg text-black">{{ $perkembangan->anak->nama_anak ?? 'Tidak ada data' }}</p>
                    </div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Ibu</h3>
                        <p class="text-lg text-black">{{ $perkembangan->anak->pengguna->nama ?? '-' }}</p>
                    </div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tempat Lahir</h3>
                        <p class="text-lg text-black">{{ $perkembangan->anak->tempat_lahir ?? '-' }}</p>
                    </div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tanggal</h3>
                        <p class="text-lg text-black">
                            @if(isset($perkembangan->tanggal) && $perkembangan->tanggal instanceof \DateTime)
                                {{ $perkembangan->tanggal->format('d F Y') }}
                            @else
                                Tidak ada data
                            @endif
                        </p>
                    </div>
                </div>
                <div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm mb-4">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Berat Badan</h3>
                        <p class="text-lg text-black">{{ $perkembangan->berat_badan }} kg</p>
                    </div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tinggi Badan</h3>
                        <p class="text-lg text-black">{{ $perkembangan->tinggi_badan }} cm</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Terdaftar Pada</h3>
                <p class="text-lg text-black">
                    @if(isset($perkembangan->created_at) && $perkembangan->created_at instanceof \DateTime)
                        {{ $perkembangan->created_at->format('d F Y, H:i') }}
                    @else
                        Tidak ada data
                    @endif
                </p>
            </div>
            
            <div class="mt-8 flex justify-between">
                <a href="{{ route('perkembangan.create') }}" class="bg-gradient-to-r from-green-500 to-green-400 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Tambah Data</a>
                
                <form action="{{ route('perkembangan.destroy', $perkembangan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-gradient-to-r from-red-500 to-red-400 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Hapus Data</button>
                </form>
            </div>
        </div>
    @elseif(isset($action) && $action == 'riwayat')
        <!-- Riwayat View -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Riwayat Perkembangan: {{ $anak->nama_anak }}</h3>
                <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            
            <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-[#06B3BF]">Data Anak</h3>
                        <p class="text-sm text-gray-600">Nama: {{ $anak->nama_anak }}</p>
                        <p class="text-sm text-gray-600">Tanggal Lahir: {{ $anak->tanggal_lahir->format('d F Y') }}</p>
                        <p class="text-sm text-gray-600">Jenis Kelamin: {{ $anak->jenis_kelamin }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Menyimpan data anak untuk JavaScript -->
            <script>
                // Menyimpan data anak untuk diakses dari JavaScript
                var anak = {
                    id: {{ $anak->id }},
                    nama_anak: "{{ $anak->nama_anak }}"
                };
                console.log('Anak data initialized:', anak);
            </script>
            
            <!-- Row Page Selector untuk Riwayat -->
            <div class="flex justify-end mb-4">
                <form action="{{ route('perkembangan.riwayat', $anak->id) }}" method="GET" class="flex items-center">
                    <label for="perPage" class="mr-2 text-sm">Tampilkan:</label>
                    <select name="perPage" id="perPage" class="border rounded p-1 text-sm" onchange="this.form.submit()">
                        <option value="5" {{ request('perPage') == 5 || request('perPage') == null && 5 == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="ml-2 text-sm">data per halaman</span>
                </form>
            </div>
            
            <!-- Tabel Riwayat -->
            <div class="bg-white/60 p-6 rounded-xl border border-white/40">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] border-collapse border border-gray-300 text-left rounded-xl overflow-hidden">
                        <thead class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white">
                            <tr>
                                <th class="border border-gray-300 p-3 w-16">No</th>
                                <th class="border border-gray-300 p-3">Tanggal</th>
                                <th class="border border-gray-300 p-3">Berat Badan</th>
                                <th class="border border-gray-300 p-3">Status BB</th>
                                <th class="border border-gray-300 p-3">Tinggi Badan</th>
                                <th class="border border-gray-300 p-3">Status TB</th>
                                <th class="border border-gray-300 p-3">Terdaftar</th>
                                <th class="border border-gray-300 p-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($perkembangan as $index => $p)
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ ($perkembangan->currentPage() - 1) * $perkembangan->perPage() + $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">
                                    @if(isset($p->tanggal) && $p->tanggal instanceof \DateTime)
                                        {{ $p->tanggal->format('d F Y') }}
                                    @else
                                        Tanggal tidak valid
                                    @endif
                                    </td>
                                    <td class="border border-gray-300 p-3">{{ $p->berat_badan ?? '-' }} kg</td>
                                    <td class="border border-gray-300 p-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($p->status_berat_badan == 'Sangat Kurang') bg-red-100 text-red-800
                                            @elseif($p->status_berat_badan == 'Kurang') bg-orange-100 text-orange-800
                                            @elseif($p->status_berat_badan == 'Resiko Kurang') bg-yellow-100 text-yellow-800
                                            @elseif($p->status_berat_badan == 'Normal') bg-green-100 text-green-800
                                            @elseif($p->status_berat_badan == 'Resiko Lebih') bg-blue-100 text-blue-800
                                            @elseif($p->status_berat_badan == 'Lebih') bg-indigo-100 text-indigo-800
                                            @elseif($p->status_berat_badan == 'Sangat Lebih') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $p->status_berat_badan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 p-3">{{ $p->tinggi_badan ?? '-' }} cm</td>
                                    <td class="border border-gray-300 p-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($p->status_tinggi_badan == 'Sangat Kurang') bg-red-100 text-red-800
                                            @elseif($p->status_tinggi_badan == 'Kurang') bg-orange-100 text-orange-800
                                            @elseif($p->status_tinggi_badan == 'Resiko Kurang') bg-yellow-100 text-yellow-800
                                            @elseif($p->status_tinggi_badan == 'Normal') bg-green-100 text-green-800
                                            @elseif($p->status_tinggi_badan == 'Resiko Lebih') bg-blue-100 text-blue-800
                                            @elseif($p->status_tinggi_badan == 'Lebih') bg-indigo-100 text-indigo-800
                                            @elseif($p->status_tinggi_badan == 'Sangat Lebih') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $p->status_tinggi_badan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 p-3">
                                    @if(isset($p->created_at) && $p->created_at instanceof \DateTime)
                                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">
                                        {{ $p->created_at->diffForHumans() }}
                                </span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            <a href="javascript:void(0)" onclick="fetchDetail({{ $p->id }})" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</a>
                                            <a href="{{ route('perkembangan.edit', ['id' => $p->id, 'return_to_riwayat' => $anak->id]) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Edit</a>
                                            <form action="{{ url('/perkembangan/'.$p->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                                        Tidak ada data riwayat perkembangan untuk anak ini
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($perkembangan) > 0 && count($perkembangan) < 4)
                                @for($i = 0; $i < 4 - count($perkembangan); $i++)
                                    <tr>
                                        <td class="border border-gray-300 p-3 h-12 bg-white/60" colspan="6"></td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                        </div>
                    </div>
            
            <!-- Pagination untuk Riwayat -->
            <div class="mt-6 flex justify-center">
                {{ $perkembangan->links() }}
            </div>
        </div>
    @else
        <!-- Index View (List) -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="bg-white/60 p-6 rounded-xl border border-white/40">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
                    <div class="flex gap-2">
                        <a href="javascript:void(0)" onclick="showTambahForm()" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Tambah</a>
                        <a href="{{ route('perkembangan.excel') }}" class="bg-gradient-to-r from-green-500 to-green-400 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Unduh Excel
                        </a>
                    </div>
                    <form action="{{ route('perkembangan.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('perkembangan.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(isset($search) && $search)
                    <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong> ({{ $perkembangan->total() }} hasil)
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Row Page Selector -->
                <div class="flex justify-end mb-4">
                    <form action="{{ route('perkembangan.index') }}" method="GET" class="flex items-center">
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

                <!-- Wrapper untuk Scroll Horizontal -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] border-collapse border border-gray-300 text-left rounded-xl overflow-hidden">
                        <thead class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white">
                            <tr>
                                <th class="border border-gray-300 p-3 w-16">No</th>
                                <th class="border border-gray-300 p-3">Nama Anak</th>
                                <th class="border border-gray-300 p-3">Nama Ibu</th>
                                <th class="border border-gray-300 p-3">Tempat Lahir</th>
                                <th class="border border-gray-300 p-3">Tanggal</th>
                                <th class="border border-gray-300 p-3">Berat Badan</th>
                                <th class="border border-gray-300 p-3">Status BB</th>
                                <th class="border border-gray-300 p-3">Tinggi Badan</th>
                                <th class="border border-gray-300 p-3">Status TB</th>
                                <th class="border border-gray-300 p-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($perkembangan as $index => $p)
                                {{-- Debugging: Tampilkan ID perkembangan --}}
                                @php
                                    Log::info('Processing perkembangan ID: ' . ($p->id ?? 'NULL'));
                                @endphp
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ ($perkembangan->currentPage() - 1) * $perkembangan->perPage() + $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->nama_anak ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->pengguna->nama ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->tempat_lahir ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">
                                        @if(isset($p->tanggal) && $p->tanggal instanceof \DateTime)
                                            {{ $p->tanggal->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-3">{{ $p->berat_badan ?? '-' }} kg</td>
                                    <td class="border border-gray-300 p-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($p->status_berat_badan == 'Sangat Kurang') bg-red-100 text-red-800
                                            @elseif($p->status_berat_badan == 'Kurang') bg-orange-100 text-orange-800
                                            @elseif($p->status_berat_badan == 'Resiko Kurang') bg-yellow-100 text-yellow-800
                                            @elseif($p->status_berat_badan == 'Normal') bg-green-100 text-green-800
                                            @elseif($p->status_berat_badan == 'Resiko Lebih') bg-blue-100 text-blue-800
                                            @elseif($p->status_berat_badan == 'Lebih') bg-indigo-100 text-indigo-800
                                            @elseif($p->status_berat_badan == 'Sangat Lebih') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $p->status_berat_badan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 p-3">{{ $p->tinggi_badan ?? '-' }} cm</td>
                                    <td class="border border-gray-300 p-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($p->status_tinggi_badan == 'Sangat Kurang') bg-red-100 text-red-800
                                            @elseif($p->status_tinggi_badan == 'Kurang') bg-orange-100 text-orange-800
                                            @elseif($p->status_tinggi_badan == 'Resiko Kurang') bg-yellow-100 text-yellow-800
                                            @elseif($p->status_tinggi_badan == 'Normal') bg-green-100 text-green-800
                                            @elseif($p->status_tinggi_badan == 'Resiko Lebih') bg-blue-100 text-blue-800
                                            @elseif($p->status_tinggi_badan == 'Lebih') bg-indigo-100 text-indigo-800
                                            @elseif($p->status_tinggi_badan == 'Sangat Lebih') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $p->status_tinggi_badan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            @if($p->id)
                                                <a href="javascript:void(0)" onclick="fetchDetail({{ $p->id }})" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</a>
                                                <a href="javascript:void(0)" onclick="showTambahForm({{ $p->anak_id }})" class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Tambah</a>
                                                <a href="{{ route('perkembangan.riwayat', $p->anak_id) }}" class="bg-purple-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Riwayat</a>
                                                <form action="{{ url('/perkembangan/'.$p->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Hapus</button>
                                                </form>
                                            @else
                                                <span class="text-red-500">ID tidak valid</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-gray-300 p-3 text-center bg-white/60">
                                        @if(isset($search) && $search)
                                            Tidak ada data perkembangan anak yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data perkembangan anak
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($perkembangan) > 0 && count($perkembangan) < 4)
                                @for($i = 0; $i < 4 - count($perkembangan); $i++)
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
                        @if($perkembangan->count() > 0)
                            Menampilkan {{ $perkembangan->firstItem() ?? 0 }} sampai {{ $perkembangan->lastItem() ?? 0 }} dari {{ $perkembangan->total() }} data
                        @else
                            Tidak ada data yang ditampilkan
                        @endif
                    </div>
                    <div>
                        {{ $perkembangan->appends(['search' => $search ?? '', 'perPage' => request('perPage') ?? 10])->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal untuk Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-black">Detail Perkembangan Anak</h3>
            <button onclick="closeModal('detailModal')" class="text-gray-500 hover:text-gray-700">
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
                    <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Ibu</h3>
                    <p id="detail_nama_ibu" class="text-lg text-black">Loading...</p>
                </div>
                <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tempat Lahir</h3>
                    <p id="detail_tempat_lahir" class="text-lg text-black">Loading...</p>
                </div>
                <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tanggal</h3>
                    <p id="detail_tanggal" class="text-lg text-black">Loading...</p>
                </div>
                <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Berat Badan</h3>
                    <p id="detail_berat_badan" class="text-lg text-black">Loading...</p>
                </div>
                <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tinggi Badan</h3>
                    <p id="detail_tinggi_badan" class="text-lg text-black">Loading...</p>
                </div>
                <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Terdaftar Pada</h3>
                    <p id="detail_terdaftar" class="text-lg text-black">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-black">Edit Data Perkembangan Anak</h3>
            <button onclick="closeModal('editModal')" class="text-gray-500 hover:text-gray-700">
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
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black">Tanggal*</label>
                    <input type="date" name="tanggal" id="edit_tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                    <input type="number" name="berat_badan" id="edit_berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Berat Badan">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                    <input type="number" name="tinggi_badan" id="edit_tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Tinggi Badan">
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk Tambah Data -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-black">Tambah Data Perkembangan Anak</h3>
            <button onclick="closeModal('tambahModal')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="tambahForm" action="{{ route('perkembangan.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                    <select name="anak_id" id="tambah_anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                        <option value="">-Pilih Anak-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black">Tanggal*</label>
                    <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                    <input type="number" name="berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Berat Badan">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                    <input type="number" name="tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Tinggi Badan">
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
// Fungsi untuk membuka modal
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
    document.body.style.overflow = '';
}

// Fungsi untuk fetch data detail
function fetchDetail(id) {
    // Tampilkan loading state
    document.getElementById('detail_nama_anak').textContent = 'Loading...';
    document.getElementById('detail_nama_ibu').textContent = 'Loading...';
    document.getElementById('detail_tempat_lahir').textContent = 'Loading...';
    document.getElementById('detail_tanggal').textContent = 'Loading...';
    document.getElementById('detail_berat_badan').textContent = 'Loading...';
    document.getElementById('detail_tinggi_badan').textContent = 'Loading...';
    document.getElementById('detail_terdaftar').textContent = 'Loading...';
    
    // Buka modal
    openModal('detailModal');
    
    // Ambil data
    fetch(`/perkembangan/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Format tanggal
            const tanggal = new Date(data.perkembangan.tanggal);
            const formattedTanggal = tanggal.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            const createdAt = new Date(data.perkembangan.created_at);
            const formattedCreatedAt = createdAt.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Isi data
            document.getElementById('detail_nama_anak').textContent = data.anak ? data.anak.nama_anak : '-';
            document.getElementById('detail_nama_ibu').textContent = data.anak && data.anak.pengguna ? data.anak.pengguna.nama : '-';
            document.getElementById('detail_tempat_lahir').textContent = data.anak ? data.anak.tempat_lahir : '-';
            document.getElementById('detail_tanggal').textContent = formattedTanggal;
            document.getElementById('detail_berat_badan').textContent = `${data.perkembangan.berat_badan} kg`;
            document.getElementById('detail_tinggi_badan').textContent = `${data.perkembangan.tinggi_badan} cm`;
            document.getElementById('detail_terdaftar').textContent = formattedCreatedAt;
        } else {
            alert('Gagal mengambil data detail');
            closeModal('detailModal');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data');
        closeModal('detailModal');
    });
}

// Fungsi untuk fetch data edit
function fetchEdit(id) {
    console.log('Fetching edit for ID:', id);
    // Cek keberadaan DOM elements
    const editModal = document.getElementById('editModal');
    if (!editModal) {
        console.error('Modal edit tidak ditemukan');
        alert('Terjadi kesalahan: Modal edit tidak ditemukan');
        return;
    }
    
    // Buka modal
    openModal('editModal');
    
    // Ambil data
    fetch(`/perkembangan/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Edit response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Edit data received:', data);
        
        // Set form action dengan URL absolut
        const editForm = document.getElementById('editForm');
        if (editForm) {
            // Gunakan base URL dari dokumen untuk memastikan URL absolut yang benar
            const baseUrl = window.location.origin;
            editForm.action = `${baseUrl}/perkembangan/${id}`;
            console.log('Form action set to:', editForm.action);
        } else {
            console.error('Form edit tidak ditemukan');
        }
        
        // Clear existing options except the first one
        const anakSelect = document.getElementById('edit_anak_id');
        if (!anakSelect) {
            console.error('Select anak_id tidak ditemukan');
            return;
        }
        
        anakSelect.innerHTML = '<option value="">-Pilih Anak-</option>';
        
        // Cek apakah kita berada di halaman riwayat
        const isRiwayatPage = window.location.href.includes('/perkembangan/riwayat/');
        console.log('Is riwayat page:', isRiwayatPage);
        
        if (isRiwayatPage) {
            // Di halaman riwayat, kita selalu tahu ID anak dari URL
            // Format URL: /perkembangan/riwayat/{anak_id}
            const urlParts = window.location.href.split('/');
            const anakIdFromUrl = urlParts[urlParts.length - 1].split('?')[0]; // Ambil anak_id dari URL, hapus query string jika ada
            console.log('Anak ID from URL:', anakIdFromUrl);
            
            // Jika ada data anak di halaman
            if (typeof anak !== 'undefined') {
                console.log('Anak data from window:', anak);
                // Buat single option dengan data anak dari halaman
                const option = document.createElement('option');
                option.value = anak.id;
                option.textContent = anak.nama_anak;
                option.selected = true;
                anakSelect.appendChild(option);
                anakSelect.disabled = true; // Disable select karena hanya ada satu opsi
            } 
            // Jika tidak ada variabel anak di window, gunakan dari response
            else if (data.anak) {
                console.log('Anak data from response:', data.anak);
                const option = document.createElement('option');
                option.value = data.anak.id;
                option.textContent = data.anak.nama_anak;
                option.selected = true;
                anakSelect.appendChild(option);
                anakSelect.disabled = true;
            }
            // Jika masih gagal, gunakan dari dataAnak
            else if (data.dataAnak && data.dataAnak.length > 0 && data.perkembangan.anak_id) {
                data.dataAnak.forEach(anak => {
                    if (anak.id == data.perkembangan.anak_id) {
                        const option = document.createElement('option');
                        option.value = anak.id;
                        option.textContent = anak.nama_anak;
                        option.selected = true;
                        anakSelect.appendChild(option);
                    }
                });
                anakSelect.disabled = true;
            }
        } 
        // Jika bukan di halaman riwayat, tampilkan semua anak seperti biasa
        else if (data.dataAnak && data.dataAnak.length > 0) {
            data.dataAnak.forEach(anak => {
                const option = document.createElement('option');
                option.value = anak.id;
                option.textContent = anak.nama_anak;
                if (anak.id === data.perkembangan.anak_id) {
                    option.selected = true;
                }
                anakSelect.appendChild(option);
            });
        }
        
        // Set other form values
        const tanggalInput = document.getElementById('edit_tanggal');
        const beratInput = document.getElementById('edit_berat_badan');
        const tinggiInput = document.getElementById('edit_tinggi_badan');
        
        if (tanggalInput) {
            if (typeof data.perkembangan.tanggal === 'string') {
                tanggalInput.value = data.perkembangan.tanggal.split('T')[0];
            } else if (data.perkembangan.tanggal) {
                // Handle jika tanggal adalah objek
                console.log('Tanggal format:', data.perkembangan.tanggal);
                // Coba ambil format tanggal (YYYY-MM-DD) dari objek tanggal
                try {
                    // Jika format sudah Y-m-d, gunakan langsung
                    if (data.perkembangan.tanggal.date) {
                        const dateParts = data.perkembangan.tanggal.date.split(' ')[0].split('-');
                        tanggalInput.value = dateParts.join('-');
                    }
                } catch (e) {
                    console.error('Error parsing date:', e);
                }
            }
        }
        
        if (beratInput) {
            beratInput.value = data.perkembangan.berat_badan;
        }
        
        if (tinggiInput) {
            tinggiInput.value = data.perkembangan.tinggi_badan;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data');
        closeModal('editModal');
    });
}

// Fungsi untuk menampilkan form tambah
function showTambahForm(anak_id = null) {
    // Buka modal
    openModal('tambahModal');
    
    // Cek apakah kita berada di halaman riwayat
    const isRiwayatPage = window.location.href.includes('/perkembangan/riwayat/');
    console.log('Is riwayat page (tambah):', isRiwayatPage); // Log untuk debugging
    
    // Jika halaman riwayat dan variabel anak tersedia
    if (isRiwayatPage && typeof anak !== 'undefined') {
        console.log('Menggunakan anak dari halaman riwayat:', anak);
        
        // Clear existing options
        const anakSelect = document.getElementById('tambah_anak_id');
        anakSelect.innerHTML = '';
        
        // Tambahkan hanya 1 option dengan data anak dari halaman riwayat
        const option = document.createElement('option');
        option.value = anak.id;
        option.textContent = anak.nama_anak;
        option.selected = true;
        anakSelect.appendChild(option);
        anakSelect.disabled = true; // Disable select karena hanya ada satu opsi
    }
    // Jika bukan halaman riwayat atau tidak ada variabel anak, gunakan API seperti biasa
    else {
    // Ambil data anak untuk dropdown
    fetch('/api/anak', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Clear existing options except the first one
        const anakSelect = document.getElementById('tambah_anak_id');
        anakSelect.innerHTML = '<option value="">-Pilih Anak-</option>';
        
        // Populate anak options
        if (data && data.length > 0) {
            data.forEach(anak => {
                const option = document.createElement('option');
                option.value = anak.id;
                option.textContent = anak.nama_anak;
                    // Jika ada anak_id yang diberikan, pilih anak tersebut
                    if (anak_id && anak.id == anak_id) {
                        option.selected = true;
                    }
                anakSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data anak');
    });
    }
}

// Event listener untuk tombol
document.addEventListener('DOMContentLoaded', function() {
    // Ganti link tambah dengan tombol yang membuka modal
    const tambahBtn = document.querySelector('a[href="{{ route("perkembangan.create") }}"]');
    if (tambahBtn) {
        tambahBtn.href = 'javascript:void(0)';
        tambahBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showTambahForm();
        });
    }

    // Close modal ketika klik di luar modal
    window.addEventListener('click', function(event) {
        const detailModal = document.getElementById('detailModal');
        const editModal = document.getElementById('editModal');
        const tambahModal = document.getElementById('tambahModal');
        
        if (event.target === detailModal) closeModal('detailModal');
        if (event.target === editModal) closeModal('editModal');
        if (event.target === tambahModal) closeModal('tambahModal');
    });
    
    // Close modal dengan escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('detailModal');
            closeModal('editModal');
            closeModal('tambahModal');
        }
    });
    
    // Submit form handler untuk edit
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi form sebelum submit
            const anakId = document.getElementById('edit_anak_id').value;
            const tanggal = document.getElementById('edit_tanggal').value;
            const beratBadan = document.getElementById('edit_berat_badan').value;
            const tinggiBadan = document.getElementById('edit_tinggi_badan').value;
            
            if (!anakId || !tanggal || !beratBadan || !tinggiBadan) {
                alert('Semua field harus diisi!');
                return;
            }
            
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            // Log data untuk debugging
            console.log('Submitting form to URL:', this.action);
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(result => {
                console.log('Response result:', result);
                if (result.success) {
                    alert('Data berhasil diperbarui');
                    closeModal('editModal');
                    
                    // Redirect ke URL yang disediakan server jika ada, atau reload halaman
                    if (result.data && result.data.redirect_url) {
                        window.location.href = result.data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                } else {
                    let errorMessage = 'Terjadi kesalahan';
                    if (result.message) {
                        errorMessage = result.message;
                    } else if (result.errors) {
                        errorMessage = Object.values(result.errors).flat().join('\n');
                    }
                    alert('Gagal memperbarui data: ' + errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui data');
            });
        });
    } else {
        console.warn('Form edit tidak ditemukan');
    }
    
    // Submit form handler untuk tambah
    const tambahForm = document.getElementById('tambahForm');
    if (tambahForm) {
        tambahForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Data berhasil disimpan');
                    closeModal('tambahModal');
                    window.location.reload();
                } else {
                    alert('Gagal menyimpan data: ' + (result.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            });
        });
    } else {
        console.warn('Form tambah tidak ditemukan');
    }
});
</script>

@endsection
