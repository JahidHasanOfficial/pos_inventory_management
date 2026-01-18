<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Step 1: Seed basic data (users, categories, brands)
            AdminUserSeeder::class,
            ProductCategorySeeder::class,
            BrandSeeder::class,
            
            // Step 2: Seed products
            ProductSeeder::class,
            
            // Step 3: Seed suppliers and customers
            SupplierSeeder::class,
            CustomerSeeder::class,
            
            // Step 4: Seed transactions (purchases first, then sales)
            PurchaseSeeder::class,
            SaleSeeder::class,
            
            // Step 5: Seed stock movements
            StockSeeder::class,
        ]);
    }
}
