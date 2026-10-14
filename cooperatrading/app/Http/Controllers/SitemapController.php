<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate and serve dynamic XML sitemap with 24-hour cache.
     */
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_xml_content', 86400, function () {
            $categories = Category::query()
                ->when(\Schema::hasColumn('categories', 'is_active'), fn ($q) => $q->where('is_active', true))
                ->orderBy('sort_order', 'asc')
                ->get();

            $products = Product::query()
                ->when(\Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
                ->with('category')
                ->get();

            $staticPages = [
                ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
                ['url' => route('products'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
                ['url' => route('about'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
                ['url' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
                ['url' => route('quote.create'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ];

            $categoryEntries = [];
            foreach ($categories as $cat) {
                if (empty($cat->slug)) continue;
                $categoryEntries[] = [
                    'url' => route('products.category', $cat->slug),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => ($cat->updated_at ?? now())->toAtomString(),
                ];
            }

            $productEntries = [];
            foreach ($products as $prod) {
                if (empty($prod->slug) || !$prod->category || empty($prod->category->slug)) continue;
                $productEntries[] = [
                    'url' => route('products.show', [$prod->category->slug, $prod->slug]),
                    'priority' => '0.9',
                    'changefreq' => 'weekly',
                    'lastmod' => ($prod->updated_at ?? now())->toAtomString(),
                ];
            }

            $entries = array_merge($staticPages, $categoryEntries, $productEntries);

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '
                . 'xmlns:xhtml="http://www.w3.org/1999/xhtml" '
                . 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

            foreach ($entries as $e) {
                $xml .= "  <url>\n";
                $xml .= "    <loc>" . htmlspecialchars($e['url'], ENT_XML1, 'UTF-8') . "</loc>\n";
                $xml .= "    <lastmod>" . $e['lastmod'] . "</lastmod>\n";
                $xml .= "    <changefreq>" . $e['changefreq'] . "</changefreq>\n";
                $xml .= "    <priority>" . $e['priority'] . "</priority>\n";
                $xml .= "  </url>\n";
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex', // Sitemap itself shouldn't be indexed as content
        ]);
    }
}
