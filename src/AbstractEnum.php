<?php


namespace Catt\Enum;


use Catt\Enum\Cast\EnumCast;
use Catt\Enum\Contract\EnumInterface;
use Catt\Enum\Exception\InvalidEnumKeyException;
use Catt\Enum\Exception\InvalidEnumMemberException;
use Hyperf\Constants\AbstractConstants;
use Hyperf\Contract\Castable;
use Hyperf\Contract\CastsAttributes;
use Hyperf\Utils\Contracts\Arrayable;
use ReflectionClass;

/**
 * Class AbstractEnum
 *
 * @property-read string $key
 * @property-read mixed  $value
 * @property-read string $label
 * @package Catt\Enum
 */
abstract class AbstractEnum extends AbstractConstants implements EnumInterface, Arrayable, \JsonSerializable, Castable {

    /**
     * Default value for static::fromDefault()
     * 枚举默认值，当值在枚举范围内时，可通过 `fromDefault` 创建实例。
     *
     * @var mixed|null
     */
    public static $default = null;

    /**
     * The name of enum
     * 枚举命名，在发生异常进行捕获的时候，可以通过对应静态方法`getName()`，返回友好的报错提示给前端。
     * 若未设置，则返回类名。
     *
     * @var string|null
     */
    public static $name = null;

    /**
     * Exclude value if it was deprecated
     * 配置需要在 `getExcludeConstants()` 和 `getExcludeOptions()` 默认排除的选项值范围。
     *
     * @var array
     */
    public static $exclude = [];

    /**
     * 枚举值
     *
     * @var mixed
     */
    protected $value;

    /**
     * 枚举键
     *
     * @var mixed
     */
    protected $key;

    /**
     * 枚举标签
     *
     * @var mixed
     */
    protected $label;

    /**
     * 常量键值对缓存
     *
     * @var array
     */
    protected static $constants = [];

    /**
     * AbstractEnum constructor.
     *
     * @param $enumValue
     *
     * @throws InvalidEnumMemberException
     */
    public function __construct () {

        $enumValue = 1;

        var_dump($enumValue);
        var_dump('xx');

        if (!static::hasValue($enumValue)) {
            throw new InvalidEnumMemberException($enumValue, $this);
        }

        $this->value = $enumValue;
        $this->key   = static::getKey($enumValue);
        $this->label = static::getLabel($enumValue);
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
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
     * 自定义的枚举名称，若未设置，则返回类名。
     *
     * @return string|null
     */
    public static function getName (): string {
        return static::$name ?: class_basename(static::class);
    }

    /**
     * 返回所有枚举键值对
     * 若 `$values` 传入数组值，则仅返回 `$values` 范围内的键值对
     *
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

    /**
     * 返回 `$values` 范围内的键值对。
     * 若为 `null` 则返回空数组
     *
     * @param array|null $values
     *
     * @return array
     */
    public static function getOnlyConstants (array $values = null): array {

        if (is_null($values)) {
            $values = [];
        }

        return static::getConstants($values);
    }

    /**
     * 返回 `$values` 范围外的键值对。
     * 若为 `null` 则读取静态变量 `static $default` 的值。
     * 若仍旧为 `null`，则认为传入了空数组 `[]`， 将返回所有键值对。
     *
     * @param array|null $values
     *
     * @return array
     */
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
     * 返回`$values`范围内的实例数组，以常量key作为键值
     * 若 `$values` 为 `null`，则返回所有。
     *
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
        }, static::getConstants($values));
    }

    /**
     * 返回`$value`范围内的选项列表数组。
     * 若 `$value` 为 `null` 则返回所有。
     *
     * @param array|null $values
     *
     * @return array
     */
    public static function getOptions (array $values = null): array {
        return array_map(function ($instance) {
            return $instance->toArray();
        }, array_values(static::getInstances($values)));
    }

    /**
     * 返回`$value`范围内的选项列表数组。
     * 若 `$value` 为 `null` 则返回空数组。
     *
     * @param array|null $values
     *
     * @return array
     */
    public static function getOnlyOptions (array $values = null): array {
        return static::getOptions(static::getOnlyConstants($values));
    }

    /**
     * 返回`$value`范围外的选项列表数组。
     * 若 `$value` 为 `null` 则读取 `static $exclude`。
     * 若仍旧为 `null` 则认为传入了空数组 `[]`，将返回所有。
     *
     * @param array|null $values
     *
     * @return array
     */
    public static function getExcludeOptions (array $values = null): array {
        return static::getOptions(static::getExcludeConstants($values));
    }


    /**
     * 返回包含所有枚举键的数组。
     *
     * @return array
     */
    public static function getKeys (): array {
        return array_keys(static::getConstants());
    }

    /**
     * 返回包含所有枚举值的数组。
     *
     * @return array
     */
    public static function getValues (): array {
        return array_values(static::getConstants());
    }

    /**
     * 返回所有枚举标签数组。
     *
     * @return array
     */
    public static function getLabels (): array {
        return array_map(function ($value) {
            return static::getLabel($value);
        }, static::getValues());
    }

    /**
     * 读取 `$value` 对应的 `$key`， 若不存在则返回空字符串。
     *
     * @param $value
     *
     * @return string
     */
    public static function getKey ($value): string {
        return array_search($value, static::getConstants(), true);
    }

    /**
     * 读取`$key`对应的`$value`，若不存在则返回 `null`。
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function getValue (string $key) {
        return @static::getConstants()[$key];
    }

    /**
     * 读取 `$value` 对应的 `$label`。
     * 若不存在则返回空字符串。
     *
     * @param $value
     *
     * @throws \Hyperf\Constants\Exception\ConstantsException
     * @return mixed|string
     */
    public static function getLabel ($value) {
        return static::__callStatic('getLabel', func_get_args());
    }

    /**
     * 是否存在 `$key`。
     *
     * @param string $key
     *
     * @return bool
     */
    public static function hasKey (string $key): bool {
        return in_array($key, static::getKeys(), true);
    }

    /**
     * 是否存在 `$value`。
     * `$strict` 是否严格校验数据类型，默认开启。
     *
     * @param      $value
     * @param bool $strict
     *
     * @return bool
     */
    public static function hasValue ($value, bool $strict = true): bool {
        $validValues = static::getValues();

        if ($strict) {
            return in_array($value, $validValues, true);
        }

        return in_array((string) $value, array_map('strval', $validValues), true);
    }


    /**
     * 校验枚举变量或枚举值是否有效。
     * 无效则抛出 `InvalidEnumMemberException` 异常。
     *
     * @param mixed|static $enumValue
     *
     * @throws InvalidEnumMemberException
     */
    public static function verify ($enumValue) {
        if (!$enumValue instanceof static) {
            new static($enumValue);
        }
    }

    /**
     * 按照 key 实例化。
     *
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
     * 按照枚举变量或者枚举值实例化。
     * 注：若传入枚举变量，则会返回原实例，而不是创建一个新的。如果希望返回新实例，请使用枚举值实例化。
     *
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
     * 根据默认值 `static $default` 实例化。
     * 若未配置，则返回 `null`。
     *
     * @throws \Exception
     * @return static|null
     */
    public static function fromDefault (): ?self {
        return static::coerce(static::$default);
    }

    /**
     * 尝试使用给定的键名或值实例化一个枚举。如果无法实例化，则返回 `null`。
     * 由于同时尝试键名或键值有时会让结果出乎意料，因此默认只尝试键值，若参数 `$tryKey` 设置为 `true`，则继续尝试键名。
     *
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

    /**
     * 比较枚举值是否相等。
     *
     * @param mixed $enumValue
     *
     * @return bool
     */
    public function is ($enumValue): bool {
        if ($enumValue instanceof static) {
            return $this->value === $enumValue->value;
        }
        return $this->value === $enumValue;
    }

    /**
     * 比较枚举值是否不等，与 `is($enumValue)` 逻辑相反。
     *
     * @param mixed $enumValue
     *
     * @return bool
     */
    public function isNot ($enumValue): bool {
        return !$this->is($enumValue);
    }

    /**
     * 判断枚举是否在给定值范围内。
     *
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

    /**
     * 转换为数组
     *
     * @return array
     */
    public function toArray (): array {
        return [
            'key'   => $this->key,
            'value' => $this->value,
            'label' => $this->label,
        ];
    }

    /**
     * JSON序列化
     *
     * @return array|mixed
     */
    public function jsonSerialize () {
        return $this->toArray();
    }

    /**
     * 字符串序列化
     *
     * @return string
     */
    public function __toString () {
        return strval($this->value);
    }

    /**
     * @return EnumCast|CastsAttributes|\Hyperf\Contract\CastsInboundAttributes|string
     */
    public static function castUsing () {
        return new EnumCast(static::class);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function parseDatabase ($value) {
        return $value;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function serializeDatabase ($value) {
        return $value;
    }

}
