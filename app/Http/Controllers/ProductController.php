<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {}

    public function index()
    {
        try {
            $products = $this->productService->getAll();
            return response()->json([
                'data' => $products,
                'message' => 'Products retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Products could not be retrieved',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->store($request->validated());
            return response()->json([
                'data' => $product,
                'message' => 'Product created successfully'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Product could not be created',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $product = $this->productService->findById($id);
            return response()->json([
                'data' => $product,
                'message' => 'Product retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Product not found',
                'message' => $th->getMessage()
            ], 404);
        }
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = $this->productService->update($id, $request->validated());
            return response()->json([
                'data' => $product,
                'message' => 'Product updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Product could not be updated',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->productService->delete($id);
            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Product could not be deleted',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}