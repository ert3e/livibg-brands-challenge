<?php
namespace App\Repositories;

interface SearchRepositoryInterface
{
    public function find(string $query): ?array;
    public function save(string $query, array $results): void;
}
