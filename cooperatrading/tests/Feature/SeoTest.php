<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;
    public function test_sitemap_xml_returns_valid_xml_with_public_urls(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', $response->getContent());
        $this->assertStringContainsString(url('/'), $response->getContent());
        $this->assertStringContainsString(url('/products'), $response->getContent());
        $this->assertStringContainsString(url('/about'), $response->getContent());
        $this->assertStringContainsString(url('/contact'), $response->getContent());
        $this->assertStringContainsString(url('/quote'), $response->getContent());
    }

    public function test_robots_txt_disallows_admin_and_points_to_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('Disallow: /admin', $response->getContent());
        $this->assertStringContainsString('Disallow: /login', $response->getContent());
        $this->assertStringContainsString('Disallow: /dashboard', $response->getContent());
        $this->assertStringContainsString('Sitemap: https://cooperatrading.com/sitemap.xml', $response->getContent());
    }

    public function test_homepage_contains_canonical_opengraph_and_schema_markup(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:image"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type": ["Corporation", "WholesaleStore"]', false);
    }
}
