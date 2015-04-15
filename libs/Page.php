<?php
/**
 * @abstract 站内分页类
 * @author lostsun@sohu-inc.com
 * @date 2012-07-09
 */
class Page{
	/**
     * 用户中心的分页
     * @author lostsun@sohu-inc.com
     * @param int   $oPageCount 数据总量
     * @param int   $oPage      当前页码
     * @param int   $oPageMax   页面最大显示长度，默认为20
     * @param bool  $lastPage   是否开启显示最后一页 默认不显示
     * @return string
     */
    public static function vipPage($oPageCount, $oPage, $oPageMax = 20, $lastPage = false){
        $oURL = "";
        //当前页码
        $page = $oPage;
        //总记录数
        $pageCount = intval($oPageCount);
        //分页组长度
        $pageCut = 5;
        //页面最大数据数
        $pageMax = $oPageMax;
        //总页面数
        $pages = $pageCount % $pageMax == 0 ? intval($pageCount/$pageMax) : intval($pageCount/$pageMax) + 1;
        //当前分页组的标示
        //$pageIndex = ceil($page/$pageCut);
        $pageIndex = $page;
        //分页组起始页面
        //$pageFrom = ($pageIndex-1)*$pageCut + 1;
        $pageFrom = $pageIndex < 3 ? 1 : ($pageIndex - 2);
        //分页组结束页面，超出最大页面设置为最大页面
        $pageTo = min($pages, $pageFrom+$pageCut-1);
        //需要过滤的获取到的参数
        $killREQUEST = array(
            'page',
            '_url'
        );
        //过滤$_COOKIE中的数据
        foreach ($_COOKIE as $key=>$value){
            unset($_REQUEST[$key]);
        }
        foreach ($_REQUEST as $key=>$value){
            if ( in_array($key, $killREQUEST)) {
                continue;
            }
            if ( empty($value) ) {
                unset($_REQUEST[$key]);
                continue;
            }
            $oURL .= "&amp;".$key."=".urlencode(Utility::filterSubject($value));
        }
        //分页代码
        if ($pageCount == 0) {
            $sHTML = "暂无数据";
            return $sHTML;
        }
        
        //输出上翻页
        $sHTML = "共有  <b class=\"tahoma red\">{$pageCount}</b> 条记录 这是  <b class=\"tahoma red\">".(($page-1)*$pageMax+1)."-".min($page*$pageMax, $pageCount)."</b> 条";
		$sHTML .= "<div>";
        $sHTML .= $page==1 ? "" : "<a href=\"?page=1".$oURL."\" title=\"首页\">首页</a>";
        $sHTML .= $page==1 ? "" : "<a href=\"?page=".($page-1<1?1:$page-1).$oURL."\" title=\"前翻1页\">上一页</a>";
     	//输出中间导航页
        if ( $pageFrom+$pageCut>$pages ) {
            if ( $pages-$pageCut <= 0 ) {
                $begin = 1;
            }
            else {
                $begin = $pages-$pageCut+1;
            }
        }
        else {
            $begin = $pageFrom;
        }
        for ($i = $begin; $i <= $pageTo; $i++){
            $sHTML .= $page == $i ? "<strong>$i</strong>" : "<a href=\"?page=$i".$oURL."\" title=\"第".$i."页\">$i</a>";
        }
        //输出后翻页
        $sHTML .= $page==$pages ? "" : "<a href=\"?page=".($page+1>$pages?$page:$page+1).$oURL."\" title=\"下一页\">下一页</a>";
        if ( $lastPage ) {
            $sHTML .= $page==$pages?"":"<a href=\"?page=$pages".$oURL."\" title=\"末页\">末页</a>";
        }
        $sHTML .= "</div>";
        return $sHTML;
    }
    
    /**
     * 公司管理系统的分页
     * @author 翟健雯 <jianwenzhai@51f.com>
     * @param int   $oPageCount 数据总量
     * @param int   $oPage      当前页码
     * @param int   $oPageMax   页面最大显示长度，默认为20
     * @param bool  $lastPage   是否开启显示最后一页 默认不显示
     * @return string
     */
    public static function CompanyPage($oPageCount, $oPage, $oPageMax = 20, $lastPage = false){
    	$oURL = "";
    	//当前页码
    	$page = $oPage;
    	//总记录数
    	$pageCount = intval($oPageCount);
    	//分页组长度
    	$pageCut = 5;
    	//页面最大数据数
    	$pageMax = $oPageMax;
    	//总页面数
    	$pages = $pageCount % $pageMax == 0 ? intval($pageCount/$pageMax) : intval($pageCount/$pageMax) + 1;
    	//当前分页组的标示
    	//$pageIndex = ceil($page/$pageCut);
    	$pageIndex = $page;
    	//分页组起始页面
    	//$pageFrom = ($pageIndex-1)*$pageCut + 1;
    	$pageFrom = $pageIndex < 3 ? 1 : ($pageIndex - 2);
    	//分页组结束页面，超出最大页面设置为最大页面
    	$pageTo = min($pages, $pageFrom+$pageCut-1);
    	//需要过滤的获取到的参数
    	$killREQUEST = array(
            'page',
            '_url'
    	);
    	//过滤$_COOKIE中的数据
    	foreach ($_COOKIE as $key=>$value){
    		unset($_REQUEST[$key]);
    	}
    	foreach ($_REQUEST as $key=>$value){
    		if ( in_array($key, $killREQUEST)) {
    			continue;
    		}
    		if ( empty($value) ) {
    			unset($_REQUEST[$key]);
    			continue;
    		}
    		$oURL .= "&amp;".$key."=".urlencode(Utility::filterSubject($value));
    	}
    	//分页代码
    	if ($pageCount == 0) {
    		$sHTML = "<b class=\"f_1\">暂无数据</b>";
    		return $sHTML;
    	}
    
    	//输出上翻页
    	$sHTML = "<span class=\"fl\">共有 <span class=\"red\"> {$pageCount}</span> 条记录 这是 <span class=\"red\">".(($page-1)*$pageMax+1)."-".min($page*$pageMax, $pageCount)."</span> 条</span>";
    	$sHTML .= "<span class=\"fr\">";
    	$sHTML .= "<ul>";
    	$sHTML .= $page==1 ? "<li><a href=\"#\" title=\"首页\">&lt;&lt;</a></li>" : "<li><a href=\"?page=1".$oURL."\" title=\"首页\">&lt;&lt;</a></li>";
    	$sHTML .= $page==1 ? "<li><a href=\"#\" title=\"上一页\">&lt;</a></li>" : "<li><a href=\"?page=".($page-1<1?1:$page-1).$oURL."\" title=\"前翻1页\">&lt;</a></li>";
    	//输出中间导航页
    	if ( $pageFrom+$pageCut>$pages ) {
    		if ( $pages-$pageCut <= 0 ) {
    			$begin = 1;
    		}
    		else {
    			$begin = $pages-$pageCut+1;
    		}
    	}
    	else {
    		$begin = $pageFrom;
    	}
    	for ($i = $begin; $i <= $pageTo; $i++){
    		$sHTML .= $page == $i ? "<li><a href=\"#\" class=\"curr\" title=\"第".$i."页\">$i</a></li>" : "<li><a href=\"?page=$i".$oURL."\" title=\"第".$i."页\">$i</a></li>";
    	}
    	//输出后翻页
    	$sHTML .= $page==$pages ? "<li><a href=\"#\" title=\"下一页\">&gt;</a></li>" : "<li><a href=\"?page=".($page+1>$pages?$page:$page+1).$oURL."\" title=\"下一页\">&gt;</a></li>";
    	if ( $lastPage ) {
    		$sHTML .= $page==$pages?"<li><a href=\"#\" title=\"尾页\">&gt;&gt;</a></li>":"<li><a href=\"?page=$pages".$oURL."\" title=\"尾页\">&gt;&gt;</a></li>";
    	}
    	$sHTML .= "</ul>";
    	$sHTML .= "</span>";
    	return $sHTML;
    }
    
	/**
     * 房源管理的分页
     * @author lostsun@sohu-inc.com
     * @param int   $oPageCount 数据总量
     * @param int   $oPage      当前页码
     * @param int   $oPageMax   页面最大显示长度，默认为20
     * @param bool  $lastPage   是否开启显示最后一页 默认不显示
     * @param bool  $footStyle  是否底部样式
     * @return array
     */
    public static function housePage($oPageCount, $oPage, $oPageMax = 20, $lastPage = false){
        $oURL = "";
        //当前页码
        $page = $oPage;
        //总记录数
        $pageCount = intval($oPageCount);
        //分页组长度
        $pageCut = 5;
        //页面最大数据数
        $pageMax = $oPageMax;
        //总页面数
        $pages = $pageCount % $pageMax == 0 ? intval($pageCount/$pageMax) : intval($pageCount/$pageMax) + 1;
        //当前分页组的标示
        //$pageIndex = ceil($page/$pageCut);
        $pageIndex = $page;
        //分页组起始页面
        //$pageFrom = ($pageIndex-1)*$pageCut + 1;
        $pageFrom = $pageIndex < 3 ? 1 : ($pageIndex - 2);
        //分页组结束页面，超出最大页面设置为最大页面
        $pageTo = min($pages, $pageFrom+$pageCut-1);
        //需要过滤的获取到的参数
        $killREQUEST = array(
            'page',
            '_url'
        );
        //过滤$_COOKIE中的数据
        foreach ($_COOKIE as $key=>$value){
            unset($_REQUEST[$key]);
        }
        foreach ($_REQUEST as $key=>$value){
            if ( in_array($key, $killREQUEST)) {
                continue;
            }
            if ( empty($value) ) {
                unset($_REQUEST[$key]);
                continue;
            }
            $oURL .= "&amp;".$key."=".urlencode(Utility::filterSubject($value));
        }
        //分页代码
        if ($pageCount == 0) {
            $sHTML = "暂无数据";
            return array('head' => '', 'foot' => '');
        }
        
        //输出上翻页
        $sHTML = "<div class=\"l\">共有<em>{$pageCount}</em>条记录 这是<em>".(($page-1)*$pageMax+1)."-".min($page*$pageMax, $pageCount)."</em>条</div>";
		$sHeadHTML = $sHTML."<div class=\"r\">";
		$sFootHTML = $sHTML."<div class=\"r\"><span class=\"page_nav\">";
        $sFootHTML .= $page==1 ? "" : "<a class=\"\" href=\"?page=1".$oURL."\" title=\"首页\">首页</a>";
        $sFootHTML .= $page==1 ? "" : "<a href=\"?page=".($page-1<1?1:$page-1).$oURL."\" title=\"前翻1页\">上一页</a>";
     	//输出中间导航页
        if ( $pageFrom+$pageCut>$pages ) {
            if ( $pages-$pageCut <= 0 ) {
                $begin = 1;
            }
            else {
                $begin = $pages-$pageCut+1;
            }
        }
        else {
            $begin = $pageFrom;
        }
		$sHeadHTML .= "<div class=\"dropdown\">";
		$sHeadHTML .= "<span class=\"dropdown_btn\">".$page."/".$pages."页<i></i></span><ul class=\"dropdown_list\" style=\"display: none;\">";
        for ($i = 1; $i <= $pages; $i++){
            $sHeadHTML .= $page == $i ? "<li><a class=\"selected\" href=\"?page=$i".$oURL."\">$i/".$pages."页</a></li>" : "<li><a href=\"?page=$i".$oURL."\">$i/".$pages."页</a></li>";
            if ($i >= $begin && $i <= $pageTo) $sFootHTML .= $page == $i ? "<strong>$i</strong>" : "<a href=\"?page=$i".$oURL."\" title=\"第".$i."页\">$i</a>";
        }
		$sHeadHTML .= "</ul></div>";
        //输出后翻页
        $sHeadHTML .= $page==1 ? "<a href=\"javascript:;\" class=\"prev prev_disable\"></a>" : "<a href=\"?page=".($page-1<1?1:$page-1).$oURL."\" class=\"prev\"></a>";
        $sHeadHTML .= $page==$pages ? "<a href=\"javascript:;\" class=\"next next_disable\"></a>" : "<a href=\"?page=".($page+1>$pages?$page:$page+1).$oURL."\" class=\"next\"></a>";
        $sFootHTML .= $page==$pages ? "" : "<a href=\"?page=".($page+1>$pages?$page:$page+1).$oURL."\" title=\"下一页\">下一页</a>";
        if ( $lastPage ) {
            $sFootHTML .= $page==$pages?"":"<a href=\"?page=$pages".$oURL."\" title=\"末页\">末页</a>";
        }
        $sHeadHTML .= "</div>";
        $sFootHTML .= "</span></div>";
        return array('head' => $sHeadHTML, 'foot' => $sFootHTML);
    }

	/**
	 * 分页2,带"定位到某页",经纪人培训后端使用
	 * @param int   $oPageCount 数据总量
	 * @param int   $oPage      当前页码
	 * @param int   $oPageMax   页面最大显示长度，默认为20
	 * @param bool  $lastPage   是否开启显示最后一页 默认不显示
	 * @param bool  $oPageGoTo  是否显示定位到某页
	 * @return string
	 */
	public static function PageWithGoTo($oPageCount, $oPage, $oPageMax = 20, $lastPage = FALSE,$oPageGoTo = FALSE){
		$oURL = "";
		//当前页码
		$page = $oPage;
		//总记录数
		$pageCount = intval($oPageCount);
		//分页组长度
		$pageCut = 5;
		//页面最大数据数
		$pageMax = $oPageMax;
		//总页面数
		$pages = $pageCount % $pageMax == 0 ? intval($pageCount/$pageMax) : intval($pageCount/$pageMax) + 1;
		//当前分页组的标示
		//$pageIndex = ceil($page/$pageCut);
		$pageIndex = $page;
		//分页组起始页面
		//$pageFrom = ($pageIndex-1)*$pageCut + 1;
		$pageFrom = $pageIndex < 3 ? 1 : ($pageIndex - 2);
		//分页组结束页面，超出最大页面设置为最大页面
		$pageTo = min($pages, $pageFrom+$pageCut-1);
		//需要过滤的获取到的参数
		$killREQUEST = array(
				'page'
		);
		//过滤$_COOKIE中的数据
		foreach ($_COOKIE as $key=>$value){
			unset($_REQUEST[$key]);
		}
		foreach ($_REQUEST as $key=>$value){
			if ( in_array($key, $killREQUEST)) {
				continue;
			}
			if ( empty($value) ) {
				unset($_REQUEST[$key]);
				continue;
			}
			$oURL .= "&amp;".$key."=".urlencode(Utility::filterSubject($value));
		}
		//分页代码
		if ($pageCount == 0) {
			$sHTML = "<b class=\"f_1\">暂无数据</b>";
			return $sHTML;
		}

		//输出上翻页
		$sHTML = "共有  <b class=\"org\">{$pageCount}</b> 条记录 ";//这是  <b class=\"tahoma red\">".(($page-1)*$pageMax+1)."-".min($page*$pageMax, $pageCount)."</b> 条
		$sHTML .='<p class="page">';
		if ($page == 1){
			$sHTML .='<span>';
		}else{
			$sHTML .= "<a href=\"?page=".($page-1<1?1:$page-1).$oURL."\" title=\"前翻1页\">";
		}
		$sHTML .= "<s></s><img style=\"margin-right:6px;\" src=\"http://src.esf.focus.cn/upload/images/learningbroker/icon07.gif\">";
		$sHTML .= "上一页";
		if ($page == 1){
			$sHTML .= "</span>\n";
		}else{
			$sHTML .= "</a>\n";
		}
		//输出中间导航页
		if ( $pageFrom+$pageCut>$pages ) {
			if ( $pages-$pageCut <= 0 ) {
				$begin = 1;
			}
			else {
				$begin = $pages-$pageCut+1;
			}
		}
		else {
			$begin = $pageFrom;
		}
		for ($i = $begin; $i <= $pageTo; $i++){
			$sHTML .= $page == $i ? "<strong>$i</strong>\n" : "<a href=\"?page=$i".$oURL."\" title=\"第".$i."页\">$i</a>\n";
		}
		//输出后翻页
		if($page == $pages){
			$sHTML .= "<span>";
		}else{
			$sHTML .= "<a href=\"?page=".($page+1>$pages?$page:$page+1).$oURL."\" title=\"后翻1页\">";
		}
		$sHTML .= '<s></s>';
		$sHTML .= '下一页';
		$sHTML .= "<img style=\"margin-left:6px;\" src=\"http://src.esf.focus.cn/upload/images/learningbroker/icon08.gif\">";
		if($page == $pages){
			$sHTML .= "</span>";
		}else{
			$sHTML .= "</a>";
		}
		if ( $lastPage ) {
			$sHTML .= $page==$pages?"&gt;&gt;":"<a href=\"?page=$pages".$oURL."\" title=\"后翻1页\">&gt;&gt;</a>";
		}
		$sHTML .= "</p>";
		if ($oPageGoTo){
		 $sHTML .= '<form action="?page='.$oURL.'" method="post">到第<input class="txt" type="text" style="width:18px;" name="page">页<input class="btn03" type="submit" value="确定" name="button" ></form>';
		}
		return $sHTML;
	}
    /**
     * 前端列表页分页
     * @author 刘晓峰
     * @date 2012-10-10
     * @param integer $intPage 当前页
     * @param integer $intTotalNum 总条数
     * @param integer $intPageSize 每页最大条数
     * @param string $strPageUrl 当前页url
     * @return string
     */
    public static function wwwBottomPage($intPage, $intTotalNum, $intPageSize=20, $strPageUrl='', $strDesc = '套房源'){
        //当前页
        $intPage = intval($intPage);
        //总条数
        $intTotalNum = intval($intTotalNum);
        //每页最大条数
        $intPageSize = intval($intPageSize);
        //总页数
        $intTotalPage = ceil($intTotalNum/$intPageSize);
        $intSphinxLimit = 1000/$intPageSize;
        $intTotalPage = $intTotalPage>$intSphinxLimit? $intSphinxLimit: $intTotalPage; //这里做了1000条记录的限定，即sphinx设定的的，有人说是为了效率
        //最多显示页码x2+1
        $intPageCut = 4;

        //设置当前页码超限问题
        if( $intPage>$intTotalPage ){
            $intPage = $intTotalPage;
        }elseif( $intPage<1 ){
            $intPage = 1;
        }

        $strMetion = '<div class="l" style="float:left;margin-left:10px;">共找到 <b class="red">'.$intTotalNum.'</b> '.$strDesc.'</div>';
        if( empty($intTotalNum) ){
            return $strMetion;
        }


        //组合中间导航页码
        $intFirstPage = $intPage-$intPageCut;
        $intFirstPage = $intFirstPage<1? 1: $intFirstPage;
        $html = $strMetion.'<div class="r" style="float:right;margin-right:10px;"><a href="'.self::changeUrlPage($strPageUrl, 1).'">首页</a> <a href="'.self::changeUrlPage($strPageUrl, $intPage-1).'"><s></s><img src="http://src.esf.focus.cn/upload/wj3/images/icon06.gif" style="margin-right:6px;"/>上一页</a> ';
        for($i=$intFirstPage; $i<$intPage; $i++){
            $html .= ' <a href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
        }

        $html .= '<strong>'.$intPage.'</strong>'; //这里是当前页

        $intLastPage = $intPage+$intPageCut;
        $intLastPage = $intLastPage>$intTotalPage? $intTotalPage: $intLastPage;
        for($i=$intPage+1; $i<=$intLastPage; $i++){
            $html .= ' <a href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a>';
        }

        $intNextPage = $intPage+1>$intLastPage? $intLastPage: $intPage+1;
        $html .= ' <a href="'.self::changeUrlPage($strPageUrl, $intNextPage).'"><s></s>下一页<img src="http://src.esf.focus.cn/upload/wj3/images/icon07.gif" style="margin-left:6px;" /></a> <a href="'.self::changeUrlPage($strPageUrl, $intTotalPage).'">末页</a></div>';

        return $html;
    }
    
    /**
     * 简单版快速翻页
     *
     * @param int $intPage
     * @param int $intTotalNum
     * @param int $intPageSize
     * @param string $strPageUrl
     * @param string $strDesc
     * @return string
     */
	public static function wwwTopPage($intPage, $intTotalNum, $intPageSize=20, $strPageUrl='', $strDesc = '套房源'){
        //当前页
        $intPage = intval($intPage);
        //总条数
        $intTotalNum = intval($intTotalNum);
        //每页最大条数
        $intPageSize = intval($intPageSize);
        //总页数
        $intTotalPage = ceil($intTotalNum/$intPageSize);
        $intSphinxLimit = 1000/$intPageSize;
        $intTotalPage = $intTotalPage>$intSphinxLimit? $intSphinxLimit: $intTotalPage; //这里做了1000条记录的限定，即sphinx设定的的，有人说是为了效率
        //最多显示页码x2+1
        $intPageCut = 4;
        //设置当前页码超限问题
        if( $intPage>$intTotalPage ){
            $intPage = $intTotalPage;
        }elseif( $intPage<1 ){
            $intPage = 1;
        }
		$html = '<span>共<b>'.$intTotalNum.'</b>'.$strDesc.'</span>';
        if( empty($intTotalNum) ){
            return $strMetion;
        }
        //组合中间导航页码
        $html .= '<ul class="fr"><li class="no_up"><a href="'.self::changeUrlPage($strPageUrl, $intPage-1 <= 0 ? 1 : $intPage-1).'">上一页</a></li><li>'.$intPage.'/'.$intTotalPage.'</li><li class="no_down" ><a href="'.self::changeUrlPage($strPageUrl, $intPage>=$intTotalPage ? $intTotalPage : $intPage+1).'" title="下一页">下一页</a></li></ul>';
        return $html;
    }

    /**
     * 替换分页参数
     * @author 刘晓峰
     * @date 2012-10-10
     * @param integer $strUrl 当前页url
     * @param integer $intPage 当前页码
     * @return string
     */
    public static  function changeUrlPage($strUrl, $intPage){
    	if ( $intPage == 0 ) $intPage = 1;
    	$arrR = explode('?', $strUrl);
    	preg_match('/p(?P<p>[0-9]+)/i', $strUrl, $a);
    	if ( !isset($a['p']) ) {
    		$arrR[1] = isset($arrR[1]) ? $arrR[1] : '';
    		return $arrR[0].'p'.$intPage.(!empty($arrR[1]) ? '?' : '').$arrR[1];
    	}
    	return preg_replace('/p([0-9]+)/i', 'p'.$intPage, $strUrl);
    }
	
	/**
	 * 前端列表页分页
	 * @author 刘晓峰
	 * @date 2012-10-10
	 * @param integer $intPage 当前页
	 * @param integer $intTotalNum 总条数
	 * @param integer $intPageSize 每页最大条数
	 * @param string $strPageUrl 当前页url
	 * @param string $intSphinxMax sphinx最大限制条数 如果是0表示无限
	 * @return string
	 */
	public static function wwwBottomPageNew($intPage, $intTotalNum, $intPageSize=20, $strPageUrl='', $strDesc = '套房源', $intSphinxMax=1000){
			//$intPage = $_GET['page'];
			//$intTotalNum = $_GET['totalNum'];
			//$intPageSize = 10;
			//总条数
			$intTotalNum = intval($intTotalNum);
			$intTotalNumForShow = $intTotalNum;
			$intTotalNum = $intSphinxMax>0&&$intTotalNum>$intSphinxMax? $intSphinxMax: $intTotalNum; //这里由于sphinx限制了最大条数

			//每页最大条数
			$intPageSize = intval($intPageSize);

			//总页数
			$intTotalPage = ceil($intTotalNum/$intPageSize);

			//当前页
			$intPage = intval($intPage);
			$intPage = $intPage<1? 1: $intPage;
			$intPage = $intPage>$intTotalPage? $intTotalPage: $intPage;
			
			//当前页左右最大显示页数 保证每次显示条数为10条
			if( $intPage<6 ){ //当前页小时保证后面多显示 
				$intCutNumPre = $intPage;
				$intCutNumNext = 9-$intCutNumPre;
			}elseif( $intTotalPage-$intPage<7 ){ //当前页大时保证前面显示多些
				$intCutNumNext = $intTotalPage-$intPage;
				$intCutNumPre = 8-$intCutNumNext;
			}else{ //当前页中间时
				$intCutNumPre = 3;
				$intCutNumNext = 7-$intCutNumPre;
			}

			//当分页数据为空时
			if( empty($intTotalNum) ){
				return '<div class="l">共找到 <b>0</b> '.$strDesc.'</div>';
			}
			
			$strPage = '<div class="l">共找到 <b>'.$intTotalNumForShow.' </b> '.$strDesc.'</div><div class="r">';
			//分页展示数据
			$strPage .= $intPage==1? '': '<a href="'.self::changeUrlPage($strPageUrl, $intPage-1).'">上一页</a> ';
				
			$tmp = $intPage-$intCutNumPre;
			//当前页之前的拼装
			if( $tmp<3 ){  //无省略号的
				for($i=1; $i<$intPage; $i++){
					$strPage .= '<a href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
				}
			}else{ //有省略号的
				$strPage .= '<a href="'.self::changeUrlPage($strPageUrl, $i).'">1</a> ... ';
				for($i=$tmp; $i<$intPage; $i++){
					$strPage .= '<a href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
				}
			}

			//当前页
			$strPage .= '<strong>'.$intPage.'</strong> ';

			//当前页之后的拼装
			$tmp = $intTotalPage-$intCutNumNext-$intPage;
			if( $tmp<3 ){ //无省略号的
				for($i=$intPage+1; $i<=$intTotalPage; $i++){
					$strPage .= '<a href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
				}
			}else{ //有省略号的
				$strPage .= '';
				$tmp = $intPage+$intCutNumNext+1;
				for($i=$intPage+1; $i<$tmp; $i++){
					$strPage .= '<a href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
				}
				$strPage .= ' ... <a href="'.self::changeUrlPage($strPageUrl, $intTotalPage).'">'.$intTotalPage.'</a>';
			}

			$strPage .= $intPage==$intTotalPage? ' ': ' <a href="'.self::changeUrlPage($strPageUrl, $intPage+1).'">下一页</a>';
			$strPage .= '</div>';
			return $strPage;
		}
		
	/**
     * 新闻频道分页
     * @param int   $oPageCount 数据总量
     * @param int   $oPage      当前页码
     * @param int   $oPageMax   页面最大显示长度，默认为25
     * @return string
     */
    public static function newsPage($oPageCount, $oPage, $oPageMax = 25){
        $oURL = "";
        $sHTML = '';
        //当前页码
        $page = $oPage;
        //总记录数
        $pageCount = intval($oPageCount);
        //分页组长度
        $pageCut = 5;
        //页面最大数据数
        $pageMax = $oPageMax;
        //总页面数
        $pages = $pageCount % $pageMax == 0 ? intval($pageCount/$pageMax) : intval($pageCount/$pageMax) + 1;
        //当前分页组的标示
        $pageIndex = $page;
        //分页组起始页面
        $pageFrom = $pageIndex < 3 ? 1 : ($pageIndex - 2);
        //分页组结束页面，超出最大页面设置为最大页面
        $pageTo = min($pages, $pageFrom+$pageCut-1);
        //需要过滤的获取到的参数
        $killREQUEST = array(
            'p'
        );
        //过滤$_COOKIE中的数据
        foreach ($_COOKIE as $key=>$value){
            unset($_REQUEST[$key]);
        }
        foreach ($_REQUEST as $key=>$value){
            if ( in_array($key, $killREQUEST)) {
                continue;
            }
            $oURL .= "&amp;".$key."=".urlencode(Utility::filterSubject($value));
        }
        //分页代码
     	if ($pageCount == 0) {
            return "";
        }
        //输出上翻页
        $sHTML .= $page==1 ? "<a href=\"javascript:;\" class=\"no_goto\">上一页</a>" : "<a href=\"?p=".($page-1<1?1:$page-1).$oURL."\" title=\"上一页\">上一页</a>";
    	//输出中间导航页
        if ( $pageFrom+$pageCut>$pages ) {
            if ( $pages-$pageCut <= 0 ) {
                $begin = 1;
            }
            else {
                $begin = $pages-$pageCut+1;
            }
        }
        else {
            $begin = $pageFrom;
        }
//        }                       
		$sHTML .= "<span><b>".$page."</b>/".$pages."</span>";
		 //输出后翻页
        $sHTML .= $page==$pages ? "<a href=\"javascript:;\" class=\"no_goto\">下一页</a>" : "<a href=\"?p=".($page+1>$pages?$page:$page+1).$oURL."\" title=\"下一页\">下一页</a>";
        return $sHTML;
    }

    /**
     * 前端列表页分页
     * @author 刘晓峰
     * @date 2012-10-10
     * @param integer $intPage 当前页
     * @param integer $intTotalNum 总条数
     * @param integer $intPageSize 每页最大条数
     * @param string $strPageUrl 当前页url
     * @param string $intSphinxMax sphinx最大限制条数 如果是0表示无限
     * @return string
     */
    public static function wwwPageNew($intPage, $intTotalNum, $intPageSize=20, $strPageUrl='', $strDesc = '套', $intSphinxMax=1000, $nofollowFlag=0){
        //$intPage = $_GET['page'];
        //$intTotalNum = $_GET['totalNum'];
        //$intPageSize = 10;
        //总条数
        $intTotalNum = intval($intTotalNum);
        $intTotalNumForShow = $intTotalNum;
        $intTotalNum = $intSphinxMax>0&&$intTotalNum>$intSphinxMax? $intSphinxMax: $intTotalNum; //这里由于sphinx限制了最大条数

        //每页最大条数
        $intPageSize = intval($intPageSize);

        //总页数
        $intTotalPage = ceil($intTotalNum/$intPageSize);

        //当前页
        $intPage = intval($intPage);
        $intPage = $intPage<1? 1: $intPage;
        $intPage = $intPage>$intTotalPage? $intTotalPage: $intPage;

        //当前页左右最大显示页数 保证每次显示条数为10条
        if( $intPage<5 ){ //当前页小时保证后面多显示
            $intCutNumPre = $intPage;
            $intCutNumNext = 8-$intCutNumPre;
        }elseif( $intTotalPage-$intPage<6 ){ //当前页大时保证前面显示多些
            $intCutNumNext = $intTotalPage-$intPage;
            $intCutNumPre = 7-$intCutNumNext;
        }else{ //当前页中间时
            $intCutNumPre = 3;
            $intCutNumNext = 6-$intCutNumPre;
        }

        //当分页数据为空时
        if( empty($intTotalNum) ){
            return '<p class="left">共找到 <em>0</em> '.$strDesc.'</p>';
        }

        $strPage = '<p class="left">共找到 <em>'.$intTotalNumForShow.' </em> '.$strDesc.'</p><div class="pages_nav fr">';
        //分页展示数据
        $strPage .= '<span class="total">'.$intPage.'/'.$intTotalPage.'页</span>';
   	 	if($nofollowFlag==1){
	    	$strPage .= $intPage==1? '<span class="pre pre_disable"><em></em>上一页</span>': '<a class="pre" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $intPage-1).'"><em></em>上一页</a> ';
	    }else{
	     	$strPage .= $intPage==1? '<span class="pre pre_disable"><em></em>上一页</span>': '<a class="pre" href="'.self::changeUrlPage($strPageUrl, $intPage-1).'"><em></em>上一页</a> ';
	    }
	    
        $tmp = $intPage-$intCutNumPre;
        //当前页之前的拼装
        if( $tmp<3 ){  //无省略号的
            for($i=1; $i<$intPage; $i++){
	            if($nofollowFlag==1){
	               $strPage .= '<a class="num" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
	            }else{
	            	$strPage .= '<a class="num" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
	           	}
                
            }
        }else{ //有省略号的
        	if($nofollowFlag==1){
                $strPage .= '<a class="num" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $i).'">1</a><span>...</span>';
            }else{
            	$strPage .= '<a class="num" href="'.self::changeUrlPage($strPageUrl, $i).'">1</a><span>...</span>';
           	}
            
            for($i=$tmp; $i<$intPage; $i++){
            	if($nofollowFlag==1){
                	$strPage .= '<a class="num" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
            	}else{
            		$strPage .= '<a class="num" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
            	}   
            }
        }

        //当前页
        $strPage .= '<span class="num on">'.$intPage.'</span> ';

        //当前页之后的拼装
        $tmp = $intTotalPage-$intCutNumNext-$intPage;
        if( $tmp<3 ){ //无省略号的
            for($i=$intPage+1; $i<=$intTotalPage; $i++){
            	if($nofollowFlag==1){
                	$strPage .= '<a class="num" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
            	}else{
            		$strPage .= '<a class="num" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
            	}
            }
        }else{ //有省略号的
            $strPage .= '';
            $tmp = $intPage+$intCutNumNext+1;
            for($i=$intPage+1; $i<$tmp; $i++){
            	if($nofollowFlag==1){
                	$strPage .= '<a class="num" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
            	}else{
            		$strPage .= '<a class="num" href="'.self::changeUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
            	}
            }
        	if($nofollowFlag==1){
               	$strPage .= '<span>...</span><a class="num" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $intTotalPage).'">'.$intTotalPage.'</a>';
            }else{
            	$strPage .= '<span>...</span><a class="num" href="'.self::changeUrlPage($strPageUrl, $intTotalPage).'">'.$intTotalPage.'</a>';
            }
        }
		if($nofollowFlag==1){
        	$strPage .= $intPage==$intTotalPage? '<span class="next next_disable"><em></em>下一页</span> ': ' <a class="next" rel="nofollow" href="'.self::changeUrlPage($strPageUrl, $intPage+1).'"><em></em>下一页</a>';
		}else{
			$strPage .= $intPage==$intTotalPage? '<span class="next next_disable"><em></em>下一页</span> ': ' <a class="next" href="'.self::changeUrlPage($strPageUrl, $intPage+1).'"><em></em>下一页</a>';
		}
        $strPage .= '</div>';
        return $strPage;
    }
    
    /**
     * 导购
     * @param integer $intPage 当前页
     * @param integer $intTotalNum 总条数
     * @param integer $intPageSize 每页最大条数
     * @param string $strPageUrl 当前页url
     * @param string $intSphinxMax sphinx最大限制条数 如果是0表示无限
     * @return string
     */
    public static function daogouPage($intPage, $intTotalNum, $intPageSize=20, $strPageUrl='', $strDesc = '套'){
        $strPageUrl = $strPageUrl ? $strPageUrl : $_SERVER['REQUEST_URI'];
        //总条数
        $intTotalNum = intval($intTotalNum);
        $intTotalNumForShow = $intTotalNum;

        //每页最大条数
        $intPageSize = intval($intPageSize);

        //总页数
        $intTotalPage = ceil($intTotalNum/$intPageSize);

        //当前页
        $intPage = intval($intPage);
        $intPage = $intPage<1? 1: $intPage;
        $intPage = $intPage>$intTotalPage? $intTotalPage: $intPage;

        //当前页左右最大显示页数 保证每次显示条数为10条
        if( $intPage<5 ){ //当前页小时保证后面多显示
            $intCutNumPre = $intPage;
            $intCutNumNext = 8-$intCutNumPre;
        }elseif( $intTotalPage-$intPage<6 ){ //当前页大时保证前面显示多些
            $intCutNumNext = $intTotalPage-$intPage;
            $intCutNumPre = 7-$intCutNumNext;
        }else{ //当前页中间时
            $intCutNumPre = 3;
            $intCutNumNext = 6-$intCutNumPre;
        }

        //当分页数据为空时
        if( empty($intTotalNum) ){
            return '<p class="fl">共找到 <span class="fb c_txt5">0</span> '.$strDesc.'</p>';
        }

        $strPage = '<p class="fl">共找到 <span class="fb c_txt5">'.$intTotalNumForShow.' </span> '.$strDesc.'</p><div class="pages_nav fr">';
        //分页展示数据
        $strPage .= '<span class="total">'.$intPage.'/'.$intTotalPage.'页</span>';
        $strPage .= $intPage==1? '<span class="pre pre_disable"><em></em>上一页</span>': '<a class="pre" href="'.self::pageUrl($strPageUrl, $intPage-1).'"><em></em>上一页</a> ';

        $tmp = $intPage-$intCutNumPre;
        //当前页之前的拼装
        if( $tmp<3 ){  //无省略号的
            for($i=1; $i<$intPage; $i++){
                $strPage .= '<a class="num" href="'.self::pageUrl($strPageUrl, $i).'">'.$i.'</a> ';
            }
        }else{ //有省略号的
            $strPage .= '<a class="num" href="'.self::pageUrl($strPageUrl, $i).'">1</a><span>...</span>';
            for($i=$tmp; $i<$intPage; $i++){
                $strPage .= '<a class="num" href="'.self::pageUrl($strPageUrl, $i).'">'.$i.'</a> ';
            }
        }

        //当前页
        $strPage .= '<span class="num on">'.$intPage.'</span> ';

        //当前页之后的拼装
        $tmp = $intTotalPage-$intCutNumNext-$intPage;
        if( $tmp<3 ){ //无省略号的
            for($i=$intPage+1; $i<=$intTotalPage; $i++){
                $strPage .= '<a class="num" href="'.self::pageUrl($strPageUrl, $i).'">'.$i.'</a> ';
            }
        }else{ //有省略号的
            $strPage .= '';
            $tmp = $intPage+$intCutNumNext+1;
            for($i=$intPage+1; $i<$tmp; $i++){
                $strPage .= '<a class="num" href="'.self::pageUrl($strPageUrl, $i).'">'.$i.'</a> ';
            }
            $strPage .= '<span>...</span><a class="num" href="'.self::pageUrl($strPageUrl, $intTotalPage).'">'.$intTotalPage.'</a>';
        }

        $strPage .= $intPage==$intTotalPage? '<span class="next next_disable"><em></em>下一页</span> ': ' <a class="next" href="'.self::pageUrl($strPageUrl, $intPage+1).'"><em></em>下一页</a>';
        $strPage .= '</div>';
        return $strPage;
    }
    
    
     /**
     * 前端导购房源精选特殊分页
     * @author 刘晓峰
     * @date 2012-10-10
     * @param integer $intPage 当前页
     * @param integer $intTotalNum 总条数
     * @param integer $intPageSize 每页最大条数
     * @param string $strPageUrl 当前页url
     * @param string $intSphinxMax sphinx最大限制条数 如果是0表示无限
     * @return string
     */
    public static function daogouHousePage($intPage, $intTotalNum, $intPageSize=20, $strPageUrl='', $strDesc = '套'){
        //$intPage = $_GET['page'];
        //$intTotalNum = $_GET['totalNum'];
        //$intPageSize = 10;
        //总条数
        $intTotalNum = intval($intTotalNum);
        $intTotalNumForShow = $intTotalNum;
        //每页最大条数
        $intPageSize = intval($intPageSize);

        //总页数
        //特殊规则,第一页显示7条,后面都显示8条
        $intTotalPage = ceil(($intTotalNum+1)/$intPageSize);

        //当前页
        $intPage = intval($intPage);
        $intPage = $intPage<1? 1: $intPage;
        $intPage = $intPage>$intTotalPage? $intTotalPage: $intPage;

        //当前页左右最大显示页数 保证每次显示条数为10条
        if( $intPage<5 ){ //当前页小时保证后面多显示
            $intCutNumPre = $intPage;
            $intCutNumNext = 8-$intCutNumPre;
        }elseif( $intTotalPage-$intPage<6 ){ //当前页大时保证前面显示多些
            $intCutNumNext = $intTotalPage-$intPage;
            $intCutNumPre = 7-$intCutNumNext;
        }else{ //当前页中间时
            $intCutNumPre = 3;
            $intCutNumNext = 6-$intCutNumPre;
        }

        //当分页数据为空时
        if( empty($intTotalNum) ){
            return '<p class="left">共有 <span class="fb c_txt5">0</span> '.$strDesc.'</p>';
        }

        $strPage = '<p class="left">共有 <span class="fb c_txt5">'.$intTotalNumForShow.' </span> '.$strDesc.'</p><div class="pages_nav fr">';
        //分页展示数据
        $strPage .= '<span class="total">'.$intPage.'/'.$intTotalPage.'页</span>';
        $strPage .= $intPage==1? '<span class="pre pre_disable"><em></em>上一页</span>': '<a class="pre" href="'.self::changeDaogouUrlPage($strPageUrl, $intPage-1).'"><em></em>上一页</a> ';

        $tmp = $intPage-$intCutNumPre;

        for($i=1; $i<$intPage; $i++){
            $strPage .= '<a class="num" href="'.self::changeDaogouUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
        }
        $strPage .= '<span class="num on">'.$intPage.'</span> ';
        for($i=$intPage+1; $i<=$intTotalPage; $i++){
            $strPage .= '<a class="num" href="'.self::changeDaogouUrlPage($strPageUrl, $i).'">'.$i.'</a> ';
        }
        $strPage .= $intPage==$intTotalPage? '<span class="next next_disable"><em></em>下一页</span> ': ' <a class="next" href="'.self::changeDaogouUrlPage($strPageUrl, $intPage+1).'"><em></em>下一页</a>';
        $strPage .= '</div>';
        return $strPage;
    }
    
    public static  function changeDaogouUrlPage($strUrl, $intPage){
    	if ( $intPage == 0 ) $intPage = 1;
    	preg_match('/(?P<id>[0-9]+)\_(?P<p>[0-9]+)\.html/i', $strUrl, $a);
        if($a['id'] && $a['p']){
            return preg_replace('/([0-9]+)_([0-9]+)/i', $a['id'].'_'.$intPage, $strUrl);
        }
    }
    
    public static  function pageUrl($strUrl, $intPage){
        if($intPage == 0) {
            $intPage = 1;
        } 
    	$parses = parse_url($strUrl);
        $params = array();
        if($parses['query']) {
            $eles = explode("&", $parses['query']);
            foreach($eles as $v) {
                if(false === strpos($v, '=')) {
                    continue;
                } 
                list($type, $name) = explode('=', $v);
                $params[$type] = $name;
            }
        }
        $params['p'] = $intPage;
        //echo '<pre>';var_dump($parses,$params);
        if($parses['scheme'] && $parses['host']) {
            return $parses['scheme'] . '://' .$parses['host'] . $parses['path'] . '?'. http_build_query($params);
        } else {
            return $parses['path'] . '?'. http_build_query($params);
        }
    }

    public static function  parkSEOPAGE($intPage, $intTotalNum, $intPageSize=15, $strPageUrl='', $strDesc = '套', $intSphinxMax=1000, $nofollowFlag=0){
        $intTotalNum = intval($intTotalNum);
        $intTotalNumForShow = $intTotalNum;
        $intTotalNum = $intSphinxMax>0&&$intTotalNum>$intSphinxMax? $intSphinxMax: $intTotalNum; //这里由于sphinx限制了最大条数

        //每页最大条数
        $intPageSize = intval($intPageSize);

        //总页数
        $intTotalPage = ceil($intTotalNum/$intPageSize);

        //当前页
        $intPage = intval($intPage);
        $intPage = $intPage<1? 1: $intPage;
        $intPage = $intPage>$intTotalPage? $intTotalPage: $intPage;

        //当前页左右最大显示页数 保证每次显示条数为10条
        if( $intPage<5 ){ //当前页小时保证后面多显示
            $intCutNumPre = $intPage;
            $intCutNumNext = 8-$intCutNumPre;
        }elseif( $intTotalPage-$intPage<6 ){ //当前页大时保证前面显示多些
            $intCutNumNext = $intTotalPage-$intPage;
            $intCutNumPre = 7-$intCutNumNext;
        }else{ //当前页中间时
            $intCutNumPre = 3;
            $intCutNumNext = 6-$intCutNumPre;
        }

        //当分页数据为空时
        if( empty($intTotalNum) ){
            return '<p class="left">共找到 <em>0</em> '.$strDesc.'</p>';
        }

        $strPage = '<p class="left">共找到 <em>'.$intTotalNumForShow.' </em> '.$strDesc.'</p><div class="pages_nav fr">';
        $strPage = '<div class="pages_nav fr">';
        //分页展示数据
        $strPage .= '<span class="total">'.$intPage.'/'.$intTotalPage.'页</span>';
        if($nofollowFlag==1){
            $strPage .= $intPage==1? '<span class="pre pre_disable"><em></em>上一页</span>': '<a class="pre" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, $intPage-1).'"><em></em>上一页</a> ';
        }else{
            $strPage .= $intPage==1? '<span class="pre pre_disable"><em></em>上一页</span>': '<a class="pre" href="'.self::changeParkSEOPage($strPageUrl, $intPage-1).'"><em></em>上一页</a> ';
        }

        $tmp = $intPage-$intCutNumPre;
        //当前页之前的拼装
        if( $tmp<3 ){  //无省略号的
            for($i=1; $i<$intPage; $i++){
                if($nofollowFlag==1){
                    $strPage .= '<a class="num" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }else{
                    $strPage .= '<a class="num" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }

            }
        }else{ //有省略号的
            if($nofollowFlag==1){
                $strPage .= '<a class="num" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, 1).'">1</a><span>...</span>';
            }else{
                $strPage .= '<a class="num" href="'.self::changeParkSEOPage($strPageUrl, 1).'">1</a><span>...</span>';
            }

            for($i=$tmp; $i<$intPage; $i++){
                if($nofollowFlag==1){
                    $strPage .= '<a class="num" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }else{
                    $strPage .= '<a class="num" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }
            }
        }

        //当前页
        $strPage .= '<span class="num on">'.$intPage.'</span> ';

        //当前页之后的拼装
        $tmp = $intTotalPage-$intCutNumNext-$intPage;
        if( $tmp<3 ){ //无省略号的
            for($i=$intPage+1; $i<=$intTotalPage; $i++){
                if($nofollowFlag==1){
                    $strPage .= '<a class="num" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }else{
                    $strPage .= '<a class="num" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }
            }
        }else{ //有省略号的
            $strPage .= '';
            $tmp = $intPage+$intCutNumNext+1;
            for($i=$intPage+1; $i<$tmp; $i++){
                if($nofollowFlag==1){
                    $strPage .= '<a class="num" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }else{
                    $strPage .= '<a class="num" href="'.self::changeParkSEOPage($strPageUrl, $i).'">'.$i.'</a> ';
                }
            }
            if($nofollowFlag==1){
                $strPage .= '<span>...</span><a class="num" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, $intTotalPage).'">'.$intTotalPage.'</a>';
            }else{
                $strPage .= '<span>...</span><a class="num" href="'.self::changeParkSEOPage($strPageUrl, $intTotalPage).'">'.$intTotalPage.'</a>';
            }
        }
        if($nofollowFlag==1){
            $strPage .= $intPage==$intTotalPage? '<span class="next next_disable"><em></em>下一页</span> ': ' <a class="next" rel="nofollow" href="'.self::changeParkSEOPage($strPageUrl, $intPage+1).'"><em></em>下一页</a>';
        }else{
            $strPage .= $intPage==$intTotalPage? '<span class="next next_disable"><em></em>下一页</span> ': ' <a class="next" href="'.self::changeParkSEOPage($strPageUrl, $intPage+1).'"><em></em>下一页</a>';
        }
        $strPage .= '</div>';
        return $strPage;
    }

    public static function changeParkSEOPage($url, $page){
        if ($page == 1){
            if (preg_match('/[a-zA-Z0-9]\/p[1-9][0-9]*\.html/',$url)){
                return preg_replace('/\/p([0-9]+)/i', '', $url);
            }
            elseif (preg_match('/[a-zA-Z0-9]\.html/', $url)){
                $url = str_replace('.html','',$url);
                return $url."/p".$page.".html";
            }
        }
        if (preg_match('/[a-zA-Z0-9]\/p[1-9][0-9]*\.html/',$url)){
            return preg_replace('/p([0-9]+)/i', 'p'.$page, $url);
        }
        elseif (preg_match('/[a-zA-Z0-9]\.html/', $url)){
            $url = str_replace('.html','',$url);
            return $url."/p".$page.".html";
        }
    }
}
?>