<?php


namespace HyperfTest\Enum;


use Catt\Enum\Traits\CastEnums;
use Hyperf\Database\Model\Model;

/**
 * Class ExampleModel
 *
 * @property string $handleStatus 处理状态
 * @package HyperfTest\Enum
 */
class ExampleDefaultCastsModel extends Model {

    protected $casts = [
        'handleStatus' => HandleStatusEnum::class,
    ];

    protected $fillable = [
        'handleStatus',
    ];

}
