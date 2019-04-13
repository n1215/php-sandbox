<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class CliRouter
{
    /**
     * @var CommandInterface[]
     */
    private $commandMap;

    public function __construct(array $commandMap)
    {
        if (empty($commandMap)) {
            throw new \InvalidArgumentException('empty command map');
        }

        $this->commandMap = $commandMap;
    }

    public function match(InputInterface $input): Context
    {
        $inputs = $input->shiftArgument();

        $pathCandidate = $inputs->getArgument(0);
        if ($pathCandidate === null) {
            return new Context($this->commandMap[0], $inputs);
        }

        if (array_key_exists($pathCandidate, $this->commandMap)) {
            return new Context($this->commandMap[$pathCandidate], $inputs->shiftArgument());
        }

        return new Context($this->commandMap[0], $inputs);
    }
}
