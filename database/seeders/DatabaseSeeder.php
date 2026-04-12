<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Part;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════
        // COMPANY 1: Optimum Inventory Control
        // ═══════════════════════════════════════════════════════
        $optimum = Company::create([
            'name'     => 'Optimum Inventory Control',
            'slug'     => 'optimum',
            'industry' => 'Industrial Manufacturing',
            'logo_url' => '/images/assetnova-logo.png',
        ]);

        User::create([
            'company_id' => $optimum->id,
            'name'       => 'Chief Ops',
            'email'      => 'admin@optimum.in',
            'password'   => Hash::make('password123'),
            'role'       => 'admin',
        ]);

        // Categories
        $mechCat  = Category::create(['company_id'=>$optimum->id, 'name'=>'Mechanical',  'description'=>'Mechanical drive and structural components']);
        $elecCat  = Category::create(['company_id'=>$optimum->id, 'name'=>'Electrical',  'description'=>'Electrical and electronic components']);
        $consCat  = Category::create(['company_id'=>$optimum->id, 'name'=>'Consumable',  'description'=>'Lubricants, solvents, and process chemicals']);
        $hwCat    = Category::create(['company_id'=>$optimum->id, 'name'=>'Hardware',    'description'=>'Fasteners, fittings, and structural hardware']);

        // Suppliers
        $tata   = Supplier::create(['company_id'=>$optimum->id, 'name'=>'Tata Steel and Spares',    'contact_email'=>'contact@tataspare.in',     'phone'=>'+91 98765 43210', 'address'=>'Jamshedpur, Jharkhand, IN',  'rating'=>4.8]);
        $ril    = Supplier::create(['company_id'=>$optimum->id, 'name'=>'Reliance Electronics',      'contact_email'=>'ops@reliance-elec.in',     'phone'=>'+91 98222 33444', 'address'=>'Navi Mumbai, Maharashtra, IN','rating'=>4.2]);
        $jindal = Supplier::create(['company_id'=>$optimum->id, 'name'=>'Jindal Forge Works',        'contact_email'=>'sales@jindalforge.in',     'phone'=>'+91 91234 56789', 'address'=>'Hisar, Haryana, IN',         'rating'=>2.5]);

        // Parts
        $pValve    = Part::create(['company_id'=>$optimum->id,'sku'=>'AX-902-TR',    'name'=>'Titanium Alloy Valve v2',    'description'=>'High-pressure titanium valve for hydraulic systems. Rated 250 bar.', 'category_id'=>$mechCat->id, 'supplier_id'=>$tata->id,   'cost'=>8750,  'stock_quantity'=>12,   'min_threshold'=>25,  'location'=>'Aisle 3, Rack A',         'image_url'=>'/images/parts/valve.png']);
        $pProc     = Part::create(['company_id'=>$optimum->id,'sku'=>'NP-G4-8822',   'name'=>'Neural Processor G4',        'description'=>'Central processing module for PLC-based automation control units.',  'category_id'=>$elecCat->id, 'supplier_id'=>$ril->id,    'cost'=>42500, 'stock_quantity'=>87,   'min_threshold'=>20,  'location'=>'Aisle B, Bin 4',           'image_url'=>'/images/parts/processor.png']);
        $pLube     = Part::create(['company_id'=>$optimum->id,'sku'=>'SL-2024-04',   'name'=>'Synthetic Lubricant X-Treme','description'=>'High-viscosity synthetic lubricant for extreme-temperature bearings.',  'category_id'=>$consCat->id, 'supplier_id'=>$jindal->id, 'cost'=>1840,  'stock_quantity'=>220,  'min_threshold'=>50,  'location'=>'Chemical Storage, Bay 2',  'image_url'=>'/images/parts/lubricant.png']);
        $pFast     = Part::create(['company_id'=>$optimum->id,'sku'=>'FAST-M12-SS',  'name'=>'Industrial Fastener Set',    'description'=>'Grade 12.9 stainless steel M12 bolt and nut kit. Box of 100.',          'category_id'=>$hwCat->id,   'supplier_id'=>$tata->id,   'cost'=>310,   'stock_quantity'=>4800, 'min_threshold'=>500, 'location'=>'Bulk Storage C',           'image_url'=>'/images/parts/fastener.png']);

        // Transactions
        Transaction::create(['company_id'=>$optimum->id,'part_id'=>$pFast->id,  'type'=>'in',  'quantity'=>2500,'notes'=>'Monthly restocking batch from Tata Steel.']);
        Transaction::create(['company_id'=>$optimum->id,'part_id'=>$pValve->id, 'type'=>'in',  'quantity'=>450, 'notes'=>'Emergency order for hydraulics shutdown.']);
        Transaction::create(['company_id'=>$optimum->id,'part_id'=>$pProc->id,  'type'=>'out', 'quantity'=>12,  'notes'=>'Issued to PLC maintenance crew.']);
        Transaction::create(['company_id'=>$optimum->id,'part_id'=>$pLube->id,  'type'=>'out', 'quantity'=>5,   'notes'=>'Quarterly bearing lubrication schedule.']);

        // ═══════════════════════════════════════════════════════
        // COMPANY 2: Caterpillar Inc.
        // ═══════════════════════════════════════════════════════
        $cat = Company::create([
            'name'     => 'Caterpillar Inc.',
            'slug'     => 'caterpillar',
            'industry' => 'Heavy Equipment Manufacturing',
            'logo_url' => '/images/caterpillar-logo.png',
        ]);

        User::create([
            'company_id' => $cat->id,
            'name'       => 'Fleet Manager',
            'email'      => 'admin@caterpillar.in',
            'password'   => Hash::make('password123'),
            'role'       => 'admin',
        ]);

        // Categories for Cat
        $filterCat  = Category::create(['company_id'=>$cat->id,'name'=>'Filters & Separators','description'=>'Oil, hydraulic, and fuel filtration systems']);
        $sensorCat  = Category::create(['company_id'=>$cat->id,'name'=>'Sensors & Switches',  'description'=>'Pressure, temperature, and flow monitoring devices']);
        $sealCat    = Category::create(['company_id'=>$cat->id,'name'=>'Seals & Gaskets',     'description'=>'O-rings, seals, and gaskets for leak prevention']);
        $hydrCat    = Category::create(['company_id'=>$cat->id,'name'=>'Hydraulic Systems',   'description'=>'Hydraulic filters, hoses, and cylinders']);

        // Suppliers for Cat
        $catOEM = Supplier::create(['company_id'=>$cat->id,'name'=>'Caterpillar OEM Parts India','contact_email'=>'oem@cat-india.in', 'phone'=>'+91 80 4568 9900','address'=>'Whitefield, Bengaluru, Karnataka, IN','rating'=>4.9]);
        $mahle  = Supplier::create(['company_id'=>$cat->id,'name'=>'MAHLE Filters India',        'contact_email'=>'sales@mahle.in',   'phone'=>'+91 22 6123 4400','address'=>'Bhiwandi, Maharashtra, IN',            'rating'=>4.5]);
        $parker = Supplier::create(['company_id'=>$cat->id,'name'=>'Parker Hannifin India',      'contact_email'=>'india@parker.com', 'phone'=>'+91 20 6678 2200','address'=>'Pimpri-Chinchwad, Pune, IN',           'rating'=>4.7]);

        // Parts for Cat (real Caterpillar part numbers)
        $catOilFilter  = Part::create(['company_id'=>$cat->id,'sku'=>'1R-0739',    'name'=>'Engine Oil Filter',               'description'=>'Removes contaminants from engine oil, ensuring cleaner oil circulation in C-series engines.',          'category_id'=>$filterCat->id,'supplier_id'=>$catOEM->id,'cost'=>1850,  'stock_quantity'=>320,'min_threshold'=>80, 'location'=>'Shelf F1, Bay A',         'image_url'=>'/images/parts/cat_oil_filter.png']);
        $catSensor     = Part::create(['company_id'=>$cat->id,'sku'=>'274-6717',   'name'=>'Oil Pressure Sensor Switch',      'description'=>'Critical sensor for monitoring oil pressure in C11, C13, and C15 engines. Shuts down engine on fault.','category_id'=>$sensorCat->id,'supplier_id'=>$catOEM->id,'cost'=>6400,  'stock_quantity'=>45, 'min_threshold'=>20, 'location'=>'Shelf S2, Bin 3',          'image_url'=>'/images/parts/cat_sensor.png']);
        $catHydFilter  = Part::create(['company_id'=>$cat->id,'sku'=>'HF-CAT-HX', 'name'=>'Hydraulic Filter Element',        'description'=>'Ensures hydraulic fluid cleanliness to maintain optimal responsiveness of Cat hydraulic systems.',       'category_id'=>$hydrCat->id,  'supplier_id'=>$parker->id,'cost'=>3200,  'stock_quantity'=>180,'min_threshold'=>60, 'location'=>'Hydraulics Bay, Rack 2',   'image_url'=>'/images/parts/cat_hyd_filter.png']);
        $catFuelFilter = Part::create(['company_id'=>$cat->id,'sku'=>'FF-WS-CAT', 'name'=>'Fuel Filter / Water Separator',   'description'=>'Prevents moisture and debris from damaging fuel injectors and pumps in marine and heavy equipment.',     'category_id'=>$filterCat->id,'supplier_id'=>$mahle->id, 'cost'=>2750,  'stock_quantity'=>95, 'min_threshold'=>40, 'location'=>'Fuel Systems, Bay 1',      'image_url'=>'/images/parts/cat_fuel_filter.png']);
        $catSealKit    = Part::create(['company_id'=>$cat->id,'sku'=>'SK-ORING-50','name'=>'O-Ring & Seal Kit (50-piece)',    'description'=>'Vital components to prevent leaks in Cat engines, transmissions, and hydraulic systems. Mixed sizes.',   'category_id'=>$sealCat->id,  'supplier_id'=>$catOEM->id,'cost'=>4500,  'stock_quantity'=>12, 'min_threshold'=>25, 'location'=>'Workshop D, Drawer 7',     'image_url'=>'/images/parts/cat_oring_kit.png']);
        $catGasket     = Part::create(['company_id'=>$cat->id,'sku'=>'GK-HT-CAT', 'name'=>'High-Temp Cylinder Head Gasket', 'description'=>'High-performance multi-layer steel gasket for Cat C-series diesel engines. Rated 850°C.',                'category_id'=>$sealCat->id,  'supplier_id'=>$parker->id,'cost'=>8900,  'stock_quantity'=>28, 'min_threshold'=>10, 'location'=>'Engine Bay, Cabinet A',    'image_url'=>'/images/parts/cat_gasket.png']);

        // Stock In transactions (staggered timestamps — oldest first)
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catOilFilter->id,  'type'=>'in', 'quantity'=>200,'notes'=>'Q1 bulk order from Caterpillar OEM Parts India.',      'created_at'=>now()->subDays(10),'updated_at'=>now()->subDays(10)]);
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catHydFilter->id,  'type'=>'in', 'quantity'=>150,'notes'=>'Hydraulic maintenance season restock.',               'created_at'=>now()->subDays(8), 'updated_at'=>now()->subDays(8)]);
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catFuelFilter->id, 'type'=>'in', 'quantity'=>80, 'notes'=>'Marine fleet maintenance batch.',                     'created_at'=>now()->subDays(7), 'updated_at'=>now()->subDays(7)]);
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catSensor->id,     'type'=>'in', 'quantity'=>30, 'notes'=>'C13 engine overhaul project stock.',                  'created_at'=>now()->subDays(6), 'updated_at'=>now()->subDays(6)]);
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catGasket->id,     'type'=>'in', 'quantity'=>20, 'notes'=>'Heavy equipment rebuild programme.',                  'created_at'=>now()->subDays(5), 'updated_at'=>now()->subDays(5)]);
        // Stock Out transactions (happen after stock-ins)
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catOilFilter->id,  'type'=>'out','quantity'=>35, 'notes'=>'Issued to fleet workshop — routine engine service.',  'created_at'=>now()->subDays(4), 'updated_at'=>now()->subDays(4)]);
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catSealKit->id,    'type'=>'out','quantity'=>8,  'notes'=>'Transmission overhaul — leakage repair.',             'created_at'=>now()->subDays(3), 'updated_at'=>now()->subDays(3)]);
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catHydFilter->id,  'type'=>'out','quantity'=>20, 'notes'=>'Dozer hydraulics PM schedule.',                       'created_at'=>now()->subDays(2), 'updated_at'=>now()->subDays(2)]);
        Transaction::create(['company_id'=>$cat->id,'part_id'=>$catFuelFilter->id, 'type'=>'out','quantity'=>15, 'notes'=>'Marine engine servicing — 3 units.',                  'created_at'=>now()->subDays(1), 'updated_at'=>now()->subDays(1)]);
    }
}
