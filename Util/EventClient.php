<?php
/**
 * 消息中心相关操作类
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Util;

/**
 * EventClient类.
 */
class EventClient
{

    /**
     * 向消息中心发放消息(rpc).
     * 
     * @param string  $key         消息名.
     * @param array   $message     消息数据.
     * @param integer $priority    优先级.
     * @param integer $timeToDelay 延时发送时间.
     *
     * @return array
     */
    public static function sendMessage($key, $message, $priority = 1024, $timeToDelay = 0)
    {
        if (empty($key) || empty($message)) {
            return false;
        }

        return \EventClient\RpcClient::instance('psProto')->setClass('Broadcast')->Send($key, $message, $priority, $timeToDelay);
    }

}
