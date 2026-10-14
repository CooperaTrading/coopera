@extends('layouts.admin')

@section('title', 'Hero Slides')

@section('header', 'Hero Slides')
@section('subheader', 'Manage the home page hero carousel — upload, reorder, edit, and toggle slides')

@section('actions')
    <a href="{{ route('admin.hero-slides.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
        + New Slide
    </a>
@endsection

@section('content')
<div x-data="heroSlidesManager({
    saveUrl: '{{ route('admin.hero-slides.reorder') }}',
    csrf: '{{ csrf_token() }}'
})" class="space-y-4">

    {{-- Filter & Sort Toolbar --}}
    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-wrap items-center justify-between gap-4 text-sm shadow-sm">
        <form method="GET" action="{{ route('admin.hero-slides.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="flex items-center gap-2">
                <label for="status" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs py-1.5 pl-2.5 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" @selected(!request('status'))>All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active (Visible)</option>
                    <option value="hidden" @selected(request('status') === 'hidden')>Hidden from Public</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label for="sort" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sort by:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 text-xs py-1.5 pl-2.5 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="sort_order" @selected(request('sort') === 'sort_order' || !request('sort'))>Display Order (Default)</option>
                    <option value="newest" @selected(request('sort') === 'newest')>Newest First</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                </select>
            </div>

            @if(request()->anyFilled(['status', 'sort']))
                <a href="{{ route('admin.hero-slides.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium py-1.5">Reset</a>
            @endif
        </form>

        <div class="text-xs text-gray-500 font-medium ml-auto">
            Showing {{ $slides->total() }} {{ Str::plural('slide', $slides->total()) }}
            <span x-show="dirty" x-cloak class="ml-2 inline-flex items-center gap-1 text-amber-700">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                Unsaved reorder
            </span>
            <span x-show="saving" x-cloak class="ml-2 inline-flex items-center gap-1 text-indigo-600">
                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Saving…
            </span>
            <span x-show="lastSavedAt && !dirty && !saving" x-cloak class="ml-2 inline-flex items-center gap-1 text-emerald-700">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Order saved
            </span>
        </div>
    </div>

    {{-- Action bar --}}
    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-wrap items-center justify-between gap-3 text-sm shadow-sm">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Drag rows by the handle, or use the up/down arrows. Reordering auto-saves.
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="resetOrder()" :disabled="!dirty || saving"
                class="px-3 py-1.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                Undo Changes
            </button>
            <button type="button" @click="saveOrder()" :disabled="!dirty || saving"
                class="px-3 py-1.5 rounded-md text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                Save Order
            </button>
        </div>
    </div>

    {{-- Slides list --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
        @if($slides->count() === 0)
            <div class="px-5 py-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                No hero slides found. Click <span class="font-semibold">+ New Slide</span> to add one.
            </div>
        @else
            <ul class="divide-y divide-gray-100" x-ref="list">
                @foreach($slides as $slide)
                    <li class="flex items-stretch gap-3 px-4 py-4 {{ $slide->is_active ? 'bg-white' : 'bg-gray-50/60' }}"
                        data-id="{{ $slide->id }}"
                        draggable="true"
                        @dragstart="onDragStart($event, {{ $slide->id }})"
                        @dragover.prevent="onDragOver($event)"
                        @drop.prevent="onDrop($event, {{ $slide->id }})"
                        @dragend="onDragEnd($event)"
                        :class="draggingId === {{ $slide->id }} ? 'opacity-40' : ''">

                        {{-- Drag handle --}}
                        <div class="flex flex-col items-center justify-center gap-1 text-gray-400 cursor-grab active:cursor-grabbing select-none w-6 shrink-0" title="Drag to reorder">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><circle cx="7" cy="5" r="1.5"/><circle cx="13" cy="5" r="1.5"/><circle cx="7" cy="10" r="1.5"/><circle cx="13" cy="10" r="1.5"/><circle cx="7" cy="15" r="1.5"/><circle cx="13" cy="15" r="1.5"/></svg>
                        </div>

                        {{-- Order arrows --}}
                        <div class="flex flex-col gap-0.5 shrink-0">
                            <button type="button" @click="moveUp({{ $slide->id }})" :disabled="saving || {{ $loop->first ? 'true' : 'false' }}"
                                title="Move up"
                                class="p-1 rounded text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" @click="moveDown({{ $slide->id }})" :disabled="saving || {{ $loop->last ? 'true' : 'false' }}"
                                title="Move down"
                                class="p-1 rounded text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>

                        {{-- Thumbnail --}}
                        <div class="w-32 sm:w-40 aspect-video rounded-md overflow-hidden border border-gray-200 bg-slate-900 shrink-0 relative">
                            <img src="{{ $slide->image_url }}" alt="{{ $slide->image_alt ?? $slide->title }}" class="w-full h-full object-cover {{ $slide->is_active ? '' : 'grayscale opacity-60' }}">
                            @if(!$slide->is_active)
                                <span class="absolute top-1 left-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-500/90 text-white uppercase tracking-wider">Hidden</span>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            @if($slide->badge)
                                <div class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">{{ $slide->badge }}</div>
                            @endif
                            <div class="font-semibold {{ $slide->is_active ? 'text-gray-900' : 'text-gray-500' }} truncate">
                                {{ $slide->title }}
                            </div>
                            @if($slide->description)
                                <div class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $slide->description }}</div>
                            @endif
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5 text-[11px] text-gray-400">
                                @if($slide->cta_label)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        {{ $slide->cta_label }}
                                    </span>
                                @endif
                                @if(is_array($slide->tags) && count($slide->tags) > 0)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/></svg>
                                        {{ count($slide->tags) }} {{ Str::plural('tag', count($slide->tags)) }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 font-mono">
                                    #{{ $slide->sort_order }}
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 shrink-0">
                            <form action="{{ route('admin.hero-slides.toggle-active', $slide) }}" method="POST">
                                @csrf
                                @if($slide->is_active)
                                    <button type="submit" title="Hide from public home page"
                                        class="inline-flex items-center justify-center px-2.5 py-1.5 rounded text-xs font-medium bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 transition">
                                        Hide
                                    </button>
                                @else
                                    <button type="submit" title="Show on public home page"
                                        class="inline-flex items-center justify-center px-2.5 py-1.5 rounded text-xs font-medium bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 transition">
                                        Show
                                    </button>
                                @endif
                            </form>
                            <a href="{{ route('admin.hero-slides.edit', $slide) }}"
                                class="inline-flex items-center justify-center px-2.5 py-1.5 rounded text-xs font-semibold text-indigo-600 hover:bg-indigo-50 border border-transparent">
                                Edit
                            </a>
                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST"
                                onsubmit="return confirm('Delete this slide permanently? Tip: you can hide it from the home page instead.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete permanently"
                                    class="inline-flex items-center justify-center px-2.5 py-1.5 rounded text-xs text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="px-5 py-4 border-t border-gray-200">
                {{ $slides->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function heroSlidesManager({ saveUrl, csrf }) {
    return {
        saveUrl,
        csrf,
        dirty: false,
        saving: false,
        lastSavedAt: null,
        draggingId: null,
        originalOrder: [],

        init() {
            this.originalOrder = Array.from(this.$refs.list?.children ?? []).map(li => parseInt(li.dataset.id, 10));
        },

        currentOrder() {
            return Array.from(this.$refs.list?.children ?? []).map(li => parseInt(li.dataset.id, 10));
        },

        move(id, delta) {
            const list = this.$refs.list;
            if (!list) return;
            const node = list.querySelector(`li[data-id="${id}"]`);
            if (!node) return;
            const swap = (delta < 0) ? node.previousElementSibling : node.nextElementSibling;
            if (!swap) return;
            if (delta < 0) list.insertBefore(node, swap); else list.insertBefore(swap, node);
            this.dirty = true;
        },

        moveUp(id) { this.move(id, -1); },
        moveDown(id) { this.move(id, 1); },

        onDragStart(e, id) {
            this.draggingId = id;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', String(id));
        },

        onDragOver(e) {
            e.dataTransfer.dropEffect = 'move';
            const list = this.$refs.list;
            if (!list) return;
            const target = e.target.closest('li[data-id]');
            if (!target) return;
            const rect = target.getBoundingClientRect();
            const before = (e.clientY - rect.top) < rect.height / 2;
            target.parentNode.insertBefore(this.draggingEl(), before ? target : target.nextSibling);
            this.dirty = true;
        },

        draggingEl() {
            return this.$refs.list.querySelector(`li[data-id="${this.draggingId}"]`);
        },

        onDrop(e, id) {
            // Already inserted during dragover
            this.draggingId = null;
        },

        onDragEnd(e) {
            this.draggingId = null;
        },

        async saveOrder() {
            if (!this.dirty || this.saving) return;
            this.saving = true;
            const order = this.currentOrder();
            try {
                const res = await fetch(this.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ order }),
                });
                if (!res.ok) throw new Error('Save failed');
                this.dirty = false;
                this.lastSavedAt = new Date();
                this.originalOrder = order;
                setTimeout(() => { if (!this.dirty) this.lastSavedAt = null; }, 2500);
            } catch (err) {
                alert('Failed to save order: ' + err.message);
                this.resetOrder();
            } finally {
                this.saving = false;
            }
        },

        resetOrder() {
            if (!this.$refs.list) return;
            const map = new Map(Array.from(this.$refs.list.children).map(li => [li.dataset.id, li]));
            this.originalOrder.forEach(id => {
                const node = map.get(String(id));
                if (node) this.$refs.list.appendChild(node);
            });
            this.dirty = false;
        },
    };
}
</script>
@endsection
