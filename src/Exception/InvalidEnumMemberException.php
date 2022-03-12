<?php


namespace Catt\Enum\Exception;


use Catt\Enum\AbstractEnum;

class InvalidEnumMemberException extends \Exception {

    public $invalidValue;

    public $enum;

    /**
     * Create an InvalidEnumMemberException.
     *
     * @param mixed $invalidValue
     */
    public function __construct ($invalidValue, AbstractEnum $enum) {

        $this->invalidValue = $invalidValue;
        $this->enum         = $enum;

        $invalidValueType = gettype($invalidValue);
        $enumValues       = implode(', ', $enum::getValues());
        $enumClassName    = class_basename($enum);

        parent::__construct("Cannot construct an instance of {$enumClassName} using the value ({$invalidValueType}) `{$invalidValue}`. Possible values are [{$enumValues}].");
    }
}
