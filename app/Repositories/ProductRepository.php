<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductCreateRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface, ProductCreateRepositoryInterface
{
    /**
     * @param int|null $perPage
     *
     * @return Paginator
     */
    public function getAll(?int $perPage = 10): Paginator
    {
        return Product::paginate($perPage);
    }

    /**
     * @param mixed $id
     *
     * @return Product|null
     */
    public function getById($id): ?Product
    {
        return Product::find($id);
    }

    /**
     * @param mixed $uuid
     *
     * @return Product|null
     */
    public function getByUuid($uuid): ?Product
    {
        return Product::whereUuid($uuid)->first();
    }

    /**
     * @param array $data
     *
     * @return Product|null
     */
    public function create(array $data): ?Product
    {
        $product = new Product();

        $this->insertData($product, $data);

        $product->save();

        return $product;
    }

    /**
     * @param Product $product
     * @param array $data
     *
     * @return Product|null
     */
    public function insertData(Product $product, array $data): ?Product
    {
        // fill data from request
        $product->fill($data);
        // add user id
        $this->addUserId($product);
        // add slug if available
        if (!empty($data['slug'])) {
            $this->insertSlug($product, $data['slug']);
        }
        // upload image
        $this->uploadImage($product, $data['image']);

        return $product;
    }

    /**
     * @param Product $product
     *
     * @return int|null
     */
    public function addUserId(Product $product): ?int
    {
        return $product->user_id = auth()->user()->id;
    }

    /**
     * @param Product $product
     * @param string $slug
     *
     * @return string|null
     */
    public function insertSlug(Product $product, string $slug): ?string
    {
        return $product->slug =  Str::slug($slug);
    }

    /**
     * @param Product $product
     * @param object $image
     *
     * @return string|null
     */
    public function uploadImage(Product $product, object $image): ?string
    {
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->storePubliclyAs('public', $imageName);
        return $product->image =  $imageName;
    }
}
