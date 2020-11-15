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

                    // 初始化报表表格内容
                    $tableContent = '';

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
                    $salesData = \Model\ReportSalesIndexModel::instance()->getData($date, $fields);
                    // 计算 付款转化率 payment_rate
                    $salesData['payment_rate'] = (round($salesData['pay_orders']/$salesData['create_orders'], 4) * 100).'%';
                    // 计算 订单转化率 order_conversion_rate
                    $salesData['order_conversion_rate'] = (round($salesData['pay_orders']/$salesData['dau'], 4) * 100).'%';
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

                    // 处理留存指标
                    // --查询昨日留存数据报表
                    $fields = [
                        'dau',
                        'keep_next_day_user',
                        'keep_next_day_l_uv',
                        'keep_next_day_t_uv',
                        'keep_third_day_l_uv',
                        'keep_third_day_t_uv',
                        'keep_seven_day_l_uv',
                        'keep_seven_day_t_uv',
                        'keep_fifteen_day_l_uv',
                        'keep_fifteen_day_t_uv',
                        'keep_thirty_day_l_uv',
                        'keep_thirty_day_t_uv',
                    ];
                    $appKeepData = \Model\ReportAppKeepIndexModel::instance()->getData($date, $fields);
                    // 计算 次日留存用户占比
                    $appKeepData['keep_next_day_precent'] = (round($appKeepData['keep_next_day_user']/$appKeepData['dau'], 4) * 100).'%';
                    // 计算 次日留存率
                    $appKeepData['keep_next_day_rate'] = $appKeepData['keep_next_day_t_uv'] > 0 ? (round($appKeepData['keep_next_day_l_uv']/$appKeepData['keep_next_day_t_uv'], 4) * 100).'%' : '0%';
                    // 计算 三日留存率
                    $appKeepData['keep_third_day_rate'] = $appKeepData['keep_third_day_t_uv'] > 0 ? (round($appKeepData['keep_third_day_l_uv']/$appKeepData['keep_third_day_t_uv'], 4) * 100).'%' : '0%';
                    // 计算 七日留存率
                    $appKeepData['keep_seven_day_rate'] = $appKeepData['keep_seven_day_t_uv'] > 0 ? (round($appKeepData['keep_seven_day_l_uv']/$appKeepData['keep_seven_day_t_uv'], 4) * 100).'%' : '0%';
                    // 计算 十五日留存率
                    $appKeepData['keep_fifteen_day_rate'] = $appKeepData['keep_fifteen_day_t_uv'] > 0 ? (round($appKeepData['keep_fifteen_day_l_uv']/$appKeepData['keep_fifteen_day_t_uv'], 4) * 100).'%' : '0%';
                    // 计算 三十日留存率
                    $appKeepData['keep_thirty_day_rate'] = $appKeepData['keep_thirty_day_t_uv'] > 0 ? (round($appKeepData['keep_thirty_day_l_uv']/$appKeepData['keep_thirty_day_t_uv'], 4) * 100).'%' : '0%';

                    $appKeepReportData = [
                        'dau'                   => $appKeepData['dau'],
                        'keep_next_day_precent' => $appKeepData['keep_next_day_precent'],
                        'keep_next_day_rate'    => $appKeepData['keep_next_day_rate'],
                        'keep_third_day_rate'   => $appKeepData['keep_third_day_rate'],
                        'keep_seven_day_rate'   => $appKeepData['keep_seven_day_rate'],
                        'keep_fifteen_day_rate' => $appKeepData['keep_fifteen_day_rate'],
                        'keep_thirty_day_rate'  => $appKeepData['keep_thirty_day_rate'],
                    ];
                    // --获取APP留存指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_APP_KEEP);
                    // --获取APP留存指标内容
                    $tbody = \Util\MailTemplate::indicatorTemplate($appKeepReportData);
                    // --获取APP留存指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_APP_KEEP);

                    // --APP留存指标表格 HTML
                    $tableContent .= $thead.$tbody.$tfoot;

                    // 处理首页金刚区指标
                    // --查询昨日首页金刚区数据报表
                    $fields = [
                        'limit_view_uv',
                        'limit_click_uv',
                        'limit_ctr',
                        'brand_view_uv',
                        'brand_click_uv',
                        'brand_ctr',
                        'fashion_view_uv',
                        'fashion_click_uv',
                        'fashion_ctr',
                        'par_view_uv',
                        'par_click_uv',
                        'par_ctr',
                        'versatile_view_uv',
                        'versatile_click_uv',
                        'versatile_ctr',
                    ];
                    $homeJinGangData = \Model\ReportHomeJinGangIndexModel::instance()->getData($date, $fields);
                    // --计算 限时特价-CTR limit_ctr
                    $homeJinGangData['limit_ctr'] = ($homeJinGangData['limit_ctr'] * 100).'%';
                    // --计算 大牌运动-CTR brand_ctr
                    $homeJinGangData['brand_ctr'] = ($homeJinGangData['brand_ctr'] * 100).'%';
                    // --计算 潮人服饰-CTR fashion_ctr
                    $homeJinGangData['fashion_ctr'] = ($homeJinGangData['fashion_ctr'] * 100).'%';
                    // --计算 平价鞋包-CTR par_ctr
                    $homeJinGangData['par_ctr'] = ($homeJinGangData['par_ctr'] * 100).'%';
                    // --计算 百搭服饰-CTR versatile_ctr
                    $homeJinGangData['versatile_ctr'] = ($homeJinGangData['versatile_ctr'] * 100).'%';

                    // --获取首页金刚指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_HOME_JINGANG);
                    // --获取首页金刚指标内容
                    $tbody = \Util\MailTemplate::indicatorTemplate($homeJinGangData);
                    // --获取首页金刚指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_HOME_JINGANG);

                    // --首页金刚指标表格 HTML
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
