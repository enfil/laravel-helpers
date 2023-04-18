<?php

namespace Enfil\Laravel\Helpers\Response;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Api
{
    protected int $code = 200;
    protected ?string $message;
    protected ?array $data;
    protected ?array $errors;
    protected ?array $pagination;
    protected ?array $customData = null;

    public function __construct(
        int $code = 200,
        string $message = null,
        array $data = null,
        array $errors = null,
        array $pagination = null,
        array $customData = null
    ) {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
        $this->pagination = $pagination;
        $this->customData = $customData;
    }

    public static function unauthorized(): self
    {
        return new self(Response::HTTP_UNAUTHORIZED);
    }

    public static function forbidden(string $message = 'Доступ запрещён'): self
    {
        return new self(Response::HTTP_FORBIDDEN, $message);
    }

    public static function validationFailed(array $errors, string $message = 'Ошибка валидации'): self
    {
        return new self(Response::HTTP_UNPROCESSABLE_ENTITY, $message, null, $errors);
    }

    public static function notFound(string $message): self
    {
        return new self(Response::HTTP_NOT_FOUND);
    }

    public static function created(array $data = []): self
    {
        return new self(Response::HTTP_CREATED, null, $data);
    }

    public static function successData(array $data): self
    {
        return new self(Response::HTTP_OK, null, $data);
    }

    public static function successDataPaginated(
        Request $request,
        LengthAwarePaginator $paginator,
        ?string $resourceClass = null,
        ?array $customData = null
    ): self {
        if (null === $resourceClass) {
            $data = $paginator->items();
        } else {
            $data = (new $resourceClass($paginator->items()))->toArray($request);
        }

        $pagination = [
            'page' => $paginator->currentPage(),
            'total' => $paginator->total(),
            'max' => $paginator->perPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'pages' => $paginator->lastPage(),

            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),

            'deprecatedKeys' => [
                'currentPage',
                'lastPage',
                'perPage',
            ]
        ];

        return new self(Response::HTTP_OK, null, $data, null, $pagination, $customData);
    }

    public static function successMessage(string $message): self
    {
        return new self(Response::HTTP_OK, $message);
    }

    public static function successEmpty(): self
    {
        return new self(Response::HTTP_OK);
    }

    public static function errorMessage(string $message): self
    {
        return new self(Response::HTTP_BAD_REQUEST, $message);
    }

    public static function errorEmpty(): self
    {
        return new self(Response::HTTP_BAD_REQUEST);
    }

    public function toJson(): JsonResponse
    {
        $content = [];
        if (isset($this->data)) {
            $content['data'] = $this->data;

            if ($this->pagination) {
                $content['pagination'] = $this->pagination;
            }
        }

        if ($this->message) {
            $content['message'] = $this->message;
        }

        if ($this->errors) {
            $content['errors'] = $this->errors;
        }

        if ($this->customData) {
            $content = array_merge($content, $this->customData);
        }

        return new JsonResponse(
            $content,
            $this->code
        );
    }
}
