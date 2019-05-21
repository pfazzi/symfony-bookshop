<?php

declare(strict_types=1);

namespace Bookshop\Infrastructure\Doctrine;

use Bookshop\Domain\Author\Author;
use Bookshop\Domain\Author\AuthorRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class AuthorRepository implements AuthorRepositoryInterface
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
        $this->objectRepository = $entityManager->getRepository(Author::class);
    }

    public function get(UuidInterface $id): Author
    {
        return $this->objectRepository->find($id);
    }

    public function store(Author $author): void
    {
        $this->entityManager->persist($author);
        $this->entityManager->flush();
    }
}
