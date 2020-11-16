<?php
/**
 * Class ReportSalesIndex
 *
 * @author Lin Hao <lin.hao@xiaonianyu.com>
 * @date 2020-11-15 20:28:30
 */

namespace Model;

/**
 * 销售指标 Model.
 */
class ReportSalesIndex extends \Model\DbBase
{
    const DB_NAME = 'default';
    const TABLE_NAME = 'report_sales_index';
    const SELECT_METHOD_READ = 'read';
    const SELECT_METHOD_WRITE = 'write';

    public static $fields = array (
        'id',
        'report_date',
        'gmv',
        'pay_amount',
        'sales',
        'pay_child_orders',
        'pay_orders',
        'create_orders',
        'payment_rate',
        'order_conversion_rate',
        'pay_users',
        'per_order_price',
        'per_capita_orders',
        'gross_profit',
        'gross_margin',
        'dau',
        'is_send',
        'last_send_time',
    );

    /**
     * Get instance.
     *
     * @param boolean $singleton 单例对象.
     *
     * @return static
     */
    public static function instance($singleton = true)
    {
        return parent::instance($singleton);
    }

    /**
     * 构造查询字段.
     *
     * @param mixed $fields 查询字段.
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
     * @param string $date   统计日期.
     * @param mixed  $fields 查询字段.
     *
     * @return array
     * @throws \Exception
     */
    public function getData($date, $fields = '*')
    {
        $cond = [
            'report_date' => $date
        ];

        $columns = $this->getSelectColumns($fields);

        return $this->db(self::DB_NAME, self::SELECT_METHOD_READ)->select($columns)->from(self::TABLE_NAME)->where($cond)->queryRow();
    }

}
