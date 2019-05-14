<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Model\Book\BookRepositoryInterface;
use App\Service\Book\AddBookToCatalogRequest;
use App\Service\Book\BookService;
use JMS\Serializer\SerializerInterface;
use Pfazzi\Isbn\Isbn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
     */
    public function create(Request $request): Response
    {
        /** @var AddBookToCatalogRequest $dto */
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            AddBookToCatalogRequest::class,
            'json'
        );

        /** @var ConstraintViolationListInterface $violations */
        $violations = $this->validator->validate($dto);
        if ($violations->count() > 0) {
            return $this->handleViolations($violations);
        }

        $this->bookService->addToCatalog($dto);

        return $this->handleEntity(
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
        return $this->handleEntity(
            $this->bookRepository->get(Isbn::fromString($isbn)),
            Response::HTTP_CREATED
        );
    }
}
