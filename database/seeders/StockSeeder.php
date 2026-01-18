<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        if ($products->isEmpty() || $suppliers->isEmpty()) {
            return;
        }

        // Create initial stock entries for products that don't have any stock movements yet
        // This handles products created directly without purchases
        foreach ($products as $product) {
            $hasStockMovements = Stock::where('product_id', $product->id)->exists();
            
            // Only create initial stock entry if product has stock but no stock movements
            if (!$hasStockMovements && $product->stock_quantity > 0) {
                $supplier = $suppliers->random();
                
                Stock::create([
                    'product_id' => $product->id,
                    'supplier_id' => $supplier->id,
                    'type' => 'in',
                    'quantity' => $product->stock_quantity,
                    'unit_cost' => $product->cost_price,
                    'total_cost' => $product->stock_quantity * $product->cost_price,
                    'reference' => 'Initial Stock',
                    'notes' => 'Initial stock entry from seeder',
                    'transaction_date' => Carbon::now()->subDays(rand(60, 90)),
                ]);
            }
        }
    }
}
