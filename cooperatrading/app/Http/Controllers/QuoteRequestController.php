<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequestRequest;
use App\Models\Product;
use App\Models\QuoteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function create(Request $request): View
    {
        $products = Product::with('category')->orderBy('name')->get();
        $selectedIds = collect($request->query('products', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->all();

        return view('quote.create', [
            'products' => $products,
            'selectedIds' => $selectedIds,
        ]);
    }

    public function store(StoreQuoteRequestRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $quote = QuoteRequest::create([
            'customer_name' => $data['customer_name'],
            'company_name' => $data['company_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        foreach ($data['items'] as $item) {
            $product = Product::find($item['product_id']);
            if (! $product) {
                continue;
            }
            $quote->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_of_measure' => $product->unit_of_measure,
                'quantity' => (int) $item['quantity'],
            ]);
        }

        return redirect()->route('quote.thank-you');
    }

    public function thankYou(): View
    {
        return view('quote.thank-you');
    }
}
