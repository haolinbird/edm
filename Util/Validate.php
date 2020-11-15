<?php
/**
 * 消息中心相关操作类
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Util;

/**
 * 验证类.
 */
class Validate
{

    /**
     * 验证参数是否是合乎规范的整数.可选：取值范围.
     * 
     * @param integer $validate 待验证的参数.
     * @param array   $scope    可选：取值范围.
     * 
     * @return boolean
     */
    public static function checkIntInScope($validate, array $scope = array())
    {
        if (!ctype_digit((string)$validate)) {
            return false;
        }

        if (self::arrayNotEmpty($scope)) {
            if (isset($scope['max']) &&( !is_int($scope['max']) || $validate > $scope['max'])) {
                return false;
            }
            if (isset($scope['min']) &&( !is_int($scope['min']) || $validate < $scope['min'])) {
                return false;
            }
            // 不超过mysql int类型取值范围.
            if ($validate > 2147483647) {
                return false;
            }
        }
        return true;
    }

     /**
     * 验证传入参数是否是非空的数组.
     * 
     * @param array $validate 待验证的参数.
     * 
     * @return boolean
     */
    public static function arrayNotEmpty($validate = array())
    {
        if (!is_array($validate) || empty($validate)) {
            return false;
        }
        return true;
    }

    /**
     * 验证数组的每一个元素是不是非空数组.
     * 
     * @param array $validate 待验证的参数.
     * 
     * @return boolean
     */
    public static function arraysNotEmpty($validate = array())
    {
        if (self::arrayNotEmpty($validate)) {
            foreach ($validate as $arr) {
                if (! self::arrayNotEmpty($arr)) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * 验证参数是否是布尔值.
     * 
     * @param integer $validate 待验证的参数.
     * 
     * @return boolean
     */
    public static function isBoolean($validate)
    {
        return is_bool($validate);
    }

    /**
     * 验证邮编参数.
     * 
     * @param integer $validate 待验证的参数.
     * 
     * @return boolean
     */
    public static function isPostalCode($validate)
    {
        if (empty($validate) || !preg_match('/\d{6}/', $validate)) {
            return false;
        }

        return true;
    }

    /**
     * 验证参数是否为空.
     * 
     * @param string $validate 待验证的参数.
     * 
     * @return boolean
     */
    public static function isEmpty($validate)
    {
        if (is_string($validate)) {
            $validate = trim($validate);
        }
        return empty($validate);
    }
    
    /**
     * 检查是否参数是否是数字或字母.
     * 
     * @param string $validate 待验证参数.
     * @param array  $lenArr   字符串最大长度和最小长度.
     * 
     * @return boolean
     */
    public static function checkString($validate, $lenArr = array())
    {
        if (self::isEmpty($validate) || !ctype_alnum((string)$validate)) return false;
        if (isset($lenArr['minlen'])) {
            if (strlen($validate) <= $lenArr['minlen']) {
                return false;
            }
        }
        if (isset($lenArr['maxlen'])) {
            if (strlen($validate) >= $lenArr['maxlen']) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * 验证数组的每一个元素是不是非空.
     *
     * @param array  $validate 待验证的参数.
     * @param string $type     参数类型.
     * @param array  $scope    其他限制条件.
     *
     * @return boolean
     */
    public static function arrayEveryOneNotEmpty($validate = array(), $type = '', $scope = array('min' => 0))
    {
        if (self::arrayNotEmpty($validate)) {
            foreach ($validate as $str) {
                switch ($type) {
                    case 'integer':
                        if (!self::checkIntInScope($str,$scope)) {
                            return false;
                        }
                        break;
                    case 'string':
                        if (!self::checkStringInScope($str,$scope)) {
                            return false;
                        }
                        break;
                    default:
                        if (self::isEmpty($str)) {
                            return false;
                        }
                        break;
                }
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * 是否是空的字符串.
     * 
     * @param string  $validate 待验证参数.
     * @param boolean $isEmpty  是否可以为空.
     * @param integer $length   字符串最大长度.
     * 
     * @return boolean
     */
    public static function isValidateString($validate, $isEmpty = false, $length = 0)
    {
        if (!is_string($validate)) {
            return false;
        }
        $validate = trim($validate);
        if ($length > 0 && strlen($validate) > $length) {
            return false;
        }
        if ($isEmpty === false) {
            return !empty($validate);
        }
        return true;
    }

    /**
     * 验证是否是手机号.
     * 
     * @param string $mobile Mobile.
     *
     * @return boolean
     */
    public static function isMobile($mobile)
    {
        return !(preg_match('/^1\d{10}$/',$mobile) == '0');
    }

    /**
     * 检查SHARDING字符.
     * 
     * @param string $str 需要检查的SHARDING字段.
     *
     * @return boolean
     */
    public static function checkShardingColumn($str)
    {
        $regx = '/^[2-9A-Z^(OI)]+$/';
        return preg_match($regx, $str) ? true : false;
    }

    /**
     * 验证参数是否是合乎规范的数字.可选：取值范围.
     * 
     * @param integer $validate 待验证的参数.
     * @param array   $scope    可选：取值范围.
     * 
     * @return boolean
     */
    public static function checkNumericInScope($validate, array $scope = array())
    {
        if (!is_numeric((string)$validate)) {
            return false;
        }

        if (self::arrayNotEmpty($scope)) {
            if (isset($scope['max']) &&( !is_int($scope['max']) || $validate > $scope['max'])) {
                return false;
            }
            if (isset($scope['min']) &&( !is_int($scope['min']) || $validate < $scope['min'])) {
                return false;
            }
            if (isset($scope['less']) && $validate >= $scope['less']) {
                return false;
            }
            if (isset($scope['more']) && $validate <= $scope['more']) {
                return false;
            }
        }
        return true;
    }

    /**
     * 验证参数是否是合乎规范的数字.可选：取值范围.
     * 
     * @param integer $validate  待验证的参数.
     * @param integer $precision 浮点数精度.
     * @param array   $scope     可选：取值范围.
     * 
     * @return boolean
     */
    public static function checkFloatInScope($validate, $precision, array $scope = array())
    {
        if (!is_numeric((string)$validate)) {
            return false;
        }

        $compareData = round($validate,$precision);

        if ($compareData != $validate) {
            return false;
        }

        if (self::arrayNotEmpty($scope)) {
            if (isset($scope['max']) &&( !is_int($scope['max']) || $validate > $scope['max'])) {
                return false;
            }
            if (isset($scope['min']) &&( !is_int($scope['min']) || $validate < $scope['min'])) {
                return false;
            }
            if (isset($scope['less']) && $validate >= $scope['less']) {
                return false;
            }
            if (isset($scope['more']) && $validate <= $scope['more']) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检查是否为字符串.
     * 
     * @param string $validate    待验证参数.
     * @param array  $lengthScope 字符串最大长度和最小长度.
     * 
     * @return boolean
     */
    public static function checkStringInScope($validate, $lengthScope = array())
    {
        $validate = trim($validate);
        if (!is_string($validate) && !is_numeric($validate)) {
            return false;
        }

        if (isset($lengthScope['min']) && strlen($validate) < $lengthScope['min']) {
            return false;
        }
        if (isset($lengthScope['max']) && strlen($validate) > $lengthScope['max']) {
            return false;
        }
        return true;
    }

    /**
     * 检查中文字符串.
     * 
     * @param string $validate    待验证参数.
     * @param array  $lengthScope 字符串最大长度和最小长度.
     * @param array  $encoding    中文编码.
     * 
     * @return boolean
     */
    public static function checkMbStringInScope($validate, $lengthScope = array(), $encoding = 'UTF-8')
    {
        $validate = trim($validate);
        if (!is_string($validate) && !is_numeric($validate)) {
            return false;
        }

        if (isset($lengthScope['min']) && mb_strlen($validate, $encoding) < $lengthScope['min']) {
            return false;
        }
        if (isset($lengthScope['max']) && mb_strlen($validate, $encoding) > $lengthScope['max']) {
            return false;
        }
        return true;
    }

}
