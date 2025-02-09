<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inflasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="transition-all duration-100 ease-in-out">
    @include('partials.sidebar')

    <div id="main-content" class="ml-0 transition-all duration-100 ease-in-out mt-16">
        @yield('body')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggleBtn = document.querySelector('[data-drawer-toggle]');
            const mainContent = document.getElementById('main-content');
            const sidebar = document.getElementById('logo-sidebar');

            sidebarToggleBtn.addEventListener('click', function() {
                // Toggle the sidebar's visibility
                sidebar.classList.toggle('-translate-x-full');

                // Adjust the margin of main content when sidebar is visible
                if (sidebar.classList.contains('-translate-x-full')) {
                    mainContent.style.marginLeft = '0'; // No margin when sidebar is hidden
                } else {
                    mainContent.style.marginLeft = '16rem'; // Sidebar width (64px * 4 = 16rem)
                }
            });
        });
    </script>
</body>


</html>
