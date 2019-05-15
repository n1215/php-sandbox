<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use N1215\Phlower\Builder\GraphBuilder;
use N1215\Phlower\GraphInterface;
use N1215\Phlower\Source\ArraySource;
use N1215\Phlower\Flow\Take;
use N1215\Phlower\Flow\Tap;
use N1215\Phlower\Flow\Map;
use N1215\Phlower\Sink\CallableSink;

$sleep = new Tap(function ($data) { usleep(200 * 1000); });

$echo = new CallableSink(function (string $x) { echo $x . PHP_EOL; });

/** @var GraphInterface $graph */
$graph = (new GraphBuilder())
    ->from(new ArraySource(range(1, 10000)))
    ->via(new Take(50))
    ->via($sleep)
    ->via(new Map(function ($x) {
        if ($x % 15 === 0) return 'FizzBuzz';
        if ($x % 5 === 0) return 'Buzz';
        if ($x % 3 === 0) return 'Fizz';
        return (string) $x;
    }))
    ->to($echo)
    ->build();

$graph->run();
