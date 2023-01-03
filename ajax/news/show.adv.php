<?php
$host = $_SERVER['HTTP_HOST'];

ini_set('display_errors', 1);
$page = SystemIO::post('page', 'def', '');
$array_adv_home = array('pos_1' => '
              <a href="http://'.$host.'/358/hoa-giai-doi-thoai-tai-toa-an/">
                <img title="" src="http://'.$host.'/adv/hoa_giai_anh.gif" alt="">
            </a>
            <a href="http://tv.congly.vn/phim-tai-lieu-toa-an-nhan-dan-xet-xu-nhung-vu-an-dien-hinh-tap-1-d6509.html">
                <img src="http://'.$host.'/adv/Phim-tai-lieu-TAND.gif" alt="">
            </a>
            <a target="_blank" href="https://toaan.gov.vn">
                <img src="http://'.$host.'/adv/tandtc.png" width="300px">
            </a>
             <a href="http://'.$host.'/phap-luat/nhan-tin/huong-dan-thu-tuc-nhan-tin-98458.html">
                <img src="http://'.$host.'/data/adv/2013/6/huong-dan-thu-tuc1-ds1-copy-ok.gif" alt="">
            </a>
            <a href="http://'.$host.'/phap-luat/nhan-tin/">
                <img title="" src="http://'.$host.'/data/adv/2013/6/Thongtincanbietok2.gif" alt="">
            </a>
			 <a href="https://portal.vietcombank.com.vn/news/Pages/home.aspx">
                <img title="" src="http://'.$host.'/adv/vietcombank.jpg" alt="">
            </a>
            <a href="https://www.bidv.com.vn/uudaithe/">
                <img title="" src="http://'.$host.'/adv/bidv_12_10.jpg" alt="">
            </a>
        
            <a target="_blank" href="https://thacogroup.vn">
                <img src="http://'.$host.'/adv/truonghai.gif" width="300px">
            </a>
            <a href="https://www.vietinbank.vn/vn/tin-tuc/Mien-phi-6-thang-duy-tri-VietinBank-iPay-cho-khach-hang-dang-ky-moi-20180321151131.html" target="_blank">
                <img style="padding:0px 0px 0px 0px;" width="300" src="http://'.$host.'/adv/viettinban166.jpg" alt="">
           </a>
           <a target="_blank" href="http://novalandexpo.com.vn/?utm_source=congly&utm_medium=banner_300x300&utm_campaign=Expo">
                <img src="http://'.$host.'/adv/novalland123.gif" width="300px">
            </a>
            <a href="https://phuquoc-marina.vn/intercontinental-phu-quoc/" target="_blank">
                <img width="300" style="padding:5px 0px 10px 0px; display: none" src="http://'.$host.'/adv/home2home.gif" alt="Viet combank">
            </a>
             <a href="http://tpb.vn
             " target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/son_tung.jpg" alt="">
            </a>
            <a target="_blank">
                <img src="http://'.$host.'/adv/namabank.png" width="300px" />
            </a>
            <a href="https://q7riverside.com.vn/" target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="300" src="http://'.$host.'/adv/canhoq7.gif"alt="">
            </a>
           <a style="display: none" href="https://www.bidv.com.vn/bidv/ca-nhan/khuyen-mai/uu-dai-khac/bidv-cho-cuoc-song-xanh" target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/bidvmoi.gif" alt="">
            </a>
             <a href="#" target="_blank" style="display: none">
                <img width="1" height="1" style="padding:5px 0px 0px 0px;" src="http://'.$host.'/adv/bia.jpg" alt="Bia">
            </a>',
    'pos_2' => '
             <a href="http://'.$host.'/adv/cty_dongbac_noidung.jpg" target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/cty_dongbac.jpg" alt="">
            </a>
            <a href="http://'.$host.'/adv/than_coc_sau_noidung.jpg" target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/than_coc_sau.jpg" alt="">
            </a>
               
            
           <a href="" target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/600.800.png" alt="">
            </a>
           <a href="https://himlamgreenpark.com/?utm_source=PR&utm_medium=banner&utm_campaign=https%3A%2F%2Fcongly.vn" target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/himlam.jpg" alt="">
            </a>
            <a href="" target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/vinamilk1.jpg" alt="">
            </a>
            <a  target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/banner-moi.jpg" alt="">
           </a>
          
           
           <a  target="_blank" href="http://'.$host.'/adv/2020/aulacquangninh_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/2020/aulacquangninh.jpg" alt="">
           </a>
           <a  target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/2020/chuongmy.jpg" alt="">
           </a>
           <a  target="_blank" href="http://'.$host.'/adv/2020/dienluchanam_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/2020/dienluchanam.jpg" alt="">
           </a>
           <a  target="_blank" href="http://'.$host.'/adv/2020/dienlucvietnam2020_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/2020/dienlucvietnam2020.jpg" alt="">
           </a>
           <a  target="_blank" href="http://'.$host.'/adv/2020/longson_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/2020/longson.jpg" alt="">
           </a>
           <a  target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/2020/thainguyen.jpg" alt="">
           </a>
           <a  target="_blank" href="http://'.$host.'/adv/2020/campha_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/2020/campha.jpg" alt="">
           </a>
       
            <a  target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/SW_BanaHill.jpg" alt="">
           </a>
			<a  target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/ssg.jpg" alt="">
           </a>
           <a  target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/nganhangphattrien.jpg" alt="">
           </a>
           
            <a target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="290" height="270px" src="http://'.$host.'/adv/fra.png" alt="">
           </a>
          
            <a href="https://www.vinamilk.com.vn/vi" target="_blank">
                <img style="padding:0px 0px 0px 0px;" width="280" src="http://'.$host.'/adv/vinamilk.jpg" alt="">
           </a>
             <a  target="_blank" href="http://'.$host.'/adv/hongaivina_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/hongaivina.jpg" alt="">
           </a>
            <a  target="_blank" href="http://'.$host.'/adv/longson_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/ximanglongson.jpg" alt="">
           </a> 
             <a  target="_blank" href="http://'.$host.'/adv/bv_quany_content.jpg">
                <img style="padding:5px 0px 5px 0px;" width="290" src="http://'.$host.'/adv/bvquanly.jpg" alt="">
           </a> 
		 
		   <a target="_blank">
              
           </a>
		
           <a target="_blank">
               
           </a>
          
           '
);
$array_adv_cate = array('pos_1' => '
                <a href="http://'.$host.'/hoat-dong-toa-an/70-nam-truyen-thong-tand/trien-khai-cuoc-van-dong-viet-ve-nhung-ky-niem-sau-sac-trong-nganh-toa-an-nhan-dan-340384.html">
                <img src="http://'.$host.'/adv/van-dong-tand.gif" alt="">
            </a>
                <a href="https://saigonmia.com.vn/" target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="300" src="http://'.$host.'/adv/canhoq7.gif"alt="">
            </a>
            <a href="http://'.$host.'/giai-tri/nhac/dem-giao-luu-bien-dao-que-huong-225782.html">
                <img src="http://'.$host.'/adv/BDQH-BANNER-WEB1.gif" alt="" style="display: none">
            </a>
            <a target="_blank" href="https://pamas.com.vn/">
                <img width="300" alt="" src="http://'.$host.'/adv/IMG_8894.JPG" style="padding:5px 0px 10px 0px;">
            </a>
			<a target="_blank" href="https://pamas.com.vn/">
                <img width="300" alt="" src="http://'.$host.'/adv/ubxavinhthinh.jpg" style="padding:5px 0px 10px 0px;">
            </a>
                
					',
    'pos_2' => '
        <a href="#" target="_blank"><img style="padding:0px 0px 0px 0px;" width="300"
                                         src="http://'.$host.'/adv/300x250-gif-low-resolution.gif" alt=""></a>
                                         <img style="padding:0px 0px 0px 0px;" width="300"
                                         src="http://'.$host.'/adv/baohiemhanam.jpg" alt="">
                                         <img style="padding:5px 0px 0px 0px;" width="300"
                                         src="http://'.$host.'/adv/duongthuyso9.jpg" alt="">'
);
$array_adv_detail = array('pos_1' => '
                <a href="http://'.$host.'/hoat-dong-toa-an/70-nam-truyen-thong-tand/trien-khai-cuoc-van-dong-viet-ve-nhung-ky-niem-sau-sac-trong-nganh-toa-an-nhan-dan-340384.html">
                <img src="http://'.$host.'/adv/van-dong-tand.gif" alt="">
            </a>
                <a href="https://saigonmia.com.vn" target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="300" src="http://'.$host.'/adv/canhoq7.gif"alt="">
            </a> <a href="http://'.$host.'/giai-tri/nhac/dem-giao-luu-bien-dao-que-huong-225782.html">
                <img src="http://'.$host.'/adv/BDQH-BANNER-WEB1.gif" alt="">
            </a>
            
					',
    'pos_2' => '
            <a  target="_blank">
                <img src="http://'.$host.'/adv/thaihung.jpg" width="300px" height="250"/>
            </a>
<a href="http://'.$host.'/adv/dienluccaobangdetail.jpg">
                <img src="http://'.$host.'/adv/dienluccaobang.jpg" alt="">
            </a>
        <a href="#" target="_blank"><img style="padding:0px 0px 0px 0px;" width="300"
                                         src="http://'.$host.'/adv/300x250-gif-low-resolution.gif" alt=""></a>
     '
);

if ($page == 'congly_home')
    $array_adv = $array_adv_home;
elseif ($page == 'congly_cate')
    $array_adv = $array_adv_cate;
elseif ($page == 'congly_detail')
    $array_adv = $array_adv_detail;
else
    $array_adv = $array_adv_home;
echo json_encode($array_adv);
