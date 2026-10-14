<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Coopera Trading') | Coopera Trading</title>
    <meta name="description"
        content="@yield('description', 'Coopera Trading — your trusted partner for construction, industrial chemicals, water solutions, packaging, technology, and export products.')">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased bg-coopera-cream text-slate-800">

    @php
        $navLinks = [
            ['route' => 'home', 'label' => 'Home'],
            ['route' => 'about', 'label' => 'About'],
            ['route' => 'products', 'label' => 'Products'],
            ['route' => 'contact', 'label' => 'Contact'],
        ];
    @endphp

    {{-- ===== SIKA-STYLE 3-ROW HEADER ===== --}}
    <header x-data="{
        scrolled: false,
        activeDropdown: null,
        dropdownTimeout: null,
        mobileOpen: false,
        mobileExpanded: null,
        searchOpen: false,
        searchQuery: '',
        searchResults: [],
        searchLoading: false,
        searchDebounce: null,
        init() {
            this.scrolled = window.scrollY > 100;
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 100;
                if (this.activeDropdown !== null) {
                    this.closeDropdown(0);
                }
            }, { passive: true });
        },
        openDropdown(idx) {
            clearTimeout(this.dropdownTimeout);
            this.activeDropdown = idx;
        },
        closeDropdown(delay = 0) {
            clearTimeout(this.dropdownTimeout);
            if (delay > 0) {
                this.dropdownTimeout = setTimeout(() => {
                    this.activeDropdown = null;
                }, delay);
            } else {
                this.activeDropdown = null;
            }
        },
        toggleDropdown(idx) {
            clearTimeout(this.dropdownTimeout);
            this.activeDropdown = this.activeDropdown === idx ? null : idx;
        },
        toggleMobileCategory(idx) {
            this.mobileExpanded = this.mobileExpanded === idx ? null : idx;
        },
        async doSearch() {
            clearTimeout(this.searchDebounce);
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                return;
            }
            this.searchLoading = true;
            this.searchDebounce = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/products/search?q=${encodeURIComponent(this.searchQuery)}`);
                    this.searchResults = await res.json();
                } catch(e) {
                    this.searchResults = [];
                }
                this.searchLoading = false;
            }, 250);
        },
        clearSearch() {
            this.searchQuery = '';
            this.searchResults = [];
            this.searchOpen = false;
        }
    }" class="fixed top-0 left-0 right-0 z-50" @scroll.window="closeDropdown(0)" @click.outside="closeDropdown(0)"
        @keydown.escape.window="closeDropdown(0); searchOpen = false; mobileOpen = false">
        {{-- ===== ROW 1: META-BAR ===== --}}
        <div class="bg-coopera-navy-deep text-white text-xs transition-all duration-300 overflow-hidden border-b border-coopera-navy/40"
            :class="scrolled ? 'max-h-0 opacity-0' : 'max-h-12 opacity-100'">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-9">
                    {{-- Left: Phone & Email --}}
                    <div class="hidden sm:flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-white/70" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:+251959440446" class="hover:text-white transition-colors font-medium">+251 959
                                440 446</a>
                            <span class="text-white/30">|</span>
                            <a href="tel:+251973397012" class="hover:text-white transition-colors font-medium">+251 97
                                339 7012</a>
                        </div>
                        <span class="text-white/30">|</span>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-white/70" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:info@cooperatrading.com"
                                class="hover:text-white transition-colors">info@cooperatrading.com</a>
                        </div>
                    </div>

                    {{-- Right: Utility links --}}
                    <div class="flex items-center gap-1 sm:gap-4 ml-auto">
                        <a href="{{ route('about') }}" class="hover:text-white transition-colors px-1.5 py-1">About
                            Us</a>
                        <span class="text-white/30 hidden sm:inline">|</span>
                        <a href="{{ route('contact') }}" class="hover:text-white transition-colors px-1.5 py-1">Contact
                            Us</a>
                        <span class="text-white/30 hidden sm:inline">|</span>
                        <a href="{{ route('quote.create') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition-all border border-white/30 hover:border-white/60">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Get a Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== ROW 2: BRAND BAR ===== --}}
        <div class="bg-coopera-navy-deep transition-all duration-300 relative z-40"
            :class="scrolled ? 'max-h-0 opacity-0 overflow-hidden pointer-events-none' : 'max-h-24 opacity-100 overflow-visible'">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-[72px]">
                    {{-- Left: Logo + Brand Name --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                        <img src="{{ asset('assets/brand/logo-horizontal-white.svg') }}" alt="Coopera Trading"
                            class="h-8 lg:h-10 w-auto">
                    </a>

                    {{-- Right: Search Field --}}
                    <div class="hidden md:block relative w-full max-w-sm lg:max-w-md ml-auto">
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @input="doSearch()" @focus="searchOpen = true"
                                @click.outside="searchOpen = false" placeholder="Find Products"
                                class="w-full bg-transparent border-0 border-b-2 border-white/30 text-white placeholder-white/60 text-sm py-2 pr-10 pl-1 focus:border-white focus:ring-0 focus:outline-none transition-colors font-medium">
                            <button type="button"
                                class="absolute right-0 top-1/2 -translate-y-1/2 p-2 text-white/80 hover:text-white transition-colors"
                                @click="searchOpen = !searchOpen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>

                        {{-- Search Results Dropdown --}}
                        <div x-show="searchOpen && searchQuery.length >= 2"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1" x-cloak
                            class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-2xl border border-slate-200 overflow-hidden z-[100]">
                            <template x-if="searchLoading">
                                <div class="p-4 text-sm text-slate-500 text-center">Searching…</div>
                            </template>
                            <template x-if="!searchLoading && searchResults.length === 0 && searchQuery.length >= 2">
                                <div class="p-4 text-sm text-slate-500 text-center">No products found</div>
                            </template>
                            <template x-for="result in searchResults" :key="result.id">
                                <a :href="result.url"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0">
                                    <div
                                        class="w-8 h-8 rounded bg-coopera-navy/10 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-coopera-navy" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium text-slate-900 truncate" x-text="result.name">
                                        </div>
                                        <div class="text-xs text-slate-500" x-text="result.category"></div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 ml-auto shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </div>

                    {{-- Mobile: Hamburger --}}
                    <button @click="mobileOpen = true"
                        class="md:hidden text-white p-2 hover:bg-white/10 rounded-lg transition-colors"
                        aria-label="Open menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== ROW 3: MAIN NAVIGATION BAR ===== --}}
        <nav class="bg-white border-b border-slate-200 transition-shadow duration-300"
            :class="scrolled ? 'shadow-lg' : ''">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="hidden md:flex items-center h-12">
                    {{-- Compact Logo (visible only when scrolled) --}}
                    <a href="{{ route('home') }}" class="shrink-0 mr-6 transition-all duration-300 overflow-hidden"
                        :class="scrolled ? 'w-8 opacity-100' : 'w-0 opacity-0'">
                        <img src="{{ asset('assets/brand/logo-mark.svg') }}" alt="Coopera" class="h-7 w-auto">
                    </a>

                    {{-- Category Navigation Items --}}
                    <div class="flex items-center gap-0 h-full">
                        @isset($headerCategories)
                            @foreach ($headerCategories as $idx => $category)
                                <div class="relative h-full flex items-center" @mouseenter="openDropdown({{ $idx }})"
                                    @mouseleave="closeDropdown(150)">
                                    @if($category->products->count() > 0)
                                        <button type="button" @click="toggleDropdown({{ $idx }})"
                                            class="flex items-center gap-1 px-3 lg:px-4 h-full text-sm font-medium transition-colors border-b-2 cursor-pointer focus:outline-none"
                                            :class="activeDropdown === {{ $idx }} ? 'text-coopera-navy border-coopera-navy' : 'text-slate-700 border-transparent hover:text-coopera-navy hover:border-coopera-navy/30'">
                                            <span class="whitespace-nowrap">{{ Str::limit($category->name, 22) }}</span>
                                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200"
                                                :class="activeDropdown === {{ $idx }} ? 'rotate-180 text-coopera-navy' : ''"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    @else
                                        <a href="{{ route('products.category', $category) }}"
                                            class="flex items-center gap-1 px-3 lg:px-4 h-full text-sm font-medium transition-colors border-b-2 text-slate-700 border-transparent hover:text-coopera-navy hover:border-coopera-navy/30">
                                            <span class="whitespace-nowrap">{{ Str::limit($category->name, 22) }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endisset
                    </div>

                    {{-- Right side: Compact search (visible when scrolled) + View All --}}
                    <div class="ml-auto flex items-center gap-3">
                        {{-- Compact search icon (scrolled state) --}}
                        <div class="relative transition-all duration-300 overflow-hidden"
                            :class="scrolled ? 'w-auto opacity-100' : 'w-0 opacity-0 pointer-events-none'">
                            <div class="relative" x-data="{ compactSearchOpen: false }">
                                <button @click="compactSearchOpen = !compactSearchOpen"
                                    class="p-2 text-slate-500 hover:text-coopera-navy transition-colors rounded-lg hover:bg-slate-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                <div x-show="compactSearchOpen" @click.outside="compactSearchOpen = false" x-transition
                                    x-cloak
                                    class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-2xl border border-slate-200 p-3 z-[100]">
                                    <input type="text" x-model="searchQuery" @input="doSearch(); searchOpen = true"
                                        placeholder="Find Products…"
                                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-coopera-navy/30 focus:border-coopera-navy"
                                        autofocus>
                                    {{-- Compact search results --}}
                                    <div x-show="searchQuery.length >= 2" class="mt-2 max-h-64 overflow-y-auto">
                                        <template x-if="searchLoading">
                                            <div class="p-3 text-sm text-slate-500 text-center">Searching…</div>
                                        </template>
                                        <template
                                            x-if="!searchLoading && searchResults.length === 0 && searchQuery.length >= 2">
                                            <div class="p-3 text-sm text-slate-500 text-center">No products found</div>
                                        </template>
                                        <template x-for="result in searchResults" :key="result.id">
                                            <a :href="result.url"
                                                class="flex items-center gap-2 px-2 py-2 hover:bg-slate-50 rounded-md text-sm">
                                                <span class="font-medium text-slate-900 truncate"
                                                    x-text="result.name"></span>
                                                <span class="text-xs text-slate-400 ml-auto shrink-0"
                                                    x-text="result.category"></span>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('products') }}"
                            class="text-xs font-semibold text-coopera-navy hover:underline whitespace-nowrap hidden lg:inline-flex items-center gap-1">
                            View All Products
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Mobile: Compact sticky bar --}}
                <div class="flex md:hidden items-center justify-between h-12">
                    <a href="{{ route('home') }}" class="shrink-0">
                        <img src="{{ asset('assets/brand/logo-mark.svg') }}" alt="Coopera" class="h-7 w-auto">
                    </a>
                    <span class="text-sm font-semibold text-coopera-navy">COOPERA TRADING</span>
                    <button @click="mobileOpen = true" class="p-2 text-slate-600 hover:text-coopera-navy"
                        aria-label="Open menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        {{-- ===== DROPDOWN MEGA-MENU PANELS ===== --}}
        @isset($headerCategories)
            @foreach ($headerCategories as $idx => $category)
                @if($category->products->count() > 0)
                    <div x-show="activeDropdown === {{ $idx }}" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2" x-cloak
                        class="absolute left-0 right-0 bg-white border-b border-slate-200 shadow-xl z-40"
                        @mouseenter="openDropdown({{ $idx }})" @mouseleave="closeDropdown(150)">
                        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
                            <div class="flex items-start gap-8">
                                {{-- Category Title --}}
                                <div class="shrink-0 w-48">
                                    <h3 class="text-lg font-bold text-coopera-navy">{{ $category->name }}</h3>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $category->description }}</p>
                                    <a href="{{ route('products.category', $category) }}"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-coopera-navy hover:underline mt-3">
                                        View All
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>

                                {{-- Sub-products Grid --}}
                                <div class="flex-1 grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-2">
                                    @foreach ($category->products as $product)
                                        <a href="{{ route('products.show', [$category, $product]) }}"
                                            class="group flex items-center gap-2 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors">
                                            <div
                                                class="w-1.5 h-1.5 rounded-full bg-coopera-navy/30 group-hover:bg-coopera-navy transition-colors shrink-0">
                                            </div>
                                            <span
                                                class="text-sm text-slate-700 group-hover:text-coopera-navy transition-colors">{{ $product->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endisset

        {{-- ===== MOBILE OFF-CANVAS DRAWER ===== --}}
        {{-- Backdrop --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-cloak
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[90] md:hidden" @click="mobileOpen = false"></div>

        {{-- Drawer --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full" x-cloak
            class="fixed top-0 right-0 bottom-0 w-[320px] max-w-[85vw] bg-white shadow-2xl z-[100] md:hidden overflow-y-auto">
            {{-- Drawer Header --}}
            <div class="flex items-center justify-between px-5 py-4 bg-coopera-navy-deep">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/brand/logo-horizontal-white.svg') }}" alt="Coopera" class="h-7 w-auto">
                </a>
                <button @click="mobileOpen = false"
                    class="p-2 text-white hover:bg-white/10 rounded-lg transition-colors" aria-label="Close menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Mobile Search --}}
            <div class="px-5 py-3 border-b border-slate-100">
                <div class="relative">
                    <input type="text" x-model="searchQuery" @input="doSearch()" placeholder="Find Products…"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-coopera-navy/30 focus:border-coopera-navy">
                    <svg class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                {{-- Mobile search results --}}
                <div x-show="searchQuery.length >= 2" class="mt-2 max-h-48 overflow-y-auto">
                    <template x-if="searchLoading">
                        <div class="p-2 text-xs text-slate-500 text-center">Searching…</div>
                    </template>
                    <template x-for="result in searchResults" :key="result.id">
                        <a :href="result.url" class="block px-2 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded"
                            @click="mobileOpen = false">
                            <span class="font-medium" x-text="result.name"></span>
                            <span class="text-xs text-slate-400 ml-1" x-text="'· ' + result.category"></span>
                        </a>
                    </template>
                </div>
            </div>

            {{-- Navigation Links --}}
            <div class="px-3 py-2">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'text-coopera-navy bg-coopera-navy/5' : 'text-slate-700 hover:bg-slate-50' }} transition-colors"
                    @click="mobileOpen = false">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
            </div>

            {{-- Category Accordions --}}
            <div class="px-3 py-1">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Product Categories
                </div>
                @isset($headerCategories)
                    @foreach ($headerCategories as $idx => $category)
                        <div class="mb-0.5">
                            <button @click="toggleMobileCategory({{ $idx }})"
                                class="flex items-center justify-between w-full px-3 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                <span>{{ $category->name }}</span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                    :class="mobileExpanded === {{ $idx }} ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="mobileExpanded === {{ $idx }}" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                                x-cloak class="overflow-hidden">
                                <div class="pl-6 pr-3 pb-2 space-y-0.5">
                                    <a href="{{ route('products.category', $category) }}"
                                        class="block px-3 py-2 text-xs font-semibold text-coopera-navy hover:bg-coopera-navy/5 rounded-md"
                                        @click="mobileOpen = false">
                                        View All {{ $category->name }}
                                    </a>
                                    @foreach ($category->products as $product)
                                        <a href="{{ route('products.show', [$category, $product]) }}"
                                            class="block px-3 py-2 text-sm text-slate-600 hover:text-coopera-navy hover:bg-slate-50 rounded-md transition-colors"
                                            @click="mobileOpen = false">
                                            {{ $product->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endisset
            </div>

            {{-- Utility Links --}}
            <div class="border-t border-slate-100 px-3 py-3 mt-2">
                <a href="{{ route('about') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-slate-700 hover:bg-slate-50 transition-colors"
                    @click="mobileOpen = false">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    About Us
                </a>
                <a href="{{ route('contact') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-slate-700 hover:bg-slate-50 transition-colors"
                    @click="mobileOpen = false">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact Us
                </a>
                <a href="tel:+251959440446"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    +251 959 440 446
                </a>
                <a href="tel:+251973397012"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    +251 97 339 7012
                </a>
                <a href="mailto:info@cooperatrading.com"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    info@cooperatrading.com
                </a>

                {{-- Social Icons in Mobile Menu --}}
                <div class="flex items-center gap-3 px-3 pt-3 mt-1 border-t border-slate-100">
                    <a href="https://t.me/cooperatrading" target="_blank" rel="noopener noreferrer"
                        class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:text-[#229ED9] hover:bg-slate-200 transition"
                        title="Telegram Channel">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" />
                        </svg>
                    </a>
                    <a href="https://t.me/cooperatradinggroup" target="_blank" rel="noopener noreferrer"
                        class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:text-cyan-500 hover:bg-slate-200 transition"
                        title="Telegram Group">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" />
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/company/cooperatrading" target="_blank" rel="noopener noreferrer"
                        class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:text-[#0A66C2] hover:bg-slate-200 transition"
                        title="LinkedIn">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.65 1.65 0 1 0 0 3.3 1.65 1.65 0 0 0 0-3.3z" />
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/cooperatrading" target="_blank" rel="noopener noreferrer"
                        class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:text-[#1877F2] hover:bg-slate-200 transition"
                        title="Facebook">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/cooperatrading" target="_blank" rel="noopener noreferrer"
                        class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:text-[#E4405F] hover:bg-slate-200 transition"
                        title="Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@cooperatrading" target="_blank" rel="noopener noreferrer"
                        class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:text-pink-500 hover:bg-slate-200 transition"
                        title="TikTok">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- CTA --}}
            <div class="px-5 py-4 border-t border-slate-100">
                <a href="{{ route('quote.create') }}"
                    class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-coopera-navy text-white text-sm font-bold hover:bg-coopera-navy-light transition-colors shadow-lg"
                    @click="mobileOpen = false">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Request a Quote
                </a>
            </div>
        </div>
    </header>

    {{-- Header spacer to offset fixed positioning --}}
    <div class="h-[132px] md:h-[132px]" :class="{ 'h-12 md:h-12': scrolled }"
        x-data="{ scrolled: window.scrollY > 100 }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 100)"></div>

    <main class="min-h-[60vh]">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-300 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                {{-- Brand & Social Media Column --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('assets/brand/logo-mark-gradient-white.svg') }}" alt="Coopera Trading"
                            class="h-10 w-auto">
                        <span class="font-semibold text-lg text-white">Coopera Trading</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed mb-5">
                        Your trusted partner for industrial, construction, and export solutions across Ethiopia and
                        beyond.
                    </p>

                    {{-- Social Media Icons Row --}}
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5">Follow & Connect
                        </div>
                        <div class="flex items-center flex-wrap gap-2">
                            <!-- Telegram Channel -->
                            <a href="https://t.me/coopera_trading" target="_blank" rel="noopener noreferrer"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-[#229ED9] text-slate-400 hover:text-white flex items-center justify-center transition-colors border border-slate-700"
                                title="Telegram Channel">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" />
                                </svg>
                            </a>
                            <!-- Telegram Group -->
                            <a href="https://t.me/coopera_trading1" target="_blank" rel="noopener noreferrer"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-cyan-500 text-slate-400 hover:text-white flex items-center justify-center transition-colors border border-slate-700"
                                title="Telegram Community Group">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" />
                                </svg>
                            </a>
                            <!-- Facebook page-->
                            <a href="https://www.facebook.com/profile.php?id=61594968556891" target="_blank"
                                rel="noopener noreferrer"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-[#1877F2] text-slate-400 hover:text-white flex items-center justify-center transition-colors border border-slate-700"
                                title="Facebook page">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <!-- Facebook -->
                            <a href="https://facebook.com/profile.php?id=61595012144629" target="_blank"
                                rel="noopener noreferrer"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-[#1877F2] text-slate-400 hover:text-white flex items-center justify-center transition-colors border border-slate-700"
                                title="Facebook">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <!-- Instagram -->
                            <a href="https://www.instagram.com/coopera_trading" target="_blank"
                                rel="noopener noreferrer"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-[#E4405F] text-slate-400 hover:text-white flex items-center justify-center transition-colors border border-slate-700"
                                title="Instagram">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                            <!-- TikTok -->
                            <a href="https://www.tiktok.com/@coopera.trading?_r=1" target="_blank"
                                rel="noopener noreferrer"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-pink-500 text-slate-400 hover:text-white flex items-center justify-center transition-colors border border-slate-700"
                                title="TikTok">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Company Links --}}
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Company</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                        <li><a href="{{ route('products') }}" class="hover:text-white transition">Products Catalog</a>
                        </li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact & Location</a>
                        </li>
                        <li><a href="{{ route('quote.create') }}" class="hover:text-white transition">Request a
                                Quote</a></li>
                    </ul>
                </div>

                {{-- Solutions --}}
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Solutions</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products') }}" class="hover:text-white transition">Construction
                                Solutions</a></li>
                        <li><a href="{{ route('products') }}" class="hover:text-white transition">Industrial
                                Chemicals</a></li>
                        <li><a href="{{ route('products') }}" class="hover:text-white transition">Water &
                                Environmental</a></li>
                        <li><a href="{{ route('products') }}" class="hover:text-white transition">Packaging &
                                Supplies</a></li>
                        <li><a href="{{ route('products') }}" class="hover:text-white transition">Export Products</a>
                        </li>
                    </ul>
                </div>

                {{-- Contact Details Column --}}
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Headquarters</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li class="flex items-start gap-2">
                            <span class="text-amber-400 mt-0.5">📍</span>
                            <span>
                                <strong class="text-slate-200">Ethio-Best Building</strong>, 3rd Floor, Office #004<br>
                                Kirkos Sub-City, Woreda 02<br>
                                Addis Ababa, Ethiopia
                            </span>
                        </li>
                        <li class="flex items-center gap-2 pt-1">
                            <span class="text-amber-400">✉️</span>
                            <a href="mailto:info@cooperatrading.com"
                                class="hover:text-white text-indigo-400 transition font-medium">info@cooperatrading.com</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-amber-400">🌐</span>
                            <a href="https://www.cooperatrading.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-white transition">www.cooperatrading.com</a>
                        </li>
                        <li class="flex items-center gap-2 pt-1">
                            <span class="text-emerald-400">📞</span>
                            <a href="tel:+251959440446" class="hover:text-white transition font-medium">+251 959 440
                                446</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-400">💬</span>
                            <a href="https://wa.me/251973397012" target="_blank" rel="noopener noreferrer"
                                class="hover:text-emerald-400 transition font-medium">+251 97 339 7012 <span
                                    class="text-[10px] bg-emerald-900/60 text-emerald-300 px-1.5 py-0.5 rounded ml-1">WhatsApp</span></a>
                        </li>
                    </ul>
                </div>

            </div>

            <div
                class="mt-12 pt-8 border-t border-slate-800 text-sm text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} Coopera Trading. All rights reserved. <span
                        class="hidden sm:inline">&middot;</span> Powered by <a href="https://www.razielcc.com"
                        target="_blank" rel="noopener noreferrer"
                        class="text-slate-400 hover:text-white transition font-medium">RCC</a></p>
                <p class="text-slate-400">Ethio-Best Building, Kirkos Sub-City, Addis Ababa</p>
            </div>
        </div>
    </footer>
</body>

</html>