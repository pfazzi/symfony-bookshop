<?php

declare(strict_types=1);

namespace Bookshop\Application\Author;

use Bookshop\Domain\Author\Name;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NewAuthorCommand
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     * @var string
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="30")
     * @var string
     */
    private $name;

    /**
     * NewAuthorCommand constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function getName(): Name
    {
        return Name::fromString($this->name);
    }


}
