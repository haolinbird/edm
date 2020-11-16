<?php
/**
 * 监控日志组件配置文件
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Config;

/**
 * Class MNLogger .
 */
class MNLogger
{
    public $exception = array (
        'logdir' => '/home/logs/monitor/',
        'app' => 'Service-Base',
        'on' => true,
    );

    public $trace = array (
        'logdir' => '/home/logs/monitor/',
        'app' => 'Service-Base',
        'on' => false,
    );
}
