<?php
/**
 * Redis配置文件
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Config;

class Redis
{

    /**
     * Configs of Redis.
     * 
     * @var array
     */
    public $default = array(
        'db' => 1,
        'nodes' =>  "#{Res.Redis.user.storage}",
        'password' => "#{Res.Redis.union.cache.auth}"
    );

}
