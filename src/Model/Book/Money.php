<?php

declare(strict_types=1);

namespace App\Model\Book;

use Assert\Assertion;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Pfazzi\Isbn\Doctrine\IsbnType;

/**
 * @ORM\Embeddable()
 */
final class Money
{
    /**
     * @ORM\Column(type="decimal",  precision=7, scale=2)
     * @Serializer\Type("float")
     *
     * @var float
     */
    private $amount;

    /**
     * @ORM\Column(length=3)
     *
     * @var float
     */
    private $currency;

    private function __construct(float $amount, string $currency)
    {
        Assertion::length($currency, 3);

        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function create(float $amount, string $currency = 'EUR'): self
    {
        return new self($amount, $currency);
    }

    public function getAmount(): float
    {
        return (float) $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
