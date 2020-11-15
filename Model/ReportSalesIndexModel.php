<?php
/**
 * Class XnyStatisticsModel
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-04-30 10:28:30
 */

namespace Model;

/**
 * 测试Model.
 */
class ReportSalesIndexModel extends \Model\DbBase
{
    const DB_NAME    = 'default';
    const TABLE_NAME = 'report_sales_index';
    const SELECT_METHOD_READ = 'read';
    const SELECT_METHOD_WRITE = 'write';

    public static $fields = array (
        'id',
        'date',
        'gmv',
        'pay_amount',
        'sales',
        'pay_child_orders',
        'pay_orders',
        'create_orders',
        'payment_rate',
        'order_conversion_rate',
        'pay_users',
        'new_users',
        'per_order_price',
        'per_capita_orders',
        'gross_profit',
        'gross_margin',
        'is_send',
        'last_send_time',
    );

    /**
     * Get instance.
     *
     * @param boolean $singleton Signleton.
     *
     * @return static
     */
    public static function instance($singleton = true)
    {
        return parent::instance($singleton);
    }

    /**
     * 构造查询字段(别名可选).
     *
     * @param string $aliases 表别名.
     *
     * @return mixed
     */
    public function getSelectColumns($fields)
    {
        if ($fields === '*') {
            $fields = implode(',', \Db\Connection::instance()->quoteObj(self::$fields));
        } else {
            $fields = is_array($fields) ? implode(',', $fields) : $fields;
        }

        return $fields;
    }

    /**
     * 获取日期
     *
     * @param string $date   统计日期
     * @param string $fields 查询字段
     *
     * @return mixed
     */
    public function getData($date, $fields = '*')
    {
        $cond = [
            'statis_date' => $date
        ];

        $columns = $this->getSelectColumns($fields);

        return $this->db(self::DB_NAME, self::SELECT_METHOD_READ)->select($columns)->from(self::TABLE_NAME)->where($cond)->queryRow();
    }

}
