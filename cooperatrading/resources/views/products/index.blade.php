@extends('layouts.app')

@section('title', 'B2B Product Catalog — Industrial & Construction Supplies in Ethiopia')
@section('description', 'Explore Coopera Trading’s extensive B2B catalog of industrial chemicals, concrete solutions, water filtration, packaging supplies, and Ethiopian export commodities.')
@section('og_title', 'B2B Product Catalog | Coopera Trading Ethiopia')

@section('content')
<section class="relative bg-slate-950 text-white min-h-[360px] lg:min-h-[400px] flex items-center overflow-hidden">
    <!-- Banner Image Background Layer -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img src="{{ asset('assets/images/hero/products-bg.jpg') }}" alt="Products Catalog" class="w-full h-full object-cover transform scale-105 opacity-60">
    </div>

    <!-- Dual-Tone Dark Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-indigo-950/80 z-10 pointer-events-none"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-900/70 to-slate-950 z-10 pointer-events-none"></div>

    <!-- Content Container -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
        <div class="max-w-3xl space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 text-emerald-400 border border-white/20 backdrop-blur-md shadow-lg shadow-black/20 uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                Catalog & Supply Lines
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white [text-shadow:_0_4px_24px_rgba(0,0,0,0.9)]">
                Our products
            </h1>
            <p class="text-lg lg:text-xl text-slate-200 leading-relaxed max-w-2xl [text-shadow:_0_2px_12px_rgba(0,0,0,0.85)]">
                Six key industrial categories, hundreds of certified products. Select a category to explore, or request a proforma invoice for any combination of items.
            </p>
        </div>
    </div>
</section>

<section class="py-16 lg:py-24 bg-coopera-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- SECTION 1: IMPORT CATEGORIES -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-2">
                🚢 Industrial Import Lines
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900">Import Product Categories</h2>
            <p class="mt-2 text-base text-slate-600">Explore construction chemicals, industrial raw materials, environmental systems, and packaging.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center max-w-6xl mx-auto">
            @foreach ($categories->reject(fn($c) => $c->slug === 'export-products') as $category)
                <a href="{{ route('products.category', $category) }}" class="group flex flex-col items-center text-center bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-indigo-600 hover:shadow-xl transition-all duration-300 h-full">
                    <div class="h-48 sm:h-52 w-full bg-slate-100 overflow-hidden shrink-0">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center\'><span class=\'text-7xl font-bold text-white/30\'>{{ substr($category->name, 0, 1) }}</span></div>'">
                    </div>
                    <div class="p-6 flex flex-col items-center justify-between flex-1 w-full">
                        <div>
                            <div class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">{{ $category->products_count }} Products</div>
                            <h2 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition">{{ $category->name }}</h2>
                            <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $category->description }}</p>
                        </div>
                        <span class="mt-5 inline-flex items-center gap-1 text-sm font-bold text-indigo-600 group-hover:gap-2 transition-all">
                            View Products <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- SECTION 2: EXPORT CATEGORIES -->
        @if($exportCat = $categories->firstWhere('slug', 'export-products'))
            <div class="mt-20 pt-16 border-t border-slate-200">
                <div class="text-center max-w-3xl mx-auto mb-10">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold uppercase tracking-wider mb-2">
                        🌍 Specialty Ethiopian Exports
                    </span>
                    <h2 class="text-3xl font-extrabold text-slate-900">Export Product Categories</h2>
                    <p class="mt-2 text-base text-slate-600">Certified Ethiopian Arabica coffee, sesame, oilseeds, and natural commodities.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center max-w-6xl mx-auto">
                    <div class="md:col-span-2 lg:col-span-1 lg:col-start-2 w-full">
                        <a href="{{ route('products.category', $exportCat) }}" class="group flex flex-col items-center text-center bg-white border-2 border-amber-500/30 rounded-2xl overflow-hidden hover:border-amber-500 hover:shadow-2xl transition-all duration-300 h-full">
                            <div class="h-48 sm:h-52 w-full bg-slate-100 overflow-hidden shrink-0">
                                <img src="{{ $exportCat->image_url }}" alt="{{ $exportCat->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            <div class="p-6 flex flex-col items-center justify-between flex-1 w-full">
                                <div>
                                    <div class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">{{ $exportCat->products_count }} Certified Export Products</div>
                                    <h2 class="text-xl font-bold text-slate-900 group-hover:text-amber-700 transition">{{ $exportCat->name }}</h2>
                                    <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $exportCat->description }}</p>
                                </div>
                                <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-amber-700 group-hover:gap-2 transition-all">
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
@endsection
