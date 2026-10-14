@extends('layouts.admin')

@section('title', 'Categories')

@section('header', 'Categories')
@section('subheader', 'Organize products into categories')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
        + New Category
    </a>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Filter & Sort Toolbar -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-wrap items-center justify-between gap-3 text-sm">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="flex items-center gap-2">
                <label for="status" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs py-1.5 pl-2.5 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" @selected(!request('status'))>All Categories</option>
                    <option value="active" @selected(request('status') === 'active')>Active (Visible)</option>
                    <option value="hidden" @selected(request('status') === 'hidden')>Hidden from Public</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label for="sort" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sort by:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs py-1.5 pl-2.5 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="sort_order" @selected(request('sort') === 'sort_order')>Display Order (Default)</option>
                    <option value="name_asc" @selected(request('sort') === 'name_asc')>Name (A to Z)</option>
                    <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z to A)</option>
                    <option value="products_count" @selected(request('sort') === 'products_count')>Most Products</option>
                </select>
            </div>
        </form>

        <div class="text-xs text-gray-500 font-medium">
            Showing {{ $categories->total() }} {{ Str::plural('category', $categories->total()) }}
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Image</th>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Slug</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Products</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr class="{{ $category->is_active ? 'hover:bg-gray-50/60' : 'bg-gray-50/40 text-gray-500' }}">
                            <td class="px-5 py-3">
                                <div class="w-12 h-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0" style="width: 48px; height: 40px; min-width: 48px; max-width: 48px; max-height: 40px;">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover {{ $category->is_active ? '' : 'grayscale opacity-60' }}" style="width: 48px; height: 40px; object-fit: cover;">
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="font-medium {{ $category->is_active ? 'text-gray-900' : 'text-gray-500' }}">{{ $category->name }}</div>
                                @if ($category->description)
                                    <div class="text-gray-500 text-xs mt-0.5 line-clamp-1">{{ $category->description }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($category->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Hidden
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center font-medium text-gray-600">{{ $category->products_count }}</td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <form action="{{ route('admin.categories.toggle-active', $category) }}" method="POST" class="inline-block mr-2">
                                    @csrf
                                    @if ($category->is_active)
                                        <button type="submit" title="Hide this category from public display" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 transition">
                                            Hide
                                        </button>
                                    @else
                                        <button type="submit" title="Show this category on public site" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 transition">
                                            Show
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold mr-2">Edit</a>

                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category completely? Tip: You can Hide it instead.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-500">No categories found matching your filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
