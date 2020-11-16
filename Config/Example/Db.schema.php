<?php
/**
 * 数据库配置文件
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
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
            'host'         => '10.9.93.199',
            'port'         => 3306,
            'db'           => 'unionoil_user',
            'user'         => 'user_read',
            'password'     => 'N1XM^4Lm3q6EwEkg',
            'confirm_link' => true,
            'options'      => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',
                \PDO::ATTR_TIMEOUT => 2,
            ),
        )
    );

    public $write = array(
        'default' => array(
            'host'         => '10.9.93.199',
            'port'         => 3306,
            'db'           => 'unionoil_user',
            'user'         => 'user_write',
            'password'     => 'QBK56RxZ#MA*RFb@',
            'confirm_link' => true,
            'options'      => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',
                \PDO::ATTR_TIMEOUT => 2,
            ),
        )
    );

}