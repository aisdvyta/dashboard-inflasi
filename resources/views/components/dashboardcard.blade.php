<div id="main2" class="min-h-[110vh] bg-biru4 py-12 relative">
    <!-- Elemen Batik -->
    <div class="absolute pt-8 top-30 left-10 ">
        <img src="{{ asset('images/landingMain2/batikKawung.svg') }}" alt="Batik Left" class="h-32 ">
    </div>
    <div class="absolute pt-8 top-30 right-10">
        <img src="{{ asset('images/landingMain2/batikKawung.svg') }}" alt="Batik Right" class="h-32 transform scale-x-[-1]">
    </div>
    <div class="absolute bottom-16 left-10">
        <img src="{{ asset('images/landingMain2/batikKawung.svg') }}" alt="Batik Left" class="h-32 transform scale-y-[-1]">
    </div>
    <div class="absolute bottom-16 right-10">
        <img src="{{ asset('images/landingMain2/batikKawung.svg') }}" alt="Batik Right"
            class="h-32 transform scale-x-[-1] scale-y-[-1]">
    </div>

    <div class="container mx-auto text-center mt-8">
        <h2 class="text-4xl font-bold text-white">
            Yuk kenali <span class="text-kuning1">Dashboard</span> yang ada!
        </h2>
        <p class="text-white text-base font-normal mt-8 mx-80">
            <span class="font-semibold text-kuning2">Dashboard Inflasi</span> yang tersedia mencakup dashboard inflasi bulanan, dashboard inflasi spasial,
            dashboard inflasi bulanan menurut kelompok pengeluaran, dan dashboard series inflasi.
        </p>
        <div class="mt-2">
            <section class="pt-12 flex items-center justify-center">
                <div class="max-w-xl w-full relative">

                    <input id="article-01" type="radio" name="slider" class="sr-only peer/01" checked>
                    <input id="article-02" type="radio" name="slider" class="sr-only peer/02">
                    <input id="article-03" type="radio" name="slider" class="sr-only peer/03">
                    <input id="article-04" type="radio" name="slider" class="sr-only peer/04">
                    <input id="article-05" type="radio" name="slider" class="sr-only peer/05">

                    <div
                        class="
                    absolute inset-0 scale-[67.5%] z-20 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)]
                    peer-focus-visible/01:[&_article]:ring
                    peer-focus-visible/01:[&_article]:ring-indigo-300
                    peer-checked/01:relative
                    peer-checked/01:z-50
                    peer-checked/01:translate-x-0
                    peer-checked/01:scale-100
                    peer-checked/01:[&>label]:pointer-events-none
                    peer-checked/02:-translate-x-28
                    peer-checked/02:scale-[83.75%]
                    peer-checked/02:z-40
                    peer-checked/02:opacity-75
                    peer-checked/03:-translate-x-56
                    peer-checked/03:z-30
                    peer-checked/03:opacity-50
                    peer-checked/04:translate-x-28
                    peer-checked/04:scale-[83.75%]
                    peer-checked/04:opacity-75">
                        <label class="absolute inset-0" for="article-03"><span class="sr-only"></span></label>
                        <article class="flex justify-center items-center h-[400px] ">
                            <img src="{{ asset('images/landingMain2/dashInflasiBulanan-card.svg') }}" alt=""
                                class="rounded-[3rem]">
                        </article>
                    </div>

                    <div
                        class="
                    absolute inset-0 scale-[67.5%] z-20 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)]
                    peer-focus-visible/02:[&_article]:ring
                    peer-focus-visible/02:[&_article]:ring-indigo-300
                    peer-checked/01:translate-x-28
                    peer-checked/01:scale-[83.75%]
                    peer-checked/01:z-40
                    peer-checked/01:opacity-75
                    peer-checked/02:relative
                    peer-checked/02:z-50
                    peer-checked/02:translate-x-0
                    peer-checked/02:scale-100
                    peer-checked/02:[&>label]:pointer-events-none
                    peer-checked/03:-translate-x-28
                    peer-checked/03:scale-[83.75%]
                    peer-checked/03:z-40
                    peer-checked/03:opacity-75
                    peer-checked/04:-translate-x-56
                    peer-checked/04:z-30">
                        <article class="flex justify-center items-center h-[400px] ">
                            <img src="{{ asset('images/landingMain2/dashInflasiKelompok-card.svg') }}" alt=""
                                class="rounded-[3rem]">
                        </article>
                    </div>

                    <div
                        class="
                    absolute inset-0 scale-[67.5%] z-20 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)]
                    peer-focus-visible/03:[&_article]:ring
                    peer-focus-visible/03:[&_article]:ring-indigo-300
                    peer-checked/01:translate-x-56
                    peer-checked/01:z-30
                    peer-checked/01:opacity-50
                    peer-checked/02:translate-x-28
                    peer-checked/02:scale-[83.75%]
                    peer-checked/02:z-40
                    peer-checked/02:opacity-75
                    peer-checked/03:relative
                    peer-checked/03:z-50
                    peer-checked/03:translate-x-0
                    peer-checked/03:scale-100
                    peer-checked/03:[&>label]:pointer-events-none
                    peer-checked/04:-translate-x-28
                    peer-checked/04:scale-[83.75%]
                    peer-checked/04:z-40
                    peer-checked/04:opacity-75">
                        <label class="absolute inset-0" for="article-03"><span class="sr-only"></span></label>
                        <article class="flex justify-center items-center h-[400px]">
                            <img src="{{ asset('images/landingMain2/dashInflasiSpasial-card.svg') }}" alt=""
                                class="rounded-[3rem]">
                        </article>
                    </div>

                    <div
                        class="
                    absolute inset-0 scale-[67.5%] z-20 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)]
                    peer-focus-visible/04:[&_article]:ring
                    peer-focus-visible/04:[&_article]:ring-indigo-300
                    peer-checked/01:-translate-x-28
                    peer-checked/01:scale-[83.75%]
                    peer-checked/01:z-40
                    peer-checked/01:opacity-75
                    peer-checked/02:translate-x-56
                    peer-checked/02:z-30
                    peer-checked/02:opacity-50
                    peer-checked/03:translate-x-28
                    peer-checked/03:scale-[83.75%]
                    peer-checked/03:z-40
                    peer-checked/03:opacity-75
                    peer-checked/04:relative
                    peer-checked/04:z-50
                    peer-checked/04:translate-x-0
                    peer-checked/04:scale-100
                    peer-checked/04:[&>label]:pointer-events-none ">
                        <article class="flex justify-center items-center h-[400px] ">
                            <img src="{{ asset('images/landingMain2/dashSeriesInflasi-card.svg') }}" alt=""
                                class="rounded-[3rem]">
                        </article>
                    </div>

                    <button
                        class="absolute -left-48 top-1/2 transform -translate-y-1/2 text-white w-10 h-10 flex items-center justify-center z-50"
                        onclick="prevSlide()">
                        <img src="{{ asset('images/landingMain2/chevronRight.svg') }}" alt="chevron kanan icon" class="transform rotate-180">
                    </button>

                    <button
                        class="absolute -right-48 top-1/2 transform -translate-y-1/2 text-white w-10 h-10 flex items-center justify-center z-50"
                        onclick="nextSlide()">
                        <img src="{{ asset('images/landingMain2/chevronRight.svg') }}" alt="chevron kanan icon">
                    </button>

                    <div class="relative w-60 h-14 pt-12 flex items-center justify-center mx-auto">
                        <div class="absolute flex space-x-2">
                            <div class="dot w-2 h-2 rounded-full bg-biru1 cursor-pointer" onclick="goToSlide(1)"></div>
                            <div class="dot w-2 h-2 rounded-full bg-biru5 cursor-pointer" onclick="goToSlide(2)"></div>
                            <div class="dot w-2 h-2 rounded-full bg-biru5 cursor-pointer" onclick="goToSlide(3)"></div>
                            <div class="dot w-2 h-2 rounded-full bg-biru5 cursor-pointer" onclick="goToSlide(4)"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let index = 1; // Artikel yang awalnya checked
        const totalSlides = 4; // Jumlah total slides
        const intervalTime = 4000; // Waktu jeda auto-slide (4 detik)
        let autoSlideInterval;

        function changeSlide(newIndex) {
            index = newIndex;
            document.getElementById(`article-0${index}`).checked = true;
            updateDots();
        }

        function nextSlide() {
            index = index + 1 > totalSlides ? 1 : index + 1;
            changeSlide(index);
            resetAutoSlide();
        }

        function prevSlide() {
            index = index - 1 < 1 ? totalSlides : index - 1;
            changeSlide(index);
            resetAutoSlide();
        }

        function goToSlide(slideIndex) {
            changeSlide(slideIndex);
            resetAutoSlide();
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                nextSlide();
            }, intervalTime);
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        function updateDots() {
            const dots = document.querySelectorAll(".dot");
            dots.forEach((dot, idx) => {
                if (idx + 1 === index) {
                    dot.classList.remove("bg-biru5");
                    dot.classList.add("bg-biru1");
                } else {
                    dot.classList.remove("bg-biru1");
                    dot.classList.add("bg-biru5");
                }
            });
        }

        // Tambahkan event listener ke tombol
        document.querySelectorAll("[onclick^='nextSlide']").forEach(btn => btn.addEventListener("click", nextSlide));
        document.querySelectorAll("[onclick^='prevSlide']").forEach(btn => btn.addEventListener("click", prevSlide));
        document.querySelectorAll("[onclick^='goToSlide']").forEach((btn, idx) => {
            btn.addEventListener("click", () => goToSlide(idx + 1));
        });

        startAutoSlide(); // Mulai auto-slide pertama kali
        updateDots(); // Update dots pertama kali
    });
</script>
