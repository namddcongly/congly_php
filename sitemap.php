<?php
function writeFile($data, $path = LOG_PATH, $mode = 'a+') {
	if (! $fp = @fopen ( $path, $mode )) {
		return false;
	}
	flock ( $fp, LOCK_EX );
	fwrite ( $fp, $data );
	flock ( $fp, LOCK_UN );
	fclose ( $fp );
	return true;
}
include 'define.php';
if (defined ( IN_JOC ))
	die ( "Direct access not allowed!" );
require (KERNEL_PATH . 'system.config.php');
require (UTILS_PATH . 'io.php');
require_once (APPLICATION_PATH . 'news' . DS . 'frontend' . DS . 'includes' . DS . 'frontend.news.php');
$cate_id = SystemIO::get ( 'cate_id', 'int', 80 );
$newsObj = new FrontendNews ();
$list_category = $newsObj->getCategory ();
$data = '';
$data .= '<?xml version="1.0" encoding="utf-8"?><sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">';
$data .= '<sitemap>';
$data .= '<loc>http://congly.vn/sitemaps/categories.xml</loc>';
$data .= '<lastmod>' . date ( 'Y-m-d', time () ) . '</lastmod>';
$data .= '</sitemap>';
$m = date ( 'n', time () );
for($k = $m; $k > 0; -- $k) {
	$data .= '<sitemap>';
	$data .= '<loc>http://congly.vn/sitemaps/news-' . date ( 'Y', time () ) . '-' . $k . '.xml</loc>';
	$data .= '</sitemap>';
}
for($i = 12; $i > 0; -- $i) {
	$data .= '<sitemap>';
	$data .= '<loc>http://congly.vn/sitemaps/news-2015-' . $i . '.xml</loc>';
	$data .= '</sitemap>';
}
for($i = 12; $i > 0; -- $i) {
	$data .= '<sitemap>';
	$data .= '<loc>http://congly.vn/sitemaps/news-2014-' . $i . '.xml</loc>';
	$data .= '</sitemap>';
}

for($i = 12; $i > 0; -- $i) {
	$data .= '<sitemap>';
	$data .= '<loc>http://congly.vn/sitemaps/news-2013-' . $i . '.xml</loc>';
	$data .= '</sitemap>';
}
$data .= '</sitemapindex>';
writeFile ( $data, ROOT_PATH . 'sitemap.xml', 'w+' );
$data = '<?xml version="1.0" encoding="utf-8"?>';
$data .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
foreach ( $list_category as $row ) {
	if ($row ['property'] & 1)
		$data .= '<url><loc>http://congly.vn/' . trim ( $row ['alias'], '/' ) . '/</loc><lastmod>' . date ( 'Y-m-d', time () ) . '</lastmod><changefreq>always</changefreq><priority>0.9</priority></url>';
}

$data .= '</urlset>';
writeFile ( $data, ROOT_PATH . 'sitemaps/categories.xml', 'w+' );
$date_e = time ();
$date_s = time () - (date ( 'd', time () ) * 86400);
$items = $newsObj->getNews ( 'store', 'id,title,img1,time_public,time_created,description,cate_id', 'time_public > ' . $date_s . ' AND time_public < ' . $date_e, 'time_public DESC', "4000", 'id', true );
$data = '<?xml version="1.0" encoding="utf-8"?>';
$data .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
$data .= '<url><loc>http://congly.vn</loc><lastmod>' . date ( 'Y-m-d' ) . '</lastmod><changefreq>always</changefreq><priority>1</priority></url>';
foreach ( $items as $item ) {
	$link = 'http://congly.vn' . Url::Link ( array (
			'cate_alias' => $list_category [$item ['cate_id']] ['alias'],
			'title' => $item ['title'],
			'id' => $item ['id'] 
	), 'news', 'congly_detail' );
	$data .= '<url><loc>' . $link . '</loc><lastmod>' . date ( 'Y-m-d', $item ['time_public'] ) . '</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>';
}
$data .= '</urlset>';
writeFile ( $data, ROOT_PATH . 'sitemaps/news-' . date ( 'Y-n' ) . '.xml', 'w+' );
/*Write site map news*/
$date_e = time ();
$date_s = time () - 2 * 86400;
$items = $newsObj->getNews ( 'store', 'id,title,img1,time_public,time_created,description,cate_id', 'type_post=3 AND time_public > ' . $date_s . ' AND time_public < ' . $date_e, 'time_public DESC', "4000", 'id', true );
$data = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
foreach($items as $item) {
	$link = 'http://congly.vn' . Url::Link ( array (
			'cate_alias' => $list_category [$item ['cate_id']] ['alias'],
			'title' => $item ['title'],
			'id' => $item ['id']
		), 'news', 'congly_detail' );
	$tag = explode('[]', $item['tag']);
	$tag = $tag['0'];
	$data .='<url><loc>'.$link.'</loc>
		<changefreq>hourly</changefreq>
		<priority>0.8</priority>
		<image:image>
			<image:loc>https://congly.vn/data/new/'.date('Y/n/j/',$item['time_created']).$item['img1'].'</image:loc>
			<image:title><![CDATA['.$item['title'].']]></image:title>
		</image:image>
		<news:news>
			<news:publication>
				<news:name>Báo Công lý</news:name>
				<news:language>vi</news:language>
			</news:publication>
			<news:genres></news:genres>
            <news:publication_date>'.date('c',$item['time_public']).'</news:publication_date>
			<news:title><![CDATA['.$item['title'].']]></news:title>
			<news:keywords><![CDATA['.$tag.']]></news:keywords>
		</news:news>
	</url>';
}

$data .= '</urlset>';
var_dump(writeFile ( $data, ROOT_PATH . '/sitemap_news.xml', 'w+' ));
echo 'Okie ' . date ( 'd-m-Y H:i:s', time () );
?>


