<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class Application
{
    private $commandMap;

    public function __construct(array $commandMap)
    {
        if (empty($commandMap)) {
            throw new \InvalidArgumentException('empty command map');
        }

        $this->commandMap = $commandMap;
    }

    public function run(array $argv): void
    {
        $context = $this->getContext($argv);
        $command = $this->commandMap[$context['path']];
        $inputs = $context['inputs'];

        call_user_func_array([$command, 'handle'], $inputs);
    }

    private function getContext(array $argv): array
    {
        $pathCandidate = $argv[1] ?? null;
        if ($pathCandidate === null) {
            return [
                'path' => 0,
                'inputs' => [],
            ];
        }

        if (array_key_exists($pathCandidate, $this->commandMap)) {
            return [
                'path' => $pathCandidate,
                'inputs' => array_slice($argv, 2),
            ];
        }

        return [
            'path' => 0,
            'inputs' => array_slice($argv, 1),
        ];
    }

}
