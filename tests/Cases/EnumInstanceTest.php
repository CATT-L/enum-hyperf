<?php


namespace HyperfTest\Cases;


use Catt\Enum\Exceptions\InvalidEnumKeyException;
use Catt\Enum\Exceptions\InvalidEnumMemberException;
use HyperfTest\Enum\HandleStatusEnum;

class EnumInstanceTest extends AbstractTestCase {

    protected function setUp (): void {
        parent::setUp();
        SetupConstants(HandleStatusEnum::class);
    }


    public function testWithNew () {

        $enum = new HandleStatusEnum(1);

        $this->assertInstanceOf(HandleStatusEnum::class, $enum);

        $this->assertEquals($enum->key, 'Processing');

        $this->assertEquals($enum->value, 1);

        $this->assertEquals($enum->label, '正在处理');

    }

    public function testFromKey () {

        $enum = HandleStatusEnum::fromKey('Processing');

        $this->assertInstanceOf(HandleStatusEnum::class, $enum);

        $this->assertEquals($enum->key, 'Processing');

        $this->assertEquals($enum->value, 1);

        $this->assertEquals($enum->label, '正在处理');
    }

    public function testFromValue () {

        $enum = HandleStatusEnum::fromValue(1);

        $this->assertInstanceOf(HandleStatusEnum::class, $enum);

        $this->assertEquals($enum->key, 'Processing');

        $this->assertEquals($enum->value, 1);

        $this->assertEquals($enum->label, '正在处理');
    }

    public function testFromValueInstance () {

        $instance = HandleStatusEnum::fromValue(1);

        $enum = HandleStatusEnum::fromValue($instance);

        $this->assertEquals($instance, $enum);
    }

    public function testInvalidEnumKeyException () {

        $this->expectException(InvalidEnumKeyException::class);

        HandleStatusEnum::fromKey('foobar');
    }

    public function testInvalidEnumMemberException () {

        $this->expectException(InvalidEnumMemberException::class);

        HandleStatusEnum::fromValue('InvalidValue');

    }


}
