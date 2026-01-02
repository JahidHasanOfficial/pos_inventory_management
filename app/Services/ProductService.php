<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Stock;

class ProductService
{
    /**
     * Create a new product
     */
    public function createProduct(array $data): Product
    {
        $product = Product::create($data);

        // Create initial stock entry if quantity > 0
        if ($data['stock_quantity'] > 0) {
            Stock::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $data['stock_quantity'],
                'unit_cost' => $data['cost_price'],
                'total_cost' => $data['cost_quantity'] * $data['cost_price'],
                'reference' => 'Initial Stock',
                'transaction_date' => now(),
            ]);
        }

        return $product;
    }

    /**
     * Update a product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        // Calculate stock difference
        $stockDifference = $data['stock_quantity'] - $product->stock_quantity;

        $product->update($data);

        // Create stock adjustment entry if quantity changed
        if ($stockDifference != 0) {
            Stock::create([
                'product_id' => $product->id,
                'type' => $stockDifference > 0 ? 'in' : 'out',
                'quantity' => abs($stockDifference),
                'unit_cost' => $data['cost_price'],
                'total_cost' => abs($stockDifference) * $data['cost_price'],
                'reference' => 'Stock Adjustment',
                'notes' => 'Updated via product edit',
                'transaction_date' => now(),
            ]);
        }

        return $product;
    }

    /**
     * Delete a product
     */
    public function deleteProduct(Product $product): bool
    {
        // Check if product has sales or purchases
        if ($product->saleItems()->exists() || $product->purchaseItems()->exists()) {
            throw new \Exception('Cannot delete product with existing transactions.');
        }

        return $product->delete();
    }

    /**
     * Adjust stock quantity
     */
    public function adjustStock(Product $product, int $quantity, string $type, array $additionalData = []): Stock
    {
        $newQuantity = $type === 'in'
            ? $product->stock_quantity + $quantity
            : $product->stock_quantity - $quantity;

        if ($newQuantity < 0) {
            throw new \Exception('Insufficient stock for this operation.');
        }

        // Update product stock
        $product->update(['stock_quantity' => $newQuantity]);

        // Create stock entry
        return Stock::create(array_merge([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'transaction_date' => now(),
        ], $additionalData));
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts()
    {
        return Product::where('stock_quantity', '<=', \DB::raw('min_stock_level'))
                     ->where('is_active', true)
                     ->get();
    }

    /**
     * Get product by SKU or barcode
     */
    public function findBySkuOrBarcode(string $identifier): ?Product
    {
        return Product::where('sku', $identifier)
                     ->orWhere('barcode', $identifier)
                     ->where('is_active', true)
                     ->first();
    }
}
