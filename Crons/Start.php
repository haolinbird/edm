<?php
/**
 * php Start.php --class=SendMail --arg1=foo --arg2="bars"
 */

namespace {
    class CronException extends \Exception{}
}

namespace Crons {
    date_default_timezone_set('Asia/Shanghai');
    define('SCRIPT_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
    require_once realpath(SCRIPT_ROOT . '..' . DIRECTORY_SEPARATOR .'init.php');
    $option = \Util\CliOptions::ParseFromArgv();
    $class = $option->getOption('class');
    $sample = <<<EOT

EOT;
    if (empty($class)) {
        echo 'class is undefined' . PHP_EOL;
        echo $sample;
        return;
    } else {
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR , $class);
        if (!file_exists(SCRIPT_ROOT . $classPath . '.php')) {
            echo $class . '.php not found in path ' . SCRIPT_ROOT;
            return;
        }
    }



    $className = __NAMESPACE__ . '\\' .$class;
    $object = new $className($option);
    $method = $option->getOption('method', 'process');
    if (!method_exists($object, $method)) {
        echo "method {$className}->{$method} not defined";
        return;
    }
    $object->{$method}();
}