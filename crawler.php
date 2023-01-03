<html
<header>
</header>
<body>
<?php
echo 123;
die;
ini_set('display_errors', 1);
session_cache_expire(3600);
session_start();
ini_set('session.gc-maxlifetime', 3600);

date_default_timezone_set('Asia/Bangkok');
include 'define.php';
require_once 'crawler/InsertData.php';
require_once 'application/news/backend/includes/backend.news.php';
$backendNews = new BackendNews();
header('Content-Type: text/html; charset=utf-8');

$data = file_get_contents('https://www3.congly.vn/crawler.php?c=324');
$datas = json_decode($data, true);
print_r($datas);
die;
foreach ($datas as $dt) {
    echo $dt['title'];
    die;
    $backendNews->insertData('store', ['title' => $dt['title']]);
    echo 'ngon';
    return;
}

die;

$crawler = new Crawler('https://bangiao.congly.vn/chinh-tri');
$crawler->setUserId(1);
$crawler->setCateId(269);
//$crawler->getContentGetLink()->getLink();
//$data = $crawler->getDataCrawler();
$insertData = new InsertData($crawler, $backendNews);
$insertData->selectData();
//$insertData->insertData();
echo 'Done';
//$insertData->insertData();

?>
</body>
