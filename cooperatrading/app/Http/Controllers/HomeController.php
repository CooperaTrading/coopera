<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Partner;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->ordered()
            ->get();

        $partners = Partner::active()
            ->ordered()
            ->get();

        $heroSlides = HeroSlide::active()
            ->ordered()
            ->get();

        return view('home', compact('categories', 'partners', 'heroSlides'));
    }
}
