<?php

declare(strict_types=1);

namespace Bookshop\Domain\Book;

use Assert\Assertion;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class Title
{
    /**
     * @ORM\Column(length=200, name="title")
     *
     * @var string
     */
    private $value;

    private function __construct($value)
    {
        Assertion::maxLength($value, 200);

        $this->value = $value;
    }

    public static function fromString(string $s): self
    {
        return new self($s);
    }

    public function __toString()
    {
        return $this->value;
    }
}
