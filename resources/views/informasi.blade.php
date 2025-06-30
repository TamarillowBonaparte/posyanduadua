@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Informasi & Bantuan</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Informasi & Bantuan</span>
            </nav>
        </div>
    </div>

    <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
        <div class="space-y-6">
            @php
                $perPage = 3; // Jumlah item per halaman
                $currentPage = request()->get('page', 1);
                $informasiPaginated = collect($informasi)->chunk($perPage);
                $currentPageItems = $informasiPaginated->get($currentPage - 1, collect());
            @endphp

            @foreach($currentPageItems as $detail)
                <div class="bg-white/60 p-6 rounded-xl border border-white/40 hover:shadow-lg transition-all duration-300">
                    <h3 class="text-2xl font-bold text-[#06B3BF] mb-4">{{ $detail['judul'] }}</h3>
                    <p class="text-gray-700 mb-4">{{ $detail['deskripsi'] }}</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        @foreach($detail['fitur'] as $fitur)
                            <li>{{ $fitur }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            {{-- Pagination --}}
            <div class="flex justify-center mt-6">
                <nav class="flex items-center space-x-2">
                    @if($currentPage > 1)
                        <a href="{{ route('informasi.index', ['page' => $currentPage - 1]) }}" 
                           class="px-4 py-2 bg-white/60 text-[#06B3BF] rounded-lg hover:bg-[#06B3BF]/10 transition">
                            &laquo; Sebelumnya
                        </a>
                    @endif

                    @php
                        $totalPages = $informasiPaginated->count();
                        $startPage = max(1, $currentPage - 1);
                        $endPage = min($totalPages, $startPage + 2);
                    @endphp

                    @for($page = $startPage; $page <= $endPage; $page++)
                        <a href="{{ route('informasi.index', ['page' => $page]) }}" 
                           class="px-4 py-2 {{ $currentPage == $page ? 'bg-[#06B3BF] text-white' : 'bg-white/60 text-[#06B3BF]' }} rounded-lg hover:opacity-90 transition">
                            {{ $page }}
                        </a>
                    @endfor

                    @if($currentPage < $totalPages)
                        <a href="{{ route('informasi.index', ['page' => $currentPage + 1]) }}" 
                           class="px-4 py-2 bg-white/60 text-[#06B3BF] rounded-lg hover:bg-[#06B3BF]/10 transition">
                            Selanjutnya &raquo;
                        </a>
                    @endif
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection 