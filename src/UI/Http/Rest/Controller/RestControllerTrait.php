<?php

declare(strict_types=1);

namespace Bookshop\UI\Http\Rest\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait RestControllerTrait
{
    /** @var SerializerInterface */
    private $serializer;

    private function handleViolations(ConstraintViolationListInterface $violations): Response
    {
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

    private function handleEntity(object $object, $status = Response::HTTP_CREATED): Response
    {
        return new JsonResponse(
            $this->serializer->serialize($object, 'json'),
            $status,
            [],
            true
        );
    }
}
