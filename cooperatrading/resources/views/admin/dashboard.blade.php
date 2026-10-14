@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Dashboard')
@section('subheader', 'Overview of catalog and quote activity')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="text-xs uppercase text-gray-500 tracking-wider">Categories</div>
        <div class="text-3xl font-semibold mt-1">{{ $stats['categories'] }}</div>
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-3 inline-block">Manage →</a>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="text-xs uppercase text-gray-500 tracking-wider">Products</div>
        <div class="text-3xl font-semibold mt-1">{{ $stats['products'] }}</div>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-3 inline-block">Manage →</a>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="text-xs uppercase text-gray-500 tracking-wider">Pending Quotes</div>
        <div class="text-3xl font-semibold mt-1 text-amber-600">{{ $stats['pending_quotes'] }}</div>
        <a href="{{ route('admin.quote-requests.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-3 inline-block">Review →</a>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="text-xs uppercase text-gray-500 tracking-wider">Processed Quotes</div>
        <div class="text-3xl font-semibold mt-1 text-green-600">{{ $stats['processed_quotes'] }}</div>
        <a href="{{ route('admin.quote-requests.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-3 inline-block">View →</a>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="text-xs uppercase text-gray-500 tracking-wider">Hero Slides</div>
        <div class="text-3xl font-semibold mt-1">
            {{ $stats['hero_slides_active'] }}<span class="text-base text-gray-400 font-normal">/{{ $stats['hero_slides_total'] }}</span>
        </div>
        <a href="{{ route('admin.hero-slides.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-3 inline-block">Manage →</a>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="font-semibold">Recent Quote Requests</h2>
        <a href="{{ route('admin.quote-requests.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3">Customer</th>
                    <th class="px-5 py-3">Company</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recentQuotes as $quote)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $quote->customer_name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $quote->company_name ?: '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $quote->email }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $quote->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $quote->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($quote->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-6 text-center text-gray-500">No quote requests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
