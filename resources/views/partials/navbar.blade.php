<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4 flex items-center justify-between py-2">
        <!-- Logo dan Judul -->
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logoBPS.svg') }}" alt="logo BPS" class="h-10">
            <h1 class="text-xl font-bold text-blue-900">Dashboard <br>Inflasi</h1>
        </div>

        <!-- Navigasi -->
        <ul class="flex items-center space-x-6 text-gray-700 font-medium">
            <li><a href="{{ route('landingPage') }}" class="hover:text-blue-600">Beranda</a></li>
            <li><a href="{{ route('dashboard.index') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><a href="{{ route('landingPage') }}" class="hover:text-blue-600">Tentang Kami</a></li>
        </ul>

        <!-- Tombol Login -->
        <div>
            <a href="{}" class="flex items-center px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-700 font-jakarta">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0v-1m6 0H5m6 0V9m0 0H5m6 0V7a3 3 0 016 0v1m-6 0h6" />
                </svg>
                Login
            </a>
        </div>
    </div>
</nav>
