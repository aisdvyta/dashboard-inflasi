<nav class="bg-white bg-opacity-80 shadow-lg fixed top-0 left-0 w-full z-20">
    <div class="container mx-auto px-4 flex items-center justify-between py-2">
        <!-- Logo dan Judul -->
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/navbar/logoBPS.svg') }}" alt="logo BPS" class="h-10">
            <h1 class="text-xl font-bold text-biru1 leading-none">
                Dashboard <span class="block leading-none">Inflasi</span>
            </h1>
        </div>

        <!-- Navigasi dan Tombol Logout -->
        <div class="flex items-center space-x-10">
            <ul class="flex items-center space-x-6 text-biru1 font-semibold">
                <li><a href="#main1" class="hover:text-biru4 text-base font-semibold">Beranda</a></li>
                <li class="relative group">
                    <a href="#main2" class="hover:text-biru4 text-base font-semibold flex items-center"
                        id="dashboardToggle">
                        Dashboard
                        <svg class="ml-1 w-4 h-4 transition-transform duration-300" id="dashboardArrow"
                            viewBox="0 0 20 20">
                            <path
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </a>
                    <ul class="absolute left-0 mt-2 w-64 font-normal bg-white shadow-lg rounded-lg hidden"
                        id="dashboardMenu">
                        <li><a href="#submenu1" class="block px-4 py-2 hover:bg-biru4 hover:text-white">Dashboard
                                Inflasi Bulanan</a></li>
                        <li><a href="#submenu2" class="block px-4 py-2 hover:bg-biru4 hover:text-white">Dashboard
                                Inflasi Spasial</a></li>
                        <li><a href="#submenu3" class="block px-4 py-2 hover:bg-biru4 hover:text-white">Dashboard
                                Inflasi Bulanan Menurut Kelompok Pengeluaran</a></li>
                        <li><a href="#submenu4" class="block px-4 py-2 hover:bg-biru4 hover:text-white">Dashboard Series
                                Inflasi</a></li>
                    </ul>
                </li>
                <li class="relative group">
                    <a href="#main3" class="hover:text-biru4 text-base font-semibold flex items-center"
                        id="menuToggle">
                        Menu
                        <svg class="ml-1 w-4 h-4 transition-transform duration-300" id="menuArrow" viewBox="0 0 20 20">
                            <path
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </a>
                    <ul class="absolute left-0 mt-2 w-52 font-normal bg-white shadow-lg rounded-lg hidden"
                        id="dropdownMenu">
                        <li><a href="#submenu1" class="block px-4 py-2 hover:bg-biru4 hover:text-white">Manajemen
                                Akun</a></li>
                        <li><a href="#submenu2" class="block px-4 py-2 hover:bg-biru4 hover:text-white">Manajemen Data
                                Inflasi</a></li>
                        <li><a href="#submenu3" class="block px-4 py-2 hover:bg-biru4 hover:text-white">Dashboard Data
                                Inflasi</a></li>
                    </ul>
                </li>
            </ul>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="flex items-center px-3 py-1 bg-biru1 text-white rounded-lg hover:bg-biru4 font-jakarta text-base font-semibold">
                    <img src="{{ asset('images/navbarProvKab/logoutIcon.svg') }}" alt="logout icon" class="mr-2">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    #menuToggle:hover #menuArrow,
    #menuToggle:focus #menuArrow,
    #dropdownMenu:not(.hidden) #menuArrow,
    #dashboardToggle:hover #dashboardArrow,
    #dashboardToggle:focus #dashboardArrow,
    #dashboardMenu:not(.hidden) #dashboardArrow {
        fill: #0077b6;
        /* Warna panah saat di-hover atau dropdown terbuka */
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuToggle = document.getElementById('menuToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const menuArrow = document.getElementById('menuArrow');
        const dashboardToggle = document.getElementById('dashboardToggle');
        const dashboardMenu = document.getElementById('dashboardMenu');
        const dashboardArrow = document.getElementById('dashboardArrow');

        menuToggle.addEventListener('click', function(event) {
            event.preventDefault();
            dropdownMenu.classList.toggle('hidden');
            menuArrow.classList.toggle('rotate-180');
        });

        dashboardToggle.addEventListener('click', function(event) {
            event.preventDefault();
            dashboardMenu.classList.toggle('hidden');
            dashboardArrow.classList.toggle('rotate-180');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!menuToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                menuArrow.classList.remove('rotate-180');
            }
            if (!dashboardToggle.contains(event.target) && !dashboardMenu.contains(event.target)) {
                dashboardMenu.classList.add('hidden');
                dashboardArrow.classList.remove('rotate-180');
            }
        });

        // Fungsi untuk smooth scrolling
        function scrollToSection(id) {
            const section = document.querySelector(id);
            if (section) {
                const offset = section.getBoundingClientRect().top + window.scrollY -
                50; // Tambahkan offset jika perlu
                window.scrollTo({
                    top: offset,
                    behavior: "smooth"
                });
            }
        }

        // Event listener untuk menu navigasi
        document.querySelectorAll("nav ul li a").forEach(link => {
            link.addEventListener("click", function(event) {
                const target = this.getAttribute("href");
                if (target.startsWith("#")) {
                    event.preventDefault();
                    scrollToSection(target);
                }
            });
        });
    });
</script>
