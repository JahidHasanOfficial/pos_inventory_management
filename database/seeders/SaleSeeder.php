<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where('is_active', true)->get();
        $customers = Customer::all();
        $users = User::all();

        if ($products->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Generate sales for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $saleDate = Carbon::now()->subDays($day);
            $salesCount = rand(5, 15); // 5-15 sales per day

            for ($i = 0; $i < $salesCount; $i++) {
                $customer = $customers->random();
                $user = $users->random();
                
                // Generate invoice number
                $invoiceNumber = 'INV-' . $saleDate->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
                
                // Select random products for this sale
                $saleProducts = $products->random(rand(1, 5));
                $subtotal = 0;
                $saleItems = [];

                foreach ($saleProducts as $product) {
                    $quantity = rand(1, 5);
                    $unitPrice = $product->selling_price;
                    $discount = rand(0, 10); // 0-10% discount
                    $itemTotal = ($quantity * $unitPrice) * (1 - $discount / 100);
                    
                    $saleItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount' => ($quantity * $unitPrice) * ($discount / 100),
                        'total_price' => $itemTotal,
                    ];
                    
                    $subtotal += $itemTotal;
                }

                // Calculate totals
                $taxRate = rand(0, 10); // 0-10% tax
                $taxAmount = $subtotal * ($taxRate / 100);
                $discountAmount = rand(0, 50); // Random discount
                $totalAmount = $subtotal + $taxAmount - $discountAmount;
                $paymentMethod = ['cash', 'card', 'online'][rand(0, 2)];
                $paidAmount = $totalAmount + rand(0, 20); // Sometimes overpaid
                $changeAmount = max(0, $paidAmount - $totalAmount);

                // Create sale
                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'payment_method' => $paymentMethod,
                    'status' => 'completed',
                    'sale_date' => $saleDate->copy()->addHours(rand(9, 18))->addMinutes(rand(0, 59)),
                ]);

                // Create sale items
                foreach ($saleItems as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'],
                        'total_price' => $item['total_price'],
                    ]);

                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock_quantity', $item['quantity']);
                        
                        // Create stock entry
                        Stock::create([
                            'product_id' => $product->id,
                            'supplier_id' => null,
                            'type' => 'out',
                            'quantity' => $item['quantity'],
                            'unit_cost' => $product->cost_price,
                            'total_cost' => $item['quantity'] * $product->cost_price,
                            'reference' => $invoiceNumber,
                            'notes' => 'Sale transaction',
                            'transaction_date' => $sale->sale_date,
                        ]);
                    }
                }

                // Update customer total purchases
                $customer->increment('total_purchases', $totalAmount);
            }
        }
    }
}
