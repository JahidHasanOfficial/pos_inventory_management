<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Services\SalesService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    protected SalesService $salesService;

    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Sale::with(['customer', 'user', 'saleItems.product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('sale_date', [$request->date_from, $request->date_to]);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $sales = $query->latest('sale_date')->paginate(15);
        $customers = Customer::where('is_active', true)->get();

        return view('pos.sales.index', compact('sales', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('pos.sales.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,online',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            $saleData = [
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $validated['paid_amount'],
                'status' => 'completed',
            ];

            $sale = $this->salesService->createSale($saleData, $validated['items']);

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'sale' => $sale->load(['customer', 'saleItems.product']),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete sale: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale): View
    {
        $sale->load(['customer', 'user', 'saleItems.product']);
        return view('pos.sales.show', compact('sale'));
    }

    /**
     * Get product details for POS
     */
    public function getProduct(Request $request): JsonResponse
    {
        $product = Product::where('is_active', true)
                         ->where(function ($query) use ($request) {
                             $query->where('sku', $request->identifier)
                                   ->orWhere('barcode', $request->identifier);
                         })
                         ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        if ($product->stock_quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product out of stock',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'selling_price' => $product->selling_price,
                'stock_quantity' => $product->stock_quantity,
                'unit' => $product->unit,
            ],
        ]);
    }

    /**
     * Cancel a sale
     */
    public function cancel(Sale $sale): RedirectResponse
    {
        if ($sale->status !== 'completed') {
            return redirect()->back()->with('error', 'Only completed sales can be cancelled.');
        }

        $this->salesService->cancelSale($sale);

        return redirect()->back()->with('success', 'Sale cancelled successfully.');
    }
}
