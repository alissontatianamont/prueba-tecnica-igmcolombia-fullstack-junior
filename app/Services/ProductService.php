<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {}

    public function store(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    public function getAll(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function findById(string $id): Product
    {
        return $this->productRepository->findById($id);
    }

    public function update(string $id, array $data): Product
    {
        return $this->productRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->productRepository->delete($id);
    }
}