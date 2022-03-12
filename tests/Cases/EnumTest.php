<?php


namespace HyperfTest\Cases;


use Hyperf\Utils\Arr;
use HyperfTest\Enum\HandleStatusEnum;

class EnumTest extends AbstractTestCase {


    protected $constants = [
        'Init'         => 0,
        'Processing'   => 1,
        'Success'      => 2,
        'Error'        => 3,
        'Deprecated_A' => 'A',
        'Deprecated_B' => 'B',
    ];

    public function testGetConstants () {

        $expect = $this->constants;

        $constants = HandleStatusEnum::getConstants();

        $this->assertEquals($expect, $constants);
    }

    public function testGetOnlyConstants () {

        $expect = $this->constants;

        $expect = Arr::only($expect, [
            'Init',
            'Processing',
        ]);

        $constants = HandleStatusEnum::getConstants([0, 1]);

        $this->assertEquals($expect, $constants);

        $constants = HandleStatusEnum::getOnlyConstants([0, 1]);

        $this->assertEquals($expect, $constants);
    }

    public function testGetExcludeConstants () {

        $expect = $this->constants;

        unset($expect['Deprecated_A']);
        unset($expect['Deprecated_B']);

        $constants = HandleStatusEnum::getConstants([0, 1, 2, 3]);

        $this->assertEquals($expect, $constants);

        $constants = HandleStatusEnum::getExcludeConstants();

        $this->assertEquals($expect, $constants);

        unset($expect['Init']);
        unset($expect['Error']);

        $constants = HandleStatusEnum::getExcludeConstants([0, 3, 'A', 'B']);

        $this->assertEquals($expect, $constants);
    }

    public function testGetLabels () {

        $expect = [
            0 => '等待处理',
            1 => '正在处理',
            2 => '处理成功',
            3 => '处理失败',
            4 => '废弃枚举值A',
            5 => '废弃枚举值B',
        ];

        $labels = HandleStatusEnum::getLabels();

        $this->assertEquals($expect, $labels);
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
