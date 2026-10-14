<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Sika AG',
                'url' => 'https://www.sika.com',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'BASF SE',
                'url' => 'https://www.basf.com',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Dow Chemical',
                'url' => 'https://www.dow.com',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'LG Chem',
                'url' => 'https://www.lgchem.com',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'SABIC',
                'url' => 'https://www.sabic.com',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Wanhua Chemical',
                'url' => 'https://www.whchem.com',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                $partner
            );
        }
    }
}
