<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Construction Product Markets' => [
                'description' => 'Comprehensive range of construction chemicals, systems, and solutions for commercial, industrial, and infrastructure projects.',
                'products' => [
                    ['Concrete Solutions', 'kg', 'High-performance concrete admixtures, additives, and specialty solutions for ready-mix, precast, and on-site applications.'],
                    ['Waterproofing Systems', 'piece', 'Integrated waterproofing membranes, coatings, and sealants for roofs, basements, wet areas, and water-retaining structures.'],
                    ['Adhesives & Sealants', 'piece', 'Industrial-grade tile adhesives, structural epoxies, polyurethane sealants, and bonding agents for construction applications.'],
                    ['Concrete Repair & Protection', 'kg', 'Repair mortars, anti-corrosion coatings, and protective systems that restore and extend the life of damaged concrete.'],
                    ['Flooring Systems', 'piece', 'Epoxy, polyurethane, and cementitious flooring systems for industrial, commercial, and decorative applications.'],
                    ['Roofing Systems', 'piece', 'Single-ply membranes, built-up roofing, and insulated roof panels engineered for durability and weather resistance.'],
                    ['Geosynthetics', 'piece', 'Geotextiles, geomembranes, geogrids, and geocomposites for soil stabilization, drainage, and reinforcement.'],
                    ['Expansion Joints & Joint Sealants', 'piece', 'Expansion joint systems and sealants for buildings, bridges, parking decks, and heavily trafficked concrete slabs.'],
                ],
            ],
            'Industrial Chemicals' => [
                'description' => 'Premium industrial chemicals and raw materials for plastics, manufacturing, and processing industries.',
                'products' => [
                    ['Plasticizers', 'kg', 'DOP, DINP, DOTP, and bio-based plasticizers for flexible PVC, rubber, and polymer compounding.'],
                    ['PVC Resins', 'kg', 'Suspension, emulsion, and specialty grade PVC resins for pipe, profile, cable, and film manufacturing.'],
                    ['PVC Stabilizers', 'kg', 'Lead-based, calcium-zinc, and tin stabilizers for heat and UV stabilization of PVC compounds.'],
                    ['Solvents', 'kg', 'Industrial and specialty solvents including aromatics, aliphatics, alcohols, ketones, and esters.'],
                    ['Fillers', 'kg', 'Calcium carbonate, talc, silica, and other mineral fillers for plastics, paints, and rubber compounding.'],
                    ['Processing Aids', 'kg', 'Acrylic and wax-based processing aids that improve PVC fusion, melt strength, and surface finish.'],
                    ['Lubricants', 'kg', 'Internal and external lubricants for PVC, polyolefin, and engineering plastics processing.'],
                    ['Pigments & Color Masterbatches', 'kg', 'Organic and inorganic pigments plus custom color masterbatches for plastics, coatings, and inks.'],
                ],
            ],
            'Water & Environmental Solutions' => [
                'description' => 'Integrated water treatment, environmental protection, and flow control solutions for municipal and industrial applications.',
                'products' => [
                    ['Water Treatment Chemicals', 'kg', 'Coagulants, flocculants, disinfectants, and scale inhibitors for potable, process, and wastewater treatment.'],
                    ['Water Treatment Equipment', 'piece', 'Reverse osmosis systems, softeners, filtration units, and packaged treatment plants.'],
                    ['Industrial Filtration', 'piece', 'Cartridge, bag, and multimedia filters for process water, chemicals, and food & beverage applications.'],
                    ['Pumps & Flow Control', 'piece', 'Industrial pumps, valves, and metering systems for water, chemicals, and slurry service.'],
                    ['Environmental Protection Systems', 'piece', 'Effluent treatment plants, odor control systems, and solid waste handling equipment.'],
                ],
            ],
            'Packaging & Industrial Supplies' => [
                'description' => 'Industrial packaging, films, and consumables engineered for safe storage, transport, and protection of goods.',
                'products' => [
                    ['Industrial Packaging', 'piece', 'Heavy-duty sacks, FIBC jumbo bags, drums, and containers for bulk chemicals and finished goods.'],
                    ['Plastic Films', 'kg', 'Stretch films, shrink films, laminating films, and protective films for packaging and palletizing.'],
                    ['Packaging Materials', 'piece', 'Strapping, tapes, edge protectors, and cushioning materials for industrial packaging operations.'],
                    ['Industrial Consumables', 'piece', 'Cleaning supplies, abrasives, adhesives, and maintenance consumables for industrial facilities.'],
                    ['Safety Equipment (PPE)', 'piece', 'Personal protective equipment including helmets, gloves, respirators, eye protection, and safety footwear.'],
                ],
            ],
            'Industrial Technology Solutions' => [
                'description' => 'Automation, electrical, and instrumentation solutions that power modern industrial operations.',
                'products' => [
                    ['Industrial Automation', 'piece', 'PLCs, HMIs, variable frequency drives, and motion control systems for process and factory automation.'],
                    ['Electrical & Power Systems', 'piece', 'LV/MV switchgear, transformers, UPS systems, and power distribution equipment for industrial facilities.'],
                    ['Instrumentation & Control', 'piece', 'Sensors, transmitters, analyzers, and control valves for process measurement and regulation.'],
                    ['Industrial Safety & Security', 'piece', 'Fire suppression, gas detection, access control, and perimeter security systems for industrial sites.'],
                    ['Smart Infrastructure', 'piece', 'IoT, SCADA, and remote monitoring solutions for smart factories and connected infrastructure.'],
                ],
            ],
            'Export Products' => [
                'description' => 'Premium Ethiopian-origin agricultural and natural products sourced, processed, and exported for international markets.',
                'products' => [
                    ['Coffee Export', 'kg', 'Specialty and commercial grade Ethiopian Arabica coffee beans, washed and natural, with full traceability.'],
                    ['Sesame & Oilseeds', 'kg', 'Hulled and natural sesame, Niger seed, and sunflower seeds processed for export markets.'],
                    ['Pulses & Spices', 'kg', 'Chickpeas, lentils, beans, and Ethiopian spices including korarima, long pepper, and berbere.'],
                    ['Natural Products', 'kg', 'Honey, beeswax, herbs, and other natural Ethiopian products meeting international quality standards.'],
                ],
            ],
        ];

        foreach ($data as $categoryName => $payload) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                [
                    'name' => $categoryName,
                    'description' => $payload['description'],
                ]
            );

            foreach ($payload['products'] as [$productName, $uom, $description]) {
                $category->products()->updateOrCreate(
                    ['slug' => Str::slug($productName)],
                    [
                        'name' => $productName,
                        'description' => $description,
                        'unit_of_measure' => $uom,
                    ]
                );
            }
        }
    }
}
