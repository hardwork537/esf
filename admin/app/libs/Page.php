<?php

/**
 * @abstract  分页类
 * @copyright Sohu-inc Team.
 * @author    Rady (yifengcao@sohu-inc.com)
 * @date      2014-05-22 16:10:06
 * @version   Crm 1.0
 */
class Page
{
    /**
     * 分页
     * @param int $pageCount 数据总量
     * @param int $Page 当前页码
     * @param int $pageSize 页面最大显示长度，默认为20
     * @param bool $lastPage 是否开启显示最后一页 默认不显示
     * @return string
     */
    public static function create($pageCount, $pageSize = 20)
    {
        $oURL      = "";
        $pageCount = intval($pageCount);
        $pageSize  = intval($pageSize);
        //当前页码
        $page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
        //每页显示分页数字个数
        $pageCut = 9;
        //总页数
        $pages = $pageCount%$pageSize == 0 ? intval($pageCount/$pageSize) : intval($pageCount/$pageSize) + 1;
        $page  = $page > $pages ? $pages : $page;
        //特殊情况 分页代码
        if($pageCount == 0)
        {
            return '';
        }
        if($pages < 2)
        {
            return '<div class="numsrow clearfix mt30">  <p class=" my_left "> 共有 <strong class=" font_orange " id="totalNum"> '.$pageCount.'  </strong>条记录  </p></div>';
        }
        if($pages <= $pageCut)
        {
            $pageFrom = 1;
            $pageTo   = $pages;
        }
        else
        {
            $pageFrom = $page <= intval($pageCut/2) ? 1 : ($page - intval($pageCut/2));
            $pageTo   = $pageCut + $pageFrom - 1;
            if($pageTo > $pages)
            {
                $pageTo   = $pages;
                $pageFrom = $pageTo - $pageCut + 1;
            }
        }


        //print_r($_REQUEST);
        foreach($_REQUEST as $key => $value)
        {
            if($key == "page")
            {
                continue;
            }
            if($key == "_url" || empty($value))
            {
                unset($_REQUEST[$key]);
                continue;
            }
            $oURL .= "&amp;".$key."=".$value;
            // $oURL .= "&amp;".$key."=".Util::utf8Togbk($value);
        }

        //输出上翻页
        $sHTML = '<div class="numsrow clearfix mt30">  <p class=" my_left "> 共有 <strong class=" font_orange " id="totalNum"> '.$pageCount.'  </strong>条记录  </p>';
        $sHTML .= '<div class="pages_nav my_right">';
        $sHTML .= $page < 2 ? '<span class="pre pre_disable"><em></em>上一页</span>' : '<a href="?page='.($page - 1).$oURL.'"><span class="pre"><em></em>上一页</span></a>';
        if($pageFrom > 1)
        {
            $sHTML .= '<a href="?page=1'.$oURL.'" class="num" >1</a>...';
            $pageFrom++;
        }
        if($pageTo < $pages)
        {
            $pageTo--;
        }

        for($i = $pageFrom; $i <= $pageTo; $i++)
        {
            $sHTML .= $page == $i ? '<a class="num on">'.$i.'</a>' : '<a href="?page='.$i.$oURL.'" class="num" >'.$i.'</a>';
        }

        if($pageTo < $pages)
        {
            $sHTML .= '...<a href="?page='.$pages.$oURL.'" class="num" >'.$pages.'</a>';
        }
        //输出后翻页
        $sHTML .= $page == $pages ? '<a class="next next_disable"><em></em>下一页</a>' : '<a class="next" href="?page='.($page + 1).$oURL.'"><em></em>下一页</a>';
        $sHTML .= "</div></div>";
        return $sHTML;
    }
}