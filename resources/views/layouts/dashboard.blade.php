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
    @stack('head')
</head>

<body class="flex flex-col min-h-screen bg-abubiru">
    <!-- Sidebar Container -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar"
            class="fixed top-0 left-0 z-50 w-64 h-screen overflow-y-auto text-white transition-all duration-300 bg-gray-800">
            @include('components.sidebar')
        </div>

        <!-- Konten Utama -->
        <div id="mainContent" class="flex flex-col flex-grow min-h-screen ml-64 transition-all duration-300">
            <div class="flex-grow p-5 overflow-auto">
                <div class="z-20">
                    @yield('body')
                </div>
            </div>

            <!-- Footer Kecil -->
            <div class="mt-auto text-white bg-gray-900 ">
                @include('components.footerKecil')
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar'); // Sidebar di layouts.dashboard
            const sidebarComponent = sidebar.querySelector('.flex.flex-col'); // Sidebar utama di components.sidebar
            const mainContent = document.getElementById('mainContent');
            const toggleButton = document.getElementById('toggleSidebar');
            const dashboardText = document.getElementById('dashboardText');

            // Toggle Sidebar
            toggleButton.addEventListener('click', () => {
                if (sidebar.classList.contains('w-64')) {
                    // Mengecilkan sidebar di layouts.dashboard
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-24');

                    // Mengecilkan sidebar utama di components.sidebar
                    if (sidebarComponent) {
                        sidebarComponent.classList.remove('w-64');
                        sidebarComponent.classList.add('w-24');
                    }

                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-24'); // Hilangkan margin kiri

                    // Fade-out dashboardText sebelum disembunyikan
                    dashboardText.style.opacity = "0";

                    setTimeout(() => {
                        dashboardText.classList.add('hidden');
                    }, 50);
                } else {
                    // Membesarkan sidebar di layouts.dashboard
                    sidebar.classList.remove('w-24');
                    sidebar.classList.add('w-64');

                    // Membesarkan sidebar utama di components.sidebar
                    if (sidebarComponent) {
                        sidebarComponent.classList.remove('w-24');
                        sidebarComponent.classList.add('w-64');
                    }

                    mainContent.classList.remove('ml-24'); // Kembalikan margin kiri ke ml-64
                    mainContent.classList.add('ml-64');

                    // Tampilkan teks dengan transisi smooth
                    dashboardText.classList.remove('hidden');

                    requestAnimationFrame(() => {
                        dashboardText.style.opacity = "0";

                        setTimeout(() => {
                            dashboardText.style.opacity = "1";
                        }, 100);
                    });
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
