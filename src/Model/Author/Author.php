<?php

declare(strict_types=1);

namespace App\Model\Author;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class Author
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Serializer\Type("string")
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Embedded(class="App\Model\Author\Name", columnPrefix=false)
     * @Serializer\Type("string")
     *
     * @var Name
     */
    private $name;

    public function __construct(UuidInterface $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
