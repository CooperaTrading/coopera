@extends('layouts.app')

@section('title', 'Contact Us')
@section('description', 'Get in touch with Coopera Trading at Ethio-Best Building, Addis Ababa. Inquire about industrial chemicals, construction materials, and export supplies.')

@section('content')
    <!-- HERO HEADER -->
    <section class="relative bg-slate-950 text-white min-h-[380px] lg:min-h-[440px] flex items-center overflow-hidden">
        <!-- Banner Image Background Layer -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <img src="{{ asset('assets/images/hero/contact-bg.jpg') }}" alt="Contact Coopera Trading Headquarters"
                class="w-full h-full object-cover transform scale-105 opacity-60">
        </div>

        <!-- Dual-Tone Dark Gradient Overlay -->
        <div
            class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-indigo-950/80 z-10 pointer-events-none">
        </div>
        <div
            class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-900/70 to-slate-950 z-10 pointer-events-none">
        </div>

        <!-- Content Container -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 w-full">
            <div class="max-w-3xl space-y-4">
                <div
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 text-emerald-400 border border-white/20 backdrop-blur-md shadow-lg shadow-black/20 uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Official Contact & Headquarters
                </div>
                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white [text-shadow:_0_4px_24px_rgba(0,0,0,0.9)]">
                    Let's talk business.
                </h1>
                <p
                    class="text-lg lg:text-xl text-slate-200 leading-relaxed max-w-2xl [text-shadow:_0_2px_12px_rgba(0,0,0,0.85)]">
                    Have a project, tender, or recurring supply requirement? Connect directly with our trading specialists
                    at our Addis Ababa headquarters or via our official channels.
                </p>
            </div>
        </div>
    </section>

    <!-- MAIN CONTACT FORM & INFO SECTION -->
    <section class="py-20 lg:py-24 bg-coopera-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-14">

                <!-- LEFT: CONTACT FORM (2 COLS) -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-10 shadow-sm">
                    <div class="border-b border-slate-100 pb-6 mb-8">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Direct Inquiries</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Send us a message</h2>
                        <p class="mt-2 text-slate-600 text-sm sm:text-base">For instant item pricing and official VAT
                            invoices, you can also <a href="{{ route('quote.create') }}"
                                class="text-amber-700 font-semibold hover:underline">request a proforma quote</a>.</p>
                    </div>

                    @if (session('contact_status'))
                        <div
                            class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('contact_status') }}</span>
                        </div>
                    @endif

                    @if ($errors->any() && !session('contact_status'))
                        <div
                            class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Please correct the errors in the form below and try again.</span>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700">Full name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    placeholder="Abebe Kebede"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 text-sm px-4 py-2.5">
                                @error('name')
                                <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="company" class="block text-sm font-semibold text-slate-700">Company /
                                    Organization</label>
                                <input type="text" name="company" id="company" value="{{ old('company') }}"
                                    placeholder="Your Company PLC"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 text-sm px-4 py-2.5">
                                @error('company')
                                <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700">Email address <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    placeholder="you@domain.com"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 text-sm px-4 py-2.5">
                                @error('email')
                                <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700">Phone number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+251 9..."
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 text-sm px-4 py-2.5">
                                @error('phone')
                                <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-semibold text-slate-700">Message / Request
                                details <span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="5" required
                                placeholder="Tell us about the products, quantities, specifications, or logistics required..."
                                class="mt-1.5 block w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 text-sm px-4 py-2.5">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-lg bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-all shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                                Send message
                            </button>
                            <span class="text-xs text-slate-500 hidden sm:inline">Typical response within 24 hours</span>
                        </div>
                    </form>
                </div>

                <!-- RIGHT: COMPANY CONTACT CARDS (1 COL) -->
                <div class="space-y-6">

                    <!-- ADDRESS CARD -->
                    <div
                        class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm hover:border-amber-600/40 transition-colors">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200/60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Office Headquarters
                                </h3>
                                <p class="mt-1.5 text-sm font-bold text-slate-900 leading-snug">
                                    Ethio-Best Building, 3rd Floor, Office #004
                                </p>
                                <p class="text-xs text-slate-600 mt-0.5">
                                    Kirkos Sub-City, Woreda 02 | Addis Ababa, Ethiopia
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- PHONE & WHATSAPP CARD -->
                    <div
                        class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm hover:border-amber-600/40 transition-colors">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200/60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="w-full">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Direct Lines &
                                    WhatsApp</h3>

                                <div class="mt-3 space-y-3">
                                    <!-- Phone 1 -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div>
                                            <a href="tel:+251959440446"
                                                class="text-sm font-bold text-slate-900 hover:text-amber-700 transition">
                                                +251 959 440 446
                                            </a>
                                            <span class="block text-[11px] text-slate-500">Sales & Trade Desk</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <a href="tel:+251959440446"
                                                class="p-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                                title="Call">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </a>
                                            <a href="https://wa.me/251959440446" target="_blank" rel="noopener noreferrer"
                                                class="p-1.5 rounded-md bg-emerald-100 hover:bg-emerald-200 text-emerald-700 transition"
                                                title="WhatsApp Chat">
                                                <span class="text-[11px] font-bold">WA</span>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Phone 2 -->
                                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100">
                                        <div>
                                            <a href="tel:+251973397012"
                                                class="text-sm font-bold text-slate-900 hover:text-amber-700 transition">
                                                +251 97 339 7012
                                            </a>
                                            <span class="block text-[11px] text-slate-500">Customer Support</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <a href="tel:+251973397012"
                                                class="p-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                                title="Call">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </a>
                                            <a href="https://wa.me/251973397012" target="_blank" rel="noopener noreferrer"
                                                class="p-1.5 rounded-md bg-emerald-100 hover:bg-emerald-200 text-emerald-700 transition"
                                                title="WhatsApp Chat">
                                                <span class="text-[11px] font-bold">WA</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EMAIL & WEB CARD -->
                    <div
                        class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm hover:border-amber-600/40 transition-colors">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-200/60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="space-y-3 w-full">
                                <div>
                                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Official Email
                                    </h3>
                                    <a href="mailto:info@cooperatrading.com"
                                        class="mt-1 block text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                                        info@cooperatrading.com
                                    </a>
                                </div>
                                <div class="pt-2 border-t border-slate-100">
                                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Web Portal</h3>
                                    <a href="https://www.cooperatrading.com" target="_blank" rel="noopener noreferrer"
                                        class="mt-1 inline-flex items-center gap-1 text-sm font-bold text-slate-900 hover:text-amber-700 transition">
                                        www.cooperatrading.com
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BUSINESS HOURS CARD -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 border border-purple-200/60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Operating Hours</h3>
                                <div class="mt-1.5 text-xs text-slate-700 space-y-1">
                                    <p><span class="font-bold text-slate-900">Monday – Friday:</span> 8:30 AM – 6:00 PM
                                        (EAT)</p>
                                    <p><span class="font-bold text-slate-900">Saturday:</span> 9:00 AM – 1:00 PM (EAT)</p>
                                    <p class="text-slate-500">Sunday & Public Holidays: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- DEDICATED SOCIAL MEDIA HUB -->
    <section class="py-16 lg:py-20 bg-slate-900 text-white relative overflow-hidden border-y border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span
                    class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 text-amber-400 text-xs font-bold uppercase tracking-wider mb-3 border border-amber-500/20">
                    🌐 Connect & Follow Us
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Official Social Media Channels
                </h2>
                <p class="mt-3 text-slate-400 text-sm sm:text-base">
                    Join our trading community, get live market alerts, product releases, and industrial updates across our
                    platforms.
                </p>
            </div>

            <!-- 6 SOCIAL MEDIA SPACES GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 1. TELEGRAM CHANNEL -->
                <a href="https://t.me/coopera_trading" target="_blank" rel="noopener noreferrer"
                    class="group block bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-[#229ED9]/60 rounded-2xl p-6 transition-all shadow-md hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div
                            class="w-12 h-12 rounded-xl bg-[#229ED9]/20 text-[#229ED9] flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#229ED9]/20 text-[#229ED9] border border-[#229ED9]/30 uppercase tracking-wider">
                            Channel
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-base font-bold text-white group-hover:text-[#229ED9] transition-colors">Telegram
                            Channel</h3>
                        <p class="text-xs text-slate-400 mt-1">Official announcements & market offers</p>
                        <div
                            class="mt-4 flex items-center justify-between text-xs font-semibold text-slate-300 group-hover:text-white">
                            <span>@coopera_trading</span>
                            <span
                                class="text-[#229ED9] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                Join Channel &rarr;
                            </span>
                        </div>
                    </div>
                </a>

                <!-- 2. TELEGRAM GROUP -->
                <a href="https://t.me/coopera_trading1" target="_blank" rel="noopener noreferrer"
                    class="group block bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-cyan-400/60 rounded-2xl p-6 transition-all shadow-md hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div
                            class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 uppercase tracking-wider">
                            Community
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-base font-bold text-white group-hover:text-cyan-400 transition-colors">Telegram
                            Group</h3>
                        <p class="text-xs text-slate-400 mt-1">Interactive community & supplier inquiries</p>
                        <div
                            class="mt-4 flex items-center justify-between text-xs font-semibold text-slate-300 group-hover:text-white">
                            <span>@coopera_trading1</span>
                            <span
                                class="text-cyan-400 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                Join Group &rarr;
                            </span>
                        </div>
                    </div>
                </a>

                <!-- 3. FACEBOOK page -->
                <a href="https://www.facebook.com/profile.php?id=61594968556891" target="_blank" rel="noopener noreferrer"
                    class="group block bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-[#1877F2]/60 rounded-2xl p-6 transition-all shadow-md hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div
                            class="w-12 h-12 rounded-xl bg-[#1877F2]/20 text-[#1877F2] flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#1877F2]/20 text-[#1877F2] border border-[#1877F2]/30 uppercase tracking-wider">
                            Facebook Page
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-base font-bold text-white group-hover:text-[#1877F2] transition-colors">Facebook
                            Page</h3>
                        <p class="text-xs text-slate-400 mt-1">Company news, product updates & events</p>
                        <div
                            class="mt-4 flex items-center justify-between text-xs font-semibold text-slate-300 group-hover:text-white">
                            <span>@coopera_trading</span>
                            <span
                                class="text-[#1877F2] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                Follow Page &rarr;
                            </span>
                        </div>
                    </div>
                </a>

                <!-- 4. FACEBOOK -->
                <a href="https://facebook.com/profile.php?id=61595012144629" target="_blank" rel="noopener noreferrer"
                    class="group block bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-[#1877F2]/60 rounded-2xl p-6 transition-all shadow-md hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div
                            class="w-12 h-12 rounded-xl bg-[#1877F2]/20 text-[#1877F2] flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#1877F2]/20 text-[#1877F2] border border-[#1877F2]/30 uppercase tracking-wider">
                            Facebook
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-base font-bold text-white group-hover:text-[#1877F2] transition-colors">Facebook
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Company news, product updates & events</p>
                        <div
                            class="mt-4 flex items-center justify-between text-xs font-semibold text-slate-300 group-hover:text-white">
                            <span>@coopera_trading</span>
                            <span
                                class="text-[#1877F2] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                Follow our Page &rarr;
                            </span>
                        </div>
                    </div>
                </a>

                <!-- 5. INSTAGRAM -->
                <a href="https://www.instagram.com/coopera_trading" target="_blank" rel="noopener noreferrer"
                    class="group block bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-[#E4405F]/60 rounded-2xl p-6 transition-all shadow-md hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div
                            class="w-12 h-12 rounded-xl bg-[#E4405F]/20 text-[#E4405F] flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#E4405F]/20 text-[#E4405F] border border-[#E4405F]/30 uppercase tracking-wider">
                            Instagram
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-base font-bold text-white group-hover:text-[#E4405F] transition-colors">Instagram
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Product visuals, warehouse & shipment highlights</p>
                        <div
                            class="mt-4 flex items-center justify-between text-xs font-semibold text-slate-300 group-hover:text-white">
                            <span>@coopera_trading</span>
                            <span
                                class="text-[#E4405F] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                Follow @coopera &rarr;
                            </span>
                        </div>
                    </div>
                </a>

                <!-- 6. TIKTOK -->
                <a href="https://www.tiktok.com/@coopera.trading?_r=1" target="_blank" rel="noopener noreferrer"
                    class="group block bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-rose-400/60 rounded-2xl p-6 transition-all shadow-md hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div
                            class="w-12 h-12 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-pink-500/20 text-pink-400 border border-pink-500/30 uppercase tracking-wider">
                            TikTok
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-base font-bold text-white group-hover:text-pink-400 transition-colors">TikTok</h3>
                        <p class="text-xs text-slate-400 mt-1">Short clips, product showcases & insights</p>
                        <div
                            class="mt-4 flex items-center justify-between text-xs font-semibold text-slate-300 group-hover:text-white">
                            <span>@coopera_trading</span>
                            <span
                                class="text-pink-400 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                Watch & Follow &rarr;
                            </span>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- VISIT HEADQUARTERS SECTION WITH PHOTO SHOWCASE & MAP -->
    <section class="bg-coopera-cream-warm/60 py-16 lg:py-24 border-t border-amber-900/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-3xl mb-12">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Find Our Headquarters</span>
                <h2 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900">Visit our
                    office in Addis Ababa</h2>
                <p class="mt-3 text-slate-600 text-sm sm:text-base">
                    Stop by our commercial offices at Ethio-Best Building. Our trading managers and proforma specialists are
                    available in person for contract negotiations and supply consultations.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

                <!-- OFFICE PHOTO & LOCATION DETAILS CARD (5 COLS) -->
                <div
                    class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Headquarters Photo -->
                        <div class="relative h-64 sm:h-72 w-full overflow-hidden bg-slate-100">
                            <img src="{{ asset('assets/images/office-hq.jpg') }}"
                                alt="Coopera Trading Headquarters at Ethio-Best Building"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent">
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-amber-500 text-slate-950 text-xs font-bold uppercase tracking-wider shadow">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Commercial Hub
                                </span>
                                <h3 class="text-white font-extrabold text-lg mt-1">Ethio-Best Building</h3>
                            </div>
                        </div>

                        <!-- Details Box -->
                        <div class="p-6 space-y-4">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200/60 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-500 uppercase">Exact Office Unit</div>
                                    <div class="text-sm font-bold text-slate-900">3rd Floor, Office #004</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200/60 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-500 uppercase">Zone & Municipality</div>
                                    <div class="text-sm font-bold text-slate-900">Kirkos Sub-City, Woreda 02</div>
                                    <div class="text-xs text-slate-600">Addis Ababa, Ethiopia</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <a href="https://maps.google.com/?q=Ethio-Best+Building+Kirkos+Addis+Ababa" target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 text-xs font-bold text-amber-700 hover:text-amber-800 transition">
                            Open in Google Maps &rarr;
                        </a>
                        <span class="text-[11px] text-slate-500">Parking available on site</span>
                    </div>
                </div>

                <!-- GOOGLE MAPS EMBED (7 COLS) -->
                <div
                    class="lg:col-span-7 rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-white min-h-[380px] lg:min-h-[420px]">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14083.823984724197!2d38.77901526787768!3d8.993393721377963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85005161ac83%3A0x6ce7fbec7937ba84!2sCoopera%20Trading!5e0!3m2!1sen!2set!4v1785138716825!5m2!1sen!2set"
                        width="100%" height="100%" style="border:0; min-height: 400px;" allowfullscreen="" loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin" title="Coopera Trading Location Map">
                    </iframe>
                </div>

            </div>
        </div>
    </section>
@endsection