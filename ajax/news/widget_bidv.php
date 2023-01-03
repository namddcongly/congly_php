<?php
//ini_set('display_errors',1);
require_once 'application/news/frontend/includes/frontend.news.php';
$newsObj = new FrontendNews();
$arrData = array(
	array(
		'title' => 'Trịnh Kim Chi:Quãng đời khốn khổ vì căn bệnh Hen suyễn, Đờm,Ho,Khó Thở',
		'href' => 'https://baokhikhang.vn/khong-ngo-a-hau-trinh-kim-chi-lai-co-cach-day-lui-ho-dom-hen-suyen-nhay-den-the?utm_source=congly&utm_medium=KimChi-PC',
		'image' => ''
	),
	array(
		'title' => 'Ai thiếu máu não, mất ngủ mãn tính: Mách họ mẹo này mừng hơn bắt được vàng!',
		'href' => 'http://migrin.com.vn/thieu-mau-nao/tuong-chung-thieu-mau-nao-nang-se-khien-nga-quy-nhung-dieu-ky-da-toi.html?utm_source=Testthu',
		'image' => ''
	),
	array(
		'title' => 'Tai biến méo miệng, liệt nửa người: Cứ làm đúng 1 bước này mỗi ngày là ổn ngay!',
		'href' => 'http://aven.com.vn/chia-se-nguoi-dung/cung-nhu-voi-toi-tai-bien-mach-nao-se-chang-con-gay-kho-cho-ai-duoc-nua.html?utm_source=Testthu',
		'image' => ''
	),
	array(
		'title' => 'Sẹo xấu do bỏng, ngã, khâu mổ: Cứ làm 1 bước này mỗi tối mờ sạch, đừng bôi trát linh tinh!',
		'href' => 'http://nacurgogel.com/lam-mo-seo/seo-loi-seo-lom-seo-tham-cu-meo-nay-ma-lam-dung-boi-chat-linh-tinh.html?utm_source=Testthu',
		'image' => ''
	)
);

$txt=json_encode($arrData);
echo $txt;