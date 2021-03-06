<?php


namespace HyperfTest\Enum;


use Catt\Enum\Traits\CastEnums;
use Hyperf\Database\Model\Model;

/**
 * Class ExampleModel
 *
 * @property string|HandleStatusEnum $handleStatus ε€ηηΆζ
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
