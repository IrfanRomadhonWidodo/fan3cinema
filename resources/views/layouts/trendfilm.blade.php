{{-- Elegant Film Slider - Height: 60vh dengan rounded corners --}}
<div class="relative w-full max-w-7xl mx-auto mt-2 mb-12 overflow-hidden rounded-2xl shadow-2xl">
    <div id="filmSlider" class="flex transition-transform duration-700 ease-in-out">
        {{-- Slide 1: How to Train Your Dragon --}}
        <div class="relative min-w-full group">
            <div class="relative h-[60vh] overflow-hidden">
                <img src="https://m.media-amazon.com/images/S/pv-target-images/e0b0f6a434a23bcfd8b3222bc7519f37c017fa6cf105ccab4ded691413ee0507._SX1080_FMjpg_.jpg" alt="How to Train Your Dragon" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                
                {{-- Content Container --}}
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full max-w-2xl ml-8 md:ml-16 text-white">
                        {{-- Badge --}}
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-2 py-1 bg-green-600 text-xs font-semibold rounded-full uppercase tracking-wide">Now Playing</span>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.564-.955L10 0l2.948 5.955 6.564.955-4.756 4.635 1.122 6.545z" />
                                </svg>
                                <span class="text-sm font-semibold">8.1</span>
                            </div>
                        </div>

                        {{-- Title --}}
                        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-lime-300 leading-tight">
                            How to Train Your Dragon
                        </h1>
                        
                        {{-- Genre --}}
                        <p class="text-gray-300 mb-3 text-sm">Animation • Adventure • Fantasy</p>
                        
                        {{-- Description --}}
                        <p class="text-gray-200 text-sm md:text-base leading-relaxed mb-4 max-w-xl">
                            Dalam dunia di mana naga adalah musuh, seorang remaja Viking bernama Hiccup justru menjalin persahabatan tak terduga dengan seekor naga yang ia sebut Toothless.
                        </p>
                        
                        {{-- Action Buttons --}}
                        <div class="flex space-x-3">
                            <button class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200 border border-white/20">
                                More Info
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Slide 2: Spider-Man No Way Home --}}
        <div class="relative min-w-full group">
            <div class="relative h-[60vh] overflow-hidden">
                <img src="https://images.unsplash.com/photo-1635805737707-575885ab0820?w=1200&h=800&fit=crop" alt="Spider-Man: No Way Home" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full max-w-2xl ml-8 md:ml-16 text-white">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-2 py-1 bg-red-600 text-xs font-semibold rounded-full uppercase tracking-wide">Multiverse</span>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.564-.955L10 0l2.948 5.955 6.564.955-4.756 4.635 1.122 6.545z" />
                                </svg>
                                <span class="text-sm font-semibold">8.2</span>
                            </div>
                        </div>

                        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-red-500 leading-tight">
                            Spider-Man: No Way Home
                        </h1>
                        
                        <p class="text-gray-300 mb-3 text-sm">Marvel • Action • Sci-Fi</p>
                        
                        <p class="text-gray-200 text-sm md:text-base leading-relaxed mb-4 max-w-xl">
                            Ketika identitas Peter Parker terbongkar, ia meminta bantuan Doctor Strange, memicu kekacauan multiverse yang membawa musuh-musuh lama dari dimensi lain.
                        </p>
                        
                        <div class="flex space-x-3">
                            <button class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200 border border-white/20">
                                More Info
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Slide 3: Oppenheimer --}}
        <div class="relative min-w-full group">
            <div class="relative h-[60vh] overflow-hidden">
                <img src="https://i.ebayimg.com/00/s/MTA5M1gxNjAw/z/bZAAAOSwRldkwuqk/$_57.JPG?set_id=8800005007" alt="Oppenheimer" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full max-w-2xl ml-8 md:ml-16 text-white">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-2 py-1 bg-orange-600 text-xs font-semibold rounded-full uppercase tracking-wide">Award Winner</span>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.564-.955L10 0l2.948 5.955 6.564.955-4.756 4.635 1.122 6.545z" />
                                </svg>
                                <span class="text-sm font-semibold">8.4</span>
                            </div>
                        </div>

                        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-orange-400 leading-tight">
                            Oppenheimer
                        </h1>
                        
                        <p class="text-gray-300 mb-3 text-sm">Biography • Drama • History</p>
                        
                        <p class="text-gray-200 text-sm md:text-base leading-relaxed mb-4 max-w-xl">
                            Kisah mendalam J. Robert Oppenheimer dalam mengembangkan bom atom pertama dunia di tengah tekanan moral dan politik.
                        </p>
                        
                        <div class="flex space-x-3">
                            <button class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200 border border-white/20">
                                More Info
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Arrows --}}
    <button id="prevBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-200 backdrop-blur-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    
    <button id="nextBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-200 backdrop-blur-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Dot Indicators --}}
    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3">
        <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-colors duration-200 dot-indicator active" data-slide="0"></button>
        <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-colors duration-200 dot-indicator" data-slide="1"></button>
        <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-colors duration-200 dot-indicator" data-slide="2"></button>
    </div>
</div>

<script>
    let index = 0;
    const totalSlides = 3; // Asli, tanpa duplikat
    const slider = document.getElementById('filmSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const dotIndicators = document.querySelectorAll('.dot-indicator');
    let autoSlideInterval;

    function updateDotIndicators() {
        dotIndicators.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
            dot.style.backgroundColor = i === index ? 'white' : 'rgba(255, 255, 255, 0.5)';
        });
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            index++;
            slider.style.transition = 'transform 0.7s ease-in-out';
            slider.style.transform = `translateX(-${index * 100}%)`;
            
            if (index === totalSlides) {
                // Tunggu animasi selesai, lalu reset ke 0 tanpa animasi
                setTimeout(() => {
                    slider.style.transition = 'none';
                    slider.style.transform = `translateX(0%)`;
                    index = 0;
                    updateDotIndicators();
                }, 700); // Harus sama dengan durasi animasi
            } else {
                updateDotIndicators();
            }
        }, 5000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    function goToSlide(slideIndex) {
        index = slideIndex;
        slider.style.transition = 'transform 0.7s ease-in-out';
        slider.style.transform = `translateX(-${index * 100}%)`;
        updateDotIndicators();
    }

    function nextSlide() {
        stopAutoSlide();
        index = (index + 1) % totalSlides;
        goToSlide(index);
        startAutoSlide();
    }

    function prevSlide() {
        stopAutoSlide();
        index = (index - 1 + totalSlides) % totalSlides;
        goToSlide(index);
        startAutoSlide();
    }

    // Event listeners
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    dotIndicators.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            goToSlide(i);
            startAutoSlide();
        });
    });

    // Pause auto-slide on hover
    slider.addEventListener('mouseenter', stopAutoSlide);
    slider.addEventListener('mouseleave', startAutoSlide);

    // Initialize
    updateDotIndicators();
    startAutoSlide();
</script>

<style>
    .dot-indicator.active {
        background-color: white !important;
    }
</style>
