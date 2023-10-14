<?php

declare(strict_types=1);

namespace N1215\RequestExtractor;

use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerFactory
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function create(callable $pseudoHandler): RequestHandlerInterface
    {
        $closure = \Closure::fromCallable($pseudoHandler);
        $refFunc = new \ReflectionFunction($closure);
        $refParams = $refFunc->getParameters();

        /**
         * @var array<string, mixed> $extractorOrParamsMap
         */
        $extractorOrParamsMap = [];
        foreach ($refParams as $refParam) {
            $refAttributes = $refParam->getAttributes();
            foreach ($refAttributes as $refAttribute) {
                $attrName = $refAttribute->getName();
                if (is_subclass_of($attrName, FromRequestAttributeInterface::class)) {
                    $attributeInstance = $refAttribute->newInstance();
                    assert($attributeInstance instanceof FromRequestAttributeInterface);
                    $requestExtractor = $this->container->get($attributeInstance->getRequestExtractor());
                    $extractorOrParamsMap[$refParam->getName()] = $requestExtractor;
                }
            }

            if (!isset($extractorOrParamsMap[$refParam->getName()])) {
                $refType = $refParam->getType();
                $refTypeName = $refType->getName();
                if ($refTypeName === ServerRequestInterface::class) {
                    $extractorOrParamsMap[$refParam->getName()] = fn (ServerRequestInterface $request) => $request;
                } else {
                    $extractorOrParamsMap[$refParam->getName()] = $this->container->get($refTypeName);
                }
            }
        }

        return new class ($extractorOrParamsMap, $refFunc) implements RequestHandlerInterface {
            /**
             * @param array<string, RequestExtractorInterface|mixed> $extractorOrParamsMap
             * @param \Closure $closure
             * @param \ReflectionFunction $refFunc
             */
            public function __construct(
                private readonly array $extractorOrParamsMap,
                private readonly \ReflectionFunction $refFunc,
            ) {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $extractedValues = array_map(
                    fn (mixed $extractor) => $extractor instanceof RequestExtractorInterface
                        ? $extractor->from($request)
                        : $extractor,
                    $this->extractorOrParamsMap,
                );
                $result = $this->refFunc->invokeArgs($extractedValues);
                return new Response(200, [], json_encode($result));
            }
        };
    }
}