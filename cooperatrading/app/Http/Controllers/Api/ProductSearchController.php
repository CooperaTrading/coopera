<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->whereHas('category', fn ($q) => $q->active())
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('category:id,name,slug')
            ->select('id', 'name', 'slug', 'category_id', 'unit_of_measure')
            ->take(8)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'unit' => $p->unit_of_measure,
                'category' => $p->category?->name,
                'category_slug' => $p->category?->slug,
                'url' => route('products.show', [$p->category?->slug, $p->slug]),
            ]);

        return response()->json($products);
    }
}
