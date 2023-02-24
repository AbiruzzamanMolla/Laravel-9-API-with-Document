<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(): Paginator
    {
        return Product::paginate(10);
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
