@extends('layouts.app')

@section('title', 'Industrial Chemicals, Construction & Export Solutions in Ethiopia')
@section('description', 'Coopera Trading is a premier Ethiopian B2B trading enterprise supplying high-performance construction chemicals, industrial raw materials, water solutions, packaging, and specialty export commodities.')
@section('og_title', 'Coopera Trading — Industrial Chemicals, Construction & Export Solutions in Ethiopia')

@section('content')
    <section x-data="{
            activeSlide: 0,
            autoplay: null,
            slides: @js($heroSlides->map(fn ($s) => [
                'badge' => $s->badge,
                'title' => $s->title,
                'description' => $s->description,
                'bgImage' => $s->image_url,
                'imageAlt' => $s->image_alt ?? $s->title,
                'ctaLabel' => $s->cta_label,
                'ctaUrl' => $s->cta_url,
                'tags' => $s->tags ?? [],
            ])->all()),
            init() {
                if (this.slides.length > 0) this.startAutoplay();
            },
            startAutoplay() {
                if (this.slides.length < 2) return;
                this.autoplay = setInterval(() => {
                    this.nextSlide();
                }, 6000);
            },
            stopAutoplay() {
                if (this.autoplay) clearInterval(this.autoplay);
            },
            nextSlide() {
                if (this.slides.length === 0) return;
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            },
            prevSlide() {
                if (this.slides.length === 0) return;
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
            }
        }" x-on:mouseenter="stopAutoplay()" x-on:mouseleave="startAutoplay()"
        class="relative bg-slate-950 text-white min-h-[720px] lg:min-h-[800px] flex items-center overflow-hidden pt-20 lg:pt-24">

        <!-- 1. Background Image Slider Layer (z-0) -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95" class="absolute inset-0">
                    <img :src="slide.bgImage" :alt="slide.badge" class="w-full h-full object-cover">
                </div>
            </template>
        </div>

        <!-- 2. Dark Gradient Overlay Layers (z-10) -->
        <div
            class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/80 to-slate-900/50 z-10 pointer-events-none">
        </div>
        <div class="absolute inset-0 bg-slate-950/30 z-10 pointer-events-none"></div>

        <!-- 3. Animated Particle Canvas Layer (z-20) -->
        <canvas id="heroCanvas" class="absolute inset-0 w-full h-full opacity-35 pointer-events-none z-20"></canvas>

        <!-- 4. Split 2-Column Hero Content Container (z-30) -->
        <div class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full pointer-events-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

                <!-- Left Column: Pillar Title & Carousel Navigation (col-span-7) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Category Badge Pill -->
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-900/60 border border-indigo-500/40 text-indigo-300 text-xs font-bold uppercase tracking-wider backdrop-blur-sm shadow-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span x-text="slides[activeSlide].badge"></span>
                    </div>

                    <!-- Headline Typography -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] text-white [text-shadow:_0_4px_24px_rgba(0,0,0,0.9)] min-h-[120px] sm:min-h-[140px]"
                        x-text="slides[activeSlide].title">
                    </h1>

                    <!-- Subtitle Description -->
                    <p class="text-base sm:text-lg text-slate-200/90 font-normal leading-relaxed max-w-xl [text-shadow:_0_2px_12px_rgba(0,0,0,0.85)] min-h-[75px]"
                        x-text="slides[activeSlide].description">
                    </p>

                    <!-- Slide Navigation Dots, Controls & Counter -->
                    <div class="flex items-center gap-6 pt-2">
                        <div class="flex gap-2">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button x-on:click="activeSlide = index"
                                    class="h-2 rounded-full transition-all duration-500 cursor-pointer"
                                    :class="activeSlide === index ? 'w-8 bg-indigo-400 shadow-md shadow-indigo-500/50' : 'w-2.5 bg-white/40 hover:bg-white/70'"
                                    :aria-label="'Go to slide ' + (index + 1)"></button>
                            </template>
                        </div>

                        <span class="text-xs text-slate-300 font-mono tracking-widest" x-show="slides.length > 0">
                            0<span x-text="activeSlide + 1"></span> / 0<span x-text="slides.length"></span>
                        </span>

                        <div class="flex items-center gap-1.5 ml-2">
                            <button x-on:click="prevSlide()"
                                class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition cursor-pointer"
                                aria-label="Previous slide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button x-on:click="nextSlide()"
                                class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition cursor-pointer"
                                aria-label="Next slide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Dynamic Interactive Glass Card Widget (col-span-5) -->

            </div>
        </div>
    </section>

    <!-- Particle Canvas Animation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('heroCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width = canvas.width = canvas.offsetWidth;
            let height = canvas.height = canvas.offsetHeight;

            window.addEventListener('resize', () => {
                width = canvas.width = canvas.offsetWidth;
                height = canvas.height = canvas.offsetHeight;
            });

            const particles = [];
            const count = 45;

            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.4,
                    vy: (Math.random() - 0.5) * 0.4,
                    radius: Math.random() * 2 + 1,
                    alpha: Math.random() * 0.5 + 0.2
                });
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);

                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 120) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(255, 255, 255, ${0.15 * (1 - dist / 120)})`;
                            ctx.lineWidth = 0.8;
                            ctx.stroke();
                        }
                    }
                }

                particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0 || p.x > width) p.vx *= -1;
                    if (p.y < 0 || p.y > height) p.vy *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 255, 255, ${p.alpha})`;
                    ctx.fill();
                });

                requestAnimationFrame(animate);
            }

            animate();
        });
    </script>

    <!-- Product Categories: Split into Import & Export Sections -->
    <section class="py-20 lg:py-24 bg-coopera-cream rounded-t-[40px] -mt-10 relative z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- SECTION 1: IMPORT CATEGORIES -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-3">
                    🚢 Industrial Import Supply Lines
                </span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900">Industrial & Construction Imports</h2>
                <p class="mt-3 text-lg text-slate-600">
                    High-performance construction chemicals, raw materials, water treatment equipment, and industrial packaging imported for Ethiopian projects.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center max-w-6xl mx-auto">
                @foreach ($categories->reject(fn($c) => $c->slug === 'export-products') as $category)
                    <a href="{{ route('products.category', $category) }}"
                        class="group flex flex-col items-center text-center bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-indigo-600 hover:shadow-xl transition-all duration-300 h-full">
                        <div class="h-48 sm:h-52 w-full bg-slate-100 overflow-hidden shrink-0">
                            <img src="{{ $category->image_url }}"
                                alt="{{ $category->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center\'><span class=\'text-6xl font-bold text-white/30\'>{{ substr($category->name, 0, 1) }}</span></div>'">
                        </div>
                        <div class="p-6 sm:p-8 flex flex-col items-center justify-between flex-1 w-full">
                            <div>
                                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-2 block">
                                    {{ $category->products_count }} Verified Products
                                </span>
                                <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition">
                                    {{ $category->name }}
                                </h3>
                                <p class="mt-2 text-sm text-slate-600 line-clamp-2">
                                    {{ $category->description }}
                                </p>
                            </div>
                            <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 group-hover:gap-2.5 transition-all">
                                Explore Line <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- SECTION 2: EXPORT CATEGORIES -->
            @if($exportCat = $categories->firstWhere('slug', 'export-products'))
                <div class="mt-20 pt-16 border-t border-slate-200/80">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold uppercase tracking-wider mb-3">
                            🌍 Specialty Ethiopian Exports
                        </span>
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900">Certified Export Commodities</h2>
                        <p class="mt-3 text-lg text-slate-600">
                            Direct Ethiopian exports of premium grade Arabica coffee, raw sesame seeds, pulses, and natural agricultural goods with complete origin traceability.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center max-w-6xl mx-auto">
                        <div class="md:col-span-2 lg:col-span-1 lg:col-start-2 w-full">
                            <a href="{{ route('products.category', $exportCat) }}"
                                class="group flex flex-col items-center text-center bg-white rounded-2xl border-2 border-amber-500/30 overflow-hidden hover:border-amber-500 hover:shadow-2xl transition-all duration-300 h-full">
                                <div class="h-48 sm:h-52 w-full bg-slate-100 overflow-hidden shrink-0">
                                    <img src="{{ $exportCat->image_url }}"
                                        alt="{{ $exportCat->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="p-6 sm:p-8 flex flex-col items-center justify-between flex-1 w-full">
                                    <div>
                                        <span class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-2 block">
                                            {{ $exportCat->products_count }} Certified Export Products
                                        </span>
                                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-amber-700 transition">
                                            {{ $exportCat->name }}
                                        </h3>
                                        <p class="mt-2 text-sm text-slate-600 line-clamp-2">
                                            {{ $exportCat->description }}
                                        </p>
                                    </div>
                                    <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold text-amber-700 group-hover:gap-2.5 transition-all">
                                        Explore Export Products <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <section class="py-20 lg:py-24 bg-coopera-cream-warm/50 border-y border-amber-900/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Why choose Coopera Trading?</h2>
                    <p class="mt-4 text-lg text-slate-600">We bridge international manufacturers and Ethiopian
                        businesses with seamless supply chain solutions, verified product quality, and transparent
                        proforma pricing.</p>
                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold">
                                1
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Direct Manufacturer Sourcing</h3>
                                <p class="text-slate-600 text-sm mt-1">Certified chemicals, materials, and equipment from
                                    trusted global producers.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold">
                                2
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Instant Proforma Generation</h3>
                                <p class="text-slate-600 text-sm mt-1">Select products and receive a formal proforma
                                    invoice with VAT breakdown in your email.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-none w-10 h-10 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold">
                                3
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Local Presence, Global Standards</h3>
                                <p class="text-slate-600 text-sm mt-1">Headquartered in Addis Ababa with full compliance,
                                    documentation, and delivery support.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-10 lg:p-12 shadow-sm">
                    <h3 class="text-2xl font-bold text-slate-900">Need a custom quote?</h3>
                    <p class="mt-3 text-slate-600">Browse our product catalog, select the items and quantities you
                        need, and our team will generate an official proforma invoice for your business.</p>
                    <a href="{{ route('quote.create') }}"
                        class="mt-8 inline-flex items-center px-6 py-3 rounded-md bg-slate-900 text-white font-medium hover:bg-slate-800 transition">
                        Start a quote request
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-24 bg-coopera-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Industries we serve</h2>
                <p class="mt-4 text-lg text-slate-600">From infrastructure to FMCG, we supply the products that keep
                    operations running.</p>
            </div>
            <div class="mt-12 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach (['Construction', 'Manufacturing', 'Water & Utilities', 'Packaging', 'Energy & Power', 'Export & Trade'] as $industry)
                    <div class="text-center p-6 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <div class="text-base font-semibold text-slate-900">{{ $industry }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PARTNERS SHOWCASE SECTION -->
    @if(isset($partners) && $partners->count() > 0)
        <section class="py-16 lg:py-24 bg-coopera-cream border-t border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold uppercase tracking-wider mb-3 border border-amber-200/60">
                        🤝 Global Manufacturing & Supply Network
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">Our Trusted Partners</h2>
                    <p class="mt-3 text-base lg:text-lg text-slate-600">
                        Direct partnerships with leading global chemical, polymer, and industrial manufacturers to deliver certified quality.
                    </p>
                </div>
            </div>

            <!-- Single Display Showcase (No Duplicates, 6-Column Grid, Generous Spacing, 1.7x Zoom) -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    @foreach ($partners as $partner)
                        <a href="{{ $partner->url ?: '#' }}"
                           @if($partner->url) target="_blank" rel="noopener noreferrer" @endif
                           title="{{ $partner->name }} - Click to visit website"
                           class="group/partner flex flex-col items-center justify-center p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:border-amber-500 hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.7] hover:z-30 cursor-pointer relative h-28">
                            <div class="h-11 w-full flex items-center justify-center overflow-hidden">
                                <img src="{{ $partner->logo_url }}"
                                     alt="{{ $partner->name }}"
                                     class="max-h-9 max-w-[120px] w-auto object-contain transition duration-300 group-hover/partner:scale-105">
                            </div>
                            <span class="mt-2 text-xs font-bold text-slate-700 group-hover/partner:text-amber-600 transition text-center line-clamp-1">
                                {{ $partner->name }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection