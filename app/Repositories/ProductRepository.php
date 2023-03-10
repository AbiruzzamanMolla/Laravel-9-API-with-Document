<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductCreateRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductUpdateRepositoryInterface;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductRepositoryInterface, ProductCreateRepositoryInterface, ProductUpdateRepositoryInterface
{
    /**
     * @param int|null $perPage
     *
     * @return Paginator
     */
    public function getAll(?array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);

        $query = Product::orderBy($filter['orderBy'], $filter['order']);

        if (!empty($filter['search'])) {
            $query->where(function ($query) use ($filter) {
                $keyword = $filter['search'];
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        return $query->paginate($filter['perPage']);
    }


    public function getFilterData(array $filterData): array
    {
        $defaultPerms = [
            'perPage' => 10,
            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc',
        ];

        return array_merge($defaultPerms, $filterData);
    }

    /**
     * @param mixed $id
     *
     * @return Product|null
     */
    public function getById(int $id): ?Product
    {
        $product =  Product::with('user')->find($id);

        if (!$product) {
            throw new Exception('Product not found.', Response::HTTP_NOT_FOUND);
        }

        return $product;
    }

    /**
     * @param mixed $uuid
     *
     * @return Product|null
     */
    public function getBySlug($slug): ?Product
    {
        $product =  Product::with('user')->where('slug', $slug)->first();

        if ($product) {
            throw new Exception('Product not found.', Response::HTTP_NOT_FOUND);
        }

        return $product;
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
        if ($data['image']) $this->uploadImage($product, $data['image']);

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
        return $product->slug =  Str::slug($slug, '-');
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

    /**
     * @param int $id
     * @param array $data
     *
     * @return Product|null
     */
    public function update(int $id, array $data): ?Product
    {
        $product = $this->getById($id);

        $this->updateData($product, $data);

        if (!empty($data['image'])) {
            $this->deleteExistingImage($product);
            $this->uploadImage($product, $data['image']);
        }

        if (!$product->save()) {
            throw new Exception('Product updating failed.', Response::HTTP_INTERNAL_SERVER_ERROR);
        };

        return $product;
    }

    /**
     * @param Product $product
     * @param array $data
     *
     * @return Product|null
     */
    public function updateData(Product $product, array $data): ?Product
    {
        return $product->fill(Arr::except($data, ['image']));
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    public function deleteExistingImage(Product $product): bool
    {
        if (!empty($product->image) && Storage::exists('public/' . $product->image)) {
            return Storage::delete('public/' . $product->image);
        }
    }

    /**
     * @param int $id
     *
     * @return Product|null
     */
    public function delete(int $id): ?Product
    {
        $product = $this->getById($id);

        $this->deleteExistingImage($product);

        $deleted = $product->delete();

        if (!$deleted) {
            throw new Exception('Product deleting failed.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $product;
    }
}
