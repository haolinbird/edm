<?php
/**
 * Class ReportScaleIndex
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date   2020-11-16 02:15:30
 */

namespace Model;

/**
 * 规模指标 Model.
 */
class ReportScaleIndex extends \Model\DbBase
{
    const DB_NAME = 'default';
    const TABLE_NAME = 'report_scale_index';
    const SELECT_METHOD_READ = 'read';
    const SELECT_METHOD_WRITE = 'write';

    public static $fields = array (
        'id',
        'report_date',
        'create_time',
        'total_register_users',
        'new_register_users',
        'dau',
        'start_cnt',
        'device_cnt',
        'first_device_cnt',
        'login_user_cnt',
        'add_shopcar_cnt',
        'add_shopcar_rate',
        'visit_frequency',
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
    public function getSelectColumns($fields = '*')
    {
        if ($fields === '*') {
            $fields = implode(',', \Db\Connection::instance()->quoteObj(self::$fields));
        } else {
            $fields = is_array($fields) ? implode(',', $fields) : $fields;
        }

        return $fields;
    }

    /**
     * 获取指定日期数据
     *
     * @param string $date   统计日期
     * @param string $fields 查询字段
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
