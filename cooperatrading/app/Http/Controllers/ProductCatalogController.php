<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->ordered()
            ->get();
        return view('products.index', compact('categories'));
    }

    public function category(Category $category, Request $request): View
    {
        abort_if(! $category->is_active, 404);

        $sort = $request->input('sort', 'default');
        $query = $category->products()->active();

        if ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sort === 'newest') {
            $query->latest();
        } else {
            $query->ordered();
        }

        $products = $query->paginate(24)->withQueryString();
        return view('products.category', compact('category', 'products', 'sort'));
    }

    public function show(Category $category, Product $product): View
    {
        abort_if(! $category->is_active || ! $product->is_active, 404);

        $related = Product::active()
            ->where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->ordered()
            ->take(3)
            ->get();
        return view('products.show', compact('category', 'product', 'related'));
    }
}
