<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = QuoteRequest::with('items')->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $quotes = $query->paginate(20)->withQueryString();

        return view('admin.quote-requests.index', [
            'quotes' => $quotes,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function show(QuoteRequest $quoteRequest): View
    {
        $quoteRequest->load('items');
        return view('admin.quote-requests.show', ['quote' => $quoteRequest]);
    }
}
