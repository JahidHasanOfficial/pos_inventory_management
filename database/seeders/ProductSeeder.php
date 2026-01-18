<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $electronics = ProductCategory::where('slug', 'electronics')->first();
        $officeSupplies = ProductCategory::where('slug', 'office-supplies')->first();
        $furniture = ProductCategory::where('slug', 'furniture')->first();
        $booksMedia = ProductCategory::where('slug', 'books-media')->first();
        $clothing = ProductCategory::where('slug', 'clothing-accessories')->first();
        $foodBeverages = ProductCategory::where('slug', 'food-beverages')->first();
        $healthBeauty = ProductCategory::where('slug', 'health-beauty')->first();
        $sports = ProductCategory::where('slug', 'sports-recreation')->first();
        $homeGarden = ProductCategory::where('slug', 'home-garden')->first();
        $automotive = ProductCategory::where('slug', 'automotive')->first();

        // Get brands
        $logitech = Brand::where('slug', 'logitech')->first();
        $corsair = Brand::where('slug', 'corsair')->first();
        $sandisk = Brand::where('slug', 'sandisk')->first();
        $hp = Brand::where('slug', 'hp')->first();
        $bic = Brand::where('slug', 'bic')->first();
        $apple = Brand::where('slug', 'apple')->first();
        $samsung = Brand::where('slug', 'samsung')->first();
        $dell = Brand::where('slug', 'dell')->first();
        $microsoft = Brand::where('slug', 'microsoft')->first();
        $lenovo = Brand::where('slug', 'lenovo')->first();

        $products = [
            // Electronics - Computer Peripherals
            [
                'name' => 'Wireless Mouse',
                'brand_id' => $logitech->id ?? null,
                'sku' => 'WM-001',
                'barcode' => '123456789012',
                'description' => 'Ergonomic wireless optical mouse with USB receiver, 2.4GHz connectivity',
                'category_id' => $electronics->id ?? null,
                'cost_price' => 15.50,
                'selling_price' => 29.99,
                'stock_quantity' => 50,
                'min_stock_level' => 10,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'brand_id' => $corsair->id ?? null,
                'sku' => 'KB-002',
                'barcode' => '123456789013',
                'description' => 'RGB backlit mechanical gaming keyboard with Cherry MX switches',
                'category_id' => $electronics->id ?? null,
                'cost_price' => 45.00,
                'selling_price' => 89.99,
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'USB Flash Drive 32GB',
                'brand_id' => $sandisk->id ?? null,
                'sku' => 'USB-003',
                'barcode' => '123456789014',
                'description' => 'High-speed USB 3.0 flash drive 32GB, read speed up to 150MB/s',
                'category_id' => $electronics->id ?? null,
                'cost_price' => 8.75,
                'selling_price' => 19.99,
                'stock_quantity' => 100,
                'min_stock_level' => 20,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'USB Flash Drive 64GB',
                'brand_id' => $sandisk->id ?? null,
                'sku' => 'USB-004',
                'barcode' => '123456789015',
                'description' => 'High-speed USB 3.0 flash drive 64GB, read speed up to 150MB/s',
                'category_id' => $electronics->id ?? null,
                'cost_price' => 12.50,
                'selling_price' => 29.99,
                'stock_quantity' => 75,
                'min_stock_level' => 15,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Wireless Headphones',
                'brand_id' => $samsung->id ?? null,
                'sku' => 'HP-005',
                'barcode' => '123456789016',
                'description' => 'Bluetooth wireless headphones with noise cancellation',
                'category_id' => $electronics->id ?? null,
                'cost_price' => 35.00,
                'selling_price' => 79.99,
                'stock_quantity' => 30,
                'min_stock_level' => 8,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Laptop Stand',
                'brand_id' => $dell->id ?? null,
                'sku' => 'LS-006',
                'barcode' => '123456789017',
                'description' => 'Adjustable aluminum laptop stand for ergonomic workspace',
                'category_id' => $electronics->id ?? null,
                'cost_price' => 18.00,
                'selling_price' => 39.99,
                'stock_quantity' => 40,
                'min_stock_level' => 10,
                'unit' => 'pcs',
                'is_active' => true,
            ],

            // Office Supplies
            [
                'name' => 'A4 Paper Pack',
                'brand_id' => $hp->id ?? null,
                'sku' => 'PAPER-007',
                'barcode' => '123456789018',
                'description' => '500 sheets A4 white printer paper, 80gsm',
                'category_id' => $officeSupplies->id ?? null,
                'cost_price' => 4.50,
                'selling_price' => 9.99,
                'stock_quantity' => 30,
                'min_stock_level' => 10,
                'unit' => 'packs',
                'is_active' => true,
            ],
            [
                'name' => 'Ballpoint Pens (Pack of 12)',
                'brand_id' => $bic->id ?? null,
                'sku' => 'PEN-008',
                'barcode' => '123456789019',
                'description' => 'Black ink ballpoint pens, pack of 12, medium point',
                'category_id' => $officeSupplies->id ?? null,
                'cost_price' => 2.25,
                'selling_price' => 5.99,
                'stock_quantity' => 15,
                'min_stock_level' => 5,
                'unit' => 'packs',
                'is_active' => true,
            ],
            [
                'name' => 'Stapler',
                'brand_id' => null,
                'sku' => 'STAP-009',
                'barcode' => '123456789020',
                'description' => 'Heavy-duty office stapler, holds up to 210 staples',
                'category_id' => $officeSupplies->id ?? null,
                'cost_price' => 5.50,
                'selling_price' => 12.99,
                'stock_quantity' => 20,
                'min_stock_level' => 5,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Sticky Notes (Pack of 5)',
                'brand_id' => null,
                'sku' => 'NOTE-010',
                'barcode' => '123456789021',
                'description' => 'Colorful sticky notes, 5 pads per pack, 76x76mm',
                'category_id' => $officeSupplies->id ?? null,
                'cost_price' => 3.00,
                'selling_price' => 7.99,
                'stock_quantity' => 25,
                'min_stock_level' => 8,
                'unit' => 'packs',
                'is_active' => true,
            ],
            [
                'name' => 'File Folders (Pack of 10)',
                'brand_id' => null,
                'sku' => 'FOLD-011',
                'barcode' => '123456789022',
                'description' => 'Manila file folders, letter size, pack of 10',
                'category_id' => $officeSupplies->id ?? null,
                'cost_price' => 4.00,
                'selling_price' => 9.99,
                'stock_quantity' => 35,
                'min_stock_level' => 10,
                'unit' => 'packs',
                'is_active' => true,
            ],
            [
                'name' => 'Printer Ink Cartridge - Black',
                'brand_id' => $hp->id ?? null,
                'sku' => 'INK-012',
                'barcode' => '123456789023',
                'description' => 'HP compatible black ink cartridge, high yield',
                'category_id' => $officeSupplies->id ?? null,
                'cost_price' => 12.00,
                'selling_price' => 24.99,
                'stock_quantity' => 45,
                'min_stock_level' => 15,
                'unit' => 'pcs',
                'is_active' => true,
            ],

            // Furniture
            [
                'name' => 'Office Chair',
                'brand_id' => null,
                'sku' => 'CHAIR-013',
                'barcode' => '123456789024',
                'description' => 'Ergonomic office chair with lumbar support, adjustable height',
                'category_id' => $furniture->id ?? null,
                'cost_price' => 85.00,
                'selling_price' => 179.99,
                'stock_quantity' => 12,
                'min_stock_level' => 3,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Desk Lamp',
                'brand_id' => null,
                'sku' => 'LAMP-014',
                'barcode' => '123456789025',
                'description' => 'LED desk lamp with adjustable brightness and color temperature',
                'category_id' => $furniture->id ?? null,
                'cost_price' => 22.00,
                'selling_price' => 49.99,
                'stock_quantity' => 18,
                'min_stock_level' => 5,
                'unit' => 'pcs',
                'is_active' => true,
            ],

            // Food & Beverages
            [
                'name' => 'Coffee Beans (500g)',
                'brand_id' => null,
                'sku' => 'COFFEE-015',
                'barcode' => '123456789026',
                'description' => 'Premium arabica coffee beans, medium roast, 500g pack',
                'category_id' => $foodBeverages->id ?? null,
                'cost_price' => 8.50,
                'selling_price' => 18.99,
                'stock_quantity' => 40,
                'min_stock_level' => 10,
                'unit' => 'packs',
                'is_active' => true,
            ],
            [
                'name' => 'Bottled Water (Pack of 24)',
                'brand_id' => null,
                'sku' => 'WATER-016',
                'barcode' => '123456789027',
                'description' => 'Purified drinking water, 500ml bottles, pack of 24',
                'category_id' => $foodBeverages->id ?? null,
                'cost_price' => 6.00,
                'selling_price' => 14.99,
                'stock_quantity' => 50,
                'min_stock_level' => 15,
                'unit' => 'packs',
                'is_active' => true,
            ],

            // Health & Beauty
            [
                'name' => 'Hand Sanitizer (500ml)',
                'brand_id' => null,
                'sku' => 'SANIT-017',
                'barcode' => '123456789028',
                'description' => 'Alcohol-based hand sanitizer, 70% alcohol, 500ml bottle',
                'category_id' => $healthBeauty->id ?? null,
                'cost_price' => 3.50,
                'selling_price' => 8.99,
                'stock_quantity' => 60,
                'min_stock_level' => 20,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Face Mask (Pack of 50)',
                'brand_id' => null,
                'sku' => 'MASK-018',
                'barcode' => '123456789029',
                'description' => 'Disposable face masks, 3-ply, pack of 50',
                'category_id' => $healthBeauty->id ?? null,
                'cost_price' => 5.00,
                'selling_price' => 12.99,
                'stock_quantity' => 80,
                'min_stock_level' => 25,
                'unit' => 'packs',
                'is_active' => true,
            ],

            // Sports & Recreation
            [
                'name' => 'Yoga Mat',
                'brand_id' => null,
                'sku' => 'YOGA-019',
                'barcode' => '123456789030',
                'description' => 'Non-slip yoga mat, 6mm thick, 183cm x 61cm',
                'category_id' => $sports->id ?? null,
                'cost_price' => 15.00,
                'selling_price' => 34.99,
                'stock_quantity' => 22,
                'min_stock_level' => 6,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Dumbbells Set (5kg each)',
                'brand_id' => null,
                'sku' => 'DUMB-020',
                'barcode' => '123456789031',
                'description' => 'Pair of 5kg adjustable dumbbells with stand',
                'category_id' => $sports->id ?? null,
                'cost_price' => 45.00,
                'selling_price' => 99.99,
                'stock_quantity' => 15,
                'min_stock_level' => 4,
                'unit' => 'sets',
                'is_active' => true,
            ],

            // Home & Garden
            [
                'name' => 'LED Light Bulb (Pack of 4)',
                'brand_id' => null,
                'sku' => 'BULB-021',
                'barcode' => '123456789032',
                'description' => 'Energy-efficient LED light bulbs, 9W equivalent to 60W, pack of 4',
                'category_id' => $homeGarden->id ?? null,
                'cost_price' => 8.00,
                'selling_price' => 19.99,
                'stock_quantity' => 55,
                'min_stock_level' => 15,
                'unit' => 'packs',
                'is_active' => true,
            ],
            [
                'name' => 'Plant Pot (Ceramic)',
                'brand_id' => null,
                'sku' => 'POT-022',
                'barcode' => '123456789033',
                'description' => 'Decorative ceramic plant pot, 20cm diameter, various colors',
                'category_id' => $homeGarden->id ?? null,
                'cost_price' => 6.50,
                'selling_price' => 14.99,
                'stock_quantity' => 28,
                'min_stock_level' => 8,
                'unit' => 'pcs',
                'is_active' => true,
            ],

            // Automotive
            [
                'name' => 'Car Phone Mount',
                'brand_id' => null,
                'sku' => 'MOUNT-023',
                'barcode' => '123456789034',
                'description' => 'Universal car phone mount, dashboard/windshield compatible',
                'category_id' => $automotive->id ?? null,
                'cost_price' => 7.50,
                'selling_price' => 16.99,
                'stock_quantity' => 32,
                'min_stock_level' => 8,
                'unit' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Car Air Freshener (Pack of 3)',
                'brand_id' => null,
                'sku' => 'FRESH-024',
                'barcode' => '123456789035',
                'description' => 'Long-lasting car air freshener, various scents, pack of 3',
                'category_id' => $automotive->id ?? null,
                'cost_price' => 4.00,
                'selling_price' => 9.99,
                'stock_quantity' => 45,
                'min_stock_level' => 12,
                'unit' => 'packs',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
