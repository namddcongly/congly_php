<?php
if (defined(IN_JOC)) {
    die("Direct access not allowed!");
}

//ini_set('display_errors',1);
class Congly_Home_Center
{
    function index()
    {
        joc()->set_file('Congly_Home_Center', Module::pathTemplate() . "frontend/home_center.htm");
        Page::setHeader('Tin tuc moi nhat 24h Đọc báo tin tức pháp luật Công lý',
            'Tin tuc, tin mới, tin tức mới nhất, tin tuc moi, tin tuc 24h, doc bao, đọc báo, báo, tin mới nhất',
            'Tin tức mới nhất trong ngày, tin tức cập nhất 24h các lĩnh vực đời sống pháp luật. Đọc báo mới nhất hôm này, thông tin thời sự mới nhất trong ngày');
        //Page::registerFile('slides.jquery.js', 'webskins/skins/news/js/jquery.jcarousellite.js', 'header', 'js');
        global $list_category;
        $frontendObj = new FrontendNews();
        page::setMeta('
            <meta name="dc.title" content="Tin tuc moi nhat 24h Đọc báo tin tức pháp luật Công lý" />
            <meta name="dc.source" CONTENT="https://congly.vn/">
            <meta name="dc.subject" content=" Tin tức, tin tuc, tin mới, tin tức trong ngày, đọc báo, tin tức online,tin tức 24h,pháp luật"/>
            <meta name="dc.keywords" CONTENT="Tin tuc, tin moi, tin tức mới nhất, tin tuc moi, tin tuc 24h, VN, doc bao, đọc báo, báo, tin mới nhất" />
            <meta name="dc.description" content="Tin tức mới nhất trong ngày được báo Công lý đưa tin nhanh nhất 24h hàng ngày. Đọc báo tin tức online cập nhật tin nóng thời sự pháp luật, giải trí..." />
            <meta name="dc.identifier" content="//congly.vn" /><link rel="alternate" href="https://congly.vn" hreflang="vi-vn" />'
        );
        /*Tin nổi bật trang chủ*/
        $news_home_highlights = $frontendObj->getNewsHome('*',
            'time_public < ' . time() . ' AND  time_public > 0 AND property & 1 = 1', 4);
        /*
        $keyId = array_keys($news_home_highlights);
        $idRand = array_rand($keyId);
        $rowTop = $news_home_highlights[$keyId[$idRand]];
        */
        $rowTop = current($news_home_highlights);

        joc()->set_var('href_0', Url::link_detail($rowTop, $list_category));
        $this->showIcon($rowTop, '0');
        joc()->set_var('title_0', $rowTop['title']);
        joc()->set_var('html_title_0', htmlspecialchars($rowTop['title']));
        joc()->set_var('description_0', SystemIO::strLeft(strip_tags($rowTop['description']), 300));
        joc()->set_var('img_0', IMG::show($rowTop));
        $k = 1;
        foreach ($news_home_highlights as $row) {
            if ($row['nw_id'] != $rowTop['nw_id']) {
                joc()->set_var('href_' . $k, Url::link_detail($row, $list_category));
                $this->showIcon($row, $k);
                joc()->set_var('title_' . $k, $row['title']);
                joc()->set_var('html_title_' . $k, htmlspecialchars($row['title']));
                joc()->set_var('description_' . $k, SystemIO::strLeft(strip_tags($row['description']), 300));
                joc()->set_var('img_' . $k, IMG::thumb($row, 'cnn_150x100'));
                ++$k;
            }

        }
        /*Tin tiêu điểm trang chủ*/
        $news_home_focus = $frontendObj->getNewsHome('*',
            'time_public < ' . time() . ' AND  time_public > 0 AND property & 4 = 4', 10);
        joc()->set_block('Congly_Home_Center', 'Focus', 'Focus');
        $txt_focus = '';
        foreach ($news_home_focus as $row) {
            joc()->set_var('title', $row['title']);
            joc()->set_var('title_cut', SystemIO::strLeft($row['title'], 250));
            $this->showIcon($row, 'f');
            joc()->set_var('html_title', htmlspecialchars($row['title']));
            $href = Url::link_detail($row, $list_category);
            joc()->set_var('href', $href);
            $txt_focus .= joc()->output('Focus');

        }
        joc()->set_var('Focus', $txt_focus);
        /*Tin mới nhất trang chủ*/
        //$news = $frontendObj->getNews('store', '*','cate_path NOT LIKE "%,269,%" AND cate_path NOT LIKE "%,338,%" AND cate_path NOT LIKE "%,288,%"', 'time_public DESC', 7);
        $news = $frontendObj->getNewsHome('*',
            'time_public < ' . time() . ' AND cate_path NOT LIKE "%,269,%" AND cate_path NOT LIKE "%,338,%" AND  time_public > 0 AND property & 2 = 2',
            6);
        joc()->set_block('Congly_Home_Center', 'New', 'New');
        $txt_new = '';
        foreach ($news as $row) {
            joc()->set_var('title', $row['title']);
            joc()->set_var('title_cut', SystemIO::strLeft($row['title'], 250));
            $this->showIcon($row, 'f');
            joc()->set_var('html_title', htmlspecialchars($row['title']));
            joc()->set_var('desc', SystemIO::strLeft($row['description'], 70));
            $href = Url::link_detail($row, $list_category);
            joc()->set_var('img', IMG::thumb($row, 'cnn_210x140'));
            $number_m = round((time() - $row['time_public']) / 60);
            if ($number_m / 60 > 1) {
                $number_m = ceil($number_m / 60) - 1 . ' giờ trước';
            } else {
                $number_m = $number_m . ' phút trước';
            }
            joc()->set_var('cate_name', $list_category[$row['cate_id']]['name']);
            joc()->set_var('cate_href', $list_category[$row['cate_id']]['alias']);
            joc()->set_var('number_m', $number_m);
            joc()->set_var('href', $href);
            $txt_new .= joc()->output('New');
        }
        joc()->set_var('New', $txt_new);

        /*Tâm điểm dư luận - góc nhìn*/

        $list_goc_nhin = $frontendObj->getNews('store', 'id,cate_id,description,title,time_created,img1,time_public',
            'cate_id = 359', 'time_public DESC', '1');
        foreach ($list_goc_nhin as $row) {
            joc()->set_var('title_gocnhin', $row['title']);
            joc()->set_var('html_title_gocnhin', htmlspecialchars($row['title']));
            joc()->set_var('desc_gocnhin', htmlspecialchars(SystemIO::strLeft($row['description'], 100)));
            joc()->set_var('img_gocnhin', IMG::thumb($row, 'cnn_185x140'));
            joc()->set_var('href_gocnhin', Url::link_detail($row, $list_category));
        }
        /*Lua chon so phan*/
        $list_news_topic = $frontendObj->getNews('store',
            'id,title,description,img2,img1,img3,time_public,time_created,cate_id',
            'topic_id = 462 AND time_public > 0', 'time_public DESC', 3);
        $j = 1;
        foreach ($list_news_topic as $row) {
            joc()->set_var('title_lcsp' . $j, $row['title']);
            joc()->set_var('html_title_lcsp' . $j, htmlspecialchars($row['title']));
            joc()->set_var('desc_lcsp' . $j, htmlspecialchars(SystemIO::strLeft($row['description'], 100)));
            joc()->set_var('img_lcsp' . $j, IMG::show($row));
            joc()->set_var('href_lcsp' . $j, Url::link_detail($row, $list_category));
            ++$j;
        }
        /*Thời sự*/
        $this->setVarCate(269, 'ts', $frontendObj);
        /*Tòa án*/
        $this->setVarCate(338, 'ta', $frontendObj);
        /*Xã hội*/
        $this->setVarCate(283, 'xh', $frontendObj);
        /*Pháp đình*/
        $this->setVarCate(273, 'pd', $frontendObj);
        /*Pháp luật*/
        $this->setVarCate(288, 'pl', $frontendObj);
        /*Thế giới*/
        $this->setVarCate(303, 'tg', $frontendObj);
        /*Sức khỏe*/
        $this->setVarCate(384, 'sk', $frontendObj);
        /*Giáo dục*/
        $this->setVarCate(386, 'gd', $frontendObj);
        /*Kinh doanh*/
        $this->setVarCate(298, 'kd', $frontendObj);
        /*Thể thao*/
        $this->setVarCate(314, 'tt', $frontendObj);
        /*Công nghệ*/
        $this->setVarCate(307, 'cn', $frontendObj);
        /*Nhắc tin*/
        $this->setVarCate(292, 'nt', $frontendObj);
        /*Nhắc tin*/
        $this->setVarCate(398, '24h', $frontendObj);
        /*Bạn đọc*/
        $this->setVarCate(414, 'bd', $frontendObj, 200);
        /*Truyền hình Công lý*/
        $this->setVarCateOther('tv.congly.com.vn');
        /*Giải trí*/
        $this->setVarCate(293, 'gt', $frontendObj, 150);
        /*Dau tu*/
        $this->setVarCate(416, 'dt', $frontendObj);
        /*BDS*/
        $this->setVarCate(300, 'bds', $frontendObj);
        /*Đọc nhiều nhất*/
        joc()->set_block('Congly_Home_Center', 'Hit', 'Hit');
        $list_hit = $frontendObj->getNews('store_hit', 'nw_id,hit',
            'cate_path NOT LIKE "%,434,%" AND time_created >' . (time() - 3 * 86400), 'hit DESC', '8');
        $news_ids = '';
        foreach ($list_hit as $_tmp) {
            $news_ids .= $_tmp['nw_id'] . ',';
        }
        $news_ids = trim($news_ids, ',');
        $list_news_hit = array();
        if ($news_ids) {
            $list_news_hit = $frontendObj->getNews('store', 'id,cate_id,title,time_created,img1,time_public',
                'id IN (' . $news_ids . ')', 'time_public DESC', '5');
        }
        $txt_hit = '';
        foreach ($list_news_hit as $row) {
            joc()->set_var('title_hit', $row['title']);
            joc()->set_var('html_title_hit', htmlspecialchars($row['title']));
            joc()->set_var('img_hit', IMG::thumb($row, 'cnn_150x100'));
            joc()->set_var('href_hit', Url::link_detail($row, $list_category));
            $txt_hit .= joc()->output('Hit');
        }

        joc()->set_var('Hit', $txt_hit);
        joc()->set_var('page', 'congly_home');
        /**
         * Hoạt động Chánh án và các Phó Chánh án
         */
        $this->setVarBoxChanhAn($frontendObj);
        /**
         * Show adv
         */
        $this->showAdvHome();
        $html = joc()->output("Congly_Home_Center");
        joc()->reset_var();
        return $html;
    }

    public function setVarCate($cate_id, $prefix, $frontendObj, $length_desc = 200)
    {
        global $list_category;
        $arr_news_cate = $this->getNewsCate($cate_id, $frontendObj);
        ksort($arr_news_cate);
        joc()->set_var('cate_child_' . $prefix, $this->showListCateChild($cate_id));
        $k = 1;
        foreach ($arr_news_cate as $row) {
            joc()->set_var('href_' . $prefix . $k, Url::link_detail($row, $list_category));
            joc()->set_var('title_' . $prefix . $k, $row['title']);
            joc()->set_var('desc_' . $prefix . $k, SystemIO::strLeft(strip_tags($row['description']), $length_desc));
            $this->showIcon($row, $prefix . $k);
            joc()->set_var('html_title_' . $prefix . $k, htmlspecialchars($row['title']));
            joc()->set_var('title_html_' . $prefix . $k, htmlspecialchars($row['title']));
            joc()->set_var('img_' . $prefix . $k, IMG::thumb($row));
            ++$k;
        }
    }

    public function setVarCateOther($prefix, $length_desc = 150)
    {
        if ($prefix == 'tv.congly.com.vn') {

            #$rss_feed = simplexml_load_file("https://tv.congly.vn/truyen-hinh-toa-an-nhan-dan/feed");
            $rss_feed = simplexml_load_file("https://tv.congly.vn/hoat-dong-toa-an/feed");

            $i = 0;
            $arr_news_cate = array();
            foreach ($rss_feed->channel->item as $node) {
                if ($i > 8) {
                    break;
                }
                $arr_news_cate[] = array(
                    'title' => (string)$node->title,
                    'href' => (string)$node->link,
                    'src' => (string)$node->enclosure['url']
                );
                ++$i;
            }

            $metaSite = $this->getSiteOG($arr_news_cate['0']['href']);
            joc()->set_var('poster',
                isset($metaSite['image']) ? $metaSite['image'] : 'webskins/skins/news/images/tvcongly.jpg');
            $k = 1;
            foreach ($arr_news_cate as $row) {
                joc()->set_var('href_' . $prefix . $k, $row['href']);
                joc()->set_var('title_' . $prefix . $k, $row['title']);
                joc()->set_var('html_title_' . $prefix . $k, htmlspecialchars($row['title']));
                joc()->set_var('src' . $prefix . $k, $row['src']);
                ++$k;
            }
        }

    }

    public function showIcon($row, $var)
    {
        joc()->set_var('icon_video_' . $var,
            $row['is_video'] ? ' <img src="webskins/skins/news/images/video.gif" />' : '');
        joc()->set_var('icon_img_' . $var,
            $row['is_video'] ? ' <img src="webskins/skins/news/images/image.gif" />' : '');
        $time = time() - $row['time_public'];
        joc()->set_var('icon_new_' . $var, $time < 18000 ? ' <img src="webskins/skins/news/images/new2.gif" />' : '');
    }

    public function showListCateChild($cate_id)
    {
        global $list_category;
        $array_cate_child = array();
        $txt_list_cate_child = '';
        $k = 0;
        foreach ($list_category as $_tmp) {
            if ($_tmp['cate_id1'] == $cate_id && (($_tmp['property'] & 1) == 1)) {
                $array_cate_child[] = $_tmp;
                ++$k;
            }
        }
        if ($k == 0) {
            return $txt_list_cate_child;
        }

        $j = 0;
        foreach ($array_cate_child as $_tmp) {
            $txt_list_cate_child .= '<li><a class="title13" title="' . $_tmp['name'] . '" href="/' . $_tmp['alias'] . '/">' . $_tmp['name'] . '</a> / </li>';
            if ($cate_id == 298 && $j == 3) {
                break;
            }
            ++$j;
            if ($j > 4) {
                break;
            }
        }

        return '<ul>' . $txt_list_cate_child . '</ul>';
    }

    public function getNewsCate($cate_id, $frontendObj)
    {
        $catNewsFocus = $frontendObj->getNewsHome('*',
            '(cate_id = "' . $cate_id . '" OR cate_path LIKE "%' . $cate_id . '%") AND property & 8 = 8', 1);
        if (count($catNewsFocus)) {
            $temp = current($catNewsFocus);
            $catNewsNew = $frontendObj->getNewsHome('*',
                '(cate_id = "' . $cate_id . '" OR cate_path LIKE "%' . $cate_id . '%") AND nw_id != ' . $temp['nw_id'],
                8);
        } else {
            $catNewsNew = $frontendObj->getNewsHome('*',
                '(cate_id = "' . $cate_id . '" OR cate_path LIKE "%' . $cate_id . '%")',
                8);
        }

        $arr_news_cate = array_merge($catNewsFocus, $catNewsNew);
        return $arr_news_cate;
    }

    public function isMobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',
                $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
                substr($useragent, 0, 4))) {
            return true;
        }
        return false;
    }

    public function setVarBoxChanhAn($frontendObj)
    {
        $list_topic = $frontendObj->getNews('topic', '*', 'property = 8', 'id DESC', 6);
        $list_topic = array_reverse($list_topic);
        $k = 1;
        foreach ($list_topic as $row) {
            joc()->set_var('topic_name_' . $k, $row['name']);
            joc()->set_var('topic_desc_' . $k, $row['description']);
            joc()->set_var('html_topic_name_' . $k, htmlspecialchars($row['name']));
            if ($_SERVER['HTTP_HOST'] == 'old.congly.com.vn') {
                if ($row['img']) {
                    joc()->set_var('topic_img_' . $k, 'http://' . $_SERVER['HTTP_HOST'] . '/data/topic/' . $row['img']);
                } else {
                    joc()->set_var('topic_img_' . $k,
                        'http://' . $_SERVER['HTTP_HOST'] . '/webskins/skins/news/images/noimg.jpg');
                }
            } else {
                if ($row['img']) {
                    joc()->set_var('topic_img_' . $k,
                        'https://' . $_SERVER['HTTP_HOST'] . '/data/topic/' . $row['img']);
                } else {
                    joc()->set_var('topic_img_' . $k,
                        'https://' . $_SERVER['HTTP_HOST'] . '/webskins/skins/news/images/noimg.jpg');
                }
            }

            joc()->set_var('topic_href_' . $k,
                Url::Link(array('topic_id' => $row['id'], 'name' => $row['name']), 'news', 'topic'));
            ++$k;
        }
    }

    public function getSiteOG($url, $specificTags = 0)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML(file_get_contents($url));
        foreach ($doc->getElementsByTagName('meta') as $m) {
            $tag = $m->getAttribute('name') ? $m->getAttribute('name') : $m->getAttribute('property');
            if (strpos($tag, 'og:') === 0) {
                $res[str_replace('og:', '', $tag)] = $m->getAttribute('content');
            }
        }
        return $specificTags ? array_intersect_key($res, array_flip($specificTags)) : $res;
    }

    public function showAdvHome()
    {
        $host = 'congly.vn';
        $text = '<a href="http://' . $host . '/358/hoa-giai-doi-thoai-tai-toa-an/">
                <img title="" src="http://' . $host . '/adv/hoa_giai_anh.gif" alt="">
            </a>
            <a href="http://tv.congly.vn/phim-tai-lieu-toa-an-nhan-dan-xet-xu-nhung-vu-an-dien-hinh-tap-1-d6509.html">
                <img src="http://' . $host . '/adv/Phim-tai-lieu-TAND.gif" alt="">
            </a>
            <a target="_blank" href="https://toaan.gov.vn">
                <img src="http://' . $host . '/adv/tandtc.png" width="300px">
            </a>
             <a href="http://' . $host . '/phap-luat/nhan-tin/huong-dan-thu-tuc-nhan-tin-98458.html">
                <img src="http://' . $host . '/data/adv/2013/6/huong-dan-thu-tuc1-ds1-copy-ok.gif" alt="">
            </a>
            <a href="http://' . $host . '/phap-luat/nhan-tin/">
                <img title="" src="http://' . $host . '/data/adv/2013/6/Thongtincanbietok2.gif" alt="">
            </a>
			 <a href="https://portal.vietcombank.com.vn/news/Pages/home.aspx">
                <img title="" src="http://' . $host . '/adv/vietcombank.jpg" alt="">
            </a>
            <a href="https://www.bidv.com.vn/uudaithe/">
                <img title="" src="http://' . $host . '/adv/bidv_12_10.jpg" alt="">
            </a>
        
            <a target="_blank" href="https://thacogroup.vn">
                <img src="http://' . $host . '/adv/truonghai.gif" width="300px">
            </a>
            <a href="https://www.vietinbank.vn/vn/tin-tuc/Mien-phi-6-thang-duy-tri-VietinBank-iPay-cho-khach-hang-dang-ky-moi-20180321151131.html" target="_blank">
                <img style="padding:0px 0px 0px 0px;" width="300" src="http://' . $host . '/adv/viettinban166.jpg" alt="">
           </a>
           <a target="_blank" href="http://novalandexpo.com.vn/?utm_source=congly&utm_medium=banner_300x300&utm_campaign=Expo">
                <img src="http://' . $host . '/adv/novalland123.gif" width="300px">
            </a>
            <a href="https://phuquoc-marina.vn/intercontinental-phu-quoc/" target="_blank">
                <img width="300" style="padding:5px 0px 10px 0px; display: none" src="http://' . $host . '/adv/home2home.gif" alt="Viet combank">
            </a>
             <a href="http://tpb.vn
             " target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://' . $host . '/adv/son_tung.jpg" alt="">
            </a>
            <a target="_blank">
                <img src="http://' . $host . '/adv/namabank.png" width="300px" />
            </a>
            <a href="https://q7riverside.com.vn/" target="_blank">
                <img style="padding:5px 0px 5px 0px;" width="300" src="http://' . $host . '/adv/canhoq7.gif"alt="">
            </a>
           <a style="display: none" href="https://www.bidv.com.vn/bidv/ca-nhan/khuyen-mai/uu-dai-khac/bidv-cho-cuoc-song-xanh" target="_blank">
                    <img style="padding:5px 0px 5px 0px;" width="290" src="http://' . $host . '/adv/bidvmoi.gif" alt="">
            </a>
             <a href="#" target="_blank" style="display: none">
                <img width="1" height="1" style="padding:5px 0px 0px 0px;" src="http://' . $host . '/adv/bia.jpg" alt="Bia">
            </a>';

        joc()->set_var('adv_home_0', $text);
        $adv_home = '<a target="_blank" href="https://asset.1cdn.vn/cly/upload/Bệnh viện đa khoa Xanh pôn.jpg">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x150 p Bệnh viện đa khoa Xanh pôn.jpg">
                                </a>
                                <a target="_blank" href="https://bimgroup.com/vi">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/Bim.jpg">
                                </a>
                                <a href="https://asset.1cdn.vn/cly/upload/Phòng VHTT thành phố Vũng Tàu-16092022.jpg">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x150px Phòng văn hóa và thông tin thành phố Vũng Tàu-16092022.png">
                                </a>
                                <a href="https://asset.1cdn.vn/cly/upload/dienlucbacgiang2709.jpg">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x150px Công ty điện lực Bắc Giang-16092022.jpg">
                                </a>
                                <a href="https://congly.vn/tandtc-phat-dong-chuong-trinh-hien-mau-nhan-dao-giot-hong-se-chia-nam-2022-203892.html">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/HienMau1.jpg">
                                </a>
                                <a target="_blank" href="https://asset.1cdn.vn/cly/upload/Công ty CP Xi măng Vicem Bút Sơn.jpg">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x150pxmbs.png" alt="banner">
                                </a>
                                <a>
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/PrintAd-A5ngang-01.jpg" alt="banner">
                                </a>
                                <a>
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/ICIC chot.jpg" alt="banner">
                                </a>
                                <a target="_blank" href="https://www.bidv.com.vn/">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/bidv_10-5-2022.png" alt="banner">
                                </a>
                                <a href="https://congly.vn/ban-thuong-vu-dang-uy-tandtc-gap-mat-chuc-mung-doan-tncs-ho-chi-minh-tandtc-205270.html">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/banner-6-5-2022.jpg" alt="banner">
                                </a>
                                <a target="_blank" href="http://www.vnpost.vn/">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/930 x 764_23042021.png" alt="banner">
                                </a>
                              
                                <a>
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/Van Huong.jpg">
                                </a>
                                <a target="_blank" href="https://asset.1cdn.vn/cly/upload/benhvienphusan23122021_link.jpg">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/benhvienphusan23122021.jpg">
                                </a>
                                <a target="_blank" href="https://asset.1cdn.vn/cly/upload/Công ty tuyn than Hòn Gai.jpg">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x150px Công ty tuyển than Hòn Gai - Vinacomin.jpg">
                                </a>
                                </a>
                                <img width="290px" src="https://asset.1cdn.vn/cly/upload/phongvanhoathongtintinhthainguyen.png">
                                <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x150pxtapdoandienlucvn.jpg">
                                                          
                            
                                <a target="_blank" href="https://www.baca-bank.vn/SitePages/website/tin-tuc.aspx?ttid=662&amp;lttid=18&amp;pb=False&amp;s=TT&amp;tt=BAC%20A%20BANK%20CH%C3%8DNH%20TH%E1%BB%A8C%20RA%20M%E1%BA%AET%20INTERNET%20BANKING%20&amp;%20MOBILE%20BANKING%20PHI%C3%8AN%20B%E1%BA%A2N%20M%E1%BB%9AI&amp;ltt=Tin%20S%E1%BA%A3n%20ph%E1%BA%A9m%20-%20D%E1%BB%8Bch%20v%E1%BB%A5">
                                    <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x250bac-a-bank.png" alt="banner">
                                </a>
                              
                                <a target="_blank" href="https://asset.1cdn.vn/cly/upload/Công ty Nhiệt điện Thái Bình.jpg">
                                 <img width="290px" src="https://asset.1cdn.vn/cly/upload/300x150px Công ty Nhiệt điện Thái Bình.jpg" alt="banner">
                                </a>';
        joc()->set_var('adv_home', $adv_home);
    }
}