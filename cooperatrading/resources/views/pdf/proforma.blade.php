<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Proforma Invoice {{ $proforma->proforma_number }}</title>
    <style>
        @page { margin: 18pt 24pt 80pt 24pt; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 9.5pt; color: #0f172a; line-height: 1.35; }
        
        /* Header Block */
        .header { margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #0f172a; }
        .header-flex { display: table; width: 100%; }
        .header-cell { display: table-cell; vertical-align: middle; }
        .header-cell.right { text-align: right; }
        .meta { font-size: 8.5pt; color: #475569; line-height: 1.3; }
        .meta strong { color: #0f172a; }
        
        /* Address Info Grid */
        .info-grid { display: table; width: 100%; margin-bottom: 12px; }
        .info-cell { display: table-cell; width: 50%; vertical-align: top; }
        .info-cell:last-child { padding-left: 12px; }
        .info-block { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 5px; padding: 8px 12px; }
        .info-label { text-transform: uppercase; font-size: 7.5pt; font-weight: 700; color: #64748b; letter-spacing: 0.05em; margin-bottom: 2px; }
        .info-name { font-size: 10pt; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .info-text { font-size: 8.5pt; color: #334155; line-height: 1.35; }
        
        /* Items Table - Single Line Enforcement */
        table.items-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; font-size: 9pt; }
        table.items-table th { background: #0f172a; color: #ffffff; font-weight: 700; text-transform: uppercase; font-size: 7.5pt; letter-spacing: 0.05em; padding: 6px 8px; text-align: left; white-space: nowrap; }
        table.items-table td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; white-space: nowrap; }
        table.items-table td.description-cell { white-space: normal; word-break: break-word; }
        table.items-table tr:nth-child(even) td { background: #f8fafc; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .nowrap { white-space: nowrap; }
        
        /* Totals Block */
        .totals-container { margin-top: 8px; margin-bottom: 12px; text-align: right; }
        .totals { display: inline-table; width: 45%; font-size: 9pt; }
        .totals td { border: none; padding: 3px 8px; white-space: nowrap; }
        .totals .label { font-weight: 600; color: #475569; text-align: left; }
        .totals .grand td { border-top: 2px solid #0f172a; border-bottom: 2px solid #0f172a; font-weight: 800; font-size: 10.5pt; color: #0f172a; padding-top: 5px; padding-bottom: 5px; }
        
        /* Amount in Words */
        .words-block { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 5px; padding: 6px 10px; margin-top: 8px; margin-bottom: 10px; font-size: 8.5pt; color: #065f46; }
        .words-label { font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; font-size: 7pt; color: #047857; margin-right: 4px; }
        .words-text { font-style: italic; font-weight: 700; color: #0f172a; }

        /* Terms & Payment Grid */
        .terms { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 5px; padding: 10px; margin-top: 10px; margin-bottom: 10px; font-size: 8.5pt; }
        .terms h3 { font-size: 8.5pt; font-weight: 700; color: #0f172a; margin: 0 0 3px 0; text-transform: uppercase; letter-spacing: 0.03em; }
        .terms p { margin: 0; color: #334155; line-height: 1.3; }
        .terms-grid { display: table; width: 100%; }
        .terms-cell { display: table-cell; vertical-align: top; padding-right: 8px; }
        
        /* Permanent Footer & Signature */
        .document-footer { position: fixed; bottom: -62pt; left: 0; right: 0; padding-top: 8px; border-top: 1.5px solid #e2e8f0; font-size: 8pt; color: #64748b; }
        .footer-table { display: table; width: 100%; }
        .footer-col-left { display: table-cell; width: 50%; vertical-align: bottom; text-align: left; }
        .footer-col-right { display: table-cell; width: 50%; vertical-align: bottom; text-align: right; }
        .qr-wrapper { display: inline-block; text-align: left; }
        .qr-header-label { display: block; font-size: 8pt; font-weight: 800; color: #334155; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.05em; }
        .qr-sub-label { display: block; margin-top: 3px; font-size: 7.5pt; color: #475569; font-weight: 700; letter-spacing: 0.04em; }
        .badge { display: inline-block; padding: 2px 6px; background: #0f172a; color: #fff; font-size: 8pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; border-radius: 2px; }
    </style>
</head>
<body>

@php
    $secList = $sections ?? \App\Models\ProformaTemplate::getActive()->sections;
@endphp

@foreach ($secList as $sec)
    @if (!empty($sec['visible']))

        {{-- ELEMENT 1: Header Logo & Proforma Title --}}
        @if ($sec['key'] === 'header_branding')
            <div class="header">
                <div class="header-flex">
                    <div class="header-cell">
                        @if (!empty($logo_data_uri))
                            <img src="{{ $logo_data_uri }}" alt="{{ $app_name ?? 'Coopera Trading' }}" style="width: 200px; height: auto; max-width: 100%;">
                        @else
                            <div style="width: 36px; height: 36px; background: #0f172a; color: #fff; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">CT</div>
                        @endif
                    </div>
                    <div class="header-cell right">
                        <span class="badge">Proforma Invoice</span>
                        <div class="meta" style="margin-top: 4px;">
                            <strong>{{ $proforma->proforma_number }}</strong><br>
                            Issued: {{ $proforma->issue_date->format('M d, Y') }}<br>
                            Valid until: {{ $proforma->validity_date->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ELEMENT 2: Party Details (Address Placement) --}}
        @if ($sec['key'] === 'party_details')
            <div class="info-grid">
                <div class="info-cell" style="{{ ($sec['layout'] ?? 'split') === 'stacked_right' ? 'width: 100%; text-align: right;' : 'width: 50%;' }}">
                    <div class="info-block">
                        <div class="info-label">From Supplier</div>
                        <div class="info-name">{{ $app_name ?? 'Coopera Trading' }}</div>
                        <div class="info-text">
                            Ethio-Best Building, 3rd Floor, Office #004<br>
                            Kirkos Sub-City, Woreda 02, Addis Ababa, Ethiopia<br>
                            info@cooperatrading.com · www.cooperatrading.com<br>
                            +251 959 440 446 · +251 97 339 7012
                        </div>
                    </div>
                </div>
                <div class="info-cell" style="{{ ($sec['layout'] ?? 'split') === 'stacked_left' ? 'width: 100%; padding-left: 0; margin-top: 6px;' : 'width: 50%;' }}">
                    <div class="info-block">
                        <div class="info-label">Bill to Customer</div>
                        <div class="info-name">{{ $proforma->quoteRequest->customer_name }}</div>
                        @if ($proforma->quoteRequest->company_name)
                            <div class="info-text">{{ $proforma->quoteRequest->company_name }}</div>
                        @endif
                        <div class="info-text">
                            {{ $proforma->quoteRequest->email }}<br>
                            @if ($proforma->quoteRequest->phone)
                                {{ $proforma->quoteRequest->phone }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ELEMENT 3: Products Items Table --}}
        @if ($sec['key'] === 'items_table')
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th style="width: 46%;">Description</th>
                        <th style="width: 10%;" class="text-center">Unit</th>
                        <th style="width: 10%;" class="text-right">Qty</th>
                        <th style="width: 15%;" class="text-right">Unit Price</th>
                        <th style="width: 15%;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proforma->items as $i => $item)
                        <tr>
                            <td class="nowrap">{{ $i + 1 }}</td>
                            <td class="description-cell">
                                <strong>{{ $item->product_name }}</strong>
                            </td>
                            <td class="text-center nowrap">{{ $item->unit_of_measure }}</td>
                            <td class="text-right nowrap">{{ number_format($item->quantity, 0) }}</td>
                            <td class="text-right nowrap">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right nowrap">{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- ELEMENT 4: Financial Summary --}}
        @if ($sec['key'] === 'financial_summary')
            <div class="totals-container">
                <table class="totals">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="text-right nowrap">{{ number_format($proforma->subtotal, 2) }} ETB</td>
                    </tr>
                    <tr>
                        <td class="label">VAT (15%)</td>
                        <td class="text-right nowrap">{{ number_format($proforma->vat, 2) }} ETB</td>
                    </tr>
                    <tr class="grand">
                        <td class="label">Total</td>
                        <td class="text-right nowrap">{{ number_format($proforma->total, 2) }} ETB</td>
                    </tr>
                </table>
            </div>
        @endif

        {{-- ELEMENT 5: Amount in Words Line --}}
        @if ($sec['key'] === 'amount_in_words')
            <div class="words-block">
                <span class="words-label">{{ $sec['label'] ?? 'Total Amount in Words:' }}</span>
                <span class="words-text">{{ \App\Models\ProformaTemplate::numberToWords($proforma->total) }}</span>
            </div>
        @endif

        {{-- ELEMENT 6: Bank Account & Payment Details --}}
        @if ($sec['key'] === 'payment_bank_info')
            <div class="terms">
                <h3>Bank & Payment Details</h3>
                <p style="white-space: pre-wrap;">{{ !empty($sec['content']) ? $sec['content'] : $proforma->bank_details }}</p>
            </div>
        @endif

        {{-- ELEMENT 7: Terms & Tax Info --}}
        @if ($sec['key'] === 'terms_notes')
            <div class="terms">
                <div class="terms-grid">
                    <div class="terms-cell" style="width: 50%;">
                        <h3>Payment terms</h3>
                        <p>{{ $proforma->payment_terms }}</p>
                    </div>
                    <div class="terms-cell" style="width: 50%;">
                        <h3>Delivery time</h3>
                        <p>{{ $proforma->delivery_time }}</p>
                    </div>
                </div>
                @if (!empty($sec['content']))
                    <div style="margin-top: 6px;">
                        <h3>Custom Terms & Tax Info</h3>
                        <p style="white-space: pre-wrap;">{{ $sec['content'] }}</p>
                    </div>
                @endif
                @if ($proforma->notes)
                    <div style="margin-top: 6px;">
                        <h3>Notes</h3>
                        <p>{{ $proforma->notes }}</p>
                    </div>
                @endif
            </div>
        @endif

    @endif
@endforeach

<!-- MANDATORY DOCUMENT FOOTER (Left-aligned QR Code & Right-aligned Signature) -->
<div class="document-footer">
    <div class="footer-table">
        <div class="footer-col-left">
            <div class="qr-wrapper">
                <span class="qr-header-label">Scan to verify</span>
                @if (!empty($qr_code_data_uri))
                    <img src="{{ $qr_code_data_uri }}" alt="Verification QR Code" style="width: 70px; height: 70px; display: block;">
                @else
                    <div style="width: 70px; height: 70px; background: #0f172a; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 8px; font-weight: 700;">QR CODE</div>
                @endif
                <span class="qr-sub-label">Powered by RCC</span>
            </div>
        </div>
        <div class="footer-col-right">
            <div style="border-bottom: 1.5px solid #0f172a; width: 150px; margin-left: auto; margin-bottom: 4px;"></div>
            <div style="font-weight: 700; font-size: 8.5pt; color: #0f172a;">Authorized Signature & Stamp</div>
        </div>
    </div>
</div>

</body>
</html>
