<?php
/**
 * @abstract XML操作类(Xml2Arr等)
 * @author Yuntaohu <yuntaohu@sohu-inc.com>
 * @date 2010-07-23
 *
 */
class XmlParser {
    /**
     * 将XML文件或数据转换成DOM风格的递归数组(外部调用函数)
     *
     * @param string $xml 包含完整XML内容的字符串或者XML文件路径
     *                函数将根据字符串的起始字符是否为"<"来判断参数代表XML内容或文件路径
     * @param bool $toGbk 是否转换为中文编码
     * @param bool $isShowRootName 是否需要将根节点的名字作为数组key
     *
     * @return array 与XML数据相适应的DOM风格的递归数组
     */
    public static function XmlToArr($xml='', $toGbk=false, $isShowRootName=false) {
        $xml = trim($xml);
        // 根据首字符判断参数的含义，如果是路径，则读取内容
        if (substr($xml, 0, 1) != "<") {
            //$xml=file_get_contents($xml);
        }
        // 过滤全部由空白字符组成的元素值
        if ($toGbk === true) {
            $xml = preg_replace("/>[\s]+</", "><", $xml);
        } else {
            $xml = preg_replace("/>[\s]+</", "><", $xml);
        }
        // 创建XML解析器
        $xml_parser = xml_parser_create();
        // 设置XML解析器选项：保持大小写敏感(不强制转换为大写)
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
        // 将XML数据转换为二维数组
        if (xml_parse_into_struct($xml_parser, $xml, $structs) == 0) {
            return false;   // 失败
        }
        // 释放XML解析器
        xml_parser_free($xml_parser);
        // 提取根节点名
        $root = current($structs);
        $rootName = trim($root["tag"]);

        // 递归处理节点
        if ($isShowRootName === true) {
            $cell[$rootName] = self::xmlToArrAndStrcutToCell($structs, $toGbk);
        } else {
            $cell = self::xmlToArrAndStrcutToCell($structs, $toGbk);
        }
        return $cell;
    }
    
    /**
     * 递归处理xml各节点返回数组(内部处理函数)
     *
     * @param array $structs
     * @return array $cell
     */
    public static function xmlToArrAndStrcutToCell(&$structs, $toGbk=false) {
        $root = current($structs);
        $level = $root["level"];

        $sub_cells = array();
        $cdata = "";

        if ($root["type"] == "complete") {
            if ($toGbk === true) {
                $cdata = iconv("UTF-8","GBK//IGNORE",$root["value"]);
            } else {
                $cdata = isset($root["value"]) ? $root["value"] : "";
            }
        } else {
            while ($struct = next($structs)) {
                if ($struct["level"] > $level) {
                    $tagName = $struct["tag"];
                    $new_cell = self::xmlToArrAndStrcutToCell($structs, $toGbk);
                    // 是否未有同级同名元素存在
                    if (empty($sub_cells[$tagName])) {
                        $sub_cells[$tagName] = $new_cell;
                    } else {
                        // 若已有同级同名元素存在，则进一步判断：
                        // 是否未有同级同名元素组存在(若无，则当前元素是第二个)
                        $is_second = TRUE;
                        if (gettype($sub_cells[$tagName]) == "array") {
                            $keys = array_keys($sub_cells[$tagName]);
                            $is_second = !is_numeric($keys[0]);
                        }
                        if ($is_second) {
                            $sub_cells[$tagName] = array(
                                $sub_cells[$tagName],
                                $new_cell);
                        } else {
                            $sub_cells[$tagName][] = $new_cell;
                        }
                    }
                } else {
                    if ($struct["level"] == $level) {
                        if ($struct["type"] == "cdata") {
                            if ($toGbk === true) {
                                $cdata = iconv("UTF-8","GBK",$struct["value"]);
                            } else {
                                $cdata = $struct["value"];
                            }
                            next($structs);
                        }
                    }
                    break;
                }
            }
        }
        
        $cell = array();
        // 附加属性数组
        if (!empty($root["attributes"])) {
            $attribs = array();
            foreach ($root["attributes"] as $name => $value) {
                if ($toGbk === true) {
                    $value = iconv("UTF-8","GBK",$value);
                }
                $attribs["@{$name}"] = $value;
            }
            $cell = array_merge($cell, $attribs);
        }
        // 附加子元素数组
        if (!empty($sub_cells)) {
            $cell = array_merge($cell, $sub_cells);
        }
        // 附加元素值
        if (count($cell) == 0) {
            $cell = $cdata;
        } elseif (!empty($cdata)) {
            if ($toGbk === true) {
                $cell = array_merge(array("_value_" => $cdata), $cell);
            } else {
                $cell = array_merge(array("." => $cdata), $cell);
            }
        }
        return $cell;
    }
}
?>