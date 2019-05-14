<?php

declare(strict_types=1);

namespace App\Model\Book;

use App\Model\Author\Author;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Pfazzi\Isbn\Isbn;

/**
 * @ORM\Entity()
 */
final class Book
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="isbn")
     * @Serializer\Type("string")
     *
     * @var Isbn
     */
    private $isbn;

    /**
     * @ORM\Embedded(class="App\Model\Book\Title", columnPrefix=false)
     * @Serializer\Type("string")
     *
     * @var Title
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Author\Author")
     *
     * @var Author
     */
    private $author;

    /**
     * @ORM\Embedded(class="App\Model\Book\Money")
     *
     * @var Money
     */
    private $price;

    public function __construct(Isbn $isbn, Title $title, Author $author, Money $price)
    {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->price = $price;
    }

    public function changePrice(Money $price): void
    {
        $this->price = $price;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getIsbn(): Isbn
    {
        return $this->isbn;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
}
