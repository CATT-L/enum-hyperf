<?php


namespace Catt\Enum\Cast;


use Catt\Enum\AbstractEnum;
use Hyperf\Contract\CastsAttributes;

/**
 * Class EnumCast
 *
 * @package Catt\Enum\Cast
 */
class EnumCast implements CastsAttributes {

    /**
     * @var string|AbstractEnum
     */
    protected $enumClass;

    public function __construct (string $enumClass) {
        $this->enumClass = $enumClass;
    }

    public function get ($model, string $key, $value, array $attributes) {
        return $this->castEnum($value);
    }

    public function set ($model, string $key, $value, array $attributes) {

        if (!is_null($value)) {
            if ($value instanceof $this->enumClass) {
                $value = $value->value;
            }
            else {
                $value = $this->getCastableValue($value);
            }

            $value = $this->enumClass::fromValue($value)->value;

            $value = $this->enumClass::serializeDatabase($value);
        }

        return [
            $key => $value,
        ];
    }

    /**
     * @param mixed $value
     *
     * @throws \Exception
     * @return AbstractEnum|null
     */
    protected function castEnum ($value): ?AbstractEnum {

        if ($value === null || $value instanceof $this->enumClass) {
            return $value;
        }

        $value = $this->getCastableValue($value);

        return $this->enumClass::coerce($value);
    }

    protected function getCastableValue ($value) {

        // If the enum has overridden the `parseDatabase` method, use it to get the cast value
        $value = $this->enumClass::parseDatabase($value);

        if ($value === null) {
            return null;
        }

        // If the value exists in the enum (using strict type checking) return it
        if ($this->enumClass::hasValue($value)) {
            return $value;
        }

        // Fall back to trying to construct it directly (will result in an error since it doesn't exist)
        return $value;
    }

}
