<?php
/**
 * Class ReportHomeSpecialListIndex
 *
 * @author Lin Hao <lin.hao@xiaonianyu.com>
 * @date 2020-11-15 20:28:30
 */

namespace Model;

/**
 * 测试Model.
 */
class ReportHomeSpecialListIndex extends \Model\DbBase
{
    const DB_NAME    = 'default';
    const TABLE_NAME = 'report_home_special_list_index';
    const SELECT_METHOD_READ = 'read';
    const SELECT_METHOD_WRITE = 'write';

    public static $fields = array (
        'id',
        'report_date',
        'position',
        'special_id',
        'special_name',
        'view_uv',
        'click_uv',
        'ctr',
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
     * @throws
     */
    public function getDatas($date, $fields = '*')
    {
        $cond = [
            'report_date' => $date,
            'view_uv >' => 1000
        ];

        $columns = $this->getSelectColumns($fields);

        return $this->db(self::DB_NAME, self::SELECT_METHOD_READ)->select($columns)->from(self::TABLE_NAME)->where($cond)->queryAll();
    }

}
