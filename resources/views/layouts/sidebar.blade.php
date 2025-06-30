<div class="bg-gradient-to-b from-[#FFA4D3] via-[#63BE9A] to-[#06B3BF] w-64 min-h-screen p-6 shadow-xl">
    {{-- Logo Section --}}
    <div class="flex items-center justify-center mb-8">
        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-2xl shadow-lg">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Posyandu" class="w-20 h-20 object-contain">
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="space-y-3">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('dashboard') ? 'bg-[#FFA4D3]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#FFA4D3]/20 hover:text-white' }}">
            <span class="text-xl mr-3">📊</span>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <a href="{{ route('jadwal') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('jadwal') ? 'bg-[#63BE9A]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#63BE9A]/20 hover:text-white' }}">
            <span class="text-xl mr-3">📅</span>
            <span class="font-medium">Jadwal</span>
        </a>

        <a href="{{ route('petugas.index') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('petugas') ? 'bg-[#06B3BF]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#06B3BF]/20 hover:text-white' }}">
            <span class="text-xl mr-3">👮</span>
            <span class="font-medium">Petugas</span>
        </a>

        <a href="{{ route('artikel.index') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('artikel') ? 'bg-[#FFA4D3]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#FFA4D3]/20 hover:text-white' }}">
            <span class="text-xl mr-3">📰</span>
            <span class="font-medium">Artikel</span>
        </a>

        <a href="{{ route('pengguna.index') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ request()->routeIs('pengguna.*') ? 'bg-[#63BE9A]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#63BE9A]/20 hover:text-white' }}">
            <span class="text-xl mr-3">👥</span>
            <span class="font-medium">Data Pengguna</span>
        </a>

        <a href="{{ route('informasi.index') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('informasi') ? 'bg-[#FFA4D3]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#FFA4D3]/20 hover:text-white' }}">
            <span class="text-xl mr-3">❓</span>
            <span class="font-medium">Informasi & Bantuan</span>
        </a>
    </nav>

    {{-- Logout Button --}}
    <div class="mt-3">
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
        <button onclick="confirmLogout()" 
            class="w-full flex items-center p-3 rounded-xl transition-all duration-300 text-white/80 hover:bg-red-500/20 hover:text-white">
            <span class="text-xl mr-3">🚪</span>
            <span class="font-medium">Keluar</span>
        </button>
    </div>
</div>

{{-- Confirmation Modal --}}
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Keluar</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari aplikasi?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeLogoutModal()" 
                class="px-4 py-2 text-gray-600 bg-gray-100 rounded hover:bg-gray-200 transition-colors">
                Tidak
            </button>
            <button onclick="document.getElementById('logout-form').submit()" 
                class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600 transition-colors">
                Iya
            </button>
        </div>
    </div>
</div>

<script>
function confirmLogout() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.remove('flex');
    document.getElementById('logoutModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogoutModal();
    }
});
</script>
