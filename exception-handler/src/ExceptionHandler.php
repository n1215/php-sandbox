<?php
declare(strict_types=1);

namespace N1215\ExceptionHandler;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;

/**
 * Class ExceptionHandler
 * @package App\Http\Responder\Post
 */
class ExceptionHandler
{
    /**
     * @var object
     */
    private $exceptionHandlerTemplate;

    /**
     * @var ReflectionMethod[]
     */
    private $refMethodMap;

    /**
     * ArgumentBasedMethodDispatcher constructor.
     * @param object $exceptionHandlerTemplate
     */
    public function __construct($exceptionHandlerTemplate)
    {
        $this->exceptionHandlerTemplate = $exceptionHandlerTemplate;
        try {
            $refClass = new ReflectionClass($exceptionHandlerTemplate);
        } catch (ReflectionException $e) {
            $className = get_class($exceptionHandlerTemplate);
            throw new InvalidArgumentException(
                'failed to get template class information. className = ' . $className, 0, $e
            );
        }
        $refMethods = $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
        $this->refMethodMap = array_combine(array_map([$this, 'getTargetClass'], $refMethods), $refMethods);
        unset($this->refMethodMap[null]);
    }

    /**
     * @param ReflectionMethod $refMethod
     * @return string|null
     */
    private function getTargetClass(ReflectionMethod $refMethod)
    {
        $refParams = $refMethod->getParameters();
        if (count($refParams) !== 1) {
            return null;
        }
        $firstRefParam = $refParams[0];

        $class = $firstRefParam->getClass();
        if ($class === null) {
            return null;
        }

        return $firstRefParam->getClass()->getName();
    }

    /**
     * @param Throwable|object $exception
     * @return bool
     */
    public function supports(Throwable $exception): bool
    {
        foreach ($this->refMethodMap as $class => $refMethod) {
            if ($exception instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Throwable$exception
     * @return mixed
     */
    public function handle(Throwable $exception)
    {
        foreach ($this->refMethodMap as $class => $refMethod) {
            if ($exception instanceof $class) {
                return $refMethod->invoke($this->exceptionHandlerTemplate, $exception);
            }
        }

        throw new InvalidArgumentException(get_class($this->exceptionHandlerTemplate) . ' doesn\'t supports the given class ' . get_class($exception));
    }
}
