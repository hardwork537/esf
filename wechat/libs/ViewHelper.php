<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 8/20/14
 * Time: 3:24 PM
 */

class ViewHelper{
    static public   function staticUrl($resolvedArgs){
        return $GLOBALS['staticUrlBase'].$resolvedArgs;
    }

    static public   function intValue($resolvedArgs){
        return intval($resolvedArgs);
    }

    static public function substrChange($resolvedArgs){
        $data = $resolvedArgs['data'];
        $length = $resolvedArgs['length'];
        $ext = isset($resolvedArgs['ext']) ? $resolvedArgs['ext'] : '...';
        $realLength = $length - strlen($ext);
        $realLength = $realLength > 0 ? $realLength : 1;
        $len=strlen($data);
        $content='';
        $count=0;
        $changeFlag = 1;
        for($i=0;$i<$len;$i++){
            if(ord(substr($data,$i,1))>127){
                $content.=substr($data,$i,3);
                $i+=2;
            }else{
                $content.=substr($data,$i,1);
            }
            if(++$count==$realLength){
                if ($i++ == $len){
                    $changeFlag = 0;
                }
                break;
            }
        }
        if ($changeFlag){
            $content .= $ext;
        }
        return $content;
    }

    static public function advert($params){
        $ad_type = isset($params['type'])?trim($params['type']):'';
        $ad_position = isset($params['position_code'])?trim($params['position_code']):'';
        $ad_style = isset($params['style'])?trim($params['style']):'';
        $ad_path = isset($params['path'])?trim($params['path']):'';
        $ad_str = isset($params['extra_str'])?trim($params['extra_str']):'';
        $dataIndex = isset($params['data-index'])?trim($params['data-index']):0;

        return Advert::showAD($ad_type,$ad_position,$ad_style,$ad_path,$ad_str,$dataIndex);
    }

    static public function advertSource($params){
        $ad_id = isset($params['id'])?trim($params['id']):'';
        $ad_city = isset($params['city'])?trim($params['city']):'bj';
        $ad_product = isset($params['product'])?trim($params['product']):'esf';
        $ad_duilian = isset($params['duilian'])?trim($params['duilian']):0;


        if( !$ad_id ) {
            return '';
        }

        $ad_url_id = array_rand($GLOBALS['_ADV_URL_PRE_']);
        $ad_source_url_id = array_rand($GLOBALS['_ADV_SOURCE_URL_PRE_']);
        $ad_url = $GLOBALS['_ADV_URL_PRE_'][$ad_url_id];
        $ad_source_url = $GLOBALS['_ADV_SOURCE_URL_PRE_'][$ad_source_url_id];
        $script_str = "";
        $script_str .= "<script type='text/javascript' src='{$ad_url}focus_swfobject.js'></script>";
        $script_str .= "<script>var cWidth=cWidth||950;</script>";
        if( $ad_duilian ) {
            $script_str .= "<script type='text/javascript' src='{$ad_url}adm2012.js'></script>";
        }
        $script_str .= "<script type='text/javascript' src='{$ad_source_url}{$ad_city}/{$ad_product}/config/{$ad_id}.js'></script>";

        return $script_str;
    }
}