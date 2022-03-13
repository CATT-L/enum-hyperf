<?php


namespace HyperfTest\Cases;


use Catt\Enum\Exception\InvalidEnumMemberException;
use HyperfTest\Enum\ExampleDefaultCastsModel;
use HyperfTest\Enum\HandleStatusEnum;

class EnumDefaultCastTest extends AbstractTestCase {

    public function testDefaultCastsStrict () {

        $this->expectException(InvalidEnumMemberException::class);

        $model = new ExampleDefaultCastsModel();

        $model->handleStatus = strval(HandleStatusEnum::Processing);
    }

    public function testDefaultCasts () {

        $model = new ExampleDefaultCastsModel();

        $model->handleStatus = HandleStatusEnum::Processing;

        $this->assertInstanceOf(HandleStatusEnum::class, $model->handleStatus);
    }
}
