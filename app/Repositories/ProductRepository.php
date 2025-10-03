<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function getAll(): Collection
    {
        return Product::orderBy('pro_name')->get();
    }

    public function findById(string $id): Product
    {
        return Product::findOrFail($id);
    }

    public function update(string $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product->fresh();
    }

    public function delete(string $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}