<?php

declare(strict_types=1);

namespace Bookshop\UI\Http\Rest\Controller;

use JMS\Serializer\SerializerInterface;
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

    /**
     * @param Request  $request
     * @param string   $dtoType
     * @param callable $action
     * @param int      $successfulState
     *
     * @template T
     * @psalm-param class-string<T> $dtoType
     *
     * @return Response
     *
     * @throws \Exception
     */
    private function handleRequest(
        Request $request,
        string $dtoType,
        callable $action,
        int $successfulState = Response::HTTP_OK
    ): Response {
        [
            'dto' => $dto,
            'violations' => $violations
        ] = $this->deserializeAndValidateDto($request, $dtoType);

        if ($violations->count() > 0) {
            return $this->buildBadRequestResponse($violations);
        }

        return $this->buildSingleResourceResponse(
            call_user_func($action, $dto),
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

    private function buildSingleResourceResponse(object $object, $status = Response::HTTP_CREATED): Response
    {
        return new JsonResponse(
            $this->serializer->serialize($object, 'json'),
            $status,
            [],
            true
        );
    }
}
