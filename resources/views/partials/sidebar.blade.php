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
            fill: #063051;
            /* Warna default panah */
        }

        .group:hover .dropdown-arrow,
        .dropdown-arrow.rotate-180 {
            fill: white;
            /* Warna panah saat di-hover atau diklik */
        }
    </style>

    <div>
        <!-- Logo & Toggle -->
        <div class="flex items-center gap-3">
            <button id="toggleSidebar" class="text-biru1 rounded">
                <img src="{{ asset('images/navbar/logoBPS.svg') }}" alt="Logo Dashboard" class="h-12 w-12">
            </button>
            <p id="dashboardText" class="text-xl font-bold text-biru1 leading-none menu-text">
                <span>Dashboard</span><br>
                <span>Inflasi</span>
            </p>
        </div>

        <div class="border-b-2 my-2 shadow-lg"></div>

        <!-- Menu Items -->
        <ul>
            <li>
                <a href="{{ route('landingPage') }}"
                    class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                    <img src="{{ asset('images/sidebar/bhomeIcon.svg') }}" alt="Ikon Beranda" class="h-6 w-6 icon"
                        data-hover="{{ asset('images/sidebar/phomeIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/bhomeIcon.svg') }}">
                    <span class="menu-text font-medium text-[15px]">Beranda</span>
                </a>
            </li>

            <li class="relative">
                <a id="dropdownToggle"
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
                <ul id="dropdownMenu" class="hidden mt-2 space-y-2 bg-white rounded">
                    <li><a href="{{ route('dashboard.bulanan') }}"
                            class="flex items-center px-3 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-bulanan">Inflasi Bulanan</a></li>
                    <li><a href="{{ route('dashboard.spasial') }}"
                            class="flex items-center px-3 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-spasial">Inflasi Spasial</a></li>
                    <li><a href="{{ route('dashboard.kelompok') }}"
                            class="flex items-center px-3 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
                            data-page="inflasi-kelompok">Kelompok Pengeluaran</a></li>
                    <li><a href="{{ route('dashboard.series') }}"
                            class="flex items-center px-3 py-2 ml-8 font-normal text-[15px] hover:bg-biru5 rounded-lg menu-text"
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

            <li>
                <a href="{{ route('landingPage') }}#main3"
                    class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-biru4 hover:text-white group">
                    <img src="{{ asset('images/sidebar/btentangIcon.svg') }}" alt="Ikon Tentang" class="h-6 w-6 icon"
                        data-hover="{{ asset('images/sidebar/ptentangIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/btentangIcon.svg') }}">
                    <span class="menu-text font-medium text-[15px]">Tentang</span>
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
        const dropdownToggle = document.getElementById("dropdownToggle");
        const dropdownMenu = document.getElementById("dropdownMenu");
        const dropdownArrow = dropdownToggle.querySelector('.dropdown-arrow');
        const currentPath = window.location.pathname;

        // Toggle Sidebar Collapse
        toggleButton.addEventListener("click", function() {
            sidebar.classList.toggle("collapsed");
        });

        // Toggle Dropdown Menu
        dropdownToggle.addEventListener("click", function() {
            dropdownMenu.classList.toggle("dropdown-active");
            dropdownArrow.classList.toggle("rotate-180");
        });

        // Highlight Active Menu Item
        document.querySelectorAll("[data-page]").forEach(item => {
            const page = item.getAttribute("data-page");
            const link = item.getAttribute("href");

            if (currentPath.includes(page) || (link && currentPath.includes(new URL(link, window
                    .location.origin).pathname))) {
                item.classList.add("active");

                // Buka dropdown jika item aktif ada di dalamnya
                if (item.closest("#dropdownMenu")) {
                    dropdownMenu.classList.add("dropdown-active");
                    dropdownArrow.classList.add("rotate-180");
                }
            }
        });

        // Change icon on hover and active
        document.querySelectorAll('.icon').forEach(icon => {
            const defaultSrc = icon.getAttribute('data-default');
            const hoverSrc = icon.getAttribute('data-hover');
            const parentLink = icon.closest('a');

            parentLink.addEventListener('mouseenter', () => {
                icon.setAttribute('src', hoverSrc);
            });

            parentLink.addEventListener('mouseleave', () => {
                if (!parentLink.classList.contains('active')) {
                    icon.setAttribute('src', defaultSrc);
                }
            });

            if (parentLink.classList.contains('active')) {
                icon.setAttribute('src', hoverSrc);
            }
        });
    });
</script>