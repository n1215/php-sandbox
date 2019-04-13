<?php
declare(strict_types=1);

namespace N1215\MicroCli;

final class Context
{
    private $command;

    /**
     * @var array
     */
    public $inputs;

    /**
     * コンストラクタ
     * @param CommandInterface $command
     * @param InputInterface $inputs
     */
    public function __construct(CommandInterface $command, InputInterface $inputs)
    {
        $this->command = $command;
        $this->inputs = $inputs;
    }

    /**
     * @return mixed
     */
    public function getCommand(): CommandInterface
    {
        return $this->command;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->inputs;
    }
}
