<?php
declare(strict_types=1);

namespace N1215\MicroCli;

interface InputInterface
{
    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @param int|string $key
     * @param string|bool|int|float|null $default
     * @return string|bool|int|float|null
     */
    public function getArgument($key, $default = null);

    /**
     * @return InputInterface
     */
    public function shiftArgument(): InputInterface;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @param string $key
     * @param string|bool|int|float|null $default
     * @return string|bool|int|float|null
     */
    public function getOption(string $key, $default = null);
}
