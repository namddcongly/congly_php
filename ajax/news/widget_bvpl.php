<?php
//ini_set('display_errors',1);
require_once 'application/news/frontend/includes/frontend.news.php';
$newsObj = new FrontendNews();
$list_category=$newsObj->getCategory('','arrange asc',200,true);
$LIST_CATEGORY=$list_category;
$LIST_CATEGORY_ALIAS=SystemIO::arrayToOption($LIST_CATEGORY,'id','alias');
$list_news_all=$newsObj->getNews('store','id,title,description,time_public,cate_id,time_created,img1,img2',' ( cate_path LIKE "%,273,%" OR cate_path LIKE "%,288,%" OR cate_path LIKE "%,283,%" ) AND time_public > 0 AND time_public < '.time(),'time_public DESC',2,'id',true);
$arr_news=array();
foreach($list_news_all as $row)
{
	$href = Url::Link(array("id" => $row['id'],"title" => $row['title'],'cate_alias'=>$LIST_CATEGORY_ALIAS[$row['cate_id']]), "news", "detail");
	$arr_news[$row['id']]['title']=$row['title'];
	$arr_news[$row['id']]['description']=$row['description'];
	$arr_news[$row['id']]['img']=IMG::Thumb($row['time_created'],$row['img1'],'cnn_150x100');
	$arr_news[$row['id']]['href']='http://congly.vn/'.trim($href,'/');
}
$txt=json_encode($arr_news);
echo $txt;