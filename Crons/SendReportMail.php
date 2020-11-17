<?php
/**
 * php Start.php --class=SendReportMail --type=overall --rc=maintain --date=2020-11-15
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date   2020-11-15 20:28:30
 */

namespace Crons;

require_once __DIR__.'/../init.php';
/**
 * 发送报表邮件.
 */
class SendReportMail
{
    // 邮件类型
    // --整体日报
    const TYPE_OVERALL = 'overall';
    // --首页专场CTR
    const TYPE_HOME_SPECIAL_CTR = 'home_special_ctr';
    // --ECPM日报
    const TYPE_ECMP = 'ecpm';

    /**
     * 处理数据.
     *
     * @return void
     * @throws \Exception
     */
    public function process()
    {
        // 接收脚本参数
        $option = \Util\CliOptions::ParseFromArgv();
        // 邮件类型
        $type = $option->getOption('type');
        // 收件人邮件组
        $addressee = $option->getOption('rc');
        // 报表统计日期, 默认统计昨日数据
        $reportTimestamp = $option->getOption('date') ? strtotime($option->getOption('date')) : strtotime('yesterday');
        // 转化为数据库存储的统计日期 kettle java 要求 date 日期格式必须是 YY-mn-dd H:i:s, 需要转化下查询日期格式
        $date = date('Y-m-d H:i:s', $reportTimestamp);

        \Util\Debug::promptOutput('start_send_report_mail');
        \Util\Debug::promptOutput('report_type -> '.$type);
        \Util\Debug::promptOutput('addressee -> '.$addressee);
        \Util\Debug::promptOutput('report_date -> '.$date);

        try {
            throw new \Exception('test exception');
            switch ($type) {
                // 整体日报
                case self::TYPE_OVERALL:
                    // 获取昨日日期
                    $date = date('Y-m-d H:i:s', strtotime('yesterday'));

                    // --获取 HTML 主体内容
                    $html = \Util\MailTemplate::mainTemplate('整体日报');

                    // 初始化报表表格内容
                    $tableContent = '';

                    // 设置销售指标
                    $tableContent .= \Module\Report::instance()->getSalesIndexReport($date);
                    \Util\Debug::promptOutput('sale_index complete');

                    // 设置规模指标
                    $tableContent .= \Module\Report::instance()->getScaleIndexReport($date);
                    \Util\Debug::promptOutput('scale_index complete');

                    // 设置留存指标
                    $tableContent .= \Module\Report::instance()->getAppKeepIndexReport($date);
                    \Util\Debug::promptOutput('app_keep_index complete');

                    // 设置专场流量指标
                    $tableContent .= \Module\Report::instance()->getSpecialFlowIndexReport($date);
                    \Util\Debug::promptOutput('special_flow_index complete');

                    // 设置流量指标
                    $tableContent .= \Module\Report::instance()->getFlowIndexReport($date);
                    \Util\Debug::promptOutput('flow_index complete');

                    // 设置首页金刚区指标
                    $tableContent .= \Module\Report::instance()->getHomeJinGangIndexReport($date);
                    \Util\Debug::promptOutput('home_jin_gangindex complete');

                    // 设置首页主推专区指标
                    $tableContent .= \Module\Report::instance()->getHomeRecommendSpecialIndexReport($date);
                    \Util\Debug::promptOutput('home_recommend_special_index complete');

                    // 设置首页专区列表指标
                    $tableContent .= \Module\Report::instance()->getHomeSpecialListIndexReport($date);
                    \Util\Debug::promptOutput('home_special_list_index complete');

                    // 组装邮件 HTML 内容
                    $html = str_replace('{#table_body}', $tableContent, $html);

                    // 设置邮件标题
                    $subject = '小年鱼-整体日报-'.date('Y-m-d', strtotime('yesterday'));

                    // 发送邮件
                    $sendResponse = \Util\Mail::instance()->sendMail($subject, $html, $addressee);
                    \Util\Debug::promptOutput('send_mail_response -> '.$sendResponse);

                    break;
                // 首页专场CTR
                case self::TYPE_HOME_SPECIAL_CTR:
                    // 获取昨日日期
                    $date = date('Y-m-d H:i:s', strtotime('yesterday'));

                    // --获取 HTML 主体内容
                    $html = \Util\MailTemplate::mainTemplate('整体日报');

                    // 初始化报表表格内容
                    $tableContent = '';

                    // 设置首页专区列表指标
                    $tableContent .= \Module\Report::instance()->getHomeSpecialListIndexReport($date);

                    // 组装邮件 HTML 内容
                    $html = str_replace('{#table_body}', $tableContent, $html);

                    $subject = '小年鱼-首页专场CTR报表-'.date('Y-m-d', strtotime('yesterday'));

                    // 发送邮件
                    $sendResponse = \Util\Mail::instance()->sendMail($subject, $html, $addressee);

                    break;
                // ECPM报表
                case self::TYPE_ECMP:
                    break;
                default:
                    break;
            }
        } catch (\Exception $e) {
            // 构造异常信息
            $errMessage = "mail_type -> ".$type.",\t report_date -> ".$date.",\t run exception -> ".$e->getMessage();

            // 输出异常信息
            \Util\Debug::promptOutput($errMessage);

            // 记录异常日志
            \Util\Log::log($errMessage, 'dayReportException', false);

            // 发送异常邮件到维护组
            $sendResponse = \Util\Mail::instance()->sendMail('报表邮件发送异常', $errMessage, \Util\Mail::ADDRESSEE_GROUP_MAINTAIN);
        }
    }
}
