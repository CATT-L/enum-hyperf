<?php


namespace HyperfTest\Enum;


use Catt\Enum\AbstractEnum;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants()
 */
class HandleStatusEnum extends AbstractEnum {

    public static $default = self::Init;

    public static $name = '处理状态';

    public static $exclude = [
        self::Deprecated_A,
        self::Deprecated_B,
    ];

    /**
     * 待处理
     * @Label("等待处理")
     */
    const Init = 0;

    /**
     * 处理中
     * @Label("正在处理")
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

    /**
     * 废弃枚举值A
     * @Label("废弃枚举值A")
     */
    const Deprecated_A = 'A';

    /**
     * 废弃枚举值B
     * @Label("废弃枚举值B")
     */
    const Deprecated_B = 'B';
}
