<?php

declare(strict_types=1);

namespace Bookshop\Application\Book;

use Bookshop\Domain\Author\AuthorRepositoryInterface;
use Bookshop\Domain\Book\Book;
use Bookshop\Domain\Book\BookFactory;
use Bookshop\Domain\Book\BookRepositoryInterface;

final class BookService
{
    /** @var AuthorRepositoryInterface */
    private $authorRepository;

    /** @var BookRepositoryInterface */
    private $bookRepository;

    /** @var BookFactory */
    private $factory;

    public function __construct(
        AuthorRepositoryInterface $authorRepository,
        BookRepositoryInterface $bookRepository,
        BookFactory $factory
    ) {
        $this->bookRepository = $bookRepository;
        $this->factory = $factory;
        $this->authorRepository = $authorRepository;
    }

    public function handleNewBook(NewBook $dto): Book
    {
        $book = $this->factory->create(
            $dto->getIsbn(),
            $dto->getTitle(),
            $this->authorRepository->get($dto->getAuthorId()),
            $dto->getPrice()
        );

        $this->bookRepository->store($book);

        return $book;
    }
}
