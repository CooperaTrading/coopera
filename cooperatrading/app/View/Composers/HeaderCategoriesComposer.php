<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class HeaderCategoriesComposer
{
    public function compose(View $view): void
    {
        try {
            $view->with(
                'headerCategories',
                Category::active()
                    ->with(['products' => fn ($q) => $q->active()->select('id', 'category_id', 'name', 'slug')->ordered()])
                    ->ordered()
                    ->get()
            );
        } catch (\Throwable $e) {
            $view->with('headerCategories', collect());
        }
    }
}
