<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Tech Wholesale Inc.',
                'email' => 'contact@techwholesale.com',
                'phone' => '+1-555-0123',
                'address' => '123 Tech Street, Silicon Valley, CA 94025',
                'contact_person' => 'John Smith',
                'is_active' => true,
            ],
            [
                'name' => 'Office Supplies Co.',
                'email' => 'sales@officesupplies.com',
                'phone' => '+1-555-0456',
                'address' => '456 Office Blvd, Business District, NY 10001',
                'contact_person' => 'Jane Doe',
                'is_active' => true,
            ],
            [
                'name' => 'Global Electronics Ltd.',
                'email' => 'info@globalelectronics.com',
                'phone' => '+1-555-0789',
                'address' => '789 Electronics Avenue, Los Angeles, CA 90001',
                'contact_person' => 'Michael Johnson',
                'is_active' => true,
            ],
            [
                'name' => 'Stationery World',
                'email' => 'orders@stationeryworld.com',
                'phone' => '+1-555-0321',
                'address' => '321 Stationery Road, Chicago, IL 60601',
                'contact_person' => 'Sarah Williams',
                'is_active' => true,
            ],
            [
                'name' => 'Furniture Direct',
                'email' => 'sales@furnituredirect.com',
                'phone' => '+1-555-0654',
                'address' => '654 Furniture Lane, Miami, FL 33101',
                'contact_person' => 'Robert Brown',
                'is_active' => true,
            ],
            [
                'name' => 'Food & Beverage Distributors',
                'email' => 'contact@fbd.com',
                'phone' => '+1-555-0987',
                'address' => '987 Food Street, Houston, TX 77001',
                'contact_person' => 'Emily Davis',
                'is_active' => true,
            ],
            [
                'name' => 'Health Products Supplier',
                'email' => 'info@healthproducts.com',
                'phone' => '+1-555-0147',
                'address' => '147 Health Avenue, Phoenix, AZ 85001',
                'contact_person' => 'David Wilson',
                'is_active' => true,
            ],
            [
                'name' => 'Sports Equipment Wholesale',
                'email' => 'sales@sportsequipment.com',
                'phone' => '+1-555-0258',
                'address' => '258 Sports Boulevard, Seattle, WA 98101',
                'contact_person' => 'Lisa Anderson',
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
