@extends('layouts.admin')

@section('title', 'Partners')

@section('header', 'Partners')
@section('subheader', 'Manage your trusted global partners and brand logos')

@section('actions')
    <a href="{{ route('admin.partners.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
        + New Partner
    </a>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Filter & Sort Toolbar -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-wrap items-center justify-between gap-4 text-sm shadow-sm">
        <form method="GET" action="{{ route('admin.partners.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
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
                    <option value="sort_order" @selected(request('sort') === 'sort_order' || !request('sort'))>Display Order (Default)</option>
                    <option value="name_asc" @selected(request('sort') === 'name_asc')>Name (A to Z)</option>
                    <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z to A)</option>
                </select>
            </div>

            @if(request()->anyFilled(['status', 'sort']))
                <a href="{{ route('admin.partners.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium py-1.5">Reset</a>
            @endif
        </form>

        <div class="text-xs text-gray-500 font-medium ml-auto">
            Showing {{ $partners->total() }} {{ Str::plural('partner', $partners->total()) }}
        </div>
    </div>

    <!-- Partners Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-5 py-3">Logo</th>
                        <th class="px-5 py-3">Company Name</th>
                        <th class="px-5 py-3">Website URL</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Order</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($partners as $partner)
                        <tr class="{{ $partner->is_active ? 'hover:bg-gray-50/60' : 'bg-gray-50/40 text-gray-500' }}">
                            <td class="px-5 py-3.5">
                                <div class="w-20 h-10 rounded-lg overflow-hidden bg-slate-50 border border-slate-200 shrink-0 p-1 flex items-center justify-center" style="width: 80px; height: 40px; min-width: 80px; max-width: 80px; max-height: 40px;">
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain {{ $partner->is_active ? '' : 'grayscale opacity-60' }}" style="max-width: 72px; max-height: 32px; object-fit: contain;">
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium {{ $partner->is_active ? 'text-gray-900' : 'text-gray-500' }}">{{ $partner->name }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($partner->url)
                                    <a href="{{ $partner->url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-1">
                                        {{ Str::limit($partner->url, 35) }}
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs italic">No URL set</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if ($partner->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Hidden
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs font-mono text-gray-500">{{ $partner->sort_order }}</td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <!-- 1-Click Hide / Show Toggle -->
                                <form action="{{ route('admin.partners.toggle-active', $partner) }}" method="POST" class="inline-block mr-2">
                                    @csrf
                                    @if ($partner->is_active)
                                        <button type="submit" title="Hide this partner from public site" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 transition">
                                            Hide
                                        </button>
                                    @else
                                        <button type="submit" title="Show this partner on public site" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 transition">
                                            Show
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('admin.partners.edit', $partner) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold mr-2">Edit</a>

                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this partner completely? Tip: You can Hide it instead.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-500">No partners found. Click "+ New Partner" to add one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $partners->links() }}
        </div>
    </div>
</div>
@endsection
