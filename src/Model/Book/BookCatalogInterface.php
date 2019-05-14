<?php

declare(strict_types=1);

namespace App\Model\Book;

use Pfazzi\Isbn\Isbn;

interface BookCatalogInterface
{
    public function exists(Isbn $isbn): bool;
}
