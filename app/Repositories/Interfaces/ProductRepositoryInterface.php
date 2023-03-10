<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;

interface ProductRepositoryInterface
{
    public function getAll(int $perPage): Paginator;

    public function getById(int $id): object|null;

    public function getByUuid(string $uuid): object|null;

    public function create(array $data): object|null;
}
