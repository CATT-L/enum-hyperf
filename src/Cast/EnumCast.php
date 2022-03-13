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
        return $this->enumClass::coerce($value);
    }

    public function set ($model, string $key, $value, array $attributes) {
        return [
            $key => $this->enumClass::fromValue($value)->value,
        ];
    }
}
