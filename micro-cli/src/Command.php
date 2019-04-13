<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class Command implements CommandInterface
{
    private $definition;

    public function __construct(callable $definition)
    {
        $this->definition = $definition;
    }

    public function handle(InputInterface $input): void
    {
        $arguments = $input->getArguments();
        call_user_func_array($this->definition, $arguments);
    }
}
