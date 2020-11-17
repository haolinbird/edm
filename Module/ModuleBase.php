<?php
/**
 * 业务层基类
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date   2020-11-16 20:28:30
 */

namespace Module;

/**
 * ModuleBase.
 */
abstract class ModuleBase
{
    /**
     *
     * Instances of the derived classes.
     * @var array
     */
    protected static $instances = array();

    /**
     * Get instance of the derived class.
     *
     * @return static
     */
    public static function instance()
    {
        $className = get_called_class();
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new $className;
        }
        return self::$instances[$className];
    }

    /**
     * 魔术方法 ，用来访问redis或其他方法.
     *
     * @param string $name 参数名.
     *
     * @return $mix       相关对象.
     */
    public function __get($name)
    {
        switch ($name)
        {
            case 'redis':
                return $this->redis();
            default:
                trigger_error('try get undefined property: '.$name.' of class '.__CLASS__, E_USER_NOTICE);
                continue;
        }
    }

    /**
     * Get a redis instance.
     *
     * @param string $endpoint Connection configruation name.
     * @param string $as       Use redis as "cache" or storage.default: storage.
     *
     * @return \RedisCache|\RedisStorage
     */
    public function redis($endpoint = 'default', $as = 'storage')
    {
        if ($as == 'storage') {
            return \Redis\RedisMultiStorage::getInstance($endpoint);
        } else {
            return \Redis\RedisMultiCache::getInstance($endpoint);
        }
    }

}
