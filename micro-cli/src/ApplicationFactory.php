<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class ApplicationFactory
{
    public function make(array $definitions): Application
    {
        $commandMap = array_map(function ($value) {
            if (is_string($value)) {
                $def = new $value;
            } else {
                $def = $value;
            }

            return new Command($def);
        }, $definitions);

        return new Application(new CliRouter($commandMap));
    }
}
