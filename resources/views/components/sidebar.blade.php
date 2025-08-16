<div id="sidebarComponent" class="flex flex-col w-64 h-screen p-5 transition-all duration-300 bg-white text-biru1">
    <style>
        .active {
            background-color: #4C84B0;
            color: white;
        }

        .dropdown-active {
            display: block !important;
        }

        .collapsed {
            width: 4rem !important;
            /* Lebar sidebar saat diperkecil */
            padding: 1rem !important;
        }

        .collapsed .menu-text {
            display: none !important;
            /* Sembunyikan teks saat sidebar diperkecil */
        }

        .collapsed .dropdown-arrow {
            display: none !important;
            /* Sembunyikan panah dropdown */
        }

        .collapsed .user-name {
            display: none !important;
        }

        .collapsed .logout-text {
            display: none !important;
        }

        .collapsed .logout-icon {
            margin: 0 auto !important;
        }

        /* Hilangkan ruang kosong ketika sidebar diperkecil */
        .collapsed .group {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0.5rem !important;
            /* sempitkan vertikal */
            padding-bottom: 0.5rem !important;
            gap: 0 !important;
        }

        /* Rapikan jarak vertikal ikon */
        .collapsed ul.space-y-3>*,
        .collapsed ul.space-y-2>* {
            margin-top: 0.125rem !important;
            /* lebih rapat */
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .dropdown-arrow {
            fill: biru1;
            /* Warna default panah */
        }

        .group:hover {
            fill: #ffffff;
            /* Warna panah saat di-hover atau diklik */
        }

        .dropdown-arrow,
        .dropdown-arrow.rotate-180 {
            fill: biru1;
            /* Warna panah saat di-hover atau diklik */
        }
    </style>

    <div class="flex-1 overflow-y-auto">
        <!-- Logo & Toggle -->
        <div class="flex items-center gap-3">
            <button id="toggleSidebar" class="rounded text-biru1">
                <img src="{{ asset('images/navbar/logoBPS.svg') }}" alt="Logo Dashboard" class="w-12 h-12">
            </button>
            <p id="dashboardText"
                class="text-xl font-bold leading-none transition-all duration-300 text-biru1 menu-text">
                <span>Dashboard</span><br>
                <span>Inflasi</span>
            </p>
        </div>
        <div class="my-2 border-b-2 shadow-lg"></div>
        <ul class="space-y-3">
            @auth
                @if (Auth::user()->id_role == 1)
                    {{-- Admin Provinsi --}}
                    <a href="{{ route('prov.index') }}"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                    @elseif(Auth::user()->id_role == 2)
                        {{-- Admin Kab/Kota --}}
                        <a href="{{ route('kabkot.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                        @else
                            {{-- Role lain, fallback ke landingPage --}}
                            <a href="{{ route('landingPage') }}"
                                class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                @endif
            @else
                {{-- Guest --}}
                <a href="{{ route('landingPage') }}"
                    class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                @endauth
                <img src="{{ asset('images/sidebar/bhomeIcon.svg') }}" alt="Ikon Beranda" class="w-6 h-6 icon"
                    data-hover="{{ asset('images/sidebar/phomeIcon.svg') }}"
                    data-default="{{ asset('images/sidebar/bhomeIcon.svg') }}">
                <span class="menu-text font-medium text-[15px]">Beranda</span>
            </a>
            </li>
            @auth
                @if (Auth::user()->id_role != 2)
                    <li class="relative">
                        <a id="dropdownToggleManajemenAkun"
                            class="flex items-center justify-between w-full gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                            <div class="flex items-center">
                                <img src="{{ asset('images/sidebar/bmanakunIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                                    class="w-6 h-6 icon" data-hover="{{ asset('images/sidebar/pmanakunIcon.svg') }}"
                                    data-default="{{ asset('images/sidebar/bmanakunIcon.svg') }}">
                                <span class="pl-3 menu-text font-medium text-[15px]">Manajemen Akun</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-300 dropdown-arrow" viewBox="0 0 20 20">
                                <path
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </a>
                        <ul id="dropdownMenuManajemenAkun"
                            class="hidden mt-2 ml-4 space-y-2 bg-white border-l-2 border-biru5">
                            <li><a href="{{ route('manajemen-akun.index') }}"
                                    class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                                    data-page="manajemen-akun">Tabel Manajemen Akun</a></li>
                            <li><a href="{{ route('master-satker.index') }}"
                                    class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                                    data-page="manajemen-satker">Master Satker</a></li>
                        </ul>
                    </li>
                @endif
                <li class="relative">
                    <a id="dropdownToggleManajemenData"
                        class="flex items-center justify-between w-full gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                        <div class="flex items-center">
                            <img src="{{ asset('images/sidebar/bmandataIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                                class="w-6 h-6 icon" data-hover="{{ asset('images/sidebar/pmandataIcon.svg') }}"
                                data-default="{{ asset('images/sidebar/bmandataIcon.svg') }}">
                            <span class="pl-3 menu-text font-medium text-[15px]">Manajemen Data Inflasi</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300 dropdown-arrow" viewBox="0 0 20 20">
                            <path
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </a>
                    <ul id="dropdownMenuManajemenData" class="hidden mt-2 ml-4 space-y-2 bg-white border-l-2 border-biru5">
                        @if (Auth::user()->id_role == 2)
                            <li><a href="{{ route('manajemen-data-inflasi.index') }}"
                                    class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                                    data-page="manajemen-data-inflasi.index">Tabel Manajemen Data Inflasi</a></li>
                        @else
                            <li><a href="{{ route('manajemen-data-inflasi.index') }}"
                                    class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                                    data-page="manajemen-data-inflasi.index">Tabel Manajemen Data Inflasi</a></li>
                            <li><a href="{{ route('master-komoditas.index') }}"
                                    class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                                    data-page="master-komoditas.index">Master Komoditas</a></li>
                            <li><a href="{{ route('komoditas-utama.index') }}"
                                    class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                                    data-page="komoditas-utama.index">Master Komoditas Utama</a></li>
                        @endif
                    </ul>
                </li>
            @endauth
            <li class="relative">
                <a id="dropdownToggleDashboard"
                    class="flex items-center justify-between w-full gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                    <div class="flex items-center">
                        <img src="{{ asset('images/sidebar/bdashboardIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                            class="w-6 h-6 icon" data-hover="{{ asset('images/sidebar/pdashboardIcon.svg') }}"
                            data-default="{{ asset('images/sidebar/bdashboardIcon.svg') }}">
                        <span class="pl-3 menu-text font-medium text-[15px]">Dashboard Inflasi</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-300 dropdown-arrow" viewBox="0 0 20 20">
                        <path
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                    </svg>
                </a>
                <ul id="dropdownMenuDashboard" class="hidden mt-2 ml-4 space-y-2 bg-white border-l-2 border-biru5">
                    {{-- <li><a href="{{ route('dashboard.bulanan') }}" class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text" data-page="dashboard.bulanan">Inflasi Bulanan</a></li> --}}
                    <li><a href="{{ route('dashboard.spasial') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-spasial">Inflasi Bulanan</a></li>
                    <li><a href="{{ route('dashboard.kelompok') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-kelompok">Kelompok Pengeluaran</a></li>
                    <li><a href="{{ route('dashboard.series') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-series">Series Inflasi</a></li>
                </ul>
            </li>
            @guest
                <li>
                    <a href="{{ route('daftar-tabel-inflasi.index') }}"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group"
                        data-page="tabel">
                        <img src="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}" alt="Ikon Data Inflasi"
                            class="w-6 h-6 icon" data-hover="{{ asset('images/sidebar/pdataInflasiIcon.svg') }}"
                            data-default="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}">
                        <span class="menu-text font-medium text-[15px]">Daftar Tabel Data Inflasi</span>
                    </a>
                </li>
            @endguest
        </ul>
    </div>
    <div class="mt-auto">
        <ul>
            <li>
                <div class="flex items-center gap-3 px-2 py-2 rounded-lg group">
                    <img src="{{ asset('images/sidebar/buserIcon.svg') }}" alt="Profil Ikon" class="w-6 h-6 icon"
                        data-hover="{{ asset('images/sidebar/puserIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/buserIcon.svg') }}">
                    <span class="menu-text font-medium text-[15px] user-name">
                        {{ Auth::user()->name ?? 'Guest' }}
                    </span>
                </div>
            </li>
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full gap-3 px-2 py-2 rounded-lg bg-biru4 hover:bg-biru1 hover:text-white group">
                            <img src="{{ asset('images/sidebar/blogoutIcon.svg') }}" alt="Logout Ikon"
                                class="w-6 h-6 icon logout-icon"
                                data-hover="{{ asset('images/sidebar/plogoutIcon.svg') }}"
                                data-default="{{ asset('images/sidebar/blogoutIcon.svg') }}">
                            <span class="menu-text font-medium text-[15px] logout-text">Logout</span>
                        </button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarComponent = document.getElementById("sidebarComponent");
        const toggleButton = document.getElementById("toggleSidebar");
        const currentPath = window.location.pathname;

        function setupDropdown(toggleId, menuId) {
            const toggle = document.getElementById(toggleId);
            const menu = document.getElementById(menuId);
            if (!toggle || !menu) return;
            const arrow = toggle.querySelector(".dropdown-arrow");
            toggle.addEventListener("click", function() {
                menu.classList.toggle("dropdown-active");
                if (arrow) arrow.classList.toggle("rotate-180");
            });
        }

        function highlightActiveMenu() {
            document.querySelectorAll("[data-page]").forEach(item => {
                const page = item.getAttribute("data-page");
                const link = item.getAttribute("href");
                if (currentPath.includes(page) || (link && currentPath.includes(new URL(link, window
                        .location.origin).pathname))) {
                    item.classList.add("active");
                    const parentMenu = item.closest("ul");
                    if (parentMenu && parentMenu.classList.contains("hidden")) {
                        parentMenu.classList.add("dropdown-active");
                        const parentToggle = parentMenu.previousElementSibling.querySelector(
                            ".dropdown-arrow");
                        if (parentToggle) parentToggle.classList.add("rotate-180");
                    }
                }
            });
        }

                function setupIconHover() {
            document.querySelectorAll(".icon").forEach(icon => {
                const defaultSrc = icon.getAttribute("data-default");
                const hoverSrc = icon.getAttribute("data-hover");
                const parentLink = icon.closest("a");
                const parentButton = icon.closest("button");
                const parentElement = parentLink || parentButton;
                
                // Check if parent element exists before adding event listeners
                if (!parentElement) {
                    console.warn('Icon without parent link/button found:', icon);
                    return;
                }
                
                parentElement.addEventListener("mouseenter", () => {
                    icon.setAttribute("src", hoverSrc);
                });
                parentElement.addEventListener("mouseleave", () => {
                    if (!parentElement.classList.contains("active")) {
                        icon.setAttribute("src", defaultSrc);
                    }
                });
                if (parentElement.classList.contains("active")) {
                    icon.setAttribute("src", hoverSrc);
                }
            });
        }
        if (sidebarComponent && toggleButton) {
            toggleButton.addEventListener("click", function(e) {
                e.stopPropagation();
                if (sidebarComponent.classList.contains('w-64')) {
                    sidebarComponent.classList.remove('w-64');
                    sidebarComponent.classList.add('w-24');
                } else {
                    sidebarComponent.classList.remove('w-24');
                    sidebarComponent.classList.add('w-64');
                }
                sidebarComponent.classList.toggle("collapsed");
            });
        }
        setupDropdown("dropdownToggleManajemenAkun", "dropdownMenuManajemenAkun");
        setupDropdown("dropdownToggleManajemenData", "dropdownMenuManajemenData");
        setupDropdown("dropdownToggleDashboard", "dropdownMenuDashboard");
        highlightActiveMenu();
        setupIconHover();
    });
</script>
