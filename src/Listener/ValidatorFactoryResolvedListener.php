<?php


namespace Catt\Enum\Listener;


use Catt\Enum\Rule\EnumValue;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;

class ValidatorFactoryResolvedListener implements ListenerInterface {

    public function listen (): array {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process (object $event) {

        if ($event instanceof ValidatorFactoryResolved) {

            $validatorFactory = $event->validatorFactory;

            $validatorFactory->extend('enum_value', function ($attribute, $value, $parameters, $validator) {

                $enum   = $parameters[0] ?? null;
                $strict = $parameters[1] ?? null;

                if (!$strict) {
                    return (new EnumValue($enum))->passes($attribute, $value);
                }

                $strict = (bool) json_decode(strtolower($strict));

                return (new EnumValue($enum, $strict))->passes($attribute, $value);
            });
        }

    }
}
