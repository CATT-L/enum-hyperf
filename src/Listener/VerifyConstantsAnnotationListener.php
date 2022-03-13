<?php


namespace Catt\Enum\Listener;

use Catt\Enum\AbstractEnum;
use Hyperf\Constants\ConstantsCollector;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;

/**
 * 枚举重复校验
 */
class VerifyConstantsAnnotationListener implements ListenerInterface {

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $name = '@Constant注解';

    public function __construct (StdoutLoggerInterface $logger) {
        $this->logger = $logger;
    }


    public function listen (): array {
        return [
            BootApplication::class,
        ];
    }

    public function process (object $event) {

        // $this->logger->info(sprintf('[%s] 开始检查', $this->name));

        foreach (ConstantsCollector::list() as $classname => $constant) {

            $ref = new \ReflectionClass($classname);

            if ($ref->isSubclassOf(AbstractEnum::class)) {

                $constant = $ref->getConstants();

                $unique = array_unique($constant);

                if (count($constant) != count($unique)) {

                    $assoc = array_diff_assoc($constant, $unique);

                    foreach ($assoc as $key => $value) {
                        $message = sprintf('[%s] 枚举[%s]中存在重复值,key为[%s],请检查', $this->name, $classname, $key);
                        $this->logger->notice($message);
                    }
                }
            }
        }

        // $this->logger->info(sprintf('[%s] 检查完毕', $this->name));
    }
}
