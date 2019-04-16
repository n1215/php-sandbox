<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class ApplicationFactory
{
    public function make(array $definitions): Application
    {
        $commandMap = array_map(function ($value) {
            return new Command(new $value);
        }, $definitions);

        return new Application(new CliRouter($commandMap));
    }
}
