<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Catt\Enum;

use Catt\Enum\Listener\ValidatorFactoryResolvedListener;
use Catt\Enum\Listener\VerifyConstantsAnnotationListener;

class ConfigProvider {
    public function __invoke (): array {
        return [
            'dependencies' => [],
            'commands'     => [],
            'listeners'    => [
                VerifyConstantsAnnotationListener::class,
                ValidatorFactoryResolvedListener::class,
            ],
            'annotations'  => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
