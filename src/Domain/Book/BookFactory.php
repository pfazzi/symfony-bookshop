<?php

declare(strict_types=1);

namespace Bookshop\Domain\Book;

use Bookshop\Domain\Author\Author;
use Pfazzi\Isbn\Isbn;

final class BookFactory
{
    /** @var BookCatalogInterface */
    private $catalog;

    public function __construct(BookCatalogInterface $catalog)
    {
        $this->catalog = $catalog;
    }

    public function create(Isbn $isbn, Title $title, Author $author, Money $price): Book
    {
        if ($this->catalog->exists($isbn)) {
            throw new \InvalidArgumentException('Book already added to catalog.');
        }

        return new Book($isbn, $title, $author, $price);
    }
}
