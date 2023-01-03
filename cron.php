<?php
$memcache = new Memcache;
$memcache->addServer('localhost', 11211);
$arr_news_cate = file_get_contents('http://tv.congly.com.vn/widget');
var_dump($memcache->set('tv.congly.com.vn',$arr_news_cate,MEMCACHE_COMPRESSED,600));
$list_conglyxaoi = file_get_contents('http://conglyxahoi.net.vn/ajax.php?fnc=widget_congly&path=news');
var_dump($memcache->set('conglyxahoinetvn', $list_conglyxaoi, MEMCACHE_COMPRESSED, 600));
