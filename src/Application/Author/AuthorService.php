<?php

declare(strict_types=1);

namespace Bookshop\Application\Author;

use Bookshop\Domain\Author\Author;
use Bookshop\Domain\Author\AuthorRepositoryInterface;

class AuthorService
{
    /** @var AuthorRepositoryInterface */
    private $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handleNewAuthor(NewAuthorCommand $command): Author
    {
        $author = new Author(
            $command->getId(),
            $command->getName()
        );

        $this->repository->store($author);

        return $author;
    }
}
