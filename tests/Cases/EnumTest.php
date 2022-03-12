<?php


namespace HyperfTest\Cases;


use HyperfTest\Enum\HandleStatusEnum;

class EnumTest extends AbstractTestCase {


    protected $constants = [
        'Init'       => 0,
        'Processing' => 1,
        'Success'    => 2,
        'Error'      => 3,
    ];

    public function testGetConstants () {

        $expect = $this->constants;

        $constants = HandleStatusEnum::getConstants();

        $this->assertEquals($expect, $constants);
    }

    public function testGetKeys () {

        $expect = array_keys($this->constants);

        $keys = HandleStatusEnum::getKeys();

        $this->assertEquals($expect, $keys);
    }

    public function testGetValues () {

        $expect = array_values($this->constants);

        $values = HandleStatusEnum::getValues();

        $this->assertEquals($expect, $values);
    }

    public function testGetKey () {

        $this->assertEquals('Init', HandleStatusEnum::getKey(0));

        $this->assertEquals('Success', HandleStatusEnum::getKey(2));

    }

    public function testGetValue () {

        $this->assertEquals(1, HandleStatusEnum::getValue('Processing'));

        $this->assertEquals(2, HandleStatusEnum::getValue('Success'));
    }

    public function testHasKey () {

        $this->assertTrue(HandleStatusEnum::hasKey('Init'));

        $this->assertTrue(HandleStatusEnum::hasKey('Processing'));

        $this->assertNotTrue(HandleStatusEnum::hasKey('None'));
    }

    public function testHasValue () {

        $this->assertTrue(HandleStatusEnum::hasValue(0));

        $this->assertTrue(HandleStatusEnum::hasValue(2));

        $this->assertNotTrue(HandleStatusEnum::hasValue(-1));
    }




}
