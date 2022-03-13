<?php


namespace HyperfTest\Cases;


use HyperfTest\Enum\HandleStatusEnum;

class EnumComparisonTest extends AbstractTestCase {

    public function testComparePlain () {

        $success = HandleStatusEnum::fromValue(HandleStatusEnum::Success);

        $this->assertTrue($success->is(HandleStatusEnum::Success));
        $this->assertTrue($success->isNot(HandleStatusEnum::Error));
        $this->assertTrue($success->isNot('foobar'));

        $this->assertFalse($success->is(HandleStatusEnum::Error));
        $this->assertFalse($success->is('foobar'));
        $this->assertFalse($success->isNot(HandleStatusEnum::Success));
    }

    public function testCompareInstance () {

        $expect  = HandleStatusEnum::fromValue(HandleStatusEnum::Success);
        $success = HandleStatusEnum::fromValue(HandleStatusEnum::Success);
        $init    = HandleStatusEnum::fromValue(HandleStatusEnum::Init);

        $this->assertTrue($expect->is($expect));
        $this->assertTrue($expect->is($success));
        $this->assertTrue($expect->isNot($init));

        $this->assertFalse($expect->is($init));
        $this->assertFalse($expect->isNot($expect));
        $this->assertFalse($expect->isNot($success));

    }

    public function testCompareInArray () {

        $success = HandleStatusEnum::fromValue(HandleStatusEnum::Success);

        $this->assertTrue($success->in([
            HandleStatusEnum::Success,
            HandleStatusEnum::Error,
        ]));

        $this->assertTrue($success->in([
            HandleStatusEnum::fromValue(HandleStatusEnum::Success),
            HandleStatusEnum::fromValue(HandleStatusEnum::Error),
        ]));

        $this->assertTrue($success->in([
            HandleStatusEnum::Success,
        ]));

        $this->assertTrue($success->in([
            HandleStatusEnum::fromValue(HandleStatusEnum::Success),
        ]));

        $this->assertFalse($success->in([
            HandleStatusEnum::Error,
        ]));

    }


}
