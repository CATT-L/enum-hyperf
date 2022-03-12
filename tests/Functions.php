<?php

if (!function_exists('SetupConstants')) {

    function SetupConstants (string $class) {
        $reader    = new \Hyperf\Constants\AnnotationReader();
        $ref       = new \ReflectionClass($class);
        $constants = $ref->getReflectionConstants();
        $data      = $reader->getAnnotations($constants);
        \Hyperf\Constants\ConstantsCollector::set($class, $data);
    }
}
