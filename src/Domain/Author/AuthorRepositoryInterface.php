<?php

declare(strict_types=1);

namespace Bookshop\Domain\Author;

use Ramsey\Uuid\UuidInterface;

interface AuthorRepositoryInterface
{
    public function get(UuidInterface $id): Author;

    public function store(Author $author): void;
}
