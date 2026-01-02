<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    /**
     * Create a new purchase
     */
    public function createPurchase(array $purchaseData, array $items): Purchase
    {
        return DB::transaction(function () use ($purchaseData, $items) {
            // Generate purchase number
            $purchaseData['purchase_number'] = $this->generatePurchaseNumber();
            $purchaseData['purchase_date'] = now();

            // Create purchase
            $purchase = Purchase::create($purchaseData);

            // Create purchase items and update stock
            $subtotal = 0;
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $totalCost = $item['quantity'] * $item['unit_cost'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $totalCost,
                ]);

                // Update product stock and cost
                $product->increment('stock_quantity', $item['quantity']);
                // Optionally update selling price based on cost + markup
                if (isset($item['selling_price'])) {
                    $product->update(['selling_price' => $item['selling_price']]);
                }

                $subtotal += $totalCost;
            }

            // Calculate totals
            $taxAmount = ($subtotal * ($purchaseData['tax_amount'] ?? 0)) / 100;
            $discountAmount = $purchaseData['discount_amount'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            // Update purchase with calculated amounts
            $purchase->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
            ]);

            return $purchase;
        });
    }

    /**
     * Update purchase payment status
     */
    public function updatePayment(Purchase $purchase, array $paymentData): Purchase
    {
        $purchase->update([
            'paid_amount' => $paymentData['paid_amount'],
        ]);

        return $purchase;
    }

    /**
     * Cancel a purchase
     */
    public function cancelPurchase(Purchase $purchase): Purchase
    {
        return DB::transaction(function () use ($purchase) {
            // Restore stock by reducing quantities
            foreach ($purchase->purchaseItems as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            $purchase->update(['status' => 'cancelled']);
            return $purchase;
        });
    }

    /**
     * Generate unique purchase number
     */
    private function generatePurchaseNumber(): string
    {
        do {
            $number = 'PUR-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Purchase::where('purchase_number', $number)->exists());

        return $number;
    }

    /**
     * Get purchase statistics
     */
    public function getPurchaseStats(string $period = 'today'): array
    {
        $query = Purchase::where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('purchase_date', today());
                break;
            case 'week':
                $query->whereBetween('purchase_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('purchase_date', now()->month)
                      ->whereYear('purchase_date', now()->year);
                break;
        }

        return [
            'total_purchases' => $query->count(),
            'total_cost' => $query->sum('total_amount'),
            'total_items' => $query->with('purchaseItems')->get()->sum(function ($purchase) {
                return $purchase->purchaseItems->sum('quantity');
            }),
        ];
    }
}
