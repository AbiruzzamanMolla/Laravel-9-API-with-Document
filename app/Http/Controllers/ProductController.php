<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseTrait;

    public function __construct(public ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get All Product for restful api",
     *     description="Get all the products with pagination through perPage parameter",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Enter Par Page Product counts",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="10",
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by title",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Order by column name",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="id",
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Order by assending and descending order",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="desc",
     *             type="string",
     *         )
     *     ),
     *     security={{ "bearer":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->productRepository->getAll($request->all());

            return $this->responseSuccess(
                $data,
                'Products fetched successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create Product",
     *     description="Create a new Product",
     *     operationId="store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Product Title",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     description="Product unique slug",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Product price",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Product Image",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *                required={"title", "price"}
     *             )
     *         )
     *     ),
     *     security={{ "bearer":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input parameters"
     *     )
     * )
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productRepository->create($request->all());
            $data = new ProductResource($product);
            return $this->responseSuccess(
                $data,
                'Product created successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }


    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Get Product for restful api",
     *     description="Get the product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Enter product id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="",
     *             type="integer",
     *         )
     *     ),
     *     security={{ "bearer":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->productRepository->getById($id);
            $product = new ProductResource($data);
            return $this->responseSuccess(
                $product,
                'Product fetched successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update Product",
     *     description="Update existing product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Enter product id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="",
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="Method",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="PUT",
     *             type="string",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Product Title",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Product price",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Product Image",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *                required={"title", "price"}
     *             )
     *         )
     *     ),
     *     security={{ "bearer":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input parameters"
     *     )
     * )
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        try {
            $product = $this->productRepository->update($id, $request->all());
            $data = new ProductResource($product);
            return $this->responseSuccess(
                $data,
                'Product updated successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a product",
     *     description="Delete the product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Enter product id",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="",
     *             type="integer",
     *         )
     *     ),
     *     security={{ "bearer":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $product = $this->productRepository->delete($id);
            $data = new ProductResource($product);
            return $this->responseSuccess(
                $data,
                'Product deleted successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }
}
