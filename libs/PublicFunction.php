<?php

/**
 * @abstract 全站静态方法调用
 *
 */
class PublicFunction
{
    /**
     * 创建目录(包括多级)
     */
    public static function createFold($dir)
    {
        return  is_dir($dir) or (self::createFold(dirname($dir)) and  mkdir($dir, 0777));
    }
}
