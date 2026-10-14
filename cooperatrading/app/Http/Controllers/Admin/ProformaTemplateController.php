<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProformaTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProformaTemplateController extends Controller
{
    public function index(): View
    {
        $template = ProformaTemplate::getActive();

        // Sample Proforma for exact template rendering
        $sampleQuote = new \App\Models\QuoteRequest([
            'customer_name' => 'Abebe Bikila',
            'company_name' => 'Midroc Construction Ltd',
            'email' => 'purchasing@midroc-eth.com',
            'phone' => '+251 91 123 4567',
        ]);

        $sampleProforma = new \App\Models\Proforma([
            'proforma_number' => 'PRO-2026-0042',
            'issue_date' => now(),
            'validity_date' => now()->addDays(30),
            'subtotal' => 187500.00,
            'vat' => 28125.00,
            'total' => 215625.00,
            'payment_terms' => '100% Advance Payment via Bank Transfer',
            'delivery_time' => '3–5 Business Days after payment confirmation',
            'bank_details' => "Commercial Bank of Ethiopia (CBE)\nAccount Name: Coopera Trading\nAccount Number: 1000123456789\nBranch: Bole Branch, Addis Ababa",
            'notes' => 'Thank you for choosing Coopera Trading.',
        ]);
        $sampleProforma->setRelation('quoteRequest', $sampleQuote);

        $sampleItems = collect([
            new \App\Models\ProformaItem([
                'product_name' => 'Concrete Admixtures (High-Flow Performance)',
                'unit_of_measure' => 'kg',
                'quantity' => 500,
                'unit_price' => 120.00,
                'total_price' => 60000.00,
            ]),
            new \App\Models\ProformaItem([
                'product_name' => 'Bitumen Waterproofing Membrane (4mm)',
                'unit_of_measure' => 'piece',
                'quantity' => 150,
                'unit_price' => 850.00,
                'total_price' => 127500.00,
            ]),
        ]);
        $sampleProforma->setRelation('items', $sampleItems);

        $logoPath = public_path('assets/brand/icon-02.png');
        $logoDataUri = is_file($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        return view('admin.proforma-templates.index', [
            'template' => $template,
            'sections' => $template->sections,
            'sampleProforma' => $sampleProforma,
            'logoDataUri' => $logoDataUri,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.key' => 'required|string',
            'sections.*.title' => 'required|string',
            'sections.*.description' => 'nullable|string',
            'sections.*.visible' => 'nullable|boolean',
            'sections.*.content' => 'nullable|string',
            'sections.*.label' => 'nullable|string',
            'sections.*.layout' => 'nullable|string',
            'sections.*.align' => 'nullable|string',
            'sections.*.qr_size' => 'nullable|string',
        ]);

        $template = ProformaTemplate::getActive();

        // Convert visible checkbox values
        $sections = array_map(function ($sec) {
            $sec['visible'] = isset($sec['visible']) && ($sec['visible'] == 1 || $sec['visible'] == 'true' || $sec['visible'] === true);
            return $sec;
        }, $validated['sections']);

        $template->update([
            'sections' => array_values($sections),
        ]);

        return redirect()->route('admin.proforma-templates.index')
            ->with('status', 'Custom Proforma Invoice layout & elements updated successfully!');
    }

    public function reset(): RedirectResponse
    {
        $template = ProformaTemplate::getActive();
        $template->update([
            'sections' => ProformaTemplate::defaultSections(),
        ]);

        return redirect()->route('admin.proforma-templates.index')
            ->with('status', 'Proforma template has been reset to default standard layout.');
    }
}
