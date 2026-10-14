@extends('layouts.admin')

@section('title', 'Edit Category')

@section('header', 'Edit Category')
@section('subheader', $category->name)

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <form id="edit-category-form" action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('slug')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Category Image</label>
                    <div class="mt-2 mb-3 flex items-center gap-4">
                        <div class="w-32 h-20 bg-slate-100 rounded-lg overflow-hidden border border-gray-200 shrink-0">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="text-xs text-gray-500">
                            @if ($category->image)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-50 text-green-700 font-medium border border-green-200">Custom
                                    Uploaded Image</span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium">Default
                                    / Static Image</span>
                            @endif
                            <p class="mt-1">Upload a new image below to replace it.</p>
                        </div>
                    </div>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep the current image. Max 5MB.</p>
                    @error('image')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">Display Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (0, 1, 2...).</p>
                        @error('sort_order') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="relative flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-4 w-4">
                            <span class="ml-2 text-sm font-medium text-gray-900">Visible on public site</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" form="delete-category-form"
                        class="px-4 py-2 rounded-md text-sm text-red-700 hover:bg-red-50">Delete</button>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.categories.index') }}"
                            class="px-4 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Cancel</a>
                        <button type="submit" form="edit-category-form"
                            class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Save
                            Changes</button>
                    </div>
                </div>
            </form>

            <form id="delete-category-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                class="hidden" onsubmit="return confirm('Delete this category? Products will also be deleted.');">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection