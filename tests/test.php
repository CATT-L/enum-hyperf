<?php

namespace HyperfTest;

use Catt\Enum\Exception\InvalidEnumMemberException;
use Catt\Enum\Rule\EnumValue;
use HyperfTest\Enum\HandleStatusEnum;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// -----------------------------------------------------------

SetupConstants(HandleStatusEnum::class);

// var_dump(HandleStatusEnum::getConstants([0, 1, 2, 3]));
//
// var_dump(in_array('B', [1,2,3]));

$r = (new EnumValue(HandleStatusEnum::class))->passes('', 1);

$r = (new EnumValue(HandleStatusEnum::class))->message();

var_dump($r);

