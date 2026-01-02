<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    protected PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Purchase::with(['supplier', 'user', 'purchaseItems.product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('purchase_date', [$request->date_from, $request->date_to]);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchases = $query->latest('purchase_date')->paginate(15);
        $suppliers = Supplier::where('is_active', true)->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $products = \App\Models\Product::where('is_active', true)->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.selling_price' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,online,credit',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $purchaseData = [
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $validated['paid_amount'] ?? 0,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ];

            $purchase = $this->purchaseService->createPurchase($purchaseData, $validated['items']);

            return response()->json([
                'success' => true,
                'message' => 'Purchase completed successfully!',
                'purchase' => $purchase->load(['supplier', 'purchaseItems.product']),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete purchase: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'user', 'purchaseItems.product']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Purchases are typically not editable once completed
        abort(403, 'Purchases cannot be edited after completion.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Purchases are typically not editable once completed
        abort(403, 'Purchases cannot be edited after completion.');
    }

    /**
     * Cancel a purchase
     */
    public function cancel(Purchase $purchase): RedirectResponse
    {
        if ($purchase->status !== 'completed') {
            return redirect()->back()->with('error', 'Only completed purchases can be cancelled.');
        }

        $this->purchaseService->cancelPurchase($purchase);

        return redirect()->back()->with('success', 'Purchase cancelled successfully.');
    }
}
