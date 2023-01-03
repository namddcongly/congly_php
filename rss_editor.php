<?php
echo '<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">';
include 'define.php';

if (defined ( IN_JOC ))
	die ( "Direct access not allowed!" );

require (KERNEL_PATH . 'system.config.php');
require (UTILS_PATH . 'io.php');
require_once (APPLICATION_PATH . 'news' . DS . 'frontend' . DS . 'includes' . DS . 'frontend.news.php');
$cate_id = SystemIO::get ( 'cate_id', 'int');

$newsObj = new FrontendNews ();
$list_category = $newsObj->getCategory ();


$items = $newsObj->getNews ( 'store', 'id,title,img1,time_public,time_created,description,cate_id', '(type_post = 2 OR type_post =3 ) AND time_public > 0 AND time_public < '.time(), 'time_public DESC', "0,10", 'id', true );
if (count ( $items ) > 0) {
	$h_item = '';
	foreach ( $items as $item ) {
		$h_item .= '
		<item>
			<title>'. htmlspecialchars ( $item ["title"] ).'</title>
			<link>' . 'http://congly.com.vn' . Url::Link ( array (
				'cate_alias' => $list_category [$item ['cate_id']] ['alias'],
				'title' => $item ['title'],
				'id' => $item ['id']
		), 'news', 'congly_detail' ) . '</link>
			<description>'.$item ["description"].'</description>
			<dc:creator>' . date ( "F d, Y H:i:s", $item ['time_public'] ) . '</dc:creator>
		</item>';
	}
}
?>
<channel>
<link>http://congly.com.vn</link>
<description>Các bài viết mới nhất trên Báo  Công lý</description>
<title>Báo điện tử Công lý</title>
<image>
        <url>http://congly.com.vn/webskins/skins/news/images/logoly2.png</url>
        <title>Báo điện tử Công lý</title>
        <link>http://congly.com.vn/tin-tuc-24h/</link>
		</image>
	<?php echo $h_item;?>
</channel>
</rss>
