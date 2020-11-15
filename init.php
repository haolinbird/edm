<?php
/**
 * 入口文件.
 *
 * @author Hao Lin <linh@jumei.com>
 *
 * @date 2014-07-14
 */

/**
 * 处理完成回调函数.
 *
 * @return void
 */
function on_phpserver_request_finish($class = '', $requestParam = '', $response = '')
{
    if (class_exists('\Redis\RedisMultiCache', false)) {
        \Redis\RedisMultiCache::close();
    }

    if (class_exists('\Redis\RedisMultiStorage', false)) {
        \Redis\RedisMultiStorage::close();
    }

    if (class_exists('\\Db\\Connection', false) && is_callable(array(\Db\Connection::instance(), 'closeAll'))) {
      //  \Db\Connection::instance()->closeAll();
    }

    if (class_exists('\\Db\\ShardingConnection', false) && is_callable(array(\Db\ShardingConnection::instance(), 'closeAll'))) {
      //  \Db\ShardingConnection::instance()->closeAll();
    }
}


define('ROOT_PATH', __DIR__.DIRECTORY_SEPARATOR);
require ROOT_PATH.'/Vendor/Bootstrap/Autoloader.php';
require_once(ROOT_PATH.'/Vendor/PHPClient/JMTextRpcClient.php');
\Bootstrap\Autoloader::instance()->addRoot(ROOT_PATH)->init();
define('JM_APP_NAME', 'promocard-service');
