<?php


namespace HyperfTest\Enum;


use Catt\Enum\AbstractEnum;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants()
 */
class HandleStatusEnum extends AbstractEnum {

    public static $default = self::Init;

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

}
