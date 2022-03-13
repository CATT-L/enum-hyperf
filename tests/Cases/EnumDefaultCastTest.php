<?php


namespace HyperfTest\Cases;


use Catt\Enum\Exception\InvalidEnumMemberException;
use HyperfTest\Enum\ExampleDefaultCastsModel;
use HyperfTest\Enum\HandleStatusEnum;

class EnumDefaultCastTest extends AbstractTestCase {

    public function testDefaultCastStrict () {

        $this->expectException(InvalidEnumMemberException::class);

        $model = new ExampleDefaultCastsModel();

        $model->handleStatus = strval(HandleStatusEnum::Processing);
    }

    public function testDefaultCast () {

        $model = new ExampleDefaultCastsModel();

        $model->handleStatus = HandleStatusEnum::Processing;

        $this->assertInstanceOf(HandleStatusEnum::class, $model->handleStatus);
    }

    public function testDefaultCastToArray () {

        $model = new ExampleDefaultCastsModel();

        $model->handleStatus = HandleStatusEnum::Processing;

        $this->assertSame(['handleStatus' => $model->handleStatus->toArray()], $model->toArray());

    }
}
