<?php

declare(strict_types=1);

namespace Bookshop\Interfaces\Http\Rest\Controller;

use Bookshop\Application\Book\NewBook;
use Bookshop\Application\Book\BookService;
use Bookshop\Domain\Book\BookRepositoryInterface;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use JMS\Serializer\SerializerInterface;
use League\Tactician\CommandBus;
use Pfazzi\Isbn\Isbn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BookController.
 *
 * @IgnoreAnnotation("template")
 */
final class BookController
{
    use RestControllerTrait;

    /** @var BookRepositoryInterface */
    private $bookRepository;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        CommandBus $commandBus,
        BookRepositoryInterface $bookRepository
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->bookRepository = $bookRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route(methods={"POST"}, path="books")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        return $this->handleCommandRequest(
            $request,
            NewBook::class,
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(methods={"GET"}, path="books/{isbn}")
     *
     * @param string $isbn
     *
     * @return Response
     */
    public function get(string $isbn): Response
    {
        return $this->buildResponse(
            $this->bookRepository->get(Isbn::fromString($isbn))
        );
    }
}
