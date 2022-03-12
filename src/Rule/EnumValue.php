<?php


namespace Catt\Enum\Rule;


use Catt\Enum\AbstractEnum;
use Hyperf\Validation\Contract\Rule;

class EnumValue implements Rule {

    protected $rule = 'enum_value';

    /**
     * @var AbstractEnum|string
     */
    protected $enumClass;

    /**
     * @var bool
     */
    protected $strict;

    public function __construct (string $enumClass, bool $strict = true) {

        $this->enumClass = $enumClass;
        $this->strict    = $strict;

        if (!class_exists($this->enumClass)) {
            throw new \InvalidArgumentException("Cannot validate against the enum, the class {$this->enumClass} doesn't exist.");
        }
    }

    public function passes (string $attribute, $value): bool {
        return $this->enumClass::hasValue($value, $this->strict);
    }

    public function message () {
        return __('enum.validation.enum_value');
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     * @see \Hyperf\Validation\ValidationRuleParser::parseParameters
     */
    public function __toString () {
        $strict = $this->strict ? 'true' : 'false';
        return "{$this->rule}:{$this->enumClass},{$strict}";
    }
}
