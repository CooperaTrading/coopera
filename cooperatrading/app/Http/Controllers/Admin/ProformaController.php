<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProformaRequest;
use App\Models\Proforma;
use App\Models\QuoteRequest;
use App\Mail\ProformaSent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ProformaController extends Controller
{
    public function create(QuoteRequest $quoteRequest): View
    {
        if ($quoteRequest->proforma) {
            return redirect()->route('admin.proformas.show', $quoteRequest->proforma);
        }
        $quoteRequest->load('items');
        return view('admin.proformas.create', ['quote' => $quoteRequest]);
    }

    public function store(StoreProformaRequest $request, QuoteRequest $quoteRequest): RedirectResponse
    {
        if ($quoteRequest->proforma) {
            return redirect()->route('admin.proformas.show', $quoteRequest->proforma);
        }

        $data = $request->validated();
        $vatRate = (float) config('app.vat_rate', env('APP_VAT_RATE', 15));

        $subtotal = 0;
        $itemPayload = [];
        foreach ($data['items'] as $itemInput) {
            $quoteItem = $quoteRequest->items()->find($itemInput['quote_item_id']);
            if (! $quoteItem) {
                continue;
            }
            $unitPrice = (float) $itemInput['unit_price'];
            $lineTotal = $unitPrice * $quoteItem->quantity;
            $subtotal += $lineTotal;
            $itemPayload[] = [
                'product_id' => $quoteItem->product_id,
                'product_name' => $quoteItem->product_name,
                'unit_of_measure' => $quoteItem->unit_of_measure,
                'quantity' => $quoteItem->quantity,
                'unit_price' => $unitPrice,
                'total_price' => $lineTotal,
            ];
        }

        $vat = round($subtotal * ($vatRate / 100), 2);
        $total = round($subtotal + $vat, 2);

        $proforma = Proforma::create([
            'quote_request_id' => $quoteRequest->id,
            'proforma_number' => Proforma::generateNextNumber(),
            'issue_date' => now()->toDateString(),
            'validity_date' => now()->addDays(30)->toDateString(),
            'payment_terms' => $data['payment_terms'],
            'delivery_time' => $data['delivery_time'],
            'bank_details' => $data['bank_details'],
            'notes' => $data['notes'] ?? null,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'total' => $total,
        ]);

        foreach ($itemPayload as $row) {
            $proforma->items()->create($row);
        }

        $quoteRequest->update(['status' => 'processed']);

        $pdf = $this->generatePdf($proforma->fresh('items'));

        if ($request->boolean('send_email')) {
            Mail::to($quoteRequest->email)->send(new ProformaSent($quoteRequest, $proforma, $pdf->output()));
        }

        return redirect()->route('admin.proformas.show', $proforma)
            ->with('status', 'Proforma ' . $proforma->proforma_number . ' generated successfully.');
    }

    public function show(Proforma $proforma): View
    {
        $proforma->load(['items', 'quoteRequest']);
        return view('admin.proformas.show', ['proforma' => $proforma]);
    }

    public function download(Proforma $proforma)
    {
        $pdf = $this->generatePdf($proforma->load('items'));
        return $pdf->download($proforma->proforma_number . '.pdf');
    }

    public function stream(Proforma $proforma)
    {
        $pdf = $this->generatePdf($proforma->load('items'));
        return $pdf->stream($proforma->proforma_number . '.pdf');
    }

    public function verify(Proforma $proforma)
    {
        $pdf = $this->generatePdf($proforma->load('items'));
        return $pdf->stream($proforma->proforma_number . '.pdf');
    }

    protected function generatePdf(Proforma $proforma): \Barryvdh\DomPDF\PDF
    {
        $proforma->load(['items', 'quoteRequest']);

        $logoPath = public_path('assets/brand/logo-horizontal.svg');
        if (! is_file($logoPath)) {
            $logoPath = public_path('assets/brand/icon-02.png');
        }
        if (! is_file($logoPath)) {
            $logoPath = public_path('icon-02.svg');
        }
        $logoDataUri = $this->fileToDataUri($logoPath);

        $verifyUrl = route('proformas.verify', ['proforma' => $proforma->proforma_number]);
        $qrCodeDataUri = $this->generateQrCodeDataUri($verifyUrl);

        $template = \App\Models\ProformaTemplate::getActive();

        $pdf = Pdf::loadView('pdf.proforma', [
            'proforma' => $proforma,
            'template' => $template,
            'sections' => $template->sections,
            'currency' => config('app.currency', 'ETB'),
            'currency_symbol' => config('app.currency_symbol', 'Br'),
            'app_name' => config('app.name', 'Coopera Trading'),
            'logo_data_uri' => $logoDataUri,
            'qr_code_data_uri' => $qrCodeDataUri,
            'verify_url' => $verifyUrl,
        ]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf;
    }

    protected function fileToDataUri(?string $path): ?string
    {
        if (! $path || ! is_file($path)) {
            return null;
        }
        $contents = @file_get_contents($path);
        if ($contents === false) {
            return null;
        }
        $mime = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }

    protected function generateQrCodeDataUri(string $data, int $size = 220): ?string
    {
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size
            . '&margin=2&format=svg&data=' . urlencode($data);

        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'header' => "User-Agent: Mozilla/5.0\r\n",
            ],
        ]);

        $contents = @file_get_contents($url, false, $context);

        if ($contents === false || $contents === null) {
            $ch = curl_init();
            if ($ch) {
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                $contents = curl_exec($ch);
                curl_close($ch);
            }
        }

        if (empty($contents)) {
            Log::warning('Failed to generate QR code for proforma verification', ['url' => $url]);
            return null;
        }

        return 'data:image/svg+xml;base64,' . base64_encode($contents);
    }
}
