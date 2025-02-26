<div id="sidebar" class="w-64 h-screen bg-white text-biru1 p-5 shadow-lg transition-all duration-300">
    <style>
        .active {
            background-color: #0077b6;
            color: white;
        }

        .dropdown-active {
            display: block !important;
        }

        .collapsed {
            width: 4rem; /* Lebar sidebar saat diperkecil */
            padding: 1rem;
        }

        .collapsed .menu-text {
            display: none; /* Sembunyikan teks saat sidebar diperkecil */
        }

        .collapsed .dropdown-arrow {
            display: none; /* Sembunyikan panah dropdown */
        }
    </style>

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
            <a href="{{ route('dashboard.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-biru4 hover:text-white group" data-page="dashboard">
                <svg class="mr-2 h-6 w-6 fill-current group-hover:fill-white" viewBox="0 0 24 24">
                    <path d="M12 2L2 10h3v10h6V14h2v6h6V10h3z" />
                </svg>
                <span class="menu-text">Beranda</span>
            </a>
        </li>

        <li class="relative">
            <button id="dropdownToggle" class="flex justify-between items-center w-full px-3 py-2 hover:bg-biru4 hover:text-white rounded-lg">
                <div class="flex items-center">
                    <svg class="mr-2 h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path d="M3 3h18v2H3V3zM5 7h14v2H5V7zM3 11h18v2H3v-2zM5 15h14v2H5v-2zM3 19h18v2H3v-2z" />
                    </svg>
                    <span class="menu-text">Dashboard Inflasi</span>
                </div>
                <svg class="w-4 h-4 dropdown-arrow" viewBox="0 0 20 20">
                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
            </button>

            <ul id="dropdownMenu" class="hidden mt-2 space-y-2 bg-white rounded">
                <li><a href="#" class="flex items-center px-3 py-2 ml-5 hover:bg-biru5 rounded-lg menu-text" data-page="inflasi-bulanan">Inflasi Bulanan</a></li>
                <li><a href="#" class="flex items-center px-3 py-2 ml-5 hover:bg-biru5 rounded-lg menu-text" data-page="inflasi-spasial">Inflasi Spasial</a></li>
                <li><a href="#" class="flex items-center px-3 py-2 ml-5 hover:bg-biru5 rounded-lg menu-text" data-page="inflasi-kelompok">Kelompok Pengeluaran</a></li>
                <li><a href="#" class="flex items-center px-3 py-2 ml-5 hover:bg-biru5 rounded-lg menu-text" data-page="inflasi-series">Series Inflasi</a></li>
            </ul>
        </li>

        <li>
            <a href="{{ route('import.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-biru4 hover:text-white group" data-page="tabel">
                <svg class="mr-2 h-6 w-6 fill-current group-hover:fill-white" viewBox="0 0 24 24">
                    <path d="M3 3v18h18V3H3zm16 16H5V5h14v14zM7 7h5v5H7V7zm0 7h5v5H7v-5zm7-7h5v5h-5V7zm0 7h5v5h-5v-5z" />
                </svg>
                <span class="menu-text">Daftar Tabel Data Inflasi</span>
            </a>
        </li>

        <li>
            <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-biru4 hover:text-white group" data-page="tentang">
                <svg class="mr-2 h-6 w-6 fill-current group-hover:fill-white" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 7h2v6h-2zm0 8h2v2h-2z" />
                </svg>
                <span class="menu-text">Tentang</span>
            </a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const toggleButton = document.getElementById("toggleSidebar");
        const dropdownToggle = document.getElementById("dropdownToggle");
        const dropdownMenu = document.getElementById("dropdownMenu");

        // Toggle Sidebar Collapse
        toggleButton.addEventListener("click", function() {
            sidebar.classList.toggle("collapsed");
        });

        // Toggle Dropdown Menu
        dropdownToggle.addEventListener("click", function() {
            dropdownMenu.classList.toggle("dropdown-active");
        });

        // Highlight Active Menu Item
        const currentPath = window.location.pathname;
        document.querySelectorAll("[data-page]").forEach(item => {
            if (currentPath.includes(item.getAttribute("data-page"))) {
                item.classList.add("active");
                let parentDropdown = item.closest("#dropdownMenu");
                if (parentDropdown) {
                    parentDropdown.classList.add("dropdown-active");
                }
            }
        });
    });
</script>
