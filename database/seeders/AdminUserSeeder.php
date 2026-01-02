<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@pos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create manager user
        \App\Models\User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@pos.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        // Create cashier user
        \App\Models\User::factory()->create([
            'name' => 'Cashier User',
            'email' => 'cashier@pos.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

        // Create sample suppliers
        \App\Models\Supplier::create([
            'name' => 'Tech Wholesale Inc.',
            'email' => 'contact@techwholesale.com',
            'phone' => '+1-555-0123',
            'address' => '123 Tech Street, Silicon Valley, CA',
            'contact_person' => 'John Smith',
        ]);

        \App\Models\Supplier::create([
            'name' => 'Office Supplies Co.',
            'email' => 'sales@officesupplies.com',
            'phone' => '+1-555-0456',
            'address' => '456 Office Blvd, Business District, NY',
            'contact_person' => 'Jane Doe',
        ]);

        // Create sample customers
        \App\Models\Customer::create([
            'name' => 'Walk-in Customer',
            'email' => null,
            'phone' => null,
            'address' => null,
        ]);

        \App\Models\Customer::create([
            'name' => 'ABC Corporation',
            'email' => 'billing@abc-corp.com',
            'phone' => '+1-555-0789',
            'address' => '789 Corporate Ave, Downtown, NY',
        ]);

        // Create sample products
        $products = [
            [
                'name' => 'Wireless Mouse',
                'sku' => 'WM-001',
                'barcode' => '123456789012',
                'description' => 'Ergonomic wireless optical mouse with USB receiver',
                'category' => 'Electronics',
                'cost_price' => 15.50,
                'selling_price' => 29.99,
                'stock_quantity' => 50,
                'min_stock_level' => 10,
                'unit' => 'pcs',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'sku' => 'KB-002',
                'barcode' => '123456789013',
                'description' => 'RGB backlit mechanical gaming keyboard',
                'category' => 'Electronics',
                'cost_price' => 45.00,
                'selling_price' => 89.99,
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'unit' => 'pcs',
            ],
            [
                'name' => 'USB Flash Drive 32GB',
                'sku' => 'USB-003',
                'barcode' => '123456789014',
                'description' => 'High-speed USB 3.0 flash drive 32GB',
                'category' => 'Electronics',
                'cost_price' => 8.75,
                'selling_price' => 19.99,
                'stock_quantity' => 100,
                'min_stock_level' => 20,
                'unit' => 'pcs',
            ],
            [
                'name' => 'A4 Paper Pack',
                'sku' => 'PAPER-004',
                'barcode' => '123456789015',
                'description' => '500 sheets A4 white printer paper',
                'category' => 'Office Supplies',
                'cost_price' => 4.50,
                'selling_price' => 9.99,
                'stock_quantity' => 30,
                'min_stock_level' => 10,
                'unit' => 'packs',
            ],
            [
                'name' => 'Ballpoint Pens (Pack of 12)',
                'sku' => 'PEN-005',
                'barcode' => '123456789016',
                'description' => 'Black ink ballpoint pens, pack of 12',
                'category' => 'Office Supplies',
                'cost_price' => 2.25,
                'selling_price' => 5.99,
                'stock_quantity' => 15,
                'min_stock_level' => 5,
                'unit' => 'packs',
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
