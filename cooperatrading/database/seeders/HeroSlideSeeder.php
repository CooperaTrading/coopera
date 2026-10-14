<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'badge' => 'Construction Product Markets',
                'title' => 'Construction chemicals & high-performance systems.',
                'description' => 'Comprehensive range of concrete admixtures, waterproofing membranes, structural epoxies, and grouting solutions built for infrastructure scale.',
                'image' => 'assets/images/hero/slide-construction.jpg',
                'image_alt' => 'Construction chemicals and high-performance systems',
                'cta_label' => 'Explore Construction',
                'cta_url' => '/products/construction-product-markets',
                'tags' => ['Concrete Admixtures', 'Waterproofing', 'Structural Epoxies', 'Grouting'],
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'badge' => 'Specialty Ethiopian Exports',
                'title' => 'Direct Ethiopian exports to global markets.',
                'description' => 'Premium Arabica coffee, natural sesame seeds, pulses, and Ethiopian spices — sourced with 100% quality and origin traceability.',
                'image' => 'assets/images/hero/slide-export.jpg',
                'image_alt' => 'Ethiopian agricultural exports to global markets',
                'cta_label' => 'View Export Catalog',
                'cta_url' => '/products/export-products',
                'tags' => ['Arabica Coffee', 'Sesame Seeds', 'Pulses & Beans', 'Spices'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'badge' => 'Industrial Chemicals & Materials',
                'title' => 'Raw materials & chemicals for manufacturing.',
                'description' => 'Industrial grade chemicals, polymer resins, plastic additives, and processing solvents tailored for high-volume production.',
                'image' => 'assets/images/hero/slide-chemical.jpg',
                'image_alt' => 'Industrial chemicals and raw materials',
                'cta_label' => 'Browse Chemicals',
                'cta_url' => '/products/industrial-chemicals',
                'tags' => ['Polymer Resins', 'Plastic Additives', 'Industrial Solvents', 'Raw Materials'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'badge' => 'Bulk Global Logistics & Supply Chain',
                'title' => 'Integrated logistics & global sourcing precision.',
                'description' => 'End-to-end supply chain management, customs clearance support, bulk cargo handling, and freight logistics across East Africa.',
                'image' => 'assets/images/hero/slide-logistics.jpg',
                'image_alt' => 'Bulk global logistics and supply chain',
                'cta_label' => 'Discover Logistics',
                'cta_url' => '/products/water-environmental-solutions',
                'tags' => ['Freight Logistics', 'Customs Clearance', 'Bulk Cargo', 'Warehousing'],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($defaults as $data) {
            HeroSlide::updateOrCreate(
                ['title' => $data['title']],
                $data,
            );
        }
    }
}
