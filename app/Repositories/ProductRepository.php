<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    /**
     * Retrieve all products.
     *
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        return Product::all();
    }

    /**
     * Retrieve a specific product by ID.
     *
     * @param int $id
     * @return Product|null
     */
    public function getProductById(int $id): Product|null
    {
        return Product::findOrFail($id);
    }

    /**
     * Store a new product.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }
}
