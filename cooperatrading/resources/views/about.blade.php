@extends('layouts.app')

@section('title', 'About Us — Leading Multi-Sector Trading Partner in Ethiopia')
@section('description', 'Discover Coopera Trading — our leadership, infrastructure, global sourcing network, and dedication to empowering Ethiopian industries and export markets.')
@section('og_title', 'About Coopera Trading | Addis Ababa, Ethiopia')

@section('content')
<section class="relative bg-slate-950 text-white min-h-[380px] lg:min-h-[440px] flex items-center overflow-hidden">
    <!-- Banner Image Background Layer -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img src="{{ asset('assets/images/hero/about-bg.jpg') }}" alt="About Coopera Trading" class="w-full h-full object-cover transform scale-105 opacity-60">
    </div>

    <!-- Dual-Tone Dark Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-indigo-950/80 z-10 pointer-events-none"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-900/70 to-slate-950 z-10 pointer-events-none"></div>

    <!-- Content Container -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 w-full">
        <div class="max-w-3xl space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 text-emerald-400 border border-white/20 backdrop-blur-md shadow-lg shadow-black/20 uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                About Coopera Trading
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white [text-shadow:_0_4px_24px_rgba(0,0,0,0.9)]">
                Trading that builds industries.
            </h1>
            <p class="text-lg lg:text-xl text-slate-200 leading-relaxed max-w-2xl [text-shadow:_0_2px_12px_rgba(0,0,0,0.85)]">
                Coopera Trading is a B2B trading company supplying construction, industrial, water, packaging, technology, and export products across Ethiopia and the wider region. We connect manufacturers and buyers with the products, documentation, and logistics they need to grow.
            </p>
        </div>
    </div>
</section>

<section class="py-20 lg:py-24 bg-coopera-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Our mission</h2>
                <p class="mt-5 text-slate-600 leading-relaxed">
                    To be the most reliable supply partner for industrial and construction buyers in Ethiopia — delivering the right product, at the right price, on time, with the documentation needed to keep projects moving.
                </p>
                <h2 class="mt-12 text-3xl font-bold text-slate-900">What we do</h2>
                <p class="mt-5 text-slate-600 leading-relaxed">
                    We source, supply, and deliver specialty products across six markets. Our team manages supplier qualification, product specification, pricing, proforma invoicing, and logistics — so our customers can focus on their own operations.
                </p>
            </div>
            <div x-data="countUpStats" class="grid grid-cols-2 gap-6">
                <div class="bg-white border border-slate-200/80 shadow-sm rounded-xl p-6">
                    <div class="text-4xl font-bold text-slate-900" x-text="values[0]">6</div>
                    <div class="mt-1 text-sm text-slate-600">Core markets served</div>
                </div>
                <div class="bg-white border border-slate-200/80 shadow-sm rounded-xl p-6">
                    <div class="text-4xl font-bold text-slate-900"><span x-text="values[1]">35</span>+</div>
                    <div class="mt-1 text-sm text-slate-600">Product categories</div>
                </div>
                <div class="bg-white border border-slate-200/80 shadow-sm rounded-xl p-6">
                    <div class="text-4xl font-bold text-slate-900"><span x-text="values[2]">24</span>h</div>
                    <div class="mt-1 text-sm text-slate-600">Proforma response time</div>
                </div>
                <div class="bg-white border border-slate-200/80 shadow-sm rounded-xl p-6">
                    <div class="text-4xl font-bold text-slate-900"><span x-text="values[3]">15</span>%</div>
                    <div class="mt-1 text-sm text-slate-600">VAT-compliant invoicing</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 lg:py-24 bg-coopera-cream-warm/50 border-t border-amber-900/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Our values</h2>
            <p class="mt-4 text-lg text-slate-600">The principles that guide every transaction.</p>
        </div>
        <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ([
                ['Reliability', 'We deliver on our commitments. Every shipment, every invoice, every timeline.'],
                ['Transparency', 'Clear pricing, clear documentation, and clear communication throughout every transaction.'],
                ['Quality', 'Every product is sourced from qualified manufacturers and meets applicable standards.']
            ] as [$title, $body])
                <div class="bg-white border border-slate-200 rounded-xl p-8">
                    <h3 class="text-xl font-semibold text-slate-900">{{ $title }}</h3>
                    <p class="mt-3 text-slate-600 leading-relaxed">{{ $body }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
