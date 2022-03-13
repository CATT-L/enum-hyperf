<?php

namespace HyperfTest;

use Catt\Enum\Exception\InvalidEnumMemberException;
use Catt\Enum\Rule\EnumValue;
use HyperfTest\Enum\ExampleDefaultCastsModel;
use HyperfTest\Enum\ExampleEnumCastsModel;
use HyperfTest\Enum\HandleStatusEnum;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// -----------------------------------------------------------

SetupConstants(HandleStatusEnum::class);

// var_dump(HandleStatusEnum::getConstants([0, 1, 2, 3]));
//
// var_dump(in_array('B', [1,2,3]));

// $r = (new EnumValue(HandleStatusEnum::class))->passes('', 1);

// $r = (new EnumValue(HandleStatusEnum::class))->message();


// $init       = HandleStatusEnum::fromValue(HandleStatusEnum::Init);
// $processing = HandleStatusEnum::fromValue('1');


// var_dump($processing);

// echo var_export(HandleStatusEnum::getOptions());


// $success    = HandleStatusEnum::fromValue(HandleStatusEnum::Success);
//
// $processing->is(HandleStatusEnum::Processing);                              // true
// $processing->is($processing);                                               // true
// $processing->is(HandleStatusEnum::fromValue(HandleStatusEnum::Processing)); // true
// $processing->is(1);                                                         // true
//
// $processing->is(HandleStatusEnum::Success);                                 // false
// $processing->is($success);                                                  // false
// $processing->is(HandleStatusEnum::fromValue(HandleStatusEnum::Success));    // false
// $processing->is('foobar');                                                  // false
//
//
// $processing = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);
// $success    = HandleStatusEnum::fromValue(HandleStatusEnum::Success);
//
// $processing->in([0, 1]);                  // true
// $processing->in([$processing, $success]); // true
//
// $processing->in([HandleStatusEnum::Init, HandleStatusEnum::Success]);  // false
// $processing->in([]);                                                   // false
// $processing->in(['foobar']);                                           // false
//

//
// $model = new ExampleDefaultCastsModel();
//
// $model->handleStatus = 1;
//
// var_dump($model->handleStatus);
//

