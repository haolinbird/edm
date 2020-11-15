<?php
/**
 * 邮件配置
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Config;

/**
 * Class Mail .
 */
class Mail {

    // 邮件服务器地址
    // public $host = 'smtp.qq.com';
    public $host = 'smtp.exmail.qq.com';

    // SMTP 鉴权加密方式
    public $secure = 'ssl';

    // SMTP 服务器的远程服务器端口号
    public $port = 465;

    // 邮件编码
    public $charset = 'UTF-8';

    // smtp登录的账号 QQ邮箱即可
    // public $username = '41114872@qq.com';
    public $username = 'i_noreply@xiaonianyu.com';

    // smtp登录的密码 使用生成的授权码
    //public $password = 'fwfvtnktpwsfbhbc';
    public $password = 'Norpxny2020123';

    // 设置发件人邮箱地址 同登录账号
    //public $sendUser = '41114872@qq.com';
    public $sendUser = 'i_noreply@xiaonianyu.com';
}
