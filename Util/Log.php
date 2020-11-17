<?php
/**
 * 日志工具类
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date   2020-11-16 20:28:30
 */

namespace Util;

/**
 * class Log.
 */
class Log
{
    /**
     * 写日志.
     *
     * @param string  $data       日志内容.
     * @param string  $filename   日志文件名.
     * @param boolean $dateFormat 文件名后是否加日期.
     *
     * @return void
     */
    public static function log($data, $filename = 'common', $dateFormat = true)
    {
        $path = ROOT_PATH.'Logs/';
        if ($dateFormat) {
            $message = date('Y-m-d H:i:s').":\t {$data}\n";
            $filename = $filename.date('Ymd');
        } else {
            $message = date('Y-m-d H:i:s').":\t {$data}\n";
            $path .= $filename;
            if (! file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $filename = date('Ymd');
        }
        $file = $path . "/{$filename}.log";
        touch($file);
        file_put_contents($file, $message,FILE_APPEND);
    }
}
