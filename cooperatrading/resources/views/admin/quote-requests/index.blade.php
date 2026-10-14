@extends('layouts.admin')

@section('title', 'Quote Requests')

@section('header', 'Quote Requests')
@section('subheader', 'Customer quote requests awaiting proforma')

@section('content')
@include('admin.quote-requests.partials.tabs')

<div class="bg-white border border-gray-200 rounded-lg">
    <div class="px-5 py-4 border-b border-gray-200 flex flex-wrap items-center gap-4 justify-between">
        <form method="GET" action="{{ route('admin.quote-requests.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()"
                    class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All statuses</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="processed" @selected($status === 'processed')>Processed</option>
                </select>
            </div>
            <div class="relative">
                <input type="search" name="search" value="{{ $search }}" placeholder="Search name, company, email..."
                    class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 pl-9">
                <svg class="w-4 h-4 absolute left-2.5 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-3 py-1.5 rounded-md bg-slate-100 text-sm text-slate-700 hover:bg-slate-200">Filter</button>
            @if ($status || $search)
                <a href="{{ route('admin.quote-requests.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3">Customer</th>
                    <th class="px-5 py-3">Company</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Items</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($quotes as $quote)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $quote->customer_name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $quote->company_name ?: '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $quote->email }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $quote->items->count() }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $quote->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $quote->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($quote->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.quote-requests.show', $quote) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-500">No quote requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-200">
        {{ $quotes->links() }}
    </div>
</div>
@endsection
