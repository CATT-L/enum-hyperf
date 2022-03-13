<?php


namespace HyperfTest\Cases;


use Catt\Enum\Exception\InvalidEnumMemberException;
use HyperfTest\Enum\ExampleDefaultCastsModel;
use HyperfTest\Enum\ExampleEnumCastsModel;
use HyperfTest\Enum\ExampleNoCastModel;
use HyperfTest\Enum\HandleStatusEnum;

class EnumCastTest extends AbstractTestCase {

    public function testFunctionsOnCastEnums () {

        $model = new ExampleEnumCastsModel();

        $this->assertTrue($model->hasEnumCast('handleStatus'));
        $this->assertFalse($model->hasEnumCast('foobar'));
    }

    public function testModelSetByEnumInstance () {

        $model = new ExampleEnumCastsModel();

        $model->handleStatus = HandleStatusEnum::fromDefault();

        $this->assertEquals(HandleStatusEnum::fromDefault(), $model->handleStatus);
    }

    public function testModelSetByEnumValue () {

        $model = new ExampleEnumCastsModel();

        $model->handleStatus = HandleStatusEnum::$default;

        $this->assertEquals(HandleStatusEnum::fromDefault(), $model->handleStatus);
    }

    public function testModelSetByInvalidEnumValue () {

        $this->expectException(InvalidEnumMemberException::class);

        $model = new ExampleEnumCastsModel();

        $model->handleStatus = -1;
    }

    public function testModelValueReturnEnumInstance () {

        $model = new ExampleEnumCastsModel();

        $model->handleStatus = HandleStatusEnum::$default;

        $this->assertInstanceOf(HandleStatusEnum::class, $model->handleStatus);
    }

    public function testModelValueSetNull () {

        $model = new ExampleEnumCastsModel();

        $model->handleStatus = null;

        $this->assertNull($model->handleStatus);
    }

    public function testModelValueSetStrict () {

        $model = new ExampleEnumCastsModel();

        $model->handleStatus = strval(HandleStatusEnum::Processing);

        $this->assertInstanceOf(HandleStatusEnum::class, $model->handleStatus);
    }

    public function testModelCastToArray () {

        $model = new ExampleEnumCastsModel();

        $model->handleStatus = HandleStatusEnum::Processing;

        $this->assertSame(['handleStatus' => HandleStatusEnum::Processing], $model->toArray());
    }

    public function testTraitButNoCasts () {

        $model = new ExampleNoCastModel();

        $model->handleStatus = HandleStatusEnum::Processing;

        $this->assertEquals($model->handleStatus, HandleStatusEnum::Processing);
    }

}
