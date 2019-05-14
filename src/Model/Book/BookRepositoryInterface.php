<?php

declare(strict_types=1);

namespace App\Model\Book;

use Pfazzi\Isbn\Isbn;

interface BookRepositoryInterface
{
    public function get(Isbn $isbn): Book;

    public function store(Book $book): void;
}
