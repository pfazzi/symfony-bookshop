<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BookController
{
    public function create(Request $request): Response
    {
        return Response::create(); // TODO: to be implemented
    }
}
