@extends('layouts.landing')

@section('body')
<section class="px-12 pt-32 min-h-screen">
    <div class="max-w-lg mx-auto relative">

        <input id="article-01" type="radio" name="slider" class="sr-only peer/01">
        <input id="article-02" type="radio" name="slider" class="sr-only peer/02">
        <input id="article-03" type="radio" name="slider" class="sr-only peer/03" checked>
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
            peer-checked/02:-translate-x-20
            peer-checked/02:scale-[83.75%]
            peer-checked/02:z-40
            peer-checked/03:-translate-x-40
            peer-checked/03:z-30
            peer-checked/04:-translate-x-40
            peer-checked/04:opacity-0
            peer-checked/05:-translate-x-40
        ">
            <label class="absolute inset-0" for="article-01"><span class="sr-only">Focus on the big picture</span></label>
            <article class="bg-white p-6 rounded-lg shadow-2xl">
                <header class="mb-2">
                    <img class="inline-flex rounded-full shadow mb-3" src="./icon.svg" width="44" height="44" alt="Icon" />
                    <h1 class="text-xl font-bold text-slate-900">Focus on the big picture</h1>
                </header>
                <div class="text-sm leading-relaxed text-slate-500 space-y-4 mb-2">
                    <p>
                        Many desktop publishing packages and web page editors now use Pinky as their default model text, and a search for more variants will uncover many web sites still in their infancy.
                    </p>
                    <p>
                        All the generators tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                    </p>
                </div>
                <footer class="text-right">
                    <a class="text-sm font-medium text-indigo-500 hover:underline" href="#0">Read more -></a>
                </footer>
            </article>
        </div>

        <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
            peer-focus-visible/02:[&_article]:ring
            peer-focus-visible/02:[&_article]:ring-indigo-300
            peer-checked/01:translate-x-20
            peer-checked/01:scale-[83.75%]
            peer-checked/01:z-40
            peer-checked/02:relative
            peer-checked/02:z-50
            peer-checked/02:translate-x-0
            peer-checked/02:scale-100
            peer-checked/02:[&>label]:pointer-events-none
            peer-checked/03:-translate-x-20
            peer-checked/03:scale-[83.75%]
            peer-checked/03:z-40
            peer-checked/04:-translate-x-40
            peer-checked/04:z-30
            peer-checked/05:-translate-x-40
            peer-checked/05:opacity-0
        ">
            <label class="absolute inset-0" for="article-02"><span class="sr-only">Focus on the big picture</span></label>
            <article class="bg-white p-6 rounded-lg shadow-2xl">
                <header class="mb-2">
                    <img class="inline-flex rounded-full shadow mb-3" src="./icon.svg" width="44" height="44" alt="Icon" />
                    <h1 class="text-xl font-bold text-slate-900">Focus on the big picture</h1>
                </header>
                <div class="text-sm leading-relaxed text-slate-500 space-y-4 mb-2">
                    <p>
                        Many desktop publishing packages and web page editors now use Pinky as their default model text, and a search for more variants will uncover many web sites still in their infancy.
                    </p>
                    <p>
                        All the generators tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                    </p>
                </div>
                <footer class="text-right">
                    <a class="text-sm font-medium text-indigo-500 hover:underline" href="#0">Read more -></a>
                </footer>
            </article>
        </div>

        <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
            peer-focus-visible/03:[&_article]:ring
            peer-focus-visible/03:[&_article]:ring-indigo-300
            peer-checked/01:translate-x-40
            peer-checked/01:z-30
            peer-checked/02:translate-x-20
            peer-checked/02:scale-[83.75%]
            peer-checked/02:z-40
            peer-checked/03:relative
            peer-checked/03:z-50
            peer-checked/03:translate-x-0
            peer-checked/03:scale-100
            peer-checked/03:[&>label]:pointer-events-none
            peer-checked/04:-translate-x-20
            peer-checked/04:scale-[83.75%]
            peer-checked/04:z-40
            peer-checked/05:-translate-x-40
            peer-checked/05:z-30
        ">
            <label class="absolute inset-0" for="article-03"><span class="sr-only">Focus on the big picture</span></label>
            <article class="bg-white p-6 rounded-lg shadow-2xl">
                <header class="mb-2">
                    <img class="inline-flex rounded-full shadow mb-3" src="./icon.svg" width="44" height="44" alt="Icon" />
                    <h1 class="text-xl font-bold text-slate-900">Focus on the big picture</h1>
                </header>
                <div class="text-sm leading-relaxed text-slate-500 space-y-4 mb-2">
                    <p>
                        Many desktop publishing packages and web page editors now use Pinky as their default model text, and a search for more variants will uncover many web sites still in their infancy.
                    </p>
                    <p>
                        All the generators tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                    </p>
                </div>
                <footer class="text-right">
                    <a class="text-sm font-medium text-indigo-500 hover:underline" href="#0">Read more -></a>
                </footer>
            </article>
        </div>

        <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
            peer-focus-visible/04:[&_article]:ring
            peer-focus-visible/04:[&_article]:ring-indigo-300

            peer-checked/01:translate-x-40
            peer-checked/01:opacity-0

            peer-checked/02:translate-x-40
            peer-checked/02:z-30

            peer-checked/03:translate-x-20
            peer-checked/03:scale-[83.75%]
            peer-checked/03:z-40

            peer-checked/04:relative
            peer-checked/04:z-50
            peer-checked/04:translate-x-0
            peer-checked/04:scale-100
            peer-checked/04:[&>label]:pointer-events-none

            peer-checked/05:-translate-x-20
            peer-checked/05:scale-[83.75%]
            peer-checked/05:z-40
        ">
            <label class="absolute inset-0" for="article-04"><span class="sr-only">Focus on the big picture</span></label>
            <article class="bg-white p-6 rounded-lg shadow-2xl">
                <header class="mb-2">
                    <img class="inline-flex rounded-full shadow mb-3" src="./icon.svg" width="44" height="44" alt="Icon" />
                    <h1 class="text-xl font-bold text-slate-900">Focus on the big picture</h1>
                </header>
                <div class="text-sm leading-relaxed text-slate-500 space-y-4 mb-2">
                    <p>
                        Many desktop publishing packages and web page editors now use Pinky as their default model text, and a search for more variants will uncover many web sites still in their infancy.
                    </p>
                    <p>
                        All the generators tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                    </p>
                </div>
                <footer class="text-right">
                    <a class="text-sm font-medium text-indigo-500 hover:underline" href="#0">Read more -></a>
                </footer>
            </article>
        </div>

        <div class="
            absolute inset-0 scale-[67.5%] z-20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]
            peer-focus-visible/05:[&_article]:ring
            peer-focus-visible/05:[&_article]:ring-indigo-300
            peer-checked/01:translate-x-40
            peer-checked/02:translate-x-40
            peer-checked/02:opacity-0
            peer-checked/03:translate-x-40
            peer-checked/03:z-30
            peer-checked/04:translate-x-20
            peer-checked/04:scale-[83.75%]
            peer-checked/04:z-40
            peer-checked/05:relative
            peer-checked/05:z-50
            peer-checked/05:translate-x-0
            peer-checked/05:scale-100
            peer-checked/05:[&>label]:pointer-events-none
        ">
            <label class="absolute inset-0" for="article-05"><span class="sr-only">Focus on the big picture</span></label>
            <article class="bg-white p-6 rounded-lg shadow-2xl">
                <header class="mb-2">
                    <img class="inline-flex rounded-full shadow mb-3" src="./icon.svg" width="44" height="44" alt="Icon" />
                    <h1 class="text-xl font-bold text-slate-900">Focus on the big picture</h1>
                </header>
                <div class="text-sm leading-relaxed text-slate-500 space-y-4 mb-2">
                    <p>
                        Many desktop publishing packages and web page editors now use Pinky as their default model text, and a search for more variants will uncover many web sites still in their infancy.
                    </p>
                    <p>
                        All the generators tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                    </p>
                </div>
                <footer class="text-right">
                    <a class="text-sm font-medium text-indigo-500 hover:underline" href="#0">Read more -></a>
                </footer>
            </article>
        </div>

        <button class="absolute -left-32 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white w-10 h-10 flex items-center justify-center rounded-full z-50" onclick="prevSlide()">&#9664;</button>
        <button class="absolute -right-32 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white w-10 h-10 flex items-center justify-center rounded-full z-50" onclick="nextSlide()">&#9654;</button>

        <!-- Navigation Dots -->
        <div class="absolute bottom-[-40px] left-1/2 transform -translate-x-1/2 flex space-x-2 pb-4 z-50">
            <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300" onclick="goToSlide(1)"></button>
            <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300" onclick="goToSlide(2)"></button>
            <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300" onclick="goToSlide(3)"></button>
            <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300" onclick="goToSlide(4)"></button>
            <button class="w-10 h-1 bg-gray-400 rounded-full transition-all duration-300" onclick="goToSlide(5)"></button>
        </div>

    </div>
</section>

@push('script')
<script>
    function prevSlide() {
        const current = document.querySelector('input[name="slider"]:checked');
        const prev = current.previousElementSibling || document.querySelector('input[name="slider"]:last-of-type');
        prev.checked = true;
    }

    function nextSlide() {
        const current = document.querySelector('input[name="slider"]:checked');
        const next = current.nextElementSibling || document.querySelector('input[name="slider"]:first-of-type');
        next.checked = true;
    }

    function goToSlide(slideNumber) {
        document.getElementById(article-0${slideNumber}).checked = true;
    }

    function updateActiveDot() {
        const slides = document.querySelectorAll('input[name="slider"]');
        const dots = document.querySelectorAll('.absolute.bottom-[-40px] button');

        slides.forEach((slide, index) => {
            if (slide.checked) {
                dots[index].classList.add('bg-indigo-500'); // Warna aktif
                dots[index].classList.remove('bg-gray-400');
            } else {
                dots[index].classList.add('bg-gray-400'); // Warna default
                dots[index].classList.remove('bg-indigo-500');
            }
        });
    }

    document.querySelectorAll('input[name="slider"]').forEach(slide => {
        slide.addEventListener('change', updateActiveDot);
    });

    updateActiveDot(); // Set awal
</script>
@endpush
@endsection
