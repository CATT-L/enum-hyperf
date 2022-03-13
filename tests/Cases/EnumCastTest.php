<?php


namespace HyperfTest\Cases;


use Catt\Enum\Exception\InvalidEnumMemberException;
use HyperfTest\Enum\ExampleModel;
use HyperfTest\Enum\ExampleNoCastModel;
use HyperfTest\Enum\HandleStatusEnum;

class EnumCastTest extends AbstractTestCase {

    protected function setUp (): void {
        parent::setUp();
        SetupConstants(HandleStatusEnum::class);
    }

    public function testFunctionsOnCastEnums () {

        $model = new ExampleModel();

        $this->assertTrue($model->hasEnumCast('handleStatus'));
        $this->assertFalse($model->hasEnumCast('foobar'));
    }

    public function testModelSetByEnumInstance () {

        $model = new ExampleModel();

        $model->handleStatus = HandleStatusEnum::fromDefault();

        $this->assertEquals(HandleStatusEnum::fromDefault(), $model->handleStatus);
    }

    public function testModelSetByEnumValue () {

        $model = new ExampleModel();

        $model->handleStatus = HandleStatusEnum::$default;

        $this->assertEquals(HandleStatusEnum::fromDefault(), $model->handleStatus);
    }

    public function testModelSetByInvalidEnumValue () {

        $this->expectException(InvalidEnumMemberException::class);

        $model = new ExampleModel();

        $model->handleStatus = -1;
    }

    public function testModelValueReturnEnumInstance () {

        $model = new ExampleModel();

        $model->handleStatus = HandleStatusEnum::$default;

        $this->assertInstanceOf(HandleStatusEnum::class, $model->handleStatus);
    }

    public function testModelValueSetNull () {

        $model = new ExampleModel();

        $model->handleStatus = null;

        $this->assertNull($model->handleStatus);
    }

    public function testModelCastToArray () {

        $model = new ExampleModel();

        $model->handleStatus = HandleStatusEnum::Processing;

        $this->assertSame(['handleStatus' => HandleStatusEnum::Processing], $model->toArray());
    }

    public function testTraitButNoCasts () {

        $model = new ExampleNoCastModel();

        $model->handleStatus = HandleStatusEnum::Processing;

        $this->assertEquals($model->handleStatus, HandleStatusEnum::Processing);
    }


}
