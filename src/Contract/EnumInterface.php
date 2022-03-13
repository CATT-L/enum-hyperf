<?php


namespace Catt\Enum\Contract;


use Catt\Enum\Exception\InvalidEnumMemberException;

interface EnumInterface {

    /**
     * EnumInterface constructor.
     *
     * @param mixed $enumValue
     */
    public function __construct ($enumValue);

    /**
     * @param mixed $enumValue
     * @return bool
     */
    public function is ($enumValue): bool;

    /**
     * @param mixed $enumValue
     *
     * @return bool
     */
    public function isNot ($enumValue): bool;

    /**
     * @param mixed[]|static[] $values
     *
     * @return bool
     */
    public function in (array $values): bool;

    /**
     * @param $enumValue
     *
     * @throws InvalidEnumMemberException
     */
    public static function verify ($enumValue);

}
