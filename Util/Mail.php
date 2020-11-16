<?php
namespace Util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('/Users/haolin/work/oilunion/edm/Vendor/autoload.php');

/**
 * MAIL 邮件发送工具类
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */
class Mail
{
    // 单例对象
    private static $instances = null;

    // 邮件配置
    private static $config = null;

    // 收件人组-管理组
    const ADDRESSEE_GROUP_MANAGER = 'manager';
    // 收件人组-运营组
    const ADDRESSEE_GROUP_OPERATORS = 'operators';
    // 收件人组-测试组
    const ADDRESSEE_GROUP_TESTER = 'tester';
    // 收件人组-维护组
    const ADDRESSEE_GROUP_MAINTAIN = 'maintain';

    /**
     * Get instance.
     *
     * @return static
     */
    public static function instance()
    {
        if (!self::$instances) {
            self::$instances = new self();
        }

        return self::$instances;
    }

    /**
     * 发送邮件
     *
     * @param string $subject        邮件主题
     * @param string $mailContent    邮件正文
     * @param mixed  $addresseeGroup 收件人
     *
     * @return boolean
     * @throws Exception
     */
    function sendMail($subject, $mailContent, $addresseeGroup = '')
    {
        $config = new \Config\Mail();

        $mail = new PHPMailer(true);

        // 实例化PHPMailer核心类
        $mail = new PHPMailer();
        // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = $config->smtpDebug;
        // 使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        // smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
        // 链接qq域名邮箱的服务器地址
        $mail->Host = $config->host;
        // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = $config->secure;
        // 设置ssl连接smtp服务器的远程服务器端口号
        $mail->Port = $config->port;
        // 设置发送的邮件的编码
        $mail->CharSet = $config->charset;
        // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = $config->fromName;
        // smtp登录的账号 QQ邮箱即可
        $mail->Username = $config->username;
        // smtp登录的密码 使用生成的授权码
        $mail->Password = $config->password;
        // 设置发件人邮箱地址 同登录账号
        $mail->From = $config->sendUser;
        // 邮件正文是否为html编码 注意此处是一个方法
        $mail->isHTML(true);
        // 设置收件人邮箱地址
        switch ($addresseeGroup) {
            // 管理组
            case self::ADDRESSEE_GROUP_MANAGER:
                $addressee = $config->managementGroup;
                break;
            // 运营组
            case self::ADDRESSEE_GROUP_OPERATORS:
                $addressee = $config->operationsGroup;
                break;
            // 测试组
            case self::ADDRESSEE_GROUP_TESTER:
                $addressee = $config->testerGroup;
                break;
            // 维护组
            case self::ADDRESSEE_GROUP_MAINTAIN:
                $addressee = $config->maintainGroup;
                break;
            default:
                // 默认发送维护组
                $addressee = $config->maintainGroup;
                break;
        }
        if (is_array($addressee)) {
            foreach ($addressee as $address) {
                $mail->addAddress($address);
            }
        } else {
            $mail->addAddress($addressee);
        }
        // 添加该邮件的主题
        $mail->Subject = $subject;
        // 添加邮件正文
        $mail->Body = $mailContent;
        // 为该邮件添加附件
        // $mail->addAttachment('./example.pdf');
        // 发送邮件 返回状态
        $status = $mail->send();

        return $status;
    }


}
