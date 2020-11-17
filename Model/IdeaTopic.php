<?php
/**
 * Class IdeaTopic
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date   2020-11-15 20:28:30
 */

namespace Model;

/**
 * 测试Model.
 */
class IdeaTopic extends \Model\DbBase
{
    const DB_NAME = 'xiaonianyu';
    const TABLE_NAME = 'idea_topic';
    const PRIMARY_KEY = 'id';
    const SELECT_METHOD_READ = 'read';
    const SELECT_METHOD_WRITE = 'write';

    public static $fields = array (
        'id',
        'title',
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
     * @param array  $ids    专场 IDS
     * @param string $fields 查询字段
     *
     * @return mixed
     * @throws
     */
    public function getInfoByIds(array $ids, $fields = '*')
    {
        if (empty($ids)) {
            return [];
        }

        $cond = [
            'id IN' => $ids
        ];

        $columns = $this->getSelectColumns($fields);

        return $this->db(self::DB_NAME, self::SELECT_METHOD_READ)->select($columns)->from(self::TABLE_NAME)->where($cond)->queryAll(null, self::PRIMARY_KEY);
    }

}
