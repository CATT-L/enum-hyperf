<?php


namespace HyperfTest\Enum;


use Catt\Enum\Traits\CastEnums;
use Hyperf\Database\Model\Model;

/**
 * Class ExampleModel
 *
 * @property string $handleStatus ε€ηηΆζ
 * @package HyperfTest\Enum
 */
class ExampleNoCastModel extends Model {

    use CastEnums;

    protected $casts = [
        'handleStatus' => 'integer',
    ];

    protected $enumCasts = [
        // 'handleStatus' => HandleStatusEnum::class,
    ];

    protected $fillable = [
        'handleStatus',
    ];

}
