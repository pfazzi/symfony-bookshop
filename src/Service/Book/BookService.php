<?php

declare(strict_types=1);

namespace App\Service\Book;

use App\Model\Author\AuthorRepositoryInterface;
use App\Model\Book\BookFactory;
use App\Model\Book\BookRepositoryInterface;

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

    public function addToCatalog(AddBookToCatalogRequest $command): void
    {
        $book = $this->factory->create(
            $command->getIsbn(),
            $command->getTitle(),
            $this->authorRepository->get($command->getAuthorId()),
            $command->getPrice()
        );

        $this->bookRepository->store($book);
    }
}
