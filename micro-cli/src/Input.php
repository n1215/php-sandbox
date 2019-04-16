<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class Input implements InputInterface
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @var array
     */
    private $longOptions;

    /**
     * @var array
     */
    private $options;

    /**
     * @param array $arguments
     * @param array $options
     * @param array $longOptions
     */
    public function __construct(array $arguments, array $options, array $longOptions)
    {
        $this->arguments = $arguments;
        $this->longOptions = $longOptions;
        $this->options = $options;

    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getArgument(int $key, $default = null)
    {
        if (array_key_exists($key, $this->arguments)) {
            return $this->arguments[$key];
        }

        return $default;
    }

    public function shiftArgument(): InputInterface
    {
        return new self(array_slice($this->arguments, 1), $this->options, $this->longOptions);
    }

    public function getLongOptions(): array
    {
        return $this->longOptions;
    }

    public function getLongOption(string $key, $default = null)
    {
        if (array_key_exists($key, $this->longOptions)) {
            return $this->longOptions[$key];
        }

        return $default;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $key, $default = null)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * todo Aura.Cliなどで書き換えたい
     * @param array $inputs
     * @return Input
     */
    public static function new(array $inputs): self
    {
        $arguments = [];
        $options = [];
        $longOptions = [];

        foreach ($inputs as $input) {
            if ($input[0] !== '-') {
                $arguments[] = $input;
                continue;
            }

            if (preg_match('/^-([A-z0-9])$/', $input,$match)) {
                $options[$match[1]] = true;
                continue;
            }

            if (preg_match('/^-([A-z0-9])=([A-z0-9_-]*)$/', $input,$match)) {
                $options[$match[1]] = $match[2];
                continue;
            }

            if (preg_match('/^--([A-z0-9_-]+)$/', $input,$match)) {
                $longOptions[$match[1]] = true;
                continue;
            }

            if (preg_match('/^--([A-z0-9_-]+)=([A-z0-9_-]*)$/', $input,$match)) {
                $longOptions[$match[1]] = $match[2];
                continue;
            }

        }
        
        return new self($arguments, $options, $longOptions);
    }
}
