<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    /**
     * Create a new supplier
     */
    public function createSupplier(array $data): Supplier
    {
        return Supplier::create($data);
    }

    /**
     * Update a supplier
     */
    public function updateSupplier(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);

        return $supplier;
    }

    /**
     * Delete a supplier
     */
    public function deleteSupplier(Supplier $supplier): bool
    {
        // Check if supplier has purchases or stock transactions
        if ($supplier->purchases()->exists() || $supplier->stocks()->exists()) {
            throw new \Exception('Cannot delete supplier with existing transactions.');
        }

        return $supplier->delete();
    }

    /**
     * Get supplier by ID or name
     */
    public function findByIdOrName(string $identifier): ?Supplier
    {
        return Supplier::where('id', $identifier)
                      ->orWhere('name', 'like', '%' . $identifier . '%')
                      ->where('is_active', true)
                      ->first();
    }

    /**
     * Get active suppliers
     */
    public function getActiveSuppliers()
    {
        return Supplier::where('is_active', true)->get();
    }
}
