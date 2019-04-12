<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class CliRouter
{
    private $commandMap;

    public function __construct(array $commandMap)
    {
        if (empty($commandMap)) {
            throw new \InvalidArgumentException('empty command map');
        }

        $this->commandMap = $commandMap;
    }

    public function match(array $argv): Context
    {
        $inputs = array_slice($argv, 1);

        $pathCandidate = $inputs[0] ?? null;
        if ($pathCandidate === null) {
            return new Context($this->commandMap[0], []);
        }

        if (array_key_exists($pathCandidate, $this->commandMap)) {
            return new Context($this->commandMap[$pathCandidate], array_slice($inputs, 1));
        }

        return new Context($this->commandMap[0], $inputs);
    }
}
