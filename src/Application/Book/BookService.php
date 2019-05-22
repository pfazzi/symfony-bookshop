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

    public function handleNewBook(NewBookCommand $command): Book
    {
        $book = $this->factory->create(
            $command->getIsbn(),
            $command->getTitle(),
            $this->authorRepository->get($command->getAuthorId()),
            $command->getPrice()
        );

        $this->bookRepository->store($book);

        return $book;
    }
}
