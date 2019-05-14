<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Model\Book\BookRepositoryInterface;
use App\Service\Book\AddBookToCatalogRequest;
use App\Service\Book\BookService;
use JMS\Serializer\SerializerInterface;
use Pfazzi\Isbn\Isbn;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BookController
{
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
        /** @var AddBookToCatalogRequest $command */
        $command = $this->serializer->deserialize(
            $request->getContent(),
            AddBookToCatalogRequest::class,
            'json'
        );

        /** @var ConstraintViolationListInterface $violations */
        $violations = $this->validator->validate($command);
        if ($violations->count() > 0) {
            return new JsonResponse(
                $this->serializer->serialize(
                    ['errors' => $violations],
                    'json'
                ),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $this->bookService->addToCatalog($command);

        return new JsonResponse(
            $this->serializer->serialize(
                $this->bookRepository->get($command->getIsbn()),
                'json'
            ),
            Response::HTTP_CREATED,
            [],
            true
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
        return new JsonResponse(
            $this->serializer->serialize(
                $this->bookRepository->get(Isbn::fromString($isbn)),
                'json'
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
