<?php

declare(strict_types=1);

namespace Bookshop\Domain\Author;

use Assert\Assertion;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class Name
{
    /**
     * @ORM\Column(length=30, name="name")
     *
     * @var string
     */
    private $value;

    private function __construct($value)
    {
        Assertion::length($value, 30);

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
