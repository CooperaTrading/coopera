@extends('layouts.admin')

@section('title', 'Quote Request #' . $quote->id)

@section('header', 'Quote Request')
@section('subheader', 'From ' . $quote->customer_name . ' · ' . $quote->created_at->format('M d, Y H:i'))

@section('actions')
    @if ($quote->isPending())
        <a href="{{ route('admin.proformas.create', $quote) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
            Generate Proforma
        </a>
    @elseif ($quote->proforma)
        <a href="{{ route('admin.proformas.show', $quote->proforma) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
            View Proforma
        </a>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold">Requested items</h2>
                <span class="text-xs text-gray-500">{{ $quote->items->count() }} item(s)</span>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Unit</th>
                        <th class="px-5 py-3 text-right">Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($quote->items as $item)
                        <tr>
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                @if ($item->product)
                                    <div class="text-xs text-gray-500">{{ $item->product->category->name ?? '' }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->unit_of_measure }}</td>
                            <td class="px-5 py-3 text-gray-900 text-right font-medium">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($quote->message)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="font-semibold">Customer message</h2>
                <p class="mt-2 text-sm text-slate-600 whitespace-pre-line">{{ $quote->message }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="font-semibold">Customer</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wider">Name</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $quote->customer_name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wider">Company</dt>
                    <dd class="mt-1 text-gray-900">{{ $quote->company_name ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wider">Email</dt>
                    <dd class="mt-1"><a href="mailto:{{ $quote->email }}" class="text-indigo-600 hover:text-indigo-800">{{ $quote->email }}</a></dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wider">Phone</dt>
                    <dd class="mt-1 text-gray-900">{{ $quote->phone ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wider">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $quote->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($quote->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wider">Submitted</dt>
                    <dd class="mt-1 text-gray-900">{{ $quote->created_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
