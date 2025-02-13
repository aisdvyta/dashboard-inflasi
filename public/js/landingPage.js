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
