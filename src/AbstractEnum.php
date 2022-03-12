<?php


namespace Catt\Enum;


use Catt\Enum\Exceptions\InvalidEnumKeyException;
use Catt\Enum\Exceptions\InvalidEnumMemberException;
use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\ConstantsCollector;
use Hyperf\Constants\Exception\ConstantsException;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Str;
use ReflectionClass;

/**
 * Class AbstractEnum
 *
 * @property-read string $key
 * @property-read mixed  $value
 * @property-read string $label
 * @method static getLabel($value)
 * @package Catt\Enum
 */
abstract class AbstractEnum extends AbstractConstants {

    /**
     * default value
     *
     * @var null
     */
    public static $default = null;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $label;

    protected static $constants = [];

    public function __construct ($enumValue) {

        if (!static::hasValue($enumValue)) {
            throw new InvalidEnumMemberException($enumValue, $this);
        }

        $this->value = $enumValue;
        $this->key   = static::getKey($enumValue);
        $this->label = static::getLabel($enumValue);
    }

    public function __get (string $name) {

        if (in_array($name, [
            'key',
            'value',
            'label',
        ])) {
            return $this->{$name};
        }

        return null;
    }


    /**
     * @return array
     */
    public static function getConstants (): array {

        $class = get_called_class();

        if (!array_key_exists($class, static::$constants)) {

            try {
                $constants = (new ReflectionClass($class))->getConstants();
            } catch (\ReflectionException $e) {
                $constants = [];
            }
            static::$constants[$class] = $constants;
        }

        return static::$constants[$class];
    }

    public static function getKeys (): array {
        return array_keys(static::getConstants());
    }

    public static function getValues (): array {
        return array_values(static::getConstants());
    }

    public static function getKey ($value): string {
        return array_search($value, static::getConstants(), true);
    }

    public static function getValue (string $key) {
        return static::getConstants()[$key];
    }

    public static function hasKey (string $key): bool {
        return in_array($key, static::getKeys(), true);
    }

    public static function hasValue ($value, bool $strict = true): bool {
        $validValues = static::getValues();

        if ($strict) {
            return in_array($value, $validValues, true);
        }

        return in_array((string) $value, array_map('strval', $validValues), true);
    }

    /**
     * @param $key
     *
     * @throws \Exception
     * @return static
     */
    public static function fromKey ($key): self {

        if (static::hasKey($key)) {
            $enumValue = static::getValue($key);
            return new static($enumValue);
        }

        throw new InvalidEnumKeyException($key, static::class);
    }

    /**
     * @param $enumValue
     *
     * @throws \Exception
     * @return static
     */
    public static function fromValue ($enumValue): self {

        if ($enumValue instanceof static) {
            return $enumValue;
        }

        return new static($enumValue);
    }

    public function is ($enumValue) {
        if ($enumValue instanceof static) {
            return $this->value === $enumValue->value;
        }
        return $this->value === $enumValue;
    }

    public function isNot ($enumValue) {
        return !$this->is($enumValue);
    }

    /**
     * @param mixed[]|static[] $values
     *
     * @return bool
     */
    public function in (array $values): bool {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return true;
            }
        }

        return false;
    }

}
