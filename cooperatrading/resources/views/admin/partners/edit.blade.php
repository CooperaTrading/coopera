@extends('layouts.admin')

@section('title', 'Edit Partner')

@section('header', 'Edit Partner')
@section('subheader', $partner->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        <form id="edit-partner-form" action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="url" class="block text-sm font-medium text-gray-700">Company Website URL</label>
                <input type="url" name="url" id="url" value="{{ old('url', $partner->url) }}" placeholder="https://www.example.com"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">Clicking the logo on the home page will redirect users to this URL.</p>
                @error('url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700">Company Logo</label>
                <div class="mt-2 mb-3 flex items-center gap-4">
                    <div class="w-28 h-14 bg-slate-50 rounded-lg overflow-hidden border border-gray-200 shrink-0 p-2 flex items-center justify-center">
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain">
                    </div>
                    <div class="text-xs text-gray-500">
                        @if ($partner->logo)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-50 text-green-700 font-medium border border-green-200">Custom Uploaded Logo</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium">Default / Built-in Logo</span>
                        @endif
                        <p class="mt-1">Upload a new image below to replace the logo.</p>
                    </div>
                </div>
                <input type="file" name="logo" id="logo" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Leave empty to keep the current logo. Max 5MB.</p>
                @error('logo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Display Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $partner->sort_order ?? 0) }}" min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first in the marquee (0, 1, 2...).</p>
                    @error('sort_order') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center pt-6">
                    <label class="relative flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $partner->is_active))
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-4 w-4">
                        <span class="ml-2 text-sm font-medium text-gray-900">Visible on public site</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-200">
                <button type="submit" form="delete-partner-form" class="px-4 py-2 rounded-md text-sm text-red-700 hover:bg-red-50">Delete</button>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.partners.index') }}" class="px-4 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Cancel</a>
                    <button type="submit" form="edit-partner-form" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Save Changes</button>
                </div>
            </div>
        </form>

        <form id="delete-partner-form" action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="hidden" onsubmit="return confirm('Delete this partner? Tip: You can Hide it instead.');">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
