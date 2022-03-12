<?php

namespace HyperfTest;

use Hyperf\Constants\AnnotationReader;
use Hyperf\Constants\ConstantsCollector;
use HyperfTest\Enum\HandleStatusEnum;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// -----------------------------------------------------------


// var_dump(HandleStatusEnum::getConstants([0, 1, 2, 3]));
//
// var_dump(in_array('B', [1,2,3]));


$r = HandleStatusEnum::getExcludeConstants([0,1]);

var_dump($r);
