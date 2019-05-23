<?php

declare(strict_types=1);

namespace Bookshop\Infrastructure\Doctrine;

use Bookshop\Domain\Book\Book;
use Bookshop\Domain\Book\BookCatalogInterface;
use Bookshop\Domain\Book\BookRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pfazzi\Isbn\Isbn;

class BookRepository implements BookRepositoryInterface, BookCatalogInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ObjectRepository */
    private $objectRepository;

    /**
     * BookRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $entityManager->getRepository(Book::class);
    }

    public function get(Isbn $isbn): Book
    {
        return $this->objectRepository->find($isbn);
    }

    public function store(Book $book): void
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function exists(Isbn $isbn): bool
    {
        if (null === $this->objectRepository->find($isbn)) {
            return false;
        }

        return true;
    }

    public function getAll(): array
    {
        return $this->objectRepository->findAll();
    }


}
