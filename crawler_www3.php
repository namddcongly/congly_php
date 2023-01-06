<?php
ini_set('display_errors', 1);
session_cache_expire(3600);
session_start();
ini_set('session.gc-maxlifetime', 3600);

date_default_timezone_set('Asia/Bangkok');
include 'define.php';
require_once 'crawler/InsertData.php';
require_once 'application/news/frontend/includes/frontend.news.php';
$backendNews = new FrontendNews();
header('Content-Type: text/html; charset=utf-8');
$limit = $_GET['limit'];
$cateId = $_GET['cate_id'];
#$data = $backendNews->getNews('store1', '*', 'ChannelId = 5 AND  PublishedTime > "2020-10-01" AND PublishedTime < "2021-01-01"', 'PublishedTime ASC');
//$data = $backendNews->getNews('store1', '*', 'ChannelId = 5 AND  PublishedTime > "2021-01-01" AND PublishedTime < "2022-01-01"', 'PublishedTime ASC');
$data = $backendNews->getNews('store1', '*', 'ChannelId = 228 AND  PublishedTime > "2022-01-01" AND PublishedTime < "2023-01-01"', 'PublishedTime ASC');
$arr = array();
$i = 0;
foreach ($data as $tmp) {

    $arr[$i]['title'] = $tmp['Title'];
    $arr[$i]['author'] = $tmp['AuthorAlias'];
    $arr[$i]['PublisherId'] = $tmp['PublisherId'];
    $arr[$i]['time_created'] = strtotime($tmp['CreatedAt']);
    $arr[$i]['ChannelId'] = $tmp['ChannelId'];
    $arr[$i]['cate_id'] = 269;
    $arr[$i]['cate_path'] = 269;
    $arr[$i]['img1'] = $tmp['Thumbnail'];
    $arr[$i]['description'] = $tmp['Headlines'];
    $arr[$i]['time_public'] = strtotime($tmp['PublishedTime']);
    $arr[$i]['img2'] = $tmp['Thumbnail2'];
    $arr[$i]['content'] = $tmp['Content'];
    ++$i;
}
echo json_encode($arr);
die;
?>
