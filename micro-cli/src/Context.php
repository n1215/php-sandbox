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
     * @param  $command
     * @param array $inputs
     */
    public function __construct($command, array $inputs)
    {
        $this->command = $command;
        $this->inputs = $inputs;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }
}
