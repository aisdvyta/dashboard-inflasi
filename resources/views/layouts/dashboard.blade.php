<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inflasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>
    <script>
        // On page load or when changing themes, best to add inline in head to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="min-h-screen bg-abubiru flex flex-col">
    <!-- Sidebar Container -->
    <div class="flex flex-grow relative">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Konten Utama -->
        <div class="flex-grow p-5  relative z-10">
            <div class="z-20">
                @yield('body')
            </div>
        </div>

        <!-- Gambar Batik Kawung -->
        <div class="absolute -top-20 right-14 z-15">
            <img src="{{ asset('images/kawung.svg') }}" alt="Batik Kawung" class="h-[25rem]">
        </div>
        <div class="absolute bottom-4 left-60 z-15">
            <img src="{{ asset('images/kawung.svg') }}" alt="Batik Kawung" class="h-[15rem] rotate-45">
        </div>
    </div>
    @include('components.footerKecil')

    <!-- Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('toggleButton');
        const dashboardText = document.getElementById('dashboardText');
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');

        // Toggle Sidebar
        toggleButton.addEventListener('click', () => {
            if (sidebar.classList.contains('w-64')) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-24');

                // Fade-out dashboardText sebelum disembunyikan
                dashboardText.style.opacity = "0";
                dashboardText.style.transform = "translateX(10px)";

                setTimeout(() => {
                    dashboardText.classList.add('hidden');
                }, 50); // Tunggu sampai animasi selesai sebelum disembunyikan
            } else {
                sidebar.classList.remove('w-24');
                sidebar.classList.add('w-64');

                // Tampilkan teks dengan transisi smooth
                dashboardText.classList.remove('hidden');

                requestAnimationFrame(() => {
                    dashboardText.style.opacity = "0";
                    dashboardText.style.transform = "translateX(10px)";

                    setTimeout(() => {
                        dashboardText.style.opacity = "1";
                        dashboardText.style.transform = "translateX(0)";
                    }, 100); // Sedikit delay supaya transisi terlihat
                });
            }
        });

        // Toggle Dropdown
        dropdownToggle.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Close Dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
