<?php
declare(strict_types=1);

namespace N1215\MicroCli;

interface CommandInterface
{
    public function handle(InputInterface $input): void;
}
