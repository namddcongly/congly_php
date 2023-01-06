<?php
$host = $_SERVER['HTTP_HOST'];

ini_set('display_errors', 1);
$page = SystemIO::post('page', 'def', '');
$array_adv_home = array(
    'pos_1' => '',
    'pos_2' => ''
);
$array_adv_cate = array(
    'pos_1' => '
                <a href="http://' . $host . '/hoat-dong-toa-an/70-nam-truyen-thong-tand/trien-khai-cuoc-van-dong-viet-ve-nhung-ky-niem-sau-sac-trong-nganh-toa-an-nhan-dan-340384.html">
                <img src="http://' . $host . '/adv/van-dong-tand.gif" alt="">
            </a>
                <a href="https://saigonmia.com.vn/" target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="300" src="http://' . $host . '/adv/canhoq7.gif"alt="">
            </a>
            <a href="http://' . $host . '/giai-tri/nhac/dem-giao-luu-bien-dao-que-huong-225782.html">
                <img src="http://' . $host . '/adv/BDQH-BANNER-WEB1.gif" alt="" style="display: none">
            </a>
            <a target="_blank" href="https://pamas.com.vn/">
                <img width="300" alt="" src="http://' . $host . '/adv/IMG_8894.JPG" style="padding:5px 0px 10px 0px;">
            </a>
			<a target="_blank" href="https://pamas.com.vn/">
                <img width="300" alt="" src="http://' . $host . '/adv/ubxavinhthinh.jpg" style="padding:5px 0px 10px 0px;">
            </a>
                
					',
    'pos_2' => '
        <a href="#" target="_blank"><img style="padding:0px 0px 0px 0px;" width="300"
                                         src="http://' . $host . '/adv/300x250-gif-low-resolution.gif" alt=""></a>
                                         <img style="padding:0px 0px 0px 0px;" width="300"
                                         src="http://' . $host . '/adv/baohiemhanam.jpg" alt="">
                                         <img style="padding:5px 0px 0px 0px;" width="300"
                                         src="http://' . $host . '/adv/duongthuyso9.jpg" alt="">'
);
$array_adv_detail = array(
    'pos_1' => '
                <a href="http://' . $host . '/hoat-dong-toa-an/70-nam-truyen-thong-tand/trien-khai-cuoc-van-dong-viet-ve-nhung-ky-niem-sau-sac-trong-nganh-toa-an-nhan-dan-340384.html">
                <img src="http://' . $host . '/adv/van-dong-tand.gif" alt="">
            </a>
                <a href="https://saigonmia.com.vn" target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="300" src="http://' . $host . '/adv/canhoq7.gif"alt="">
            </a> <a href="http://' . $host . '/giai-tri/nhac/dem-giao-luu-bien-dao-que-huong-225782.html">
                <img src="http://' . $host . '/adv/BDQH-BANNER-WEB1.gif" alt="">
            </a>
            
					',
    'pos_2' => '
            <a  target="_blank">
                <img src="http://' . $host . '/adv/thaihung.jpg" width="300px" height="250"/>
            </a>
<a href="http://' . $host . '/adv/dienluccaobangdetail.jpg">
                <img src="http://' . $host . '/adv/dienluccaobang.jpg" alt="">
            </a>
        <a href="#" target="_blank"><img style="padding:0px 0px 0px 0px;" width="300"
                                         src="http://' . $host . '/adv/300x250-gif-low-resolution.gif" alt=""></a>
     '
);

if ($page == 'congly_home') {
    $array_adv = $array_adv_home;
} elseif ($page == 'congly_cate') {
    $array_adv = $array_adv_cate;
} elseif ($page == 'congly_detail') {
    $array_adv = $array_adv_detail;
} else {
    $array_adv = $array_adv_home;
}
echo json_encode($array_adv);
