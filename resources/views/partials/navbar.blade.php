<nav class="bg-white bg-opacity-80 shadow-lg fixed top-0 left-0 w-full z-20">
    <div class="container mx-auto px-4 flex items-center justify-between py-2">
        <!-- Logo dan Judul -->
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/navbar/logoBPS.svg') }}" alt="logo BPS" class="h-10">
            <h1 class="text-xl font-bold text-biru1 leading-none">
                Dashboard <span class="block leading-none">Inflasi</span>
            </h1>
        </div>

        <!-- Navigasi dan Tombol Login -->
        <div class="flex items-center space-x-6">
            <ul class="flex items-center space-x-6 text-biru1 font-semibold">
                <li><a href="#main1"  class="hover:text-biru4 text-base font-semibold">Beranda</a></li>
                <li><a href="#main2"  class="hover:text-biru4 text-base font-semibold">Dashboard</a></li>
                <li><a href="#main3"  class="hover:text-biru4 text-base font-semibold">Tentang Kami</a></li>
            </ul>
            <a href="{}" class="flex items-center px-3 py-1 bg-biru1 text-white rounded-lg hover:bg-biru4 font-jakarta text-base font-semibold">
                <img src="{{ asset('images/navbar/loginIcon.svg') }}" alt="login icon" class="mr-2">
                Login
            </a>
        </div>
    </div>
</nav>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Fungsi untuk smooth scrolling
    function scrollToSection(id) {
        const section = document.querySelector(id);
        if (section) {
            const offset = section.getBoundingClientRect().top + window.scrollY - 50; // Tambahkan offset jika perlu
            window.scrollTo({
                top: offset,
                behavior: "smooth"
            });
        }
    }

    // Event listener untuk menu navigasi
    document.querySelectorAll("nav ul li a").forEach(link => {
        link.addEventListener("click", function (event) {
            const target = this.getAttribute("href");
            if (target.startsWith("#")) {
                event.preventDefault();
                scrollToSection(target);
            }
        });
    });
});

</script>

