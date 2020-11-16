<?php
/**
 * Class Debug
 *
 * @author Lin Hao <lin.hao@xiaonianyu.com>
 * @date 2020-11-15 20:28:30
 */

namespace Util;

/**
 * Debug 调试输出方法.
 */
class Debug
{
    /**
     * debug调试输出.
     *
     * @param string $output 输出信息.
     */
    public static function debugOutput($output)
    {
        echo date('Y-m-d H:i:s')."\t debug_info:\t".$output."\r\n";
    }

    /**
     * 运行提示输出.
     *
     * @param string $output 输出信息.
     */
    public static function promptOutput($output)
    {
        echo date('Y-m-d H:i:s')."\t info:\t".$output."\r\n";
    }
}
