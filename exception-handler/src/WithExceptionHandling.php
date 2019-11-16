<?php
declare(strict_types=1);

namespace N1215\ExceptionHandler;

/**
 * Class WithExceptionHandling
 * @package App\Http\Responder\Post
 */
class WithExceptionHandling
{
    /**
     * @var ExceptionHandler
     */
    private $exceptionHandler;

    /**
     * @var callable|null
     */
    private $successHandler;

    /**
     * WithExceptionHandling constructor.
     * @param ExceptionHandler $exceptionHandler
     * @param callable|null $successHandler
     */
    public function __construct(ExceptionHandler $exceptionHandler, callable $successHandler = null)
    {
        $this->exceptionHandler = $exceptionHandler;
        $this->successHandler = $successHandler;
    }

    /**
     * @param callable $process
     * @return mixed
     */
    public function run(callable $process)
    {
        try {
            $result = $process();
        } catch (\Throwable $e) {
            return $this->failure($e);
        }


        if ($this->successHandler === null) {
            return $result;
        }

        return ($this->successHandler)($result);
    }

    /**
     * @param \Throwable $e
     * @return mixed
     * @throws \Throwable
     */
    public function failure(\Throwable $e)
    {
        if (!$this->exceptionHandler->supports($e)) {
            throw $e;
        }

        return $this->exceptionHandler->handle($e);
    }
}
