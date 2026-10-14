@extends('layouts.app')

@section('title', $product->name)
@section('description', $product->description)

@section('content')
<section class="bg-coopera-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <nav class="text-sm text-slate-500 mb-6">
            <a href="{{ route('products') }}" class="hover:text-slate-900">Products</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products.category', $category) }}" class="hover:text-slate-900">{{ $category->name }}</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="aspect-[4/3] w-full bg-slate-100 rounded-2xl flex items-center justify-center overflow-hidden shadow-sm">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            <div>
                <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">{{ $category->name }}</div>
                <h1 class="mt-3 text-3xl lg:text-4xl font-bold text-slate-900">{{ $product->name }}</h1>
                <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm">
                    Unit: {{ $product->unit_of_measure }}
                </div>
                <div class="mt-8 prose prose-slate max-w-none">
                    <p class="text-slate-600 leading-relaxed">{{ $product->description }}</p>
                </div>
                <div class="mt-10 flex flex-wrap gap-3">
                    <a href="{{ route('quote.create') }}?products[]={{ $product->id }}" class="inline-flex items-center px-6 py-3 rounded-md bg-slate-900 text-white font-medium hover:bg-slate-800 transition">
                        Request a quote for this product
                    </a>
                    <a href="{{ route('products.category', $category) }}" class="inline-flex items-center px-6 py-3 rounded-md border border-slate-300 text-slate-700 font-medium hover:bg-slate-50">
                        ← Back to {{ $category->name }}
                    </a>
                </div>
                <div class="mt-10 grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <div class="text-slate-500">Category</div>
                        <div class="mt-1 font-medium text-slate-900">{{ $category->name }}</div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <div class="text-slate-500">Unit of Measure</div>
                        <div class="mt-1 font-medium text-slate-900">{{ $product->unit_of_measure }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($related->count())
<section class="py-16 lg:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-slate-900">Related products</h2>
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($related as $item)
                <a href="{{ route('products.show', [$category, $item]) }}" class="group block bg-white border border-slate-200 rounded-xl overflow-hidden hover:border-slate-900 hover:shadow-lg transition">
                    <div class="aspect-square bg-slate-100 flex items-center justify-center overflow-hidden">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-slate-900 line-clamp-1">{{ $item->name }}</h3>
                        <p class="mt-1 text-sm text-slate-600 line-clamp-2">{{ $item->description }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
