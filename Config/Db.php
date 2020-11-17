<?php
/**
 * 数据库配置文件
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date   2020-11-16 20:28:30
 */

namespace Config;

class Db
{
    public $DEBUG = false;
    public $DEBUG_LEVEL = 0;

    /**
     * Configs of database.
     * @var array
     */
    public $read = array(
        'default' => array(
            'host'         => '47.108.106.227',
            'port'         => 3306,
            'db'           => 'xny_statistics',
            'user'         => 'maimanman',
            'password'     => 'maimanman0901',
            'confirm_link' => true,
            'options'      => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',
                \PDO::ATTR_TIMEOUT => 2,
            ),
        ),
        'xiaonianyu' => array(
            'host'         => 'bi-data-xny.rwlb.rds.aliyuncs.com',
            'port'         => 3306,
            'db'           => 'xiaonianyu',
            'user'         => 'bi_read',
            'password'     => 'Fr2s9AK^CJ',
            'confirm_link' => true,
            'options'      => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',
                \PDO::ATTR_TIMEOUT => 2,
            ),
        )
    );

    public $write = array(
        'default' => array(
            'host'         => '47.108.106.227',
            'port'         => 3306,
            'db'           => 'xny_statistics',
            'user'         => 'maimanman',
            'password'     => 'maimanman0901',
            'confirm_link' => true,
            'options'      => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',
                \PDO::ATTR_TIMEOUT => 2,
            ),
        ),
    );

}