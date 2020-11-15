<?php
/**
 * 测试业务类
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Module;

/**
 * 测试业务类.
 */
class EdmModule extends \Module\ModuleBase
{
    /**
     * 获取单例对象.
     *
     * @return Object
     */
    public static function instance()
    {
        return parent::instance();
    }

    /**
     * 获取测试数据.
     *
     * @param string $testName test name.
     *
     * @return array.
     */
    public function getSalesReport($date)
    {
        // 查询昨日销售数据报表
        $reportData = \Model\XnyStatisticsModel::instance()->getData($date);

        //

        return $testData;
    }

}
