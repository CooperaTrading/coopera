@extends('layouts.admin')

@section('title', 'Proforma Template')

@section('header', 'Proforma Template')
@section('subheader', 'Official VAT Proforma Invoice Template')

@section('content')
@include('admin.quote-requests.partials.tabs')

<div class="space-y-6">

    <!-- PAGE TOOLBAR -->
    <div class="bg-slate-900 text-white p-4 rounded-xl shadow-md flex items-center justify-between">
        <div>
            <h3 class="font-bold text-sm">📄 Official VAT Proforma Invoice Template</h3>
            <p class="text-xs text-slate-300">This exact document structure is used for all generated customer VAT proforma invoices.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold rounded-full">
                Active System Template
            </span>
        </div>
    </div>

    <!-- EXACT A4 PROFORMA INVOICE PAPER CANVAS -->
    <div class="flex justify-center pb-12">
        <div class="w-full bg-white border border-gray-300 rounded-none shadow-2xl p-10 font-sans text-slate-900 border-t-[8px] border-slate-900 space-y-6" style="max-width:850px; min-height:1100px; position:relative; padding-bottom:120px;">
            
            <!-- HEADER BRANDING BLOCK -->
            <div class="pb-4 border-b-2 border-slate-900 flex items-center justify-between">
                <div>
                    @if(!empty($logoDataUri))
                        <img src="{{ $logoDataUri }}" alt="Coopera Trading" class="h-12 w-auto object-contain">
                    @else
                        <div class="font-black text-xl text-slate-900 tracking-tight">COOPERA TRADING</div>
                    @endif
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-slate-900 text-white text-xs font-black uppercase tracking-wider rounded">Proforma Invoice</span>
                    <div class="text-sm font-bold text-slate-900 font-mono mt-1 font-extrabold">{{ $sampleProforma->proforma_number }}</div>
                    <div class="text-xs text-slate-500">Issued: {{ $sampleProforma->issue_date->format('M d, Y') }}</div>
                    <div class="text-xs text-slate-500">Valid until: {{ $sampleProforma->validity_date->format('M d, Y') }}</div>
                </div>
            </div>

            <!-- PARTY DETAILS BLOCK (From / Bill To) -->
            <div class="grid grid-cols-2 gap-6 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
                <div class="space-y-1">
                    <div class="font-black text-slate-400 uppercase tracking-wider text-[10px]">From</div>
                    <div class="font-extrabold text-slate-900 text-sm">Coopera Trading</div>
                    <div class="text-slate-600">Ethio-Best Building, 3rd Floor, Office #004</div>
                    <div class="text-slate-600">Kirkos Sub-City, Woreda 02, Addis Ababa, Ethiopia</div>
                    <div class="text-slate-600">info@cooperatrading.com · www.cooperatrading.com</div>
                    <div class="text-slate-600">+251 959 440 446 · +251 97 339 7012</div>
                </div>
                <div class="space-y-1">
                    <div class="font-black text-slate-400 uppercase tracking-wider text-[10px]">Bill to</div>
                    <div class="font-extrabold text-slate-900 text-sm">{{ $sampleProforma->quoteRequest->customer_name }}</div>
                    <div class="text-slate-600">{{ $sampleProforma->quoteRequest->company_name }}</div>
                    <div class="text-slate-600">{{ $sampleProforma->quoteRequest->email }}</div>
                    <div class="text-slate-600">{{ $sampleProforma->quoteRequest->phone }}</div>
                </div>
            </div>

            <!-- PRODUCTS ITEMS TABLE -->
            <div class="overflow-hidden border border-slate-300 rounded-lg">
                <table class="w-full text-xs">
                    <thead class="bg-slate-900 text-white font-bold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="p-2.5 text-left w-8">#</th>
                            <th class="p-2.5 text-left">Description</th>
                            <th class="p-2.5 text-center w-16">Unit</th>
                            <th class="p-2.5 text-right w-16">Qty</th>
                            <th class="p-2.5 text-right w-28">Unit Price</th>
                            <th class="p-2.5 text-right w-28">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($sampleProforma->items as $i => $item)
                            <tr class="{{ $i % 2 === 1 ? 'bg-slate-50/60' : '' }}">
                                <td class="p-2.5 text-slate-400 font-mono whitespace-nowrap">{{ $i + 1 }}</td>
                                <td class="p-2.5 font-bold text-slate-900">{{ $item->product_name }}</td>
                                <td class="p-2.5 text-center text-slate-600 whitespace-nowrap">{{ $item->unit_of_measure }}</td>
                                <td class="p-2.5 text-right font-mono whitespace-nowrap">{{ number_format($item->quantity, 0) }}</td>
                                <td class="p-2.5 text-right font-mono whitespace-nowrap">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="p-2.5 text-right font-bold font-mono text-slate-900 whitespace-nowrap">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- FINANCIAL TOTALS SUMMARY -->
            <div class="flex justify-end pt-2">
                <table class="w-1/2 text-xs space-y-1">
                    <tbody>
                        <tr>
                            <td class="py-1 font-semibold text-slate-600">Subtotal:</td>
                            <td class="py-1 text-right font-mono font-bold text-slate-900">{{ number_format($sampleProforma->subtotal, 2) }} ETB</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-semibold text-slate-600">VAT (15%):</td>
                            <td class="py-1 text-right font-mono font-bold text-slate-900">{{ number_format($sampleProforma->vat, 2) }} ETB</td>
                        </tr>
                        <tr class="border-t-2 border-slate-900 border-b-2">
                            <td class="py-2 font-black text-slate-900 text-sm">Total:</td>
                            <td class="py-2 text-right font-mono font-extrabold text-indigo-700 text-sm">{{ number_format($sampleProforma->total, 2) }} ETB</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- AMOUNT IN WORDS -->
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-950 font-medium">
                <span class="font-extrabold uppercase tracking-wider text-[10px] text-emerald-900 mr-2">Total Amount in Words:</span>
                <span class="italic font-bold text-slate-900">{{ \App\Models\ProformaTemplate::numberToWords($sampleProforma->total) }}</span>
            </div>

            <!-- TERMS OF DELIVERY & BANK DETAILS -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs space-y-3">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <div class="font-bold text-slate-900 uppercase text-[10px] tracking-wider mb-1">Payment Terms</div>
                        <div class="text-slate-700">{{ $sampleProforma->payment_terms }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 uppercase text-[10px] tracking-wider mb-1">Delivery Time</div>
                        <div class="text-slate-700">{{ $sampleProforma->delivery_time }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 uppercase text-[10px] tracking-wider mb-1">Bank Details</div>
                        <div class="text-slate-700 whitespace-pre-wrap">{{ $sampleProforma->bank_details }}</div>
                    </div>
                </div>
                @if($sampleProforma->notes)
                    <div class="pt-2 border-t border-slate-200">
                        <div class="font-bold text-slate-900 uppercase text-[10px] tracking-wider mb-1">Notes</div>
                        <div class="text-slate-700">{{ $sampleProforma->notes }}</div>
                    </div>
                @endif
            </div>

            <!-- FOOTER SIGNATURE & QR VERIFICATION (Pinned to bottom) -->
            <div style="position:absolute; bottom:0; left:0; right:0; padding:16px 40px 32px 40px; border-top:1px solid #e2e8f0; display:flex; align-items:flex-end; justify-content:space-between; font-size:0.75rem;">
                <div class="space-y-1 text-slate-700">
                    <div class="font-extrabold uppercase text-[10px] tracking-wider text-slate-500 mb-1">Scan to verify</div>
                    <div class="w-16 h-16 bg-slate-900 text-white text-[9px] font-bold flex items-center justify-center rounded shadow-sm">
                        QR CODE
                    </div>
                    <div class="font-bold text-[10px] text-slate-600 mt-1 tracking-wide">Powered by RCC</div>
                </div>

                <div class="text-right space-y-2">
                    <div class="h-10 border-b-2 border-slate-900 w-44 ml-auto"></div>
                    <div class="font-extrabold text-slate-900 text-xs">Authorized Signature & Stamp</div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
