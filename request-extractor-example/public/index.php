<?php

declare(strict_types=1);

require '../vendor/autoload.php';

(function () {
    $containerBuilder = new DI\ContainerBuilder();
    $containerBuilder->useAutowiring(true);
    $container = $containerBuilder->build();
    $requestHandlerFactory = new N1215\RequestExtractor\RequestHandlerFactory($container);
    $requestHandler = $requestHandlerFactory->create([new N1215\RequestExtractorExample\Http\Handlers\MyHandler(), 'handle']);

    $psr17Factory = new Nyholm\Psr7\Factory\Psr17Factory();
    $creator = new Nyholm\Psr7Server\ServerRequestCreator(
        $psr17Factory,
        $psr17Factory,
        $psr17Factory,
        $psr17Factory
    );
    $request = $creator->fromGlobals();

    $response = $requestHandler->handle($request);

    $responseEmitter = new Laminas\HttpHandlerRunner\Emitter\SapiEmitter();
    $responseEmitter->emit($response);
})();
