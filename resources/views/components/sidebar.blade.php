<div id="sidebar" class="w-64 bg-white text-biru1 p-5 transition-all duration-300 flex flex-col justify-between">
    <style>
        .active {
            background-color: #4C84B0;
            color: white;
        }

        .dropdown-active {
            display: block !important;
        }

        .collapsed {
            width: 4rem;
            /* Lebar sidebar saat diperkecil */
            padding: 1rem;
        }

        .collapsed .menu-text {
            display: none;
            /* Sembunyikan teks saat sidebar diperkecil */
        }

        .collapsed .dropdown-arrow {
            display: none;
            /* Sembunyikan panah dropdown */
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

    <div>
        <!-- Logo & Toggle -->
        <div class="flex items-center gap-3">
            <button id="toggleSidebar" class="text-biru1 rounded">
                <img src="{{ asset('images/navbar/logoBPS.svg') }}" alt="Logo Dashboard" class="h-12 w-12">
            </button>
            <p id="dashboardText"
                class="text-xl font-bold text-biru1 leading-none menu-text">
                <span>Dashboard</span><br>
                <span>Inflasi</span>
            </p>
        </div>

        <div class="border-b-2 my-2 shadow-lg"></div>

        <!-- Menu Items -->
        <ul class="space-y-3">
            <li>
                <a href="{{ route(name: 'landingPage') }}"
                    class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                    <img src="{{ asset('images/sidebar/bhomeIcon.svg') }}" alt="Ikon Beranda" class="h-6 w-6 icon"
                        data-hover="{{ asset('images/sidebar/phomeIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/bhomeIcon.svg') }}">
                    <span class="menu-text font-medium text-[15px]">Beranda</span>
                </a>
            </li>

            <li class="relative">
                <a id="dropdownToggleManajemenAkun"
                    class="flex justify-between items-center w-full gap-3 px-2 py-2 hover:bg-biru4 hover:text-white rounded-lg group">
                    <div class="flex items-center">
                        <img src="{{ asset('images/sidebar/bmanakunIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                            class="h-6 w-6 icon" data-hover="{{ asset('images/sidebar/pmanakunIcon.svg') }}"
                            data-default="{{ asset('images/sidebar/bmanakunIcon.svg') }}">
                        <span class="pl-3 menu-text font-medium text-[15px]">Manajemen Akun</span>
                    </div>
                    <svg class="w-4 h-4 dropdown-arrow transition-transform duration-300" viewBox="0 0 20 20">
                        <path
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                    </svg>
                </a>
                <ul id="dropdownMenuManajemenAkun" class="hidden mt-2 space-y-2 bg-white ml-4 border-l-2 border-biru5">
                    <li><a href="{{ route('manajemen-akun.index') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-bulanan">Tabel Manajemen Akun</a></li>
                    <li><a href="{{ route('master-satker.index') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-spasial">Master Satker</a></li>
                </ul>
            </li>
            <li class="relative">
                <a id="dropdownToggleManajemenData"
                    class="flex justify-between items-center w-full gap-3 px-2 py-2 hover:bg-biru4 hover:text-white rounded-lg group">
                    <div class="flex items-center">
                        <img src="{{ asset('images/sidebar/bmandataIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                            class="h-6 w-6 icon" data-hover="{{ asset('images/sidebar/pmandataIcon.svg') }}"
                            data-default="{{ asset('images/sidebar/bmandataIcon.svg') }}">
                        <span class="pl-3 menu-text font-medium text-[15px]">Manajemen Data Inflasi</span>
                    </div>
                    <svg class="w-4 h-4 dropdown-arrow transition-transform duration-300" viewBox="0 0 20 20">
                        <path
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                    </svg>
                </a>
                <ul id="dropdownMenuManajemenData" class="hidden mt-2 space-y-2 bg-white ml-4 border-l-2 border-biru5">
                    <li><a href="{{ route('manajemen-data-inflasi.index') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-bulanan">Tabel Manajemen Data Inflasi</a></li>
                    <li><a href="{{ route('master-komoditas.index') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-spasial">Master Komoditas</a></li>
                </ul>
            </li>

            <li class="relative">
                <a id="dropdownToggleDashboard"
                    class="flex justify-between items-center w-full gap-3 px-2 py-2 hover:bg-biru4 hover:text-white rounded-lg group">
                    <div class="flex items-center">
                        <img src="{{ asset('images/sidebar/bdashboardIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                            class="h-6 w-6 icon" data-hover="{{ asset('images/sidebar/pdashboardIcon.svg') }}"
                            data-default="{{ asset('images/sidebar/bdashboardIcon.svg') }}">
                        <span class="pl-3 menu-text font-medium text-[15px]">Dashboard Inflasi</span>
                    </div>
                    <svg class="w-4 h-4 dropdown-arrow transition-transform duration-300" viewBox="0 0 20 20">
                        <path
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                    </svg>
                </a>
                <ul id="dropdownMenuDashboard" class="hidden mt-2 space-y-2 bg-white ml-4 border-l-2 border-biru5">
                    <li><a href="{{ route('dashboard.bulanan') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-bulanan">Inflasi Bulanan</a></li>
                    <li><a href="{{ route('dashboard.spasial') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-spasial">Inflasi Spasial</a></li>
                    <li><a href="{{ route('dashboard.kelompok') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-kelompok">Kelompok Pengeluaran</a></li>
                    <li><a href="{{ route('dashboard.series') }}"
                            class="flex items-center px-2 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-series">Series Inflasi</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('daftar-tabel-inlfasi.index') }}"
                    class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group"
                    data-page="tabel">
                    <img src="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}" alt="Ikon Data Inflasi"
                        class="h-6 w-6 icon" data-hover="{{ asset('images/sidebar/pdataInflasiIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}">
                    <span class="menu-text font-medium text-[15px]">Daftar Tabel Data Inflasi</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Profile -->
    {{-- <div class="mt-auto">
        <ul>
            <li>
                <a href="#"
                    class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group"
                    data-page="profil">
                    <img src="{{ asset('images/sidebar/buserIcon.svg') }}" alt="Profil Ikon" class="h-6 w-6 icon"
                        data-hover="{{ asset('images/sidebar/puserIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/buserIcon.svg') }}">
                    <span class="menu-text font-medium text-[15px]">Profil Pengguna</span>
                </a>
            </li>
        </ul>
    </div> --}}
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const toggleButton = document.getElementById("toggleSidebar");
        const currentPath = window.location.pathname;

        // Fungsi untuk toggle dropdown
        function setupDropdown(toggleId, menuId) {
            const toggle = document.getElementById(toggleId);
            const menu = document.getElementById(menuId);
            const arrow = toggle.querySelector(".dropdown-arrow");

            toggle.addEventListener("click", function() {
                menu.classList.toggle("dropdown-active");
                arrow.classList.toggle("rotate-180");
            });
        }

        // Fungsi untuk highlight menu aktif
        function highlightActiveMenu() {
            document.querySelectorAll("[data-page]").forEach(item => {
                const page = item.getAttribute("data-page");
                const link = item.getAttribute("href");

                if (currentPath.includes(page) || (link && currentPath.includes(new URL(link, window
                        .location.origin).pathname))) {
                    item.classList.add("active");

                    // Buka dropdown jika item aktif ada di dalamnya
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

        // Fungsi untuk mengubah ikon saat hover
        function setupIconHover() {
            document.querySelectorAll(".icon").forEach(icon => {
                const defaultSrc = icon.getAttribute("data-default");
                const hoverSrc = icon.getAttribute("data-hover");
                const parentLink = icon.closest("a");

                parentLink.addEventListener("mouseenter", () => {
                    icon.setAttribute("src", hoverSrc);
                });

                parentLink.addEventListener("mouseleave", () => {
                    if (!parentLink.classList.contains("active")) {
                        icon.setAttribute("src", defaultSrc);
                    }
                });

                if (parentLink.classList.contains("active")) {
                    icon.setAttribute("src", hoverSrc);
                }
            });
        }

        // Toggle Sidebar Collapse
        toggleButton.addEventListener("click", function() {
            sidebar.classList.toggle("collapsed");
        });

        // Setup dropdowns
        setupDropdown("dropdownToggleManajemenAkun", "dropdownMenuManajemenAkun");
        setupDropdown("dropdownToggleManajemenData", "dropdownMenuManajemenData");
        setupDropdown("dropdownToggleDashboard", "dropdownMenuDashboard");

        // Highlight active menu
        highlightActiveMenu();

        // Setup icon hover
        setupIconHover();
    });
</script>
