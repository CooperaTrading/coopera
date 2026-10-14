@extends('layouts.admin')

@section('title', 'Proforma ' . $proforma->proforma_number)

@section('header', 'Proforma ' . $proforma->proforma_number)
@section('subheader', 'Issued ' . $proforma->issue_date->format('M d, Y') . ' · Valid until ' . $proforma->validity_date->format('M d, Y'))

@section('actions')
    <a href="{{ route('admin.proformas.download', $proforma) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
        Download PDF
    </a>
    <a href="{{ route('admin.proformas.stream', $proforma) }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
        View PDF
    </a>
    <a href="{{ route('admin.quote-requests.show', $proforma->quoteRequest) }}" class="inline-flex items-center px-4 py-2 rounded-md border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50">
        ← Back to quote
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="font-semibold">Items</h2>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Unit</th>
                        <th class="px-5 py-3 text-right">Qty</th>
                        <th class="px-5 py-3 text-right">Unit price</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($proforma->items as $item)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $item->product_name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->unit_of_measure }}</td>
                            <td class="px-5 py-3 text-gray-900 text-right">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-gray-900 text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-5 py-3 text-gray-900 text-right font-medium">{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-200 bg-gray-50 space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">{{ number_format($proforma->subtotal, 2) }} {{ config('app.currency_symbol', 'Br') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">VAT (15%)</span>
                    <span class="font-medium">{{ number_format($proforma->vat, 2) }} {{ config('app.currency_symbol', 'Br') }}</span>
                </div>
                <div class="flex justify-between text-base font-bold pt-1 border-t border-gray-200">
                    <span>Total</span>
                    <span>{{ number_format($proforma->total, 2) }} {{ config('app.currency_symbol', 'Br') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="font-semibold">Terms</h2>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                <div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Payment terms</div>
                    <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $proforma->payment_terms }}</p>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Delivery time</div>
                    <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $proforma->delivery_time }}</p>
                </div>
                <div class="sm:col-span-2">
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Bank details</div>
                    <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $proforma->bank_details }}</p>
                </div>
                @if ($proforma->notes)
                    <div class="sm:col-span-2">
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Notes</div>
                        <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $proforma->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="font-semibold">Bill to</h2>
            <dl class="mt-4 space-y-2 text-sm">
                <div class="font-medium text-gray-900">{{ $proforma->quoteRequest->customer_name }}</div>
                @if ($proforma->quoteRequest->company_name)
                    <div class="text-gray-600">{{ $proforma->quoteRequest->company_name }}</div>
                @endif
                <div><a href="mailto:{{ $proforma->quoteRequest->email }}" class="text-indigo-600 hover:text-indigo-800">{{ $proforma->quoteRequest->email }}</a></div>
                @if ($proforma->quoteRequest->phone)
                    <div class="text-gray-600">{{ $proforma->quoteRequest->phone }}</div>
                @endif
            </dl>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="font-semibold">Proforma details</h2>
            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Number</dt><dd class="font-medium">{{ $proforma->proforma_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Issued</dt><dd>{{ $proforma->issue_date->format('M d, Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Valid until</dt><dd>{{ $proforma->validity_date->format('M d, Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Currency</dt><dd>{{ config('app.currency', 'ETB') }}</dd></div>
            </dl>
        </div>
    </div>
</div>
@endsection
