<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;

class SalesService
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Create a new sale
     */
    public function createSale(array $saleData, array $items): Sale
    {
        return DB::transaction(function () use ($saleData, $items) {
            // Generate invoice number
            $saleData['invoice_number'] = $this->generateInvoiceNumber();
            $saleData['sale_date'] = now();

            // Create sale
            $sale = Sale::create($saleData);

            // Create sale items and update stock
            $subtotal = 0;
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $totalPrice = $item['quantity'] * $item['unit_price'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total_price' => $totalPrice,
                ]);

                // Update product stock
                $this->productService->adjustStock($product, $item['quantity'], 'out', [
                    'reference' => $sale->invoice_number,
                ]);

                $subtotal += $totalPrice;
            }

            // Calculate totals
            $taxAmount = ($subtotal * ($saleData['tax_amount'] ?? 0)) / 100;
            $discountAmount = $saleData['discount_amount'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            // Update sale with calculated amounts
            $sale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
            ]);

            return $sale;
        });
    }

    /**
     * Update sale payment status
     */
    public function updatePayment(Sale $sale, array $paymentData): Sale
    {
        $sale->update([
            'paid_amount' => $paymentData['paid_amount'],
            'change_amount' => max(0, $paymentData['paid_amount'] - $sale->total_amount),
        ]);

        return $sale;
    }

    /**
     * Cancel a sale
     */
    public function cancelSale(Sale $sale): Sale
    {
        return DB::transaction(function () use ($sale) {
            // Restore stock for each item
            foreach ($sale->saleItems as $item) {
                $this->productService->adjustStock($item->product, $item->quantity, 'in', [
                    'reference' => 'Sale Cancelled: ' . $sale->invoice_number,
                    'notes' => 'Stock restored due to sale cancellation',
                ]);
            }

            $sale->update(['status' => 'cancelled']);
            return $sale;
        });
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Sale::where('invoice_number', $number)->exists());

        return $number;
    }

    /**
     * Get sales statistics
     */
    public function getSalesStats(string $period = 'today'): array
    {
        $query = Sale::where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('sale_date', today());
                break;
            case 'week':
                $query->whereBetween('sale_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('sale_date', now()->month)
                      ->whereYear('sale_date', now()->year);
                break;
        }

        return [
            'total_sales' => $query->count(),
            'total_revenue' => $query->sum('total_amount'),
            'total_items' => $query->with('saleItems')->get()->sum(function ($sale) {
                return $sale->saleItems->sum('quantity');
            }),
        ];
    }
}
