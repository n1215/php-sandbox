<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class Input implements InputInterface
{
    /**
     * @var array
     */
    private $inputs;

    /**
     * コンストラクタ
     * @param array $inputs
     */
    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
    }

    public function getArguments(): array
    {
        return $this->inputs;
    }

    public function getArgument($key, $default = null)
    {
        if (array_key_exists($key, $this->inputs)) {
            return $this->inputs[$key];
        }

        return $default;
    }

    public function shiftArgument(): InputInterface
    {
        return new self(array_slice($this->inputs, 1));
    }


    public function getOptions(): array
    {
        return [];
    }

    public function getOption(string $key, $default = null)
    {
        return [];
    }
}
