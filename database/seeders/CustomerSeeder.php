<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Walk-in Customer',
                'email' => null,
                'phone' => null,
                'address' => null,
                'total_purchases' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'ABC Corporation',
                'email' => 'billing@abc-corp.com',
                'phone' => '+1-555-0789',
                'address' => '789 Corporate Ave, Downtown, NY 10001',
                'total_purchases' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'XYZ Enterprises',
                'email' => 'purchasing@xyz-enterprises.com',
                'phone' => '+1-555-0147',
                'address' => '147 Business Park, San Francisco, CA 94102',
                'total_purchases' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Tech Solutions Ltd.',
                'email' => 'orders@techsolutions.com',
                'phone' => '+1-555-0258',
                'address' => '258 Innovation Drive, Austin, TX 78701',
                'total_purchases' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Global Trading Co.',
                'email' => 'contact@globaltrading.com',
                'phone' => '+1-555-0369',
                'address' => '369 Trade Center, Boston, MA 02101',
                'total_purchases' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Retail Store Chain',
                'email' => 'procurement@retailchain.com',
                'phone' => '+1-555-0741',
                'address' => '741 Retail Boulevard, Denver, CO 80201',
                'total_purchases' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Small Business Inc.',
                'email' => 'info@smallbusiness.com',
                'phone' => '+1-555-0852',
                'address' => '852 Main Street, Portland, OR 97201',
                'total_purchases' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Local Shop',
                'email' => 'owner@localshop.com',
                'phone' => '+1-555-0963',
                'address' => '963 Local Avenue, Nashville, TN 37201',
                'total_purchases' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
