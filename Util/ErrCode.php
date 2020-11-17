<?php
/**
 * 统一错误码对照表
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date   2020-11-16 20:28:30
 */

namespace Util;

/**
 * Errcode 定义类.
 */
class ErrCode
{

    public static $responseBody = array(
        'EXCEPTION' => array('errcode' => -2, 'errmsg' => 'system exception, please contact admin'),
        'FAILED'    => array('errcode' => -1, 'errmsg' => 'operation failed, please try again later'),
        'SUCCESS'   => array('errcode' => 0,  'errmsg' => 'ok'),
    );

}
