<?php

namespace App\Interfaces;

use App\Abstracts\FormRequest;
use Illuminate\Contracts\Pagination\Paginator;

interface CrudInterface
{
    public function getAll(FormRequest $filter): Paginator;

    public function getById(int $id): object|null;

    public function create(array $data): object|null;

    public function update(int $id, array $data): object|null;

    public function delete(int $id): object|null;
}
