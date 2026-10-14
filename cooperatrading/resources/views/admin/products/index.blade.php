@extends('layouts.admin')

@section('title', 'Products')

@section('header', 'Products')
@section('subheader', 'Manage your product catalog')

@section('actions')
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
        + New Product
    </a>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Filter & Sort Toolbar -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-wrap items-center justify-between gap-4 text-sm shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <!-- Category Filter -->
            <div class="flex items-center gap-2">
                <label for="category_id" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Category:</label>
                <select name="category_id" id="category_id" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs py-1.5 pl-2.5 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" @selected(!request('category_id'))>All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="flex items-center gap-2">
                <label for="status" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs py-1.5 pl-2.5 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" @selected(!request('status'))>All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active (Visible)</option>
                    <option value="hidden" @selected(request('status') === 'hidden')>Hidden from Public</option>
                </select>
            </div>

            <!-- Sort By -->
            <div class="flex items-center gap-2">
                <label for="sort" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sort by:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs py-1.5 pl-2.5 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="category" @selected(!request('sort') || request('sort') === 'category')>Group by Category</option>
                    <option value="name_asc" @selected(request('sort') === 'name_asc')>Name (A to Z)</option>
                    <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z to A)</option>
                    <option value="newest" @selected(request('sort') === 'newest')>Newest First</option>
                    <option value="status" @selected(request('sort') === 'status')>Status (Active First)</option>
                </select>
            </div>

            <!-- Search -->
            <div class="relative flex-1 sm:w-48">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                    class="rounded-md border-gray-300 text-xs py-1.5 pl-8 pr-3 w-full focus:ring-indigo-500 focus:border-indigo-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            @if(request()->anyFilled(['category_id', 'status', 'sort', 'search']))
                <a href="{{ route('admin.products.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium py-1.5">Reset</a>
            @endif
        </form>

        <div class="text-xs text-gray-500 font-medium ml-auto">
            Showing {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">UoM</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr class="{{ $product->is_active ? 'hover:bg-gray-50/60' : 'bg-gray-50/40 text-gray-500' }}">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0" style="width: 48px; height: 48px; min-width: 48px; max-width: 48px; max-height: 48px;">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover {{ $product->is_active ? '' : 'grayscale opacity-60' }}" style="width: 48px; height: 48px; object-fit: cover;">
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium {{ $product->is_active ? 'text-gray-900' : 'text-gray-500' }}">{{ $product->name }}</div>
                                        <div class="text-gray-400 text-xs font-mono truncate">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if ($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Hidden
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center font-medium text-gray-600 text-xs uppercase">{{ $product->unit_of_measure }}</td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <!-- 1-Click Hide / Show Toggle -->
                                <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST" class="inline-block mr-2">
                                    @csrf
                                    @if ($product->is_active)
                                        <button type="submit" title="Hide this product from public display" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 transition">
                                            Hide
                                        </button>
                                    @else
                                        <button type="submit" title="Show this product on public site" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 transition">
                                            Show
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold mr-2">Edit</a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this product completely? Tip: You can Hide it instead.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-500">No products found matching your filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
