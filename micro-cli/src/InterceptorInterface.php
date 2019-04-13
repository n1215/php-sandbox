<?php
declare(strict_types=1);

namespace N1215\MicroCli;

interface InterceptorInterface
{
    /**
     * @param InputInterface $input
     * @param CommandInterface $command
     */
    public function process(InputInterface $input, CommandInterface $command): void;
}
