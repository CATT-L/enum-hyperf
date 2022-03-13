<?php


namespace HyperfTest\Cases;


use Catt\Enum\Rule\EnumValue;
use HyperfTest\Enum\HandleStatusEnum;

class EnumValueValidateTest extends AbstractTestCase {

    public function testValidationPasses () {

        $pass = (new EnumValue(HandleStatusEnum::class))->passes('', 1);

        $this->assertTrue($pass);
    }

    public function testValidationFails () {

        $fail = (new EnumValue(HandleStatusEnum::class))->passes('', -1);

        $this->assertFalse($fail);
    }

    public function testValidateStrict () {

        $fail = (new EnumValue(HandleStatusEnum::class))->passes('', '1');

        $this->assertFalse($fail);

        $pass = (new EnumValue(HandleStatusEnum::class, false))->passes('', '1');

        $this->assertTrue($pass);
    }

    public function testNotExistingClass () {

        $this->expectException(\InvalidArgumentException::class);

        (new EnumValue('foobar'))->passes('', 1);
    }

    public function testRuleString () {

        $rule = new EnumValue(HandleStatusEnum::class);

        $this->assertEquals('enum_value:'.HandleStatusEnum::class.',true', (string) $rule);

        $rule = new EnumValue(HandleStatusEnum::class, false);

        $this->assertEquals('enum_value:'.HandleStatusEnum::class.',false', (string) $rule);
    }

}
