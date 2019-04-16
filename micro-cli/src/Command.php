<?php
declare(strict_types=1);

namespace N1215\MicroCli;

class Command implements CommandInterface
{
    private $definition;

    public function __construct($definition)
    {
        $this->definition = $definition;
    }

    public function handle(InputInterface $input): void
    {
        $method = new \ReflectionMethod($this->definition, 'handle');
        $parameters = $method->getParameters();
        $arguments = [];
        $cursor = 0;

        foreach ($parameters as $parameter) {
            $arguments[] = $this->makeCommandArgument($cursor, $input, $parameter);
        }

        call_user_func_array([$this->definition, 'handle'], $arguments);
    }

    /**
     * @param int $cursor
     * @param InputInterface $input
     * @param \ReflectionParameter $parameter
     * @return bool|float|int|string|null
     */
    public function makeCommandArgument(int &$cursor, InputInterface $input, \ReflectionParameter $parameter)
    {
        $type = $parameter->getType();
        if ($type === null) {
            throw new \LogicException('no type declaration');
        }

        $defaultValue = $parameter->isOptional() ? $parameter->getDefaultValue() : null;

        // argument
        if (!$type->allowsNull()) {
            $argument = $input->getArgument($cursor++, $defaultValue);
            return $this->cast($argument, $type);
        }

        // option
        $option = $input->getLongOption($parameter->getName());
        if ($option === null) {
            $option = $input->getOption($parameter->getName()[0], $defaultValue);
        }
        if ($option === null) {
            return null;
        }

        return $this->cast($option, $type);
    }

    /**
     * @param bool|float|int|string $value
     * @param \ReflectionType $type
     * @return bool|float|int|string
     */
    private function cast($value, \ReflectionType $type)
    {
        switch ($type->getName()) {
            case 'int':
                return (int) $value;

            case 'bool':
            case 'boolean':
                if ($value === 'true') {
                    return true;
                }
                if ($value === 'false') {
                    return false;
                }
                return (bool) $value;

            case 'float':
            case 'double':
                return (float) $value;

            case 'string':
                return (string) $value;

            default:
                throw new \LogicException('none scalar type declaration');
        }
    }
}
