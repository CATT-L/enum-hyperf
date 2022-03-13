<?php


namespace Catt\Enum;


use Catt\Enum\Contract\EnumInterface;
use Catt\Enum\Exception\InvalidEnumKeyException;
use Catt\Enum\Exception\InvalidEnumMemberException;
use Hyperf\Constants\AbstractConstants;
use Hyperf\Contract\CastsAttributes;
use Hyperf\Utils\Contracts\Arrayable;
use phpDocumentor\Reflection\Types\Mixed_;
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
abstract class AbstractEnum extends AbstractConstants implements EnumInterface, Arrayable, \JsonSerializable, CastsAttributes {

    /**
     * default value
     *
     * @var null
     */
    public static $default = null;

    /**
     * Exclude value if it was deprecated
     *
     * @var array
     */
    public static $exclude = [];

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
     * @param array|null $values
     *
     * @return array
     */
    public static function getConstants (array $values = null): array {

        $class = get_called_class();

        if (!array_key_exists($class, static::$constants)) {

            try {
                $constants = (new ReflectionClass($class))->getConstants();
            } catch (\ReflectionException $e) {
                $constants = [];
            }
            static::$constants[$class] = $constants;
        }

        $constants = static::$constants[$class];

        if (is_null($values)) {
            return $constants;
        }

        if (empty($values)) {
            return [];
        }

        return array_filter($constants, function ($item) use ($values) {
            return in_array($item, $values, true);
        });
    }

    public static function getOnlyConstants (array $values = null): array {

        if (is_null($values)) {
            $values = [];
        }

        return static::getConstants($values);
    }

    public static function getExcludeConstants (array $values = null): array {

        if (is_null($values)) {
            $values = static::$exclude;
        }

        if (empty($values)) {
            $values = null;
        }

        if (!is_null($values)) {
            $values = array_diff(static::getValues(), $values);
        }

        return static::getOnlyConstants($values);
    }


    /**
     * @param array|null $values
     *
     * @return static[]
     */
    public static function getInstances (array $values = null): array {

        if (is_null($values)) {
            $values = static::getValues();
        }

        return array_map(function ($constantValue) {
            return new static($constantValue);
        }, $values);
    }

    /**
     * @param array|null $values
     *
     * @return array
     */
    public static function getOptions (array $values = null): array {
        return array_map(function ($instance) {
            return $instance->toArray();
        }, static::getInstances($values));
    }

    public static function getExcludeOptions (array $values = null): array {
        return static::getOptions(static::getExcludeConstants($values));
    }

    public static function getOnlyOptions (array $values = null): array {
        return static::getOptions(static::getOnlyConstants($values));
    }

    public static function getKeys (): array {
        return array_keys(static::getConstants());
    }

    public static function getValues (): array {
        return array_values(static::getConstants());
    }

    public static function getLabels (): array {
        return array_map(function ($value) {
            return static::getLabel($value);
        }, static::getValues());
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

    public static function verify ($enumValue) {
        if (!$enumValue instanceof static) {
            new static($enumValue);
        }
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

    /**
     * @param mixed $enumKeyOrValue
     * @param bool  $tryKey 是否尝试匹配key
     *
     * @throws \Exception
     * @return static|null
     */
    public static function coerce ($enumKeyOrValue, $tryKey = false): ?self {

        if (is_null($enumKeyOrValue)) {
            return null;
        }

        if (static::hasValue($enumKeyOrValue)) {
            return static::fromValue($enumKeyOrValue);
        }

        if ($tryKey) {
            if (is_string($enumKeyOrValue) && static::hasKey($tryKey)) {
                $enumValue = static::getValue($enumKeyOrValue);
                return static::fromValue($enumValue);
            }
        }

        return null;
    }

    public function is ($enumValue): bool {
        if ($enumValue instanceof static) {
            return $this->value === $enumValue->value;
        }
        return $this->value === $enumValue;
    }

    public function isNot ($enumValue): bool {
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

    public function toArray (): array {
        return [
            'key'   => $this->key,
            'value' => $this->value,
            'label' => $this->label,
        ];
    }

    public function jsonSerialize () {
        return $this->toArray();
    }

    public function __toString () {
        return strval($this->value);
    }

    public function get ($model, string $key, $value, array $attributes): ?self {
        return static::coerce($value);
    }

    public function set ($model, string $key, $value, array $attributes) {
        return (static::fromValue($value))->value;
    }

}
