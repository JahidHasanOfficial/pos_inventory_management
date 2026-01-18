<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();
        $users = User::all();

        if ($products->isEmpty() || $suppliers->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Generate purchases for the last 60 days
        for ($day = 0; $day < 60; $day++) {
            $purchaseDate = Carbon::now()->subDays($day);
            $purchasesCount = rand(1, 5); // 1-5 purchases per day

            for ($i = 0; $i < $purchasesCount; $i++) {
                $supplier = $suppliers->random();
                $user = $users->random();
                
                // Generate purchase number
                $purchaseNumber = 'PUR-' . $purchaseDate->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
                
                // Select random products for this purchase
                $purchaseProducts = $products->random(rand(2, 8));
                $subtotal = 0;
                $purchaseItems = [];

                foreach ($purchaseProducts as $product) {
                    $quantity = rand(10, 100);
                    $unitCost = $product->cost_price * (1 + (rand(-5, 5) / 100)); // ±5% variation
                    $totalCost = $quantity * $unitCost;
                    
                    $purchaseItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_cost' => $unitCost,
                        'total_cost' => $totalCost,
                    ];
                    
                    $subtotal += $totalCost;
                }

                // Calculate totals
                $taxRate = rand(0, 10); // 0-10% tax
                $taxAmount = $subtotal * ($taxRate / 100);
                $discountAmount = rand(0, 100); // Random discount
                $totalAmount = $subtotal + $taxAmount - $discountAmount;
                $paymentMethod = ['cash', 'card', 'online', 'credit'][rand(0, 3)];
                $paidAmount = $paymentMethod === 'credit' ? rand(0, (int)$totalAmount) : $totalAmount;

                // Create purchase
                $purchase = Purchase::create([
                    'purchase_number' => $purchaseNumber,
                    'supplier_id' => $supplier->id,
                    'user_id' => $user->id,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'payment_method' => $paymentMethod,
                    'status' => 'completed',
                    'purchase_date' => $purchaseDate->copy()->addHours(rand(9, 17))->addMinutes(rand(0, 59)),
                ]);

                // Create purchase items and update stock
                foreach ($purchaseItems as $item) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'total_cost' => $item['total_cost'],
                    ]);

                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('stock_quantity', $item['quantity']);
                        
                        // Create stock entry
                        Stock::create([
                            'product_id' => $product->id,
                            'supplier_id' => $supplier->id,
                            'type' => 'in',
                            'quantity' => $item['quantity'],
                            'unit_cost' => $item['unit_cost'],
                            'total_cost' => $item['total_cost'],
                            'reference' => $purchaseNumber,
                            'notes' => 'Purchase order',
                            'transaction_date' => $purchase->purchase_date,
                        ]);
                    }
                }
            }
        }
    }
}
