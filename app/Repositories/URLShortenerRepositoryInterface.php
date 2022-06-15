<?php

namespace App\Repositories;


interface URLShortenerRepositoryInterface
{
    public function createResource(array $data): mixed; // create new

    public function getAll(): mixed; // get all / paginated data

    public function getByColumn(string $columnName, string $value): mixed; // get original URL
}
