<?php
/**
 * Class TestModel
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-04-30 10:28:30
 */

namespace Model;

/**
 * 测试Model.
 */
class TestModel extends \Model\DbBase
{
    const DB_NAME    = 'default';
    const TABLE_NAME = 'test';
    const SELECT_METHOD_READ = 'read';
    const SELECT_METHOD_WRITE = 'write';

    public static $fields = array (
            'id',
            'test_name',
            'create_time',
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
     * 根据 name 获取一条数据
     *
     * @param string $testName 测试名称.
     * @param  mixed $fields   查询字段.
     *
     * @return mixed
     */
    public function getInfoByName($testName, $fields = '*')
    {
        return 555;

        if (\Util\Validate::isEmpty($testName)) {
            return array();
        }

        $cond = ['test_name' => $testName];

        $columns = $this->getSelectColumns($fields);

        return $this->db(self::DB_NAME, self::SELECT_METHOD_READ)->select($columns)->from(self::TABLE_NAME)->where($cond)->queryRow();
    }

}
