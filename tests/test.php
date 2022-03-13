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

// $r = (new EnumValue(HandleStatusEnum::class))->passes('', 1);

// $r = (new EnumValue(HandleStatusEnum::class))->message();


// $init       = HandleStatusEnum::fromValue(HandleStatusEnum::Init);
$processing = HandleStatusEnum::fromValue(HandleStatusEnum::Processing);

var_dump($processing);

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

// object(App\Model\Enum\HandleStatusEnum)#9 (3) {
//   ["value":protected]=>
//   int(1)
//   ["key":protected]=>
//   string(10) "Processing"
//   ["label":protected]=>
//   string(9) "处理中"
// }

// HandleStatusEnum::getConstants(); // ['Init' => 0, 'Processing' => 1, 'Success' => 2, 'Error' => 3]

// HandleStatusEnum::getConstants([0, 1]); // ['Init' => 0, 'Processing' => 1]

// [0, 1, 2, 3]
HandleStatusEnum::getLabels();

var_dump(var_export(HandleStatusEnum::getLabels()));// var_dump(var_export(HandleStatusEnum::getConstants([0, 1])));

;
