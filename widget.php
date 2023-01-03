<?php
include 'define.php';
if(defined(IN_JOC)) die("Direct access not allowed!");
require(KERNEL_PATH.'system.config.php');
require(UTILS_PATH.'io.php');
require_once(APPLICATION_PATH.'news'.DS.'frontend'.DS.'includes'.DS.'frontend.news.php');
require_once 'application/news/frontend/includes/frontend.news.php';
$newsObj = new FrontendNews();
$list_category=$newsObj->getCategory('','arrange asc',200,true);
$LIST_CATEGORY=$list_category;
$LIST_CATEGORY_ALIAS=SystemIO::arrayToOption($LIST_CATEGORY,'id','alias');
$list_news=$newsObj->getNews('store','id,title,description,img1,time_public,time_created,cate_id,user_id,censor_id,editor_id','type_post=3  AND time_public < '.time(),'time_public DESC','10','id');
foreach($list_news as $row)
{
	$href = Url::Link(array("id" => $row['id'],"title" => $row['title'],'cate_alias'=>$LIST_CATEGORY_ALIAS[$row['cate_id']]), "news", "congly_detail");
	$arr_news[$row['id']]['title']=$row['title'];
	$arr_news[$row['id']]['description']=$row['description'];
	$arr_news[$row['id']]['img']='http://congly.com.vn/'.IMG::Thumb($row['time_created'],$row['img1'],'cnn_150x100');	
	$arr_news[$row['id']]['href']=$href;	
}
$txt=json_encode($arr_news);
echo $txt;