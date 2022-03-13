# 适用于 Hyperf 的枚举组件

- 继承 [`hyperf/constants`](https://github.com/hyperf/constants) 实现

- 类常量即枚举键值对

- 可实例化

- 支持 `hyperf/validation` 验证

- 实现 `CastsAttributes` 接口, 因此可在 `Model` 中设置 `cast` 类型转换

- 类型提示

- 属性转换

- 枚举比较

- 启动检测枚举值重复异常


> 基于 [`hyperf-ext/enum`](https://github.com/hyperf-ext/enum)  , 在部分功能上做了取舍和改动



[TOC]



## 安装

```shell
composer require catt-l/enum-hyperf
```



## 使用

#### 枚举定义

将所有可能值作为常量添加到枚举类, 给枚举类加上 `@Constants` 注解即可。

```php
<?php
declare(strict_types=1);

namespace App\Model\Enum;

use Catt\Enum\AbstractEnum;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants()
 */
class HandleStatusEnum extends AbstractEnum {

    /**
     * 待处理
     * @Label("待处理")
     */
    const Init = 0;

    /**
     * 处理中
     * @Label("处理中")
     */
    const Processing = 1;

    /**
     * 处理成功
     * @Label("处理成功")
     */
    const Success = 2;

    /**
     * 处理失败
     * @Label("处理失败")
     */
    const Error = 3;

}

```

接下来可以像常量一样使用它们。

```php
<?php
$handleStatus = HandleStatusEnum::Processing; // 值为1
```



#### 实例化

实例化后可以拥有类型提示，并且能保证传递的值是有效的。

枚举类可通过以下几种方式进行实例化。

```php
// 标准的 PHP 实例化方式，将期望的枚举值作为参数进行传递
$enumInstance = new HandleStatusEnum(HandleStatusEnum::Processing);

// 与使用构造函数一样，用值来实例化
$enumInstance = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);

// 使用枚举的键名作为参数来实例化
$enumInstance = HandleStatusEnum::fromKey('Processing');

// 尝试使用给定的键名或值实例化一个枚举。如果无法实例化，则返回 `null`
// 由于同时尝试键名或键值有时会让结果出乎意料，因此默认只尝试键值，若参数 $tryKey 设置为 true，则继续尝试键名
$enumInstance = HandleStatusEnum::coerce($someValue); // 尝试键值
$enumInstance = HandleStatusEnum::coerce($someValue, true); // 尝试键值或键名
```



#### 实例属性

有了枚举实例后， 可以将 `key` 、`value`、`label` 作为属性访问。

```php
$handleStatus = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);

$handleStatus->key; // Processing
$handleStatus->value; // 1
$handleStatus->label; // 处理中

```

这些属性是只读的，不允许修改。

#### 实例类型转换

##### 字符串

由于实现了 `__toString()` 魔术方法，它可以在视图中直接输出。

```php
$handleStatus = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);

(string) $handleStatus; // '1'
```

##### 数组

实现了 `Arrayable` 接口，可通过 `toArray` 方法将实例转换为数组。

```php
$handleStatus = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);

$handleStatus->toArray();

// [
//     'key'   => 'Processing',
//     'value' => 1,
//     'label' => '处理中',
// ]
```

##### JSON序列化

实现了 `JsonSerializable`, 可直接序列化，内容即为数组返回的内容。

```php
$handleStatus = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);

json_encode($handleStatus, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

// {
//     "key": "Processing",
//     "value": 1,
//     "label": "处理中"
// }

```



#### 实例比较

提供了4个比较方法用于实例的比较, 其中 `verify` 校验不通过的时候，会抛出 `InvalidEnumMemberException` 异常。

函数原型如下所示。

```php
interface EnumInterface {

    /**
     * @param mixed $enumValue
     *
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
```

方法 `is` 的使用示例，`isNot` 和 `is` 逻辑完全相反，故不再演示。

```php
$processing = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);
$success    = HandleStatusEnum::fromValue(HandleStatusEnum::Success);

$processing->is(HandleStatusEnum::Processing);                              // true
$processing->is($processing);                                               // true
$processing->is(HandleStatusEnum::fromValue(HandleStatusEnum::Processing)); // true
$processing->is(1);                                                         // true

$processing->is(HandleStatusEnum::Success);                                 // false
$processing->is($success);                                                  // false
$processing->is(HandleStatusEnum::fromValue(HandleStatusEnum::Success));    // false
$processing->is('foobar');                                                  // false
```

方法 `in` 的使用示例

```php
$processing = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);
$success    = HandleStatusEnum::fromValue(HandleStatusEnum::Success);

$processing->in([0, 1]);                  // true
$processing->in([$processing, $success]); // true

$processing->in([HandleStatusEnum::Init, HandleStatusEnum::Success]);  // false
$processing->in([]);                                                   // false
$processing->in(['foobar']);                                           // false
```

#### 类型提示

枚举实例的益处是让我们可以使用类型提示，如下所示。

```php
function isProcessing (HandleStatusEnum $handleStatus) {
    return $handleStatus->is(HandleStatusEnum::Processing);
}
```

#### 模型属性类型转换

由于实现了 `CastsAttributes` 接口中的 `get` 和 `set` 方法，可以方便地将属性在数据库值和枚举值之间自动转换。

在模型中的使用方法。

```php
/**
 * @property int    $id
 * @property int    $articleId 文章ID
 * @property string $handleStatus 处理状态
 */
class ArticleReview extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_review';

    /**
     * @var string
     */
    protected $comment = '文章审核';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['articleId', 'handleStatus',];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'articleId'      => 'integer',
        'handleStatus'   => HandleStatusEnum::class,
    ];

}


$review = ArticleReview::find(1);
// [
//     'id'           => 1,
//     'articleId'    => 1,
//     'handleStatus' => 1,
// ]

// 读取出来是枚举实例，可以用实例上的所有公共方法和属性
$review->handleStatus;
// object(App\Model\Enum\HandleStatusEnum)#9 (3) {
//   ["value":protected]=>
//   int(1)
//   ["key":protected]=>
//   string(10) "Processing"
//   ["label":protected]=>
//   string(9) "处理中"
// }

// 设置,可以设置枚举值，也可以设置枚举实例
$review->handleStatus = HandleStatusEnum::Success;
$review->handleStatus = 2;
$review->handleStatus = HandleStatusEnum::fromValue(2);


```



## AbstractEnum 类参考

#### static getConstants(array $values = null): array

返回所有枚举键值对，若 `$values` 传入数组值，则仅返回 `$values` 范围内的键值对

```php
// ['Init' => 0, 'Processing' => 1, 'Success' => 2, 'Error' => 3]
HandleStatusEnum::getConstants(); 

// ['Init' => 0, 'Processing' => 1]
HandleStatusEnum::getConstants([0, 1]); 
```

#### static getOnlyConstants(array $values = null): array

返回 `$values` 范围内的键值对，若为 `null` 则返回空数组

```php
// ['Init' => 0, 'Processing' => 1]
HandleStatusEnum::getOnlyConstants([0, 1]);

// []
HandleStatusEnum::getOnlyConstants();
```

#### static getExcludeConstants(array $values = null): array

返回 `$values` 范围外的键值对，若为 `null` 则读取静态变量 `static $default` 的值，若仍旧为 `null`，则认为传入了空数组 `[]`， 将返回所有键值对。

未设置 `static $default` 的情况

```php
// 未设置 $default

// ['Init' => 0, 'Processing' => 1]
HandleStatusEnum::getExcludeConstants([2, 3]);

// ['Init' => 0, 'Processing' => 1, 'Success' => 2, 'Error' => 3]
HandleStatusEnum::getExcludeConstants();

```

设置 `static $default` 为 `[0, 1]` 的情况

```php
// $default 设置为 [0, 1]

// ['Init' => 0, 'Processing' => 1]
HandleStatusEnum::getExcludeConstants([2, 3]);

// ['Success' => 2, 'Error' => 3]
HandleStatusEnum::getExcludeConstants();

// ['Init' => 0, 'Processing' => 1, 'Success' => 2, 'Error' => 3]
HandleStatusEnum::getExcludeConstants([]);
```

#### static getKeys(): array

返回包含所有枚举键的数组

```php
// ['Init', 'Processing', 'Success', 'Error']
HandleStatusEnum::getKeys();
```

#### static getValues(): array

返回包含所有枚举值的数组

```php
// [0, 1, 2, 3]
HandleStatusEnum::getValues();
```

#### static getLabels(): array

返回所有枚举标签数组

```php
['待处理', '处理中', '处理成功', '处理失败']
HandleStatusEnum::getLabels();
```



> TODO 完善剩下的内容