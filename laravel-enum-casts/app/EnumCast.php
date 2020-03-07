<?php
declare(strict_types=1);

namespace App;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use MyCLabs\Enum\Enum;

/** Enumクラス用キャストの抽象クラス */
abstract class EnumCast implements CastsAttributes
{
    abstract protected function getClass(): string;

    private function getValidatedClass(): string
    {
        if (!is_subclass_of($class = $this->getClass(), Enum::class)) {
            throw new InvalidArgumentException(
                'class should be sub class of ' . Enum::class
            );
        }
        return $class;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return $value;
        }
        $class = $this->getValidatedClass();
        return new $class($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        $class = $this->getValidatedClass();
        if ($value instanceof $class) {
            return $value->getValue();
        }
        return $value;
    }
}
