<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Logitech',
                'slug' => 'logitech',
                'description' => 'Leading manufacturer of computer peripherals and accessories',
                'website' => 'www.logitech.com',
                'country' => 'Switzerland',
                'sort_order' => 1,
            ],
            [
                'name' => 'Corsair',
                'slug' => 'corsair',
                'description' => 'High-performance gaming hardware and PC components',
                'website' => 'www.corsair.com',
                'country' => 'USA',
                'sort_order' => 2,
            ],
            [
                'name' => 'SanDisk',
                'slug' => 'sandisk',
                'description' => 'Leading brand in flash memory cards and USB drives',
                'website' => 'www.sandisk.com',
                'country' => 'USA',
                'sort_order' => 3,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'Hewlett Packard - Technology solutions and services',
                'website' => 'www.hp.com',
                'country' => 'USA',
                'sort_order' => 4,
            ],
            [
                'name' => 'Bic',
                'slug' => 'bic',
                'description' => 'Worldwide leader in stationery and promotional products',
                'website' => 'www.bicworld.com',
                'country' => 'France',
                'sort_order' => 5,
            ],
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Innovative technology products and services',
                'website' => 'www.apple.com',
                'country' => 'USA',
                'sort_order' => 6,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Global leader in technology and consumer electronics',
                'website' => 'www.samsung.com',
                'country' => 'South Korea',
                'sort_order' => 7,
            ],
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'Computer technology and services company',
                'website' => 'www.dell.com',
                'country' => 'USA',
                'sort_order' => 8,
            ],
            [
                'name' => 'Microsoft',
                'slug' => 'microsoft',
                'description' => 'Software development and technology solutions',
                'website' => 'www.microsoft.com',
                'country' => 'USA',
                'sort_order' => 9,
            ],
            [
                'name' => 'Lenovo',
                'slug' => 'lenovo',
                'description' => 'Global technology company specializing in PCs',
                'website' => 'www.lenovo.com',
                'country' => 'China',
                'sort_order' => 10,
            ],
        ];

        foreach ($brands as $brand) {
            \App\Models\Brand::create($brand);
        }
    }
}
