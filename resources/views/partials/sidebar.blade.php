<div id="sidebar" class="w-64 h-screen bg-white text-biru1 p-5 shadow-lg transition-all duration-300">
    <!-- Logo -->
    <div class="flex items-center gap-3">
        <button id="toggleButton" class="text-biru1 rounded">
            <img src="{{ asset('images/logoBPS.svg') }}" alt="Logo Dashboard" class="h-12 w-12">
        </button>
        <p id="dashboardText" class="text-xl font-bold text-biru1 leading-none">
            <span>Dashboard</span><br>
            <span>Inflasi</span>
        </p>
    </div>

    <!-- Pembatasnya -->
    <div class="border-b-2 my-4 shadow-lg"></div>

    <!-- Menu Items -->
    <ul>
        <li class="mb-4">
            <a href="#" class="block p-3 font-medium hover:bg-biru4 hover:text-white rounded-xl ">Home</a>
        </li>
        <li class="mb-4 relative">
            <!-- Dropdown Trigger -->
            <button id="dropdownToggle"
                class="flex justify-between items-center w-full p-3 font-medium hover:bg-biru4 hover:text-white rounded-xl">
                <span>Data Inflasi</span>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <ul id="dropdownMenu" class="hidden mt-2 space-y-2 font-normal bg-white rounded">
                <li><a href="#" class="block p-3 ml-5 hover:bg-biru5 rounded-xl">Inflasi Bulanan</a></li>
                <li><a href="#" class="block p-3 ml-5 hover:bg-biru5 rounded-xl">Inflasi Spasial</a></li>
                <li><a href="#" class="block p-3 ml-5 hover:bg-biru5 rounded-xl">Kelompok Pengeluaran</a>
                </li>
                <li><a href="#" class="block p-3 ml-5 hover:bg-biru5 rounded-xl">Series Inflasi</a></li>
            </ul>
        </li>
        <li>
            <a href="#"
                class="block p-3 font-medium hover:bg-biru4 hover:text-white rounded-xl">Tentang</a>
        </li>
    </ul>
</div>