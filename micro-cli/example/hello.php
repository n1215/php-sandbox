#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

class Hello
{
    /**
     * @param string $name 挨拶の対象
     * @param int|null $times 回数
     */
    public function handle(string $name = 'world', ?int $times = 1): void
    {
        if ($times <= 0) {
            throw new \InvalidArgumentException('option "times" must be greater than 0.');
        }

        while($times--) {
            echo 'Hello, ' . $name . '!' . PHP_EOL;
        }
    }
}

$app = (new \N1215\MicroCli\ApplicationFactory())->make([Hello::class]);

$app->run($argv);


// $ php hello.php
// Hello, world!

// $ php hello.php Adam
// Hello, Adam!

// $ php hello.php Adam -t=2
// Hello, Adam!
// Hello, Adam!

// $ php hello.php Adam --times=3
// Hello, Adam!
// Hello, Adam!
// Hello, Adam!
