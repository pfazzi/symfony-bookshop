<?php

declare(strict_types=1);

namespace Bookshop\Interfaces\Http\Web\Controller;

use Bookshop\Domain\Book\BookRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BookController extends AbstractController
{
    /** @var BookRepositoryInterface */
    private $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param Request $request
     * @Route(methods={"GET"}, path="books", name="book_list")
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        return $this->render(
            'book/index.html.twig',
            [
                'books' => $this->bookRepository->getAll(),
            ]
        );
    }
}
