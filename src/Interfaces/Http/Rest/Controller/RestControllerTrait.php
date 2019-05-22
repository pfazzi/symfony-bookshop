<?php

declare(strict_types=1);

namespace Bookshop\Interfaces\Http\Rest\Controller;

use JMS\Serializer\SerializerInterface;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait RestControllerTrait
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    private $validator;

    /** @var CommandBus */
    private $commandBus;

    /**
     * @param Request $request
     * @param string  $commandType
     * @param int     $successfulState
     *
     * @return Response
     *
     * @throws \Exception
     * @template T
     * @psalm-param class-string<T> $commandType
     */
    private function handleCommandRequest(
        Request $request,
        string $commandType,
        int $successfulState = Response::HTTP_OK
    ): Response {
        return $this->buildResponse(
            $this->commandBus->handle(
                $this->deserializeRequest($request, $commandType)
            ),
            $successfulState
        );
    }

    /**
     * @param Request $request
     * @param string  $type
     *
     * @template T
     * @psalm-param class-string<T> $type
     * @psalm-return T
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function deserializeRequest(Request $request, string $type)
    {
        $content = $request->getContent();
        if (is_resource($content)) {
            throw new \Exception('Unable to deserialize a resource');
        }

        return $this->serializer->deserialize(
            $content,
            $type,
            'json'
        );
    }

    private function validate(object $dto): ConstraintViolationListInterface
    {
        return $this->validator->validate($dto);
    }

    /**
     * @param Request $request
     * @param string  $type
     *
     * @template T
     * @psalm-param class-string<T> $type
     *
     * @return array
     *
     * @throws \Exception
     */
    private function deserializeAndValidateDto(Request $request, string $type): array
    {
        $violations = $this->validate(
            /* @var T $dto */
            $dto = $this->deserializeRequest($request, $type)
        );

        return [
            'dto' => $dto,
            'violations' => $violations,
        ];
    }

    private function buildBadRequestResponse(ConstraintViolationListInterface $violations): Response
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

    private function buildResponse(object $object, $status = Response::HTTP_OK): Response
    {
        return new JsonResponse(
            $this->serializer->serialize($object, 'json'),
            $status,
            [],
            true
        );
    }
}
