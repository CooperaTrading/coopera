@php
    $isEdit = isset($slide) && $slide;
    $action = $isEdit ? route('admin.hero-slides.update', $slide) : route('admin.hero-slides.store');
    $method = $isEdit ? 'PUT' : 'POST';
    $titleValue = old('title', $isEdit ? $slide->title : '');
    $badgeValue = old('badge', $isEdit ? $slide->badge : '');
    $descriptionValue = old('description', $isEdit ? $slide->description : '');
    $imageAltValue = old('image_alt', $isEdit ? $slide->image_alt : '');
    $ctaLabelValue = old('cta_label', $isEdit ? $slide->cta_label : '');
    $ctaUrlValue = old('cta_url', $isEdit ? $slide->cta_url : '');
    $tagsValue = old('tags', $isEdit ? (is_array($slide->tags) ? implode(', ', $slide->tags) : '') : '');
    $sortValue = old('sort_order', $isEdit ? $slide->sort_order : 0);
    $activeValue = (bool) old('is_active', $isEdit ? $slide->is_active : true);
@endphp

<form id="hero-slide-form" action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    {{-- Title --}}
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">
            Slide Title <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title" id="title" value="{{ $titleValue }}" required maxlength="255"
            placeholder="e.g. Construction chemicals & high-performance systems."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <p class="text-xs text-gray-500 mt-1">The main headline shown on top of the slide.</p>
        @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Badge --}}
    <div>
        <label for="badge" class="block text-sm font-medium text-gray-700">Category Badge</label>
        <input type="text" name="badge" id="badge" value="{{ $badgeValue }}" maxlength="120"
            placeholder="e.g. Construction Product Markets"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <p class="text-xs text-gray-500 mt-1">Short label that appears above the title in a pill.</p>
        @error('badge') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="3" maxlength="1000"
            placeholder="A short paragraph explaining the slide topic..."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $descriptionValue }}</textarea>
        @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Image --}}
    <div>
        <label for="image" class="block text-sm font-medium text-gray-700">
            Background Image @if(!$isEdit)<span class="text-red-500">*</span>@endif
        </label>
        @if($isEdit && $slide->image)
            <div class="mt-2 mb-3 flex items-center gap-4">
                <div class="w-40 h-20 rounded-lg overflow-hidden border border-gray-200 bg-slate-900 shrink-0">
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->image_alt ?? $slide->title }}" class="w-full h-full object-cover">
                </div>
                <div class="text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-50 text-green-700 font-medium border border-green-200">Current image</span>
                    <p class="mt-1">Upload a new image below to replace it.</p>
                </div>
            </div>
        @endif
        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp"
            @if(!$isEdit) required @endif
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        <p class="text-xs text-gray-500 mt-1">Recommended 1920×1080 or larger. JPG, PNG or WEBP, max 10MB.</p>
        @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Alt text --}}
    <div>
        <label for="image_alt" class="block text-sm font-medium text-gray-700">Image Alt Text (SEO)</label>
        <input type="text" name="image_alt" id="image_alt" value="{{ $imageAltValue }}" maxlength="255"
            placeholder="Describe the image for screen readers and search engines"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('image_alt') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- CTA --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
        <div>
            <label for="cta_label" class="block text-sm font-medium text-gray-700">CTA Button Label</label>
            <input type="text" name="cta_label" id="cta_label" value="{{ $ctaLabelValue }}" maxlength="80"
                placeholder="e.g. Explore Construction"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('cta_label') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="cta_url" class="block text-sm font-medium text-gray-700">CTA Button URL</label>
            <input type="text" name="cta_url" id="cta_url" value="{{ $ctaUrlValue }}" maxlength="2048"
                placeholder="/products/construction-product-markets"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <p class="text-xs text-gray-500 mt-1">Internal path (e.g. <code>/products/...</code>) or full https URL.</p>
            @error('cta_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Tags --}}
    <div>
        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
        <input type="text" name="tags" id="tags" value="{{ $tagsValue }}" maxlength="2000"
            placeholder="Concrete, Waterproofing, Epoxies, Grouting"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <p class="text-xs text-gray-500 mt-1">Comma-separated. Displayed as small pills under the title.</p>
        @error('tags') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Sort + Active --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700">Display Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ $sortValue }}" min="0"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first in the slider. You can also drag to reorder on the list page.</p>
            @error('sort_order') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center pt-6">
            <label class="relative flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" @checked($activeValue)
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-4 w-4">
                <span class="ml-2 text-sm font-medium text-gray-900">Visible on public home page</span>
            </label>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
        <a href="{{ route('admin.hero-slides.index') }}" class="px-4 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Cancel</a>
        <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
            {{ $isEdit ? 'Save Changes' : 'Create Slide' }}
        </button>
    </div>
</form>
