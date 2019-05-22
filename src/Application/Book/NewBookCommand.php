<?php

declare(strict_types=1);

namespace Bookshop\Application\Book;

use Bookshop\Domain\Book\Money;
use Bookshop\Domain\Book\Title;
use JMS\Serializer\Annotation as Serializer;
use Pfazzi\Isbn\Isbn;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class NewBookCommand
{
    /**
     * @Assert\NotBlank()
     * @Assert\Isbn()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $isbn;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="200")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $authorId;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\GreaterThanOrEqual(value="0")
     * @Serializer\Type("float")
     *
     * @var float
     */
    private $price;

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setAuthorId(string $authorId): void
    {
        $this->authorId = $authorId;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getIsbn(): Isbn
    {
        return Isbn::fromString($this->isbn);
    }

    public function getTitle(): Title
    {
        return Title::fromString($this->title);
    }

    public function getAuthorId(): UuidInterface
    {
        return Uuid::fromString($this->authorId);
    }

    public function getPrice(): Money
    {
        return Money::create($this->price);
    }
}
