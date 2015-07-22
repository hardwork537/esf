<?php

class MyTags extends \Phalcon\Tag
{
    static function options($data, $selected = "")
    {
        $code = "";
        if(!is_array($data) || empty($data))
        {
            return $code;
        }

        foreach($data as $k => $v)
        {
            if($k == $selected)
            {
                $code .= "<option value='{$k}' selected=selected>$v</option>";
            }
            else
            {
                $code .= "<option value='{$k}'>$v</option>";
            }
        }
        return $code;
    }

}