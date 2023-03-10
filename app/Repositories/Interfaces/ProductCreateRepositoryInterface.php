<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductCreateRepositoryInterface
{
    public function insertData(Product $product, array $data): ?Product;

    public function addUserId(Product $product): ?int;

    public function insertSlug(Product $product, string $slug): ?string;

    public function uploadImage(Product $product, object $image): ?string;
}
