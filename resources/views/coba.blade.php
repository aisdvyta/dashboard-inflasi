@extends('layouts.landing')

@section('body')
    <section class="pt-24 min-h-screen">
        <div class="max-w-xl mx-auto relative">

            <input id="article-01" type="radio" name="slider" class="sr-only peer/01" checked>
            <input id="article-02" type="radio" name="slider" class="sr-only peer/02">
            <input id="article-03" type="radio" name="slider" class="sr-only peer/03">
            <input id="article-04" type="radio" name="slider" class="sr-only peer/04">
            <input id="article-05" type="radio" name="slider" class="sr-only peer/05">

            <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
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
            peer-checked/03:-translate-x-56
            peer-checked/03:z-30
            peer-checked/04:translate-x-28
            peer-checked/04:scale-[83.75%]" >
                <label class="absolute inset-0" for="article-03"><span class="sr-only"></span></label>
                <article class="flex justify-center items-center h-full bg-white">
                    <img src="{{ asset('images/dashInflasiBulanan-card.svg') }}" alt="" class="rounded-lg shadow-2xl">
                </article>
            </div>

            <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
            peer-focus-visible/02:[&_article]:ring
            peer-focus-visible/02:[&_article]:ring-indigo-300
            peer-checked/01:translate-x-28
            peer-checked/01:scale-[83.75%]
            peer-checked/01:z-40
            peer-checked/02:relative
            peer-checked/02:z-50
            peer-checked/02:translate-x-0
            peer-checked/02:scale-100
            peer-checked/02:[&>label]:pointer-events-none
            peer-checked/03:-translate-x-28
            peer-checked/03:scale-[83.75%]
            peer-checked/03:z-40
            peer-checked/04:-translate-x-56
            peer-checked/04:z-30 ">
                <article class="flex justify-center items-center h-full bg-white">
                    <img src="{{ asset('images/dashInflasiKelompok-card.svg') }}" alt="" class="rounded-lg shadow-2xl">
                </article>
            </div>

            <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
            peer-focus-visible/03:[&_article]:ring
            peer-focus-visible/03:[&_article]:ring-indigo-300
            peer-checked/01:translate-x-56
            peer-checked/01:z-30
            peer-checked/02:translate-x-28
            peer-checked/02:scale-[83.75%]
            peer-checked/02:z-40
            peer-checked/03:relative
            peer-checked/03:z-50
            peer-checked/03:translate-x-0
            peer-checked/03:scale-100
            peer-checked/03:[&>label]:pointer-events-none
            peer-checked/04:-translate-x-28
            peer-checked/04:scale-[83.75%]
            peer-checked/04:z-40 ">
                <label class="absolute inset-0" for="article-03"><span class="sr-only"></span></label>
                <article class="flex justify-center items-center h-full bg-white">
                    <img src="{{ asset('images/dashInflasiSpasial-card.svg') }}" alt="" class="rounded-lg shadow-2xl">
                </article>
            </div>

            <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
            peer-focus-visible/04:[&_article]:ring
            peer-focus-visible/04:[&_article]:ring-indigo-300
            peer-checked/01:-translate-x-28
            peer-checked/01:scale-[83.75%]
            peer-checked/02:translate-x-56
            peer-checked/02:z-30
            peer-checked/03:translate-x-28
            peer-checked/03:scale-[83.75%]
            peer-checked/03:z-40
            peer-checked/04:relative
            peer-checked/04:z-50
            peer-checked/04:translate-x-0
            peer-checked/04:scale-100
            peer-checked/04:[&>label]:pointer-events-none ">
                <article class="flex justify-center items-center h-full bg-white">
                    <img src="{{ asset('images/dashSeriesInflasi-card.svg') }}" alt="" class="rounded-lg shadow-2xl">
                </article>
            </div>

            <button
                class="absolute -left-32 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white w-10 h-10 flex items-center justify-center rounded-full z-50"
                onclick="prevSlide()">&#9664;</button>
            <button
                class="absolute -right-32 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white w-10 h-10 flex items-center justify-center rounded-full z-50"
                onclick="nextSlide()">&#9654;</button>

            <!-- Navigation Dots -->
            <div class="absolute bottom-[-40px] left-1/2 transform -translate-x-1/2 flex space-x-2 pb-4 z-50">
                <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300"
                    onclick="goToSlide(1)"></button>
                <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300"
                    onclick="goToSlide(2)"></button>
                <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300"
                    onclick="goToSlide(3)"></button>
                <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300"
                    onclick="goToSlide(4)"></button>
                <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300"
                    onclick="goToSlide(5)"></button>
            </div>

        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let index = 3; // Artikel yang awalnya checked
            const totalSlides = 4; // Jumlah total slides
            const intervalTime = 4000; // Waktu jeda auto-slide (4 detik)
            let autoSlideInterval;

            function changeSlide(newIndex) {
                index = newIndex;
                document.getElementById(`article-0${index}`).checked = true;
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

            // Tambahkan event listener ke tombol
            document.querySelectorAll("[onclick^='nextSlide']").forEach(btn => btn.addEventListener("click",
                nextSlide));
            document.querySelectorAll("[onclick^='prevSlide']").forEach(btn => btn.addEventListener("click",
                prevSlide));
            document.querySelectorAll("[onclick^='goToSlide']").forEach((btn, idx) => {
                btn.addEventListener("click", () => goToSlide(idx + 1));
            });

            startAutoSlide(); // Mulai auto-slide pertama kali
        });
    </script>
@endsection
