<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(?int $perPage = 10): Paginator
    {
        return Product::paginate($perPage);
    }
    public function getById($id): Product
    {
        return Product::find($id);
    }
    public function getByUuid($uuid): Product
    {
        return Product::whereUuid($uuid)->first();
    }
}
