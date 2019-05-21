<?php

declare(strict_types=1);

namespace Bookshop\UI\Http\Rest\Controller;

use Bookshop\Application\Book\AddBookToCatalogDTO;
use Bookshop\Application\Book\BookService;
use Bookshop\Domain\Book\BookRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use Pfazzi\Isbn\Isbn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BookController
{
    use RestControllerTrait;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    private $validator;

    /** @var BookService */
    private $bookService;

    /** @var BookRepositoryInterface */
    private $bookRepository;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        BookService $bookService,
        BookRepositoryInterface $bookRepository
    ) {
        $this->bookService = $bookService;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->bookRepository = $bookRepository;
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
        $violations = $this->validator->validate(
            $dto = $this->deserializeRequest($request, AddBookToCatalogDTO::class)
        );
        if ($violations->count() > 0) {
            return $this->buildBadRequestResponse($violations);
        }

        $this->bookService->addToCatalog($dto);

        return $this->buildSingleResourceResponse(
            $this->bookRepository->get($dto->getIsbn()),
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
        return $this->buildSingleResourceResponse(
            $this->bookRepository->get(Isbn::fromString($isbn)),
            Response::HTTP_OK
        );
    }
}
