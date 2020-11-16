<?php
/**
 * 邮件配置
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-11-15 10:28:30
 */

namespace Config;

/**
 * Class Mail .
 */
class Mail {

    // 是否启用smtp的debug进行调试, 默认关闭debug调试模式
    public $smtpDebug = false;

    // 邮件服务器地址
    public $host = 'smtp.exmail.qq.com';

    // SMTP 鉴权加密方式
    public $secure = 'ssl';

    // SMTP 服务器的远程服务器端口号
    public $port = 465;

    // 邮件编码
    public $charset = 'UTF-8';

    // smtp登录的账号 QQ邮箱即可
    public $username = 'service@xiaonianyu.com';

    // smtp登录的密码 使用生成的授权码
    public $password = 'Q510032140qwe';

    // 设置发件人邮箱地址 同登录账号
    public $sendUser = 'service@xiaonianyu.com';

    // 设置发件人名称
    public $fromName = '小年鱼正式服务器';

    // 公司管理者邮件组
    public $managementGroup = [
//        'xiaochen.gou@xiaonianyu.com',
//        'heng.luo@xiaonianyu.com',
//        'longfei.chen@xiaonianyu.com',
//        'jiaming.chen@xiaonianyu.com',
//        'siqi.li@xiaonianyu.com',
        'yue.wei@xiaonianyu.com',
    ];

    // 公司运营部邮箱组
    public $operationsGroup = [
        'lin.hao@xiaonianyu.com',
    ];

    // 测试用户邮件组
    public $testerGroup = [
        'lin.hao@xiaonianyu.com',
        'yue.wei@xiaonianyu.com',
    ];

    // 维护者邮件组
    public $maintainGroup = [
        'lin.hao@xiaonianyu.com'
    ];
}
