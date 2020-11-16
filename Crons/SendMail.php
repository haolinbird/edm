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
    // --首页专场CTR
    const TYPE_HOME_SPECIAL_CTR = 'home_special_ctr';
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
                        'pay_users',
                        'dau',
                        'gross_profit',
                        'gross_margin',
                    ];
                    $salesData = \Model\ReportSalesIndex::instance()->getData($date, $fields);
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

                    // 处理规模指标
                    // --查询昨日规模数据报表
                    $fields = [
                        'total_register_users',
                        'new_register_users',
                        'dau',
                        'start_cnt',
                        'device_cnt',
                        'first_device_cnt',
                        'login_user_cnt',
                        'add_shopcar_cnt',
                    ];
                    $scaleData = \Model\ReportScaleIndex::instance()->getData($date, $fields);
                    // 计算 加购转化率 add_shopcar_rate
                    $scaleData['add_shopcar_rate'] = (round($scaleData['add_shopcar_cnt']/$scaleData['login_user_cnt'], 4) * 100).'%';
                    // 计算 访问频次 visit_frequency
                    $scaleData['visit_frequency'] = round($scaleData['start_cnt']/$scaleData['device_cnt'], 4);

                    $scaleReportData = [
                        'total_register_users' => $scaleData['total_register_users'],
                        'new_register_users'   => $scaleData['new_register_users'],
                        'dau'                  => $scaleData['dau'],
                        'device_cnt'           => $scaleData['device_cnt'],
                        'first_device_cnt'     => $scaleData['first_device_cnt'],
                        'login_user_cnt'       => $scaleData['login_user_cnt'],
                        'add_shopcar_cnt'      => $scaleData['add_shopcar_cnt'],
                        'add_shopcar_rate'     => $scaleData['add_shopcar_rate'],
                        'visit_frequency'      => $scaleData['visit_frequency'],
                    ];
                    // --获取规模指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_SCALE);
                    // --获取规模指标内容
                    $tbody = \Util\MailTemplate::indicatorTemplate($scaleReportData);
                    // --获取规模指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_SCALE);

                    // --销售规模表格 HTML
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
                    $appKeepData = \Model\ReportAppKeepIndex::instance()->getData($date, $fields);
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

                    // 处理专场流量指标
                    // --查询昨日专场流量数据报表
                    $fields = [
                        'new_customer_uv',
                        'new_customer_click_uv',
                        'new_customer_ctr',
                        'seckilling_uv',
                        'seckilling_click_uv',
                        'seckilling_ctr',
                        'must_buy_uv',
                        'must_buy_click_uv',
                        'must_buy_ctr',
                        'today_uv',
                        'today_click_uv',
                        'today_ctr',
                    ];
                    $specialFlowData = \Model\ReportSpecialFlowIndex::instance()->getData($date, $fields);
                    // 计算 新人专场 CTR
                    $specialFlowData['new_customer_ctr'] = (round($specialFlowData['new_customer_click_uv']/$specialFlowData['new_customer_uv'], 4) * 100).'%';
                    // 计算 秒杀专场 CTR
                    $specialFlowData['seckilling_ctr'] = (round($specialFlowData['seckilling_click_uv']/$specialFlowData['seckilling_uv'], 4) * 100).'%';
                    // 计算 必买专场 CTR
                    $specialFlowData['must_buy_ctr'] = (round($specialFlowData['must_buy_click_uv']/$specialFlowData['must_buy_uv'], 4) * 100).'%';
                    // 计算 上新专场 CTR
                    $specialFlowData['today_ctr'] = (round($specialFlowData['today_click_uv']/$specialFlowData['today_uv'], 4) * 100).'%';
                    // --获取专场流量指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_SPECIAL_FLOW);
                    // --获取专场流量指标内容
                    $tbody = \Util\MailTemplate::indicatorTemplate($specialFlowData);
                    // --获取专场流量指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_SPECIAL_FLOW);

                    // --专场流量指标表格 HTML
                    $tableContent .= $thead.$tbody.$tfoot;

                    // 处理流量指标
                    // --查询昨日流量数据报表
                    $fields = [
                        'home_main_view_uv',
                        'home_main_click_uv',
                        'home_main_ctr',
                        'search_view_uv',
                        'search_click_uv',
                        'search_ctr',
                    ];
                    $flowData = \Model\ReportFlowIndex::instance()->getData($date, $fields);
                    // 计算 首页用户数CTR home_main_ctr
                    $flowData['home_main_ctr'] = (round($flowData['home_main_click_uv']/$flowData['home_main_view_uv'], 4) * 100).'%';
                    // 计算 搜索用户CTR CTR search_ctr
                    $flowData['search_ctr'] = (round($flowData['search_click_uv']/$flowData['search_view_uv'], 4) * 100).'%';
                    // --获取流量指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_FLOW);
                    // --获取流量指标内容
                    $tbody = \Util\MailTemplate::indicatorTemplate($flowData);
                    // --获取流量指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_FLOW);

                    // --流量指标表格 HTML
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
                    $homeJinGangData = \Model\ReportHomeJinGangIndex::instance()->getData($date, $fields);
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

                    // 处理首页主推专场指标
                    // --查询昨日首页主推专场数据报表
                    $fields = [
                        'new_customer_view_uv',
                        'new_customer_click_uv',
                        'new_customer_ctr',
                        'seckilling_view_uv',
                        'seckilling_click_uv',
                        'seckilling_ctr',
                        'must_buy_view_uv',
                        'must_buy_click_uv',
                        'must_buy_ctr',
                        'today_view_uv',
                        'today_click_uv',
                        'today_ctr',
                    ];
                    $homeRecommendData = \Model\ReportHomeRecommendSpecial::instance()->getData($date, $fields);
                    // --计算 新人专场-CTR new_customer_ctr
                    $homeRecommendData['new_customer_ctr'] = (round($homeRecommendData['new_customer_click_uv']/$homeRecommendData['new_customer_view_uv'], 4) * 100).'%';
                    // --计算 秒杀专场-CTR seckilling_ctr
                    $homeRecommendData['seckilling_ctr'] = (round($homeRecommendData['seckilling_click_uv']/$homeRecommendData['seckilling_view_uv'], 4) * 100).'%';
                    // --计算 必买专场-CTR new_customer_ctr
                    $homeRecommendData['must_buy_ctr'] = (round($homeRecommendData['must_buy_click_uv']/$homeRecommendData['must_buy_view_uv'], 4) * 100).'%';
                    // --计算 上新专场-CTR today_ctr
                    $homeRecommendData['today_ctr'] = (round($homeRecommendData['today_click_uv']/$homeRecommendData['today_view_uv'], 4) * 100).'%';

                    // --获取首页主推专场指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_HOME_RECOMMEND);
                    // --获取首页主推专场指标内容
                    $tbody = \Util\MailTemplate::indicatorTemplate($homeRecommendData);
                    // --获取首页主推专场指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_HOME_RECOMMEND);

                    // --首页主推专场指标表格 HTML
                    $tableContent .= $thead.$tbody.$tfoot;

                    // 组装邮件 HTML 内容
                    $html = str_replace('{#table_body}', $tableContent, $html);

                    $subject = '小年鱼-整体日报-'.date('Y-m-d', strtotime('yesterday'));

                    // 发送邮件
                    $sendResponse = \Util\Mail::instance()->sendMail($subject, $html);

                    // 更新发送结果
                    break;
                // 首页专场CTR
                case self::TYPE_HOME_SPECIAL_CTR:
                    // 获取昨日日期
                    $date = date('Y-m-d H:i:s', strtotime('yesterday'));

                    // --获取 HTML 主体内容
                    $html = \Util\MailTemplate::mainTemplate('整体日报');

                    // 初始化报表表格内容
                    $tableContent = '';

                    // 处理首页专场列表指标
                    // --查询昨日首页专场列表数据报表
                    $fields = [
                        'position',
                        'special_id',
                        'special_name',
                        'view_uv',
                        'click_uv',
                        'ctr',
                    ];
                    $homeSpecialListData = \Model\ReportHomeSpecialListIndex::instance()->getDatas($date, $fields);

                    // 查询专场名称
                    $materialIds = [];
                    foreach ($homeSpecialListData as $value) {
                        $materialIds[] = $value['special_id'];
                    }
                    $materialIds = array_unique($materialIds);

                    if ($materialIds) {
                        $specialNames = \Model\IdeaTopic::instance()->getInfoByIds($materialIds);

                    }

                    // 组装报表数据
                    $homeSpecialListReportData = [];
                    foreach ($homeSpecialListData as $value) {
                        $homeSpecialListReportData[] = [
                            'position' => $value['position'],
                            // 拼装专场名称
                            'special_name' => !empty($specialNames[$value['special_id']]) ? $specialNames[$value['special_id']]['title'] : '未知',
                            'view_uv' => $value['view_uv'],
                            'click_uv' => $value['click_uv'],
                            // 计算 CTR
                            'ctr' => (round($value['click_uv']/$value['view_uv'], 4) * 100).'%',
                        ];
                    }

                    // --获取首页专场列表指标表头
                    $thead = \Util\MailTemplate::headTemplate(\Util\MailTemplate::INDEX_HOME_SPECIAL_LIST);
                    // --获取首页专场列表指标内容
                    $tbody = '';
                    foreach ($homeSpecialListReportData as $row) {
                        $tbody .= \Util\MailTemplate::indicatorTemplate($row);
                    }
                    // --获取首页专场列表指标表尾
                    $tfoot = \Util\MailTemplate::footTemplate(\Util\MailTemplate::INDEX_HOME_SPECIAL_LIST);

                    // --获取首页专场列表指标表格 HTML
                    $tableContent .= $thead.$tbody.$tfoot;

                    // 组装邮件 HTML 内容
                    $html = str_replace('{#table_body}', $tableContent, $html);

                    $subject = '小年鱼-首页专场CTR报表-'.date('Y-m-d', strtotime('yesterday'));

                    // 发送邮件
                    $sendResponse = \Util\Mail::instance()->sendMail($subject, $html);

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
