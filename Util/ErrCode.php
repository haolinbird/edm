<?php
/**
 * 统一错误码对照表
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Util;

/**
 * Errcode定义类.
 */
class ErrCode
{
    public static $responseBody = array(
        'EXCEPTION' => array('errcode' => -2, 'errmsg' => 'system exception,please contact admin'),
        'FAILED' => array('errcode' => -1, 'errmsg' => 'operation failed, please try again later'),
        'SUCCESS' => array('errcode' => 0, 'errmsg' => 'ok'),

        // 通用错误码
        'ILLEGAL_PAGE' => array('errcode' => 10001, 'errmsg' => "illegal page"),
        'ILLEGAL_PAGE_SIZE' => array('errcode' => 10002, 'errmsg' => "illegal page size"),

        // 用户业务错误码
        'ILLEGAL_uid' => array('errcode' => 20001, 'errmsg' => 'uid must be non-negative integers')
    );

}
