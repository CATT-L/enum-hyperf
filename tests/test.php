<?php

namespace HyperfTest;

use Catt\Enum\Exception\InvalidEnumMemberException;
use HyperfTest\Enum\HandleStatusEnum;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// -----------------------------------------------------------

SetupConstants(HandleStatusEnum::class);

// var_dump(HandleStatusEnum::getConstants([0, 1, 2, 3]));
//
// var_dump(in_array('B', [1,2,3]));

try {
    HandleStatusEnum::verify(11);
} catch (InvalidEnumMemberException $e) {

    var_dump($e->enum::getLabels());

}




