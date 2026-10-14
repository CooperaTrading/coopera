@extends('layouts.app')

@section('title', 'Thank You')
@section('description', 'Thank you for your quote request.')

@section('content')
<section class="py-24 lg:py-32 bg-coopera-cream">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="mt-6 text-3xl lg:text-4xl font-bold text-slate-900">Thank you!</h1>
        <p class="mt-4 text-lg text-slate-600">
            Your quote request has been received. Our team will prepare a proforma invoice and email it to you within 24 hours.
        </p>
        <p class="mt-2 text-sm text-slate-500">
            Reference number: <span class="font-mono text-slate-700">#{{ sprintf('%05d', \App\Models\QuoteRequest::max('id') ?? 0) }}</span>
        </p>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('products') }}" class="inline-flex items-center px-6 py-3 rounded-md bg-slate-900 text-white font-medium hover:bg-slate-800">
                Browse more products
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 rounded-md border border-slate-300 text-slate-700 font-medium hover:bg-slate-50">
                Back to home
            </a>
        </div>
    </div>
</section>
@endsection
