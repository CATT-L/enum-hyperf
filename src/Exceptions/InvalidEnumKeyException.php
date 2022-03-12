<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/enum.
 *
 * @link     https://github.com/hyperf-ext/enum
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/enum/blob/master/LICENSE
 */

namespace Catt\Enum\Exceptions;

class InvalidEnumKeyException extends \Exception {

    public $invalidKey;

    public $enumClass;

    /**
     * Create an InvalidEnumKeyException.
     *
     * @param mixed  $invalidKey
     * @param string $enumClass
     */
    public function __construct ($invalidKey, string $enumClass) {

        $this->invalidKey = $invalidKey;
        $this->enumClass  = $enumClass;

        $invalidValueType = gettype($invalidKey);
        /** @noinspection PhpUndefinedMethodInspection */
        $enumKeys      = implode(', ', $enumClass::getKeys());
        $enumClassName = class_basename($enumClass);

        parent::__construct("Cannot construct an instance of {$enumClassName} using the key ({$invalidValueType}) `{$invalidKey}`. Possible keys are [{$enumKeys}].");
    }
}
