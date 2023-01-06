<html
<header>
</header>
<body>
<?php
ini_set('display_errors', 1);
session_cache_expire(3600);
session_start();
ini_set('session.gc-maxlifetime', 3600);
date_default_timezone_set('Asia/Bangkok');
include 'define.php';
require_once 'application/news/backend/includes/backend.news.php';


//function mapCate(){
//    $backend = new BackendNews();
//    $data = $backend->getNews('Channel','ChannelId,ParentId,Name');
//    $arr = array();
//    foreach($data as $tmp){
//
//        foreach($data as $ttt){
//            if($tmp['ParentId'] == $ttt['ChannelId']){
//                $arr[$tmp['ParentId']][$ttt['ChannelId']] = $ttt;
//            }
//        }
//    }
//    print_r($arr);
//    die;
//    $data = $backend->getNews('category','id,cate_id1,alias,cate_id2');
//    print_r($data);
//}
//
//array(
//        5 => 269, // thoi su
//        21=>    // dieu tra ban doc
//        164 =>   // lam dep
//        166 =>   //dien dan cong ly
//        186 =>   // van hoa giai tri
//        195 => // toa an
//        204 => // kinh te
//        222 => // phap luat
//        226 => // xahoi
//        234 =>  // phap dinh
//        261 => // phap dinh
//        257 => // phap luat
//        267 => // Doanh nghiep
//);
//
//mapCate();
//
//echo 1;
//die;












$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => 'https://www3.congly.vn/api_get_news.php',
    CURLOPT_SSL_VERIFYPEER => false
));
$data = curl_exec($curl);
$newsObj = new BackendNews();
$datas = json_decode($data, true);
foreach ($datas as $t) {
    $content = $t['content'];
    $t['user_id'] = 1;
    unset($t['content']);
    $id = $newsObj->insertData('store', $t);
    if ($id) {
        $newsObj->insertData('store_content', array('nw_id' => $id, 'content' => $content));
    }
}
echo $id;
echo 'ngon';
die;
?>
</body>
