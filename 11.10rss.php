<?php
echo '<?xml version="1.0" encoding="utf-8"?><rss version="2.0">';
include 'define.php';

if(defined(IN_JOC)) die("Direct access not allowed!");

require(KERNEL_PATH.'system.config.php');
require(UTILS_PATH.'io.php');
require_once(APPLICATION_PATH.'news'.DS.'frontend'.DS.'includes'.DS.'frontend.news.php');
$cate_id = SystemIO::get('cate_id', 'int', 80);
$newsObj 		= new FrontendNews();
$list_category=$newsObj->getCategory();
$items = $newsObj->getNews('store','id,title,img1,time_public,time_created,description,cate_id',"cate_path LIKE '%,$cate_id,%'",'time_public DESC',"0,10",'id',true);
if(count($items) > 0)
{
    $h_item='';	
	foreach($items as $item)
	{	
		
        $img="http://congly.com.vn/data/news/".date('Y/n/j',$item['time_created'])."/".$item['img1'];
		$h_item .= '
		<item>
			<title><![CDATA[ '.htmlspecialchars($item["title"]).' ]]></title>
			<description><![CDATA[ <a href="http://congly.com.vn'.Url::Link(array('cate_alias'=>$list_category[$item['cate_id']]['alias'],'title'=>$item['title'],'id'=>$item['id']),'news','congly_detail').'"><img src="'.$img.'" /></a>'.htmlspecialchars($item["description"]).' ]]></description>
			<link>'.'http://congly.com.vn'.Url::Link(array('cate_alias'=>$list_category[$item['cate_id']]['alias'],'title'=>$item['title'],'id'=>$item['id']),'news','congly_detail').'</link>
			<pubDate>'.date("F d, Y H:i:s",$item['time_public']).'</pubDate>
		</item>';
	}
}
?>
<channel>
    <title><?php echo $list_category[$cate_id]['name'];?> - congly.com.vn</title>
    <description><?php echo $list_category[$cate_id]['description'];?></description>
    <link><?php echo 'http://congly.com.vn/'.$list_category[$cate_id]['alias'];?></link>
    <copyright>congly.com.vn</copyright>
    <generator>congly.com.vn:http://congly.com.vn</generator>
	<pubDate><?php echo date("F d, Y H:i:s");?></pubDate>
    <lastBuildDate><?php echo date("F d, Y H:i:s");?></lastBuildDate>
	<?php echo $h_item;?> 
</channel>
</rss>
