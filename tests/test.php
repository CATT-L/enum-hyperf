<?php

namespace HyperfTest;

use Hyperf\Constants\AnnotationReader;
use Hyperf\Constants\ConstantsCollector;
use HyperfTest\Enum\HandleStatusEnum;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// -----------------------------------------------------------
//
// var_dump('hello');
//
// $enum = new HandleStatusEnum();
//
// var_dump($enum);
//
// var_dump(HandleStatusEnum::getConstants());
//
// $reader = new AnnotationReader();
//
// $ref = new \ReflectionClass(HandleStatusEnum::class);
// $constants = $ref->getReflectionConstants();
//
// $data = $reader->getAnnotations($constants);
//
// ConstantsCollector::set(HandleStatusEnum::class, $data);
//
//
//
// // var_dump($data);
//
// $r = HandleStatusEnum::getLabel(1);
// var_dump($r);


// var_dump(HandleStatusEnum::getConstants());
// var_dump(HandleStatusEnum::$default);
//
// $enum = HandleStatusEnum::fromKey('Init');
//
// var_dump($enum->value);


// var_dump(HandleStatusEnum::fromValue(2) > HandleStatusEnum::fromValue(111));
// $enum->setValue();
