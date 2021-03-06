<?php
namespace Crons;

use Bootstrap\Util;

require_once __DIR__.'/../init.php';
/**
 * 发送统计邮件.
 */
class SendMail
{
    // 邮件类型
    // --整体日报
    const TYPE_OVERALL = 'overall';
    // --ECPM日报
    const TYPE_ECMP = 'ecpm';

    /**
     * 处理数据.
     *
     * @return void
     */
    public function process()
    {
        try {
            $option = \Util\CliOptions::ParseFromArgv();
            $type = $option->getOption('type');

            switch ($type) {
                // 整体日报
                case self::TYPE_OVERALL:
                    // 获取昨日日期
                    //$date = date('Y-m-d H:i:s', strtotime('yesterday'));
                    $date = date('Y-m-d H:i:s', strtotime('yesterday'));
                    // --获取 HTML 主体内容
                    $html = \Util\MailTemplate::mainTemplate('整体日报');

                    // 处理销售指标
                    // --查询昨日销售数据报表
                    $fields = [
                        'gmv',
                        'pay_amount',
                        'sales',
                        'pay_child_orders',
                        'pay_orders',
                        'create_orders',
                        //'payment_rate',
                        //'order_conversion_rate',
                        'pay_users',
                        //'new_users',
                        //'per_order_price',
                        //'per_capita_orders',
                        'dau',
                        'gross_profit',
                        'gross_margin',
                    ];
                    var_dump(33);
                    $salesData = \Model\XnyStatisticsModel::instance()->getData($date, $fields);
                    var_dump(12);
                    // 计算 付款转化率 payment_rate
                    $salesData['payment_rate'] = round($salesData['pay_orders']/$salesData['create_orders'], 2).'%';
                    // 计算 订单转化率 order_conversion_rate
                    $salesData['order_conversion_rate'] = round($salesData['pay_orders']/$salesData['dau'], 4).'%';
                    // 计算 笔单价 per_order_price
                    $salesData['per_order_price'] = round($salesData['gmv']/$salesData['pay_orders'], 2);
                    // 计算 人均订单价 per_capita_orders
                    $salesData['per_capita_orders'] = round($salesData['pay_orders']/$salesData['pay_users'], 2);

                    $salesReportData = [
                        'gmv'                   => $salesData['gmv'],
                        'pay_amount'            => $salesData['pay_amount'],
                        'sales'                 => $salesData['sales'],
                        'pay_child_orders'      => $salesData['pay_child_orders'],
                        'pay_orders'            => $salesData['pay_orders'],
                        'create_orders'         => $salesData['create_orders'],
                        'payment_rate'          => $salesData['payment_rate'],
                        'order_conversion_rate' => $salesData['order_conversion_rate'],
                        'pay_users'             => $salesData['pay_users'],
                        'per_order_price'       => $salesData['per_order_price'],
                        'per_capita_orders'     => $salesData['per_capita_orders'],
                        'gross_profit'          => $salesData['gross_profit'],
                        'gross_margin'          => $salesData['gross_margin'],
                    ];
                    // --获取销售指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_SALES);
                    // --获取销售指标内容
                    $tbody = \Util\MailTemplate::indicatorTemplate($salesReportData);
                    // --获取销售指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_SALES);

                    // --销售指标表格 HTML
                    $tableContent .= $thead.$tbody.$tfoot;

                    // 组装邮件 HTML 内容
                    $html = str_replace('{#table_body}', $tableContent, $html);

                    $subject = '小年鱼整体日报-'.date('Y-m-d', strtotime('yesterday'));

                    // 发送邮件
                    $sendResponse = \Util\Mail::instance()->sendMail($subject, $html);

                    // 更新发送结果
                    break;
                // ECPM报表
                case self::TYPE_ECMP:
                    break;
                default:
                    break;
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
