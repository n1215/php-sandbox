#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

class Main
{
    public function handle(): void
    {
        echo 'main command' . PHP_EOL;
    }
}

class Sub1
{
    public function handle(): void
    {
        echo 'sub command1' . PHP_EOL;
    }
}

class Sub2
{
    public function handle(): void
    {
        echo 'sub command2' . PHP_EOL;
    }
}

$sub3 = new class
{
    public function handle(): void
    {
        echo 'sub command3' . PHP_EOL;
    }
};

$definitions = [
    Main::class,
    'sub1' => Sub1::class,
    'sub2' => new Sub2(),
    'sub3' => $sub3,
];

$app = (new \N1215\MicroCli\ApplicationFactory())->make($definitions);

$app->run($argv);


// $ php sub_commands.php
// main command

// $ php sub_commands.php sub1
// sub command1

// $ php sub_commands.php sub2
// sub command2

// $ php sub_commands.php sub3
// sub command3
