@component('mail::message')
# Proforma Invoice {{ $proforma->proforma_number }}

Dear {{ $quoteRequest->customer_name }},

Thank you for your interest in **{{ config('app.name') }}**. Please find attached your proforma invoice **{{ $proforma->proforma_number }}** dated {{ $proforma->issue_date->format('M d, Y') }}.

## Summary

@component('mail::table')
| | |
|:--|--:|
| **Subtotal** | {{ number_format($proforma->subtotal, 2) }} {{ config('app.currency_symbol', 'Br') }} |
| **VAT (15%)** | {{ number_format($proforma->vat, 2) }} {{ config('app.currency_symbol', 'Br') }} |
| **Total** | **{{ number_format($proforma->total, 2) }} {{ config('app.currency_symbol', 'Br') }}** |
| **Valid until** | {{ $proforma->validity_date->format('M d, Y') }} |
@endcomponent

## Payment terms

{{ $proforma->payment_terms }}

## Delivery time

{{ $proforma->delivery_time }}

## Bank details

{{ $proforma->bank_details }}

@if ($proforma->notes)
## Notes

{{ $proforma->notes }}
@endif

The attached PDF contains the full proforma invoice with all item details. Please reply to this email if you have any questions or would like to proceed.

We look forward to working with you.

@component('mail::subcopy')
This is an automated message from {{ config('app.name') }}. This proforma is valid until {{ $proforma->validity_date->format('M d, Y') }}.
@endcomponent

@endcomponent
