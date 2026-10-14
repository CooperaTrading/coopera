@extends('layouts.admin')

@section('title', 'Generate Proforma')

@section('header', 'Generate Proforma Invoice')
@section('subheader', 'For quote request from ' . $quote->customer_name)

@section('content')
<div x-data="proformaForm()" class="space-y-6">
    <form action="{{ route('admin.proformas.store', $quote) }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="font-semibold">Requested items</h2>
                <p class="text-sm text-gray-500">Enter the unit price for each item; totals will be calculated automatically.</p>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Unit</th>
                        <th class="px-5 py-3 text-right">Qty</th>
                        <th class="px-5 py-3 text-right">Unit price (Br)</th>
                        <th class="px-5 py-3 text-right">Line total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($quote->items as $item)
                        <tr>
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                <input type="hidden" name="items[{{ $loop->index }}][quote_item_id]" value="{{ $item->id }}">
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->unit_of_measure }}</td>
                            <td class="px-5 py-3 text-gray-900 text-right">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-right">
                                <input type="number" name="items[{{ $loop->index }}][unit_price]" min="0" step="0.01" value="{{ old('items.' . $loop->index . '.unit_price', 0) }}" required
                                    x-model.number="prices[{{ $loop->index }}]"
                                    x-on:input="updateTotals"
                                    class="w-32 text-right rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('items.' . $loop->index . '.unit_price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </td>
                            <td class="px-5 py-3 text-right font-medium text-gray-900" x-text="formatMoney(lineTotal({{ $item->quantity }}, prices[{{ $loop->index }}] || 0))">
                                {{ number_format(0, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-right text-sm font-medium text-gray-700">Subtotal</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900" x-text="formatMoney(subtotal)">{{ number_format(0, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-right text-sm font-medium text-gray-700">VAT (15%)</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900" x-text="formatMoney(vat)">{{ number_format(0, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-right text-sm font-semibold text-gray-900">Total</td>
                        <td class="px-5 py-3 text-right font-bold text-gray-900" x-text="formatMoney(total)">{{ number_format(0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-5">
            <div>
                <label for="payment_terms" class="block text-sm font-medium text-gray-700">Payment terms</label>
                <textarea name="payment_terms" id="payment_terms" rows="2" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="e.g., 30% advance, 70% before shipment">{{ old('payment_terms', '30% advance upon confirmation, 70% balance before shipment.') }}</textarea>
                @error('payment_terms') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="delivery_time" class="block text-sm font-medium text-gray-700">Delivery time</label>
                <textarea name="delivery_time" id="delivery_time" rows="2" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="e.g., 21 days from receipt of advance payment">{{ old('delivery_time', '21 days from receipt of advance payment.') }}</textarea>
                @error('delivery_time') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="bank_details" class="block text-sm font-medium text-gray-700">Bank details</label>
                <textarea name="bank_details" id="bank_details" rows="4" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Bank name, account number, SWIFT, etc.">{{ old('bank_details', "Bank: Commercial Bank of Ethiopia\nAccount: 1000123456789\nSWIFT: CBETETAA\nBranch: Addis Ababa Main") }}</textarea>
                @error('bank_details') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes <span class="text-gray-400">(optional)</span></label>
                <textarea name="notes" id="notes" rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="send_email" id="send_email" value="1" checked
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="send_email" class="text-sm text-gray-700">Email proforma to customer ({{ $quote->email }})</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.quote-requests.show', $quote) }}" class="px-4 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Cancel</a>
            <button type="submit" class="px-5 py-2.5 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                Generate & Send
            </button>
        </div>
    </form>
</div>

<script>
function proformaForm() {
    return {
        prices: @json($quote->items->map(fn ($i) => 0)->values()),
        subtotal: 0,
        vat: 0,
        total: 0,
        vatRate: 15,
        quantities: @json($quote->items->pluck('quantity')->values()),

        init() {
            this.updateTotals();
        },

        lineTotal(qty, price) {
            return (qty || 0) * (price || 0);
        },

        updateTotals() {
            let sub = 0;
            for (let i = 0; i < this.quantities.length; i++) {
                sub += this.lineTotal(this.quantities[i], this.prices[i] || 0);
            }
            this.subtotal = sub;
            this.vat = Math.round(sub * (this.vatRate / 100) * 100) / 100;
            this.total = Math.round((sub + this.vat) * 100) / 100;
        },

        formatMoney(n) {
            return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n || 0);
        },
    };
}
</script>
@endsection
