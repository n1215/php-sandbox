<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use N1215\ExceptionHandler\ExceptionHandler;
use N1215\ExceptionHandler\WithExceptionHandling;

/**
 * Model
 */
class Post {

    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $text;

    public function __construct(int $id, string $title, string $text)
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
        ];
    }
}

/**
 * Exceptions
 */
class ModelNotFoundException extends Exception { }
class ValidationException extends Exception { }

/**
 * Service
 */
class ShowPostService {

    /**
     * @param int $id
     * @return Post
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function find(int $id): Post
    {
        if ($id <= 0) {
            throw new ValidationException('id must be greater than 0.');
        }

        if ($id !== 1) {

            throw new ModelNotFoundException('model not found');
        }

        return new Post($id, 'dummy-title', 'dummy-body');
    }
}

/**
 * Responder base class
 */
abstract class Responder
{
    /**
     * @var WithExceptionHandling
     */
    private $withExceptionHandling;

    /**
     * @param callable $service
     * @return mixed
     */
    public function respond(callable $service)
    {
        if (!method_exists($this, 'success')) {
            throw new \BadMethodCallException(static::class . ' must implements success() method.');
        }

        if ($this->withExceptionHandling === null) {
            $this->withExceptionHandling = (new WithExceptionHandling(new ExceptionHandler($this), [$this, 'success']));
        }

        return $this->withExceptionHandling->run($service);
    }
}

/**
 * Dummy JSON response
 */
class JsonResponse {
    /** @var array */
    private $body;

    /** @var int */
    private $statusCode;

    public function __construct(array $body, int $statusCode)
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    public function getContent(): string
    {
        return \json_encode($this->body);
    }
}

/**
 * Responder
 */
class ShowPostResponder extends Responder
{
    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function success(Post $post): JsonResponse
    {
        return new JsonResponse($post->toArray(), 200);
    }

    /**
     * @param ModelNotFoundException $e
     * @return JsonResponse
     */
    public function modelNotFound(ModelNotFoundException $e): JsonResponse
    {
        return new JsonResponse(['message' => 'not found'], 404);
    }

    /**
     * @param ValidationException $e
     * @return JsonResponse
     */
    public function runtimeError(ValidationException $e): JsonResponse
    {
        return new JsonResponse(['message' => 'validation error :' . $e->getMessage()], 422);
    }
}

/**
 * Controller
 */
class PostController {

    /** @var ShowPostService */
    private $service;

    /** @var ShowPostResponder */
    private $responder;

    public function __construct(ShowPostService $service, ShowPostResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function show(int $id): JsonResponse
    {
        return $this->responder->respond(function () use ($id) {
            return $this->service->find($id);
        });
    }
}

$responder = new ShowPostResponder();
$service = new ShowPostService();
$controller = new PostController($service, $responder);

test_success: {
    echo PHP_EOL . 'find post id:1' . PHP_EOL;
    $successResponse = $controller->show(1);
    assert($successResponse->getStatusCode() === 200);
    echo $successResponse->getContent() . PHP_EOL;
}

test_not_found: {
    echo PHP_EOL . 'find post id:2' . PHP_EOL;
    $notFoundResponse = $controller->show(2);
    assert($notFoundResponse->getStatusCode() === 404);
    echo $notFoundResponse->getContent() . PHP_EOL;
}

test_validation_failed: {
    echo PHP_EOL . 'find post id:3' . PHP_EOL;
    $validationFailedResponse = $controller->show(-1);
    assert($validationFailedResponse->getStatusCode() === 422);
    echo $validationFailedResponse->getContent() . PHP_EOL;
}
