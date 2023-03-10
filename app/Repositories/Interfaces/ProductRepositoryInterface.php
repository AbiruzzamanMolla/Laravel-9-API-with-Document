<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;

interface ProductRepositoryInterface
{
    public function getAll(array $filterData): Paginator;

    public function getById(int $id): object|null;

    public function getBySlug(string $slug): object|null;

    public function create(array $data): object|null;
}
