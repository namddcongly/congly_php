<?php
ini_set('display_errors',0);
include 'define.php';
if(defined(IN_JOC)) die("Direct access not allowed!");
require(KERNEL_PATH.'system.config.php');
require(UTILS_PATH.'io.php');
require_once(APPLICATION_PATH.'news'.DS.'frontend'.DS.'includes'.DS.'frontend.news.php');
require_once 'application/news/frontend/includes/frontend.news.php';
$newsObj = new FrontendNews();
$list_category=$newsObj->getCategory('','arrange asc',200,true);
$w=$_GET['w'];
$w1=(int)$w-1;
$LIST_CATEGORY=$list_category;
$LIST_CATEGORY_ALIAS=SystemIO::arrayToOption($LIST_CATEGORY,'id','alias');
$t=strtotime('15-10-2014');
//echo time();
//echo 'time_created > '.(time()-7*86400*$w).' AND time_public < '.(time()-7*86400*$w1);
$list_news1=$newsObj->getNews('store_hit','*','time_created > '.$t.' AND time_created < '.time(),'hit DESC','10','nw_id');
//print_r($list_news1);
foreach($list_news1 as $t)
{
	$ids.=$t['nw_id'].',';
}
$ids=trim($ids,',');
$list_news=$newsObj->getNews('store','*','id IN ('.$ids.')','id DESC','10','id');
foreach($list_news as $row)
{
	echo '<a href="http://congly.com.vn/?app=news&page=congly_detail&id='.$row['id'].'">'.$row['title'].'</a> <strong>'.$list_news1[$row['id']]['hit'].'</strong>-'.date(' H:i d/m/Y',$row['time_public']).'<br/>';
}