<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductUpdateRepositoryInterface
{
    public function updateData(Product $product, array $data): ?Product;
}
