<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and accessories',
                'color' => '#007bff',
                'icon' => 'mdi mdi-laptop',
                'sort_order' => 1,
            ],
            [
                'name' => 'Office Supplies',
                'slug' => 'office-supplies',
                'description' => 'Stationery and office equipment',
                'color' => '#28a745',
                'icon' => 'mdi mdi-briefcase',
                'sort_order' => 2,
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Office and home furniture',
                'color' => '#6f42c1',
                'icon' => 'mdi mdi-seat-recliner',
                'sort_order' => 3,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, magazines, and media products',
                'color' => '#fd7e14',
                'icon' => 'mdi mdi-book-open-page-variant',
                'sort_order' => 4,
            ],
            [
                'name' => 'Clothing & Accessories',
                'slug' => 'clothing-accessories',
                'description' => 'Apparel and fashion accessories',
                'color' => '#e83e8c',
                'icon' => 'mdi mdi-tshirt-crew',
                'sort_order' => 5,
            ],
            [
                'name' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'description' => 'Food items and beverages',
                'color' => '#dc3545',
                'icon' => 'mdi mdi-food',
                'sort_order' => 6,
            ],
            [
                'name' => 'Health & Beauty',
                'slug' => 'health-beauty',
                'description' => 'Health and beauty products',
                'color' => '#ffc107',
                'icon' => 'mdi mdi-medical-bag',
                'sort_order' => 7,
            ],
            [
                'name' => 'Sports & Recreation',
                'slug' => 'sports-recreation',
                'description' => 'Sports equipment and recreational items',
                'color' => '#17a2b8',
                'icon' => 'mdi mdi-soccer',
                'sort_order' => 8,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home improvement and garden supplies',
                'color' => '#6c757d',
                'icon' => 'mdi mdi-home',
                'sort_order' => 9,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car parts and automotive accessories',
                'color' => '#343a40',
                'icon' => 'mdi mdi-car',
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\ProductCategory::create($category);
        }
    }
}
