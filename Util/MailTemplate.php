<?php
/**
 * MAIL 邮件模板工具类
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Util;

/**
 * Helper方法.
 */
class MailTemplate
{
    // 销售指标
    const INDEX_SALES = 'index_sales';
    // APP留存指标
    const INDEX_APP_KEEP = 'index_app_keep';

    /**
     * 报表表格模板
     */
    public static function mainTemplate($emailTitle)
    {
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $html .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<title>'.$emailTitle.'</title>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '{#table_body}';
        $html .= '</body>';
        $html .= '</html>';

        return $html;
    }

    /**
     * 报表表格模板
     */
    public static function headTemplate($index)
    {
        $html = '<table id="travel">';
        $html .= '<thead>';

        switch ($index) {
            case self::INDEX_SALES:
                $html .= '<tr><th scope="col" colspan="13" style="font-weight:bold;background:#66a9bd;padding:5px;border:1px solid #fff;">销售指标</th></tr>';
                $html .= '<tr>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">GMV</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">实付金额</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">销售量</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">付款子订单数</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">付款订单数</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">生成订单数</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">付款转化率</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">订单转化率</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">付款用户数</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">AOV</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">人均订单数</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">毛利</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">毛利率</th>';
                $html .= '</tr>';
                break;
            case self::INDEX_APP_KEEP:
                $html .= '<tr><th scope="col" colspan="7" style="font-weight:bold;background:#66a9bd;padding:5px;border:1px solid #fff;">APP留存指标</th></tr>';
                $html .= '<tr>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">启动用户数</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">次日留存用户占比</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">次日留存率</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">第3日留存率</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">第7日留存率</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">15日留存率</th>';
                $html .= '<th scope="col" style="font-weight:bold;background:#91c5d4;">30日留存率</th>';
                $html .= '</tr>';
                break;
            default:
                break;
        }

        $html .= '</thead>';

        return $html;
    }

    /**
     * 报表表格模板
     */
    public static function indicatorTemplate($data)
    {
        $html = '<tr>';

        foreach ($data as $value) {
            $html .= '<td style="text-align:center;background:#d5eaf0;">'.$value.'</td>';
        }

        $html .= '</tr>';

        return $html;
    }

    /**
     * 报表尾部模板
     */
    public static function footTemplate($index)
    {
        $html = '<tfoot>';

        switch ($index) {
            case self::INDEX_SALES:
                $html .= '<tr>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">付款订单对应的商品总金额，包括各种优惠金额</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">用户实际支付的金额</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">付款订单中包含的商品个数</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;"></td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">当天创建且已经付款的所有订单（包括0元单）</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">当天所有创建成功的订单数</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">付款订单数/生成订单数</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">付款订单数/DAU</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">付款订单对应的去重用户数</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">GMV/主订单数</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">付款订单数/付款用户数</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;"></td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">毛利/实付金额</td>';
                $html .= '</tr>';
                break;
            case self::INDEX_APP_KEEP:
                $html .= '<tr>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">当天打开客户端的用户数</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">当天打开了客户端的用户中，前一天也打开了客户端的用户占比</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">T-1日访问用户，在当天的留存</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">T-3日访问用户，在当天的留存</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">T-7日访问用户，在当天的留存</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">T-15日访问用户，在当天的留存</td>';
                $html .= '<td style="text-align:center;background:#d7e1c5;font-size: 10px;">T-30日访问用户，在当天的留存</td>';
                $html .= '</tr>';
                break;
            default:
                break;
        }

        $html .= '</tfoot>';
        $html .= '</table>';
        $html .= '<br/>';

        return $html;
    }

}
