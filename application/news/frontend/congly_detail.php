<?php
//ini_set('display_errors', 1);
require_once 'application/news/frontend/includes/frontend.news.php';
require_once 'application/news/includes/comment_model.php';
require_once 'application/user/includes/comment.php';
include_once UTILS_PATH . 'convert.php';

class Congly_Detail
{

    function __construct()
    {

    }

    function index()
    {

        joc()->set_file('Detail', Module::pathTemplate() . "frontend" . DS . "detail.htm");
        global $list_category;
        global $info_news;
        $id = SystemIO::get('id', "int", 0);
        joc()->set_var('id', $id);
        $frontendObj = new FrontendNews();
        $detail = $info_news;
        $cate_id_current = $detail['cate_id'];
        $cate_id = $detail['cate_id'];
        if ($detail['time_public'] > time() || (int)$detail['id'] == 0 || $detail['time_public'] == 0) {
            @header('Location: https://congly.vn/');
            die;
        }

        if ($this->removeNewsId($id)) {
            @header('Location: https://congly.vn/');
            die;
        }
        if (!$detail['type_post']) {
            //@header('Location: https://congly.vn/');
            //die;
        }
        /* Navigation */
        $cate_id_parent = 0;
        if ($list_category[$cate_id]['cate_id1'] == 0) {
            joc()->set_var('cate_parent_name', $list_category[$cate_id]['name']);
            joc()->set_var('cate_parent_href', $list_category[$cate_id]['alias']);
            joc()->set_var('cate_child_name', '');
            joc()->set_var('cate_footer_name', $list_category[$cate_id]['name']);
            joc()->set_var('cate_footer_href', $list_category[$cate_id]['alias']);
            $cate_id_parent = $cate_id;
            $display_cate_child = 'none';
            joc()->set_var('class_cate_end_1', 'breadcrumble_title_end');
            joc()->set_var('class_cate_end_2', '');
        } else {
            $cate_id_parent = $list_category[$cate_id]['cate_id1'];
            joc()->set_var('cate_parent_name', $list_category[$cate_id_parent]['name']);
            joc()->set_var('cate_parent_href', $list_category[$cate_id_parent]['alias']);
            joc()->set_var('cate_child_name', $list_category[$cate_id]['name']);
            joc()->set_var('cate_child_href', $list_category[$cate_id]['alias']);
            joc()->set_var('cate_footer_name', $list_category[$cate_id]['name']);
            joc()->set_var('cate_footer_href', $list_category[$cate_id]['alias']);
            joc()->set_var('class_cate_end_2', 'breadcrumble_title_end');
            joc()->set_var('class_cate_end_1', '');
            $display_cate_child = 'block';
        }

        joc()->set_var('sdt_24', 'M???i th??ng tin chia s???, ph???n h???i xin g???i v??? h??m th?? <a href="mailto:conglydientu@congly.vn">conglydientu@congly.vn, Hotline 091.2532.315 - 096.1101.678</a>');
        if ($cate_id == '269' || $cate_id == '338' || $list_category[$cate_id]['cate_id1'] == '269' || $list_category[$cate_id]['cate_id1'] == '338') {
            $qc_geniee = '';
        } else {
            $qc_geniee = '';
        }
        joc()->set_var('qc_geniee', $qc_geniee);
        joc()->set_var('display_cate_child', $display_cate_child);
        joc()->set_var('href_rss', $list_category[$cate_id_parent]['alias'] . '.rss');
        /*Set Cate mobile*/
        joc()->set_block('Detail', 'Cate', 'Cate');
        $text_cate = '';
        foreach ($list_category as $cate) {
            if ($cate['cate_id1'] == $cate_id_parent && $cate['property'] & 1) {
                joc()->set_var('cate_name', $cate['name']);
                joc()->set_var('cate_href', $cate['alias']);
                $text_cate .= joc()->output('Cate');
            }

        }
        joc()->set_var('Cate', $text_cate);
        $seo_info = $frontendObj->readSeo($info_news['id']);
        if ((int)$seo_info['id']) {
            Page::setHeader($seo_info['title_seo'], $seo_info['keyword_seo'], $seo_info['description_seo']);
        } else {
            Page::setHeader($detail['title'], $detail['description'], $detail['description']);
        }
        /*Set meta */
        Page::setMeta(
            '<meta property="og:title" content="' . str_replace(array('"', '', '\\', '/', ')', '('), array('', '', '', '', '', ''), $detail["title"]) . '" />
        ');
        Page::setMeta('
            <meta property="og:description" content="' . htmlspecialchars(str_replace(array('"', '', '\\', '/'), array('', '', '', ''), $detail["description"])) . '" />
		');
        Page::setMeta('
            <meta itemprop="datePublished" content="' . date('D d-m-Y H:i:s', $detail['time_public']) . '" />
        ');
        Page::setMeta('
            <meta name="news_keywords" content="' . trim(trim($detail['tag']), ',') . '" />
        ');
        Page::setMeta('
            <meta property="og:url" content="' . trim(ROOT_URL, '/') . Url::Link(array("id" => $id, "title" => $detail['title'], 'cate_alias' => $list_category[$detail['cate_id']]['alias']), "news", "congly_detail") . '" />
        ');
        Page::setMeta('<meta  property="og:image" content="https://congly.vn/data/news/' . date('Y/n/j/', $detail['time_created']) . $detail['img1'] . '" id="idOgImg"/>');


        /* Set data news*/
        joc()->set_var('title', $detail['title']);
        joc()->set_var('time_public', date('d/n/Y H:i', $detail['time_public']) . ' UTC+7');
        if ($detail['type_post'] == 3 or $detail['type_post'] == 2 or $detail['type_post'] == 5 or $detail['type_post'] == 1 or $detail['type_post'] == 6)
            $detail['description'] = '(C??ng l??) - ' . $detail['description'];

        joc()->set_var('description', $detail['description']);
        joc()->set_var('description_html', htmlspecialchars($detail['description']));

        joc()->set_var('img', IMG::show($detail));
        joc()->set_var('time_public_shema', date('D d-m-Y H:i:s', $detail['time_public']));

        $detail['content'] = str_replace(array('font-size', 'font-family', 'alt=""'), array('', '', 'alt="' . htmlspecialchars($detail['title']) . '" title="' . htmlspecialchars($detail['title']) . '"'), $this->showContent($detail['content']));
        /*Is mobile*/
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            $detail['content'] = $this->showImgContent($detail['content']);
        }

        joc()->set_var('content', $frontendObj->showContent(
            str_replace('src="data/news', 'src="https://'.$_SERVER['HTTP_HOST'].'/data/news',
                $detail['content']
            )
        )
        );
        joc()->set_var('link_detail', trim(ROOT_URL, '/') . Url::Link(array("id" => $id, "title" => $detail['title'], 'cate_id' => $detail['cate_id'], 'cate_alias' => $list_category[$detail['cate_id']]['alias']), "news", "congly_detail"));
        Page::setMeta('<link rel="canonical" href="' . trim(ROOT_URL, '/') . Url::Link(array("id" => $id, "title" => $detail['title'], 'cate_id' => $detail['cate_id'], 'cate_alias' => $list_category[$detail['cate_id']]['alias']), "news", "congly_detail") . '" />');
        joc()->set_var('author', ($detail['author'] != '' ? $detail['author'] : '') . ($detail['origin'] != '' ? 'Theo ' . $detail['origin'] : ''));
        if ($detail['type_post'] == 4) {
            joc()->set_var('author', $detail['author'] . '(TH)');
        }


        /*Set search Google*/
        Page::setMeta('
        <script data-schema="Organization" type="application/ld+json">
            {"name":"Tin nhanh Congly - ?????c b??o, tin t???c online 24h ","url":"https://congly.vn/","logo":"https://congly.vn/webskins/skins/news/images/logo-congly.png","alternateName" : "Th??ng tin nhanh & m???i nh???t ???????c c???p nh???t h??ng gi???. Tin t???c Vi???t Nam & th??? gi???i v??? x?? h???i, kinh doanh, ph??p lu???t, khoa h???c, c??ng ngh???, s???c kho???, ?????i s???ng, v??n h??a, rao v???t, t??m s???...","sameAs":["https://www.facebook.com/congly.vn/?fref=ts/"],"@type":"Organization","@context":"https://schema.org"}
        </script>
        <script type="application/ld+json">
        {
            "@context"	: "https://schema.org",
            "@type"		: "BreadcrumbList",
            "itemListElement": [
                {
                    "@type"	: "ListItem",
                    "position"	: 1,
                    "item"	: {
                        "@id"	: "https://congly.vn/",
                        "name"	: "Trang ch???"
                    }
                },
                {
                    "@type": "ListItem",
                    "position"	: 2,
                    "item": {
                        "@id": "' . $list_category[$cate_id_parent]['alias'] . '",
                        "name": "' . $list_category[$cate_id_parent]['name'] . '"
                    }
                }
            ]
        }
        </script>'
        );
        /* Set poll */
        joc()->set_var('show_poll', $this->showPoll($detail, $frontendObj));
        /* Set s??? ki???n */
        if ($detail['topic_id']) {
            $list_topic = $frontendObj->getNews('topic', 'id,name,title', 'id=' . $detail['topic_id'], 'id DESC', 1);
            $topic = current($list_topic);
            $tp = '<span>S??? ki???n:</span> <a class="title4" href="' . Url::Link(array('topic_id' => $topic['id'], 'name' => $topic['name']), 'news', 'topic') . '">' . $topic['name'] . '</a>';
            joc()->set_var('topic_name', $tp);
        } else {
            joc()->set_var('topic_name', '');
        }

        /* Set tin li??n quan */
        $relate = array();
        if ($detail['relate'] != '') {
            $relate = $frontendObj->getNews("store", "id,title,cate_id,time_public", "id IN(" . rtrim($detail['relate'], ",") . ")", "time_public DESC", "0,5", "id", true);
        }

        $html_r = '';
        if (count($relate) > 0) {
            foreach ($relate as $r) {
                if ($list_category[$r['cate_id']]['alias'] != "") {
                    $html_r .= '<li><a class="title5" href="' . Url::link_detail($r, $list_category) . '" title="' . htmlspecialchars($r['title']) . '">' . $r['title'] . '</a></li>';
                }
            }
        }
        if ($html_r != "") {
            joc()->set_var('related', '<div class="news-related"><ul>' . $html_r . '</ul></div>');
        } else {
            joc()->set_var('related', "");
        }
        joc()->set_block('Detail', 'Tag', 'Tag');
        $tag = '';
        if ($detail['tag']) {
            $arr_tag_temp = explode('[]', $detail['tag']);
            $arr_tag = explode(',', $arr_tag_temp['0']);
            for ($k = 0; $k < count($arr_tag); ++$k) {
                joc()->set_var('tag_name', $arr_tag[$k]);
                joc()->set_var('tag_link', Url::link(array('tag' => 1, 'q' => str_replace(' ', '_', Convert::convertLinkTitle(trim($arr_tag[$k])))), 'news', 'search'));
                $tag .= joc()->output('Tag');
            }
        }
        joc()->set_var('Tag', trim($tag, '&nbsp;/&nbsp;'));

        $file = '';
        if (trim($detail['file'], ',')) {
            $arr_file = explode(',', trim($detail['file'], ','));
            for ($i = 0; $i < count($arr_file); ++$i) {
                $file .= '<a href="data/file/' . $arr_file[$i] . '">T???i file: ' . $arr_file[$i] . '</a><br>';
            }
        }

        joc()->set_var('file', $file);
        //C??c tin m???i kh??c danh m???c
        $detail['cate_id'] = isset($detail['cate_id']) ? $detail['cate_id'] : 1;
        $newsest = $frontendObj->getNews("store", "id,title,cate_id,img1,time_public,time_created", "cate_id != '269' AND cate_path NOT LIKE '%,338,%' AND cate_id!=" . (int)$detail['cate_id'], "time_public DESC", "0,8", "id", true);
        joc()->set_block('Detail', 'Other', 'Other');
        $html_newest = '';
        if (count($newsest) > 0) {
            foreach ($newsest as $key => $n) {

                $link = Url::link_detail($n, $list_category);
                $img = IMG::thumb($n, 'cnn_150x100');
                joc()->set_var('other_link', $link);
                joc()->set_var('other_title', $n['title']);
                joc()->set_var('other_html_title', htmlspecialchars($n['title']));
                joc()->set_var('other_img', $img);
                joc()->set_var('other_date', date('d/n', $n['time_public']));
                $html_newest .= joc()->output('Other');
            }
        }
        joc()->set_var('Other', $html_newest);
        //C??c tin m???i c??ng danh m???c
        $detail['cate_id'] = isset($detail['cate_id']) ? $detail['cate_id'] : 1;
        $newsest_same = $frontendObj->getNews("store", "id,title,cate_id,img1,time_public,time_created", "id != {$detail['id']} AND time_public < " . $detail['time_public'] . " AND cate_id=" . (int)$detail['cate_id'], "time_public DESC", "0,8", "id", true);
        joc()->set_block('Detail', 'Same', 'Same');
        $html_newest = '';
        if (count($newsest) > 0) {
            foreach ($newsest_same as $key => $n) {

                $link = Url::link_detail($n, $list_category);
                $img = IMG::thumb($n, 'cnn_150x100');
                joc()->set_var('same_link', $link);
                joc()->set_var('same_title', $n['title']);
                joc()->set_var('same_html_title', htmlspecialchars($n['title']));
                joc()->set_var('same_img', $img);
                joc()->set_var('same_date', date('d/n', $n['time_public']));
                $html_newest .= joc()->output('Same');
            }
        }
        joc()->set_var('Same', $html_newest);
        /* Li??n k???t danh m???c */
        $cate_change = array(
            '269' => array('273', '288'),
            '273' => array('269', '283'),
            '279' => array('288', '273'),
            '288' => array('314', '269'),
            '283' => array('293', '273'),
            '293' => array('283', '298'),
            '298' => array('288', '273'),
            '352' => array('349', '298'),
            '349' => array('283', '283'),
            '314' => array('307', '283'),
            '350' => array('314', '288'),
            '303' => array('307', '273'),
            '307' => array('303', '283'),
            '341' => array('348', '303'),
            '348' => array('341', '307'),
            '338' => array('359', '283'),
            '359' => array('338', '288'),
            '1' => array('288', '293')
        );

        if (array_key_exists($cate_id_parent, $cate_change)) {
            $cate_show = $cate_change[$cate_id_parent];
        } else {
            $cate_show = $cate_change['1'];
        }

        $list_news_cate1 = $frontendObj->getNews('store', 'id,title,time_public,cate_id,img1,time_created,is_video,is_img', 'cate_path LIKE "%,' . $cate_show['0'] . ',%" AND time_public > 0', 'time_public DESC', '0,6');
        $list_news_cate2 = $frontendObj->getNews('store', 'id,title,time_public,cate_id,img1,time_created,is_video,is_img', 'cate_path LIKE "%,' . $cate_show['1'] . ',%" AND time_public > 0', 'time_public DESC', '0,6');
        joc()->set_var('cate1_change_name', $list_category[$cate_show['0']]['name']);
        joc()->set_var('cate1_change_href', $list_category[$cate_show['0']]['alias']);
        $k = 1;
        foreach ($list_news_cate1 as $row) {
            joc()->set_var('title_cate1' . $k, $row['title']);
            joc()->set_var('html_title_cate1' . $k, htmlspecialchars($row['title']));
            joc()->set_var('href_cate1' . $k, Url::link_detail($row, $list_category));
            joc()->set_var('img_cate1' . $k, IMG::thumb($row, 'cnn_320x215'));
            ++$k;
        }

        joc()->set_var('cate2_change_name', $list_category[$cate_show['1']]['name']);
        joc()->set_var('cate2_change_href', $list_category[$cate_show['1']]['alias']);
        $k = 1;
        foreach ($list_news_cate2 as $row) {
            joc()->set_var('title_cate2' . $k, $row['title']);
            joc()->set_var('html_title_cate2' . $k, htmlspecialchars($row['title']));
            joc()->set_var('href_cate2' . $k, Url::link_detail($row, $list_category));
            joc()->set_var('img_cate2' . $k, IMG::thumb($row, 'cnn_320x215'));
            ++$k;
        }
        /**
         * BIDV
         */
        joc()->set_block('Detail', 'BIDV', 'BIDV');
        $dataBidv = $this->dataBidv();
        $html_bidv = '';
        foreach ($dataBidv as $b) {
            joc()->set_var('bidv_link', $b['href']);
            joc()->set_var('bidv_title', $b['title']);
            joc()->set_var('bidv_html_title', htmlspecialchars($b['title']));
            joc()->set_var('bidv_img', $b['image']);
            $html_bidv .= joc()->output('BIDV');
        }
        joc()->set_var('BIDV', $html_bidv);
        /* Box conglyxaohi.net.vn */
        joc()->set_block('Detail', 'Conglyxahoi', 'Conglyxahoi');
        $memcache = new Memcache;
        $memcache->addServer('localhost', 11211);
        $list_conglyxaoi = json_decode($memcache->get('conglyxahoinetvn'), true);
        $html_conglyxahoi = '';
        $k = 1;
        foreach ($list_conglyxaoi as $key => $n) {
            ++$k;
            $link = $n['href'];
            $img = $n['img'];
            joc()->set_var('conglyxahoi_link', $link);
            joc()->set_var('conglyxahoi_title', $n['title']);
            joc()->set_var('conglyxahoi_html_title', htmlspecialchars($n['title']));
            joc()->set_var('conglyxahoi_img', $img);
            joc()->set_var('conglyxahoi_date', date('d/n', $n['time_public']));
            $html_conglyxahoi .= joc()->output('Conglyxahoi');
            if ($k > 4) break;
        }
        joc()->set_var('Conglyxahoi', $html_conglyxahoi);

        /* Set Comment */
        joc()->set_block('Detail', 'Comment', 'Comment');
        joc()->set_block('Detail', 'Comment_a', 'Comment_a');
        $comment = new Comment();
        $comments = $comment->getList("nw_id = $id and status = 1 and (parent_id = 0 or parent_id is null)", 'time_post desc', 100);
        $total = $comment->getList("nw_id = $id and status = 1");
        $html_comment = '';
        if (count($comments)) {
            joc()->set_var('display', 'block');
            foreach ($comments as $c) {

                joc()->set_var('full_name', $c['full_name']);
                joc()->set_var('cm_id', $c['id']);
                joc()->set_var('number_like', (int)$c['number_like']);
                joc()->set_var('content_cm', strip_tags($c['content']));
                joc()->set_var('time_post', date('H:i, d/m/Y', $c['time_post']));
                $comments_a = $comment->getList("parent_id = " . $c['id'] . " and status = 1", 'time_post desc', 100);
                $html_comment_a = '';
                if (count($comments_a)) {
                    foreach ($comments_a as $cc) {
                        joc()->set_var('full_name_a', $cc['full_name']);
                        joc()->set_var('cm_id_a', $cc['id']);
                        joc()->set_var('number_like_a', (int)$cc['number_like']);
                        joc()->set_var('content_cm_a', strip_tags($cc['content']));
                        joc()->set_var('time_post_a', date('H:i, d/m/Y', $cc['time_post']));
                        $html_comment_a .= joc()->output('Comment_a');
                    }
                } else {
                    $html_comment_a = '';
                }
                joc()->set_var('html_comment_a', $html_comment_a);
                $html_comment .= joc()->output('Comment');
            }
        } else {
            joc()->set_var('display', 'none');
        }

        //global $TOTAL_ROWCOUNT;
        joc()->set_var('total', count($total));
        joc()->set_var('Comment', $html_comment);
        joc()->set_var('Comment_a', '');
        $html = joc()->output('Detail');
        joc()->reset_var();
        return $html;
    }

    function showContent($content)
    {
        $arr_cate = array(
            //'K&yacute; s??? ph&aacute;p ??&igrave;nh' => '<a href="phap-dinh/ky-su-phap-dinh">K?? s??? ph??p ????nh</a>',
            //'T&ograve;a tuy&ecirc;n &aacute;n' => '<a href="phap-dinh/sau-vanh-mong-ngua">T??a tuy??n ??n</a>',
            //'B&uacute;t k&yacute; ??i???u tra' => '<a href="phong-su/but-ky-dieu-tra">B??t k?? ??i???u tra</a>',
            //'Ghi ch&eacute;p' => '<a href="phong-su/ghi-chep">Ghi ch??p</a>',
            //'Th???i s???' => '<a href="thoi-su">Th???i s???</a>',
            //'?????i s???ng' => '<a href="xa-hoi/doi-song">?????i s???ng</a>',
            //'H??? s?? v??? &aacute;n' => '<a href="phap-luat/ho-so-vu-an">H??? s?? v??? ??n</a>',
            //'Doanh nghi???p' => '<a href="kinh-doanh/doanh-nghiep">Doanh nghi???p</a>',
            //'Tin nhanh' => '<a href="the-gioi/tin-nhanh">Tin nhanh</a>',
            //'B&oacute;ng ??&aacute;' => '<a href="the-thao/bong-da">B??ng ????</a>',
            //'Ho&agrave;n c???nh kh&oacute; kh??n' => '<a href="nhan-ai/hoan-canh-kho-khan">Ho??n c???nh kh?? kh??n</a>',
            //'70 n??m Truy???n th???ng TAND' => '<a href="hoat-dong-toa-an/70-nam-truyen-thong-tand">70 n??m truy???n th???ng tand</a>',
            //'Ph&aacute;p ??&igrave;nh' => '<a href="phap-dinh">Ph??p ????nh</a>',
            //'An ninh h&igrave;nh s???' => '<a href="an-ninh-hinh-su">An ninh h??nh s???</a>',
            //'V??? &aacute;n n???i ti???ng' => '<a href="the-gioi/vu-an-noi-tieng">V??? ??n n???i ti???ng</a>',
            //'Th???i trang - L&agrave;m ?????p' => '<a href="thoi-trang-lam-dep">Th???i trang - l??m ?????p</a>',
            //'Tennis' => '<a href="the-thao/tennis">Tennis</a>',
            //'Ch???ng kho&aacute;n' => '<a href="kinh-doanh/chung-khoan">Ch???ng kho??n</a>',
            //'Nh&agrave; h???o t&acirc;m' => '<a href="nhan-ai/nha-hao-tam">Nh?? h???o t??m</a>',
            //'Ti&ecirc;u ??i???m' => '<a href="hoat-dong-toa-an/tieu-diem">Ti??u ??i???m</a>',
            //'Gi&aacute;o d???c' => '<a href="xa-hoi/giao-duc">Gi??o d???c</a>',
            //'Chuy???n l???' => '<a href="the-gioi/chuyen-la">Chuy???n l???</a>',
            //'Golf ' => '<a href="the-thao/golf"> Golf </a>',
            //'Nh???c' => '<a href="giai-tri/nhac">Nh???c</a>',
            //'Nh???c,Ng&acirc;n h&agrave;ng' => '<a href="kinh-doanh/ngan-hang">Ng??n h??ng</a>',
            //'Tin 141' => '<a href="tin-141">Tin 141</a>',
            //' tin ' => '<a href="/tin-tuc/"> tin </a>',
            //' Tin ' => '<a href="/tin-tuc/"> Tin </a>',
            'tin t???c' => '<a href="/tin-tuc/">tin t???c</a>',
            //'Trao ?????i' => '<a href="doanh-nghiep/trao-doi">Trao ?????i</a>',
            //'Ch????ng tr&igrave;nh-S??? ki???n' => '<a href="nhan-ai/chuong-trinh-su-kien">Ch????ng tr??nh-s??? ki???n</a>',
            //'Nghi???p v???' => '<a href="hoat-dong-toa-an/nghiep-vu">Nghi???p v???</a>',
            //'S???c kh???e ' => '<a href="xa-hoi/suc-khoe">S???c kh???e </a>',
            //'Ph&aacute;p lu???t' => '<a href="phap-luat">Ph??p lu???t</a>',
            //'C???u l&ocirc;ng' => '<a href="the-thao/cau-long">C???u l??ng</a>',
            //'Phim' => '<a href="giai-tri/phim">phim</a>',
            //'Gi&aacute; v&agrave;ng' => '<a href="kinh-doanh/gia-vang">Gi?? v??ng</a>',
            //'T?? v???n' => '<a href="doanh-nghiep/tu-van">T?? v???n</a>',
            //'Phong tr&agrave;o thi ??ua' => '<a href="hoat-dong-toa-an/phong-trao-thi-dua">Phong tr??o thi ??ua</a>',
            //'X&atilde; h???i' => '<a href="xa-hoi">X?? h???i</a>',
            //'Vi???c l&agrave;m' => '<a href="xa-hoi/viec-lam">Vi???c l??m</a>',
            //'Nh???n tin' => '<a href="phap-luat/nhan-tin">Nh???n tin</a>',
            //'B???t ?????ng s???n' => '<a href="kinh-doanh/bat-dong-san">B???t ?????ng s???n</a>',
            //'Hoa h???u' => '<a href="giai-tri/hoa-hau">Hoa h???u</a>',
            //'H???i nh???p' => '<a href="doanh-nghiep/hoi-nhap">H???i nh???p</a>',
            //'C???i c&aacute;ch t?? ph&aacute;p' => '<a href="hoat-dong-toa-an/cai-cach-tu-phap">C???i c??ch t?? ph??p</a>',
            //'Du l???ch' => '<a href="giai-tri/du-lich">Du l???ch</a>',
            //'C??c m??n kh??c' => '<a href="the-thao/cac-mon-khac">C??c m??n kh??c</a>',
            //'Nh???t k?? v??nh m??ng ng???a' => '<a href="phap-dinh/nhat-ky-vanh-mong-ngua">Nh???t k?? v??nh m??ng ng???a</a>',
            //'Gia ??&igrave;nh' => '<a href="xa-hoi/gia-dinh">Gia ????nh</a>',
            //'Giao th&ocirc;ng' => '<a href="xa-hoi/giao-thong">Giao th??ng</a>',
            //'Gi???i tr&iacute;' => '<a href="giai-tri">Gi???i tr??</a>',
            //'Nh???p c???u c??ng l??' => '<a href="nhip-cau-cong-ly">Nh???p c???u c??ng l??</a>',
            //'B???o v??? ng?????i ti&ecirc;u d&ugrave;ng' => '<a href="bao-ve-nguoi-tieu-dung">B???o v??? ng?????i ti??u d??ng</a>',
            //'Th??? thao' => '<a href="the-thao">Th??? thao</a>',
            //'Kinh doanh' => '<a href="kinh-doanh">Kinh doanh</a>',
            //'Th??? gi???i' => '<a href="the-gioi">Th??? gi???i</a>',
            //'C&ocirc;ng ngh???' => '<a href="cong-nghe">C??ng ngh???</a>',
            //'Gi???i tr???' => '<a href="gioi-tre">Gi???i tr???</a>',
            //'Th?? gi??n' => '<a href="thu-gian">Th?? gi??n</a>',
            //'Ho???t ?????ng T??a ??n' => '<a href="hoat-dong-nganh">Ho???t ?????ng t??a ??n</a>',
            //'G??c nh??n' => '<a href="goc-nhin">G??c nh??n</a>',
            //'Video' => '<a href="video">Video</a>',
            //'Ng&acirc;n h&agrave;ng' => '<a href="kinh-doanh/ngan-hang">Ng??n h??ng</a>',
            //'C&ocirc;ng l&yacute;' => '<a href="http://congly.com.vn">C??ng l??</a>',
            //'K&yacute; s??? ph&aacute;p ??&igrave;nh' => '<a href="phap-dinh/ky-su-phap-dinh">k?? s??? ph??p ????nh</a>',
            't&ograve;a tuy&ecirc;n &aacute;n' => '<a href="phap-dinh/sau-vanh-mong-ngua">t??a tuy??n ??n</a>',
            'b&uacute;t k&yacute; ??i???u tra' => '<a href="phong-su/but-ky-dieu-tra">b??t k?? ??i???u tra</a>',
            'ghi ch&eacute;p' => '<a href="phong-su/ghi-chep">ghi ch??p</a>',
            'th???i s???' => '<a href="thoi-su">th???i s???</a>',
            '?????i s???ng' => '<a href="xa-hoi/doi-song">?????i s???ng</a>',
            'h??? s?? v??? &aacute;n' => '<a href="phap-luat/ho-so-vu-an">h??? s?? v??? ??n</a>',
            'doanh nghi???p' => '<a href="kinh-doanh/doanh-nghiep">doanh nghi???p</a>',
            'tin nhanh' => '<a href="the-gioi/tin-nhanh">tin nhanh</a>',
            'b&oacute;ng ??&aacute;' => '<a href="the-thao/bong-da">b??ng ????</a>',
            'ho&agrave;n c???nh kh&oacute; kh??n' => '<a href="nhan-ai/hoan-canh-kho-khan">ho??n c???nh kh?? kh??n</a>',
            '70 n??m Truy???n th???ng TAND' => '<a href="hoat-dong-toa-an/70-nam-truyen-thong-tand">70 n??m truy???n th???ng tand</a>',
            'ph&aacute;p ??&igrave;nh' => '<a href="phap-dinh">ph??p ????nh</a>',
            'an ninh h&igrave;nh s???' => '<a href="an-ninh-hinh-su">an ninh h??nh s???</a>',
            'v??? &aacute;n n???i ti???ng' => '<a href="the-gioi/vu-an-noi-tieng">v??? ??n n???i ti???ng</a>',
            'th???i trang - L&agrave;m ?????p' => '<a href="thoi-trang-lam-dep">th???i trang - l??m ?????p</a>',
            'tennis ' => '<a href="the-thao/tennis">tennis</a>',
            'ch???ng kho&aacute;n' => '<a href="kinh-doanh/chung-khoan">ch???ng kho??n</a>',
            'nh&agrave; h???o t&acirc;m' => '<a href="nhan-ai/nha-hao-tam">nh?? h???o t??m</a>',
            'ti&ecirc;u ??i???m' => '<a href="hoat-dong-toa-an/tieu-diem">ti??u ??i???m</a>',
            'gi&aacute;o d???c' => '<a href="xa-hoi/giao-duc">gi??o d???c</a>',
            'chuy???n l???' => '<a href="the-gioi/chuyen-la">chuy???n l???</a>',
            'golf ' => '<a href="the-thao/golf">golf</a>',
            //'nh???c' => '<a href="giai-tri/nhac">nh???c</a>',
            'nh???c,Ng&acirc;n h&agrave;ng' => '<a href="kinh-doanh/ngan-hang">ng??n h??ng</a>',
            'tin 141' => '<a href="tin-141">tin 141</a>',
            //'trao ?????i' => '<a href="doanh-nghiep/trao-doi">trao ?????i</a>',
            'ch????ng tr&igrave;nh-S??? ki???n' => '<a href="nhan-ai/chuong-trinh-su-kien">ch????ng tr??nh-s??? ki???n</a>',
            'nghi???p v???' => '<a href="hoat-dong-toa-an/nghiep-vu">nghi???p v???</a>',
            's???c kh???e ' => '<a href="xa-hoi/suc-khoe">s???c kh???e </a>',
            'ph&aacute;p lu???t' => '<a href="phap-luat">ph??p lu???t</a>',
            'c???u l&ocirc;ng' => '<a href="the-thao/cau-long">c???u l??ng</a>',
            //'phim ' => '<a href="giai-tri/phim">phim </a>',
            'gi&aacute; v&agrave;ng' => '<a href="kinh-doanh/gia-vang">gi?? v??ng</a>',
            //'t?? v???n' => '<a href="doanh-nghiep/tu-van">t?? v???n</a>',
            'phong tr&agrave;o thi ??ua' => '<a href="hoat-dong-toa-an/phong-trao-thi-dua">phong tr??o thi ??ua</a>',
            //'x&atilde; h???i' => '<a href="xa-hoi">x?? h???i</a>',
            'vi???c l&agrave;m' => '<a href="xa-hoi/viec-lam">vi???c l??m</a>',
            'nh???n tin' => '<a href="phap-luat/nhan-tin">nh???n tin</a>',
            'b???t ?????ng s???n' => '<a href="kinh-doanh/bat-dong-san">b???t ?????ng s???n</a>',
            'hoa h???u' => '<a href="giai-tri/hoa-hau">hoa h???u</a>',
            //'h???i nh???p' => '<a href="doanh-nghiep/hoi-nhap">h???i nh???p</a>',
            'c???i c&aacute;ch t?? ph&aacute;p' => '<a href="hoat-dong-toa-an/cai-cach-tu-phap">c???i c??ch t?? ph??p</a>',
            'du l???ch' => '<a href="giai-tri/du-lich">du l???ch</a>',
            //'c??c m??n kh??c' => '<a href="the-thao/cac-mon-khac">c??c m??n kh??c</a>',
            'nh???t k?? v??nh m??ng ng???a' => '<a href="phap-dinh/nhat-ky-vanh-mong-ngua">nh???t k?? v??nh m??ng ng???a</a>',
            'gia ??&igrave;nh' => '<a href="xa-hoi/gia-dinh">gia ????nh</a>',
            'giao th&ocirc;ng' => '<a href="xa-hoi/giao-thong">giao th??ng</a>',
            'gi???i tr&iacute;' => '<a href="giai-tri">gi???i tr??</a>',
            'nh???p c???u c??ng l??' => '<a href="nhip-cau-cong-ly">nh???p c???u c??ng l??</a>',
            'b???o v??? ng?????i ti&ecirc;u d&ugrave;ng' => '<a href="bao-ve-nguoi-tieu-dung">b???o v??? ng?????i ti??u d??ng</a>',
            'th??? thao' => '<a href="the-thao">th??? thao</a>',
            'kinh doanh' => '<a href="kinh-doanh">kinh doanh</a>',
            'th??? gi???i' => '<a href="the-gioi">th??? gi???i</a>',
            'c&ocirc;ng ngh???' => '<a href="cong-nghe">c??ng ngh???</a>',
            'gi???i tr???' => '<a href="gioi-tre">gi???i tr???</a>',
            'th?? gi??n' => '<a href="thu-gian">th?? gi??n</a>',
            'ho???t ?????ng T??a ??n' => '<a href="hoat-dong-nganh">ho???t ?????ng t??a ??n</a>',
            'g??c nh??n' => '<a href="goc-nhin">g??c nh??n</a>',
            //'video' => '<a href="video">video</a>',
            'nh&acirc;n &aacute;i' => '<a href="nhan-ai">nh??n ??i</a>',
            'ng&acirc;n h&agrave;ng' => '<a href="kinh-doanh/ngan-hang">ng??n h??ng</a>',
            'b&aacute;o' => '<a href="/">b&aacute;o</a>',
            //'B&aacute;o' => '<a href="/">B&aacute;o</a>',
        );

        foreach ($arr_cate as $key => $val) {
            $content = $this->str_replace_first($key, $val, $content);
        }

        return $content;
    }

    function dataBidv()
    {
        $arrData = array(
            array(
                'title' => '',
                'href' => '',
                'image' => ''
            ),
            array(
                'title' => '',
                'href' => '',
                'image' => ''
            ),
            array(
                'title' => '',
                'href' => '',
                'image' => ''
            ),
            array(
                'title' => '',
                'href' => '',
                'image' => ''
            )
        );
        if ($this->isMobile()) {
            //return array();
        }
        return $arrData;
    }

    function isMobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            return true;
        }
        return false;

    }

    function str_replace_first($from, $to, $subject)
    {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $subject, 1);
    }

    function showPoll($detail, $frontendObj)
    {
        $html_poll = '';
        $poll_id = $detail["poll_id"];
        $poll_cookie = isset($_COOKIE['poll']) ? $_COOKIE['poll'] : '0,';
        if ($poll_cookie == '0,') {
            $poll_cookie = isset($_SESSION['poll']) ? $_SESSION['poll'] : '0,';
        }
        //neu chua chua vote thi hien thi poll
        if ($poll_id > 0) {
            $polls = $frontendObj->getPoll($detail['poll_id']);
            $poll = $polls["poll"];
            $options = $polls["option"];
            if (strpos($poll_cookie, ",$poll_id,") === false) {
                $html_poll = '<div class="ct-poll" style="float:none;width:448px; margin:0 auto 20px;">';
                $html_poll .= ' <h4 class="poll_title">B??NH CH???N</h4><div class="ct-poll-content">
                    <h3>' . $poll["name"] . '</h3>
                   <ul>';
                if (count($options)) {
                    foreach ($options as $k => $op) {
                        if ($poll["type"] == 1)
                            $html_poll .= '<li><input class="poll_option" type="checkbox" value="' . $op["id"] . '" name="poll_option"> ' . $op["text"] . '</li>';
                        else
                            $html_poll .= '<li><input class="poll_option" type="radio" value="' . $op["id"] . '" name="poll_option"> ' . $op["text"] . '</li>';
                    }
                }
                $html_poll .= '</ul></div><div class="ct-poll-bottom">';

                $html_poll .= '<input type="button" value="B??nh ch???n" name="poll_voted" id="poll_voted" onclick="poll(' . $poll_id . ')" class="as"><input type="button" rel="' . $detail["poll_id"] . '" value="K???t qu???" name="poll_result" id="poll_result" onclick="poll_result(' . $poll_id . ')">
		    	</div></div>';
            }//show ket qua
            else {
                $html_poll .= '<div class="ct-poll" style="float:none;width:455px; margin:0 auto 20px;">';
                $html_poll .= '<table class="fb-table" width="100%"><tr><th colspan="2">K???t qu??? b??nh ch???n</th></tr><tr><td colspan="2" class="td-ask"><strong>' . $poll["name"] . '</strong> </td></tr>';
                if (count($options)) {
                    $total = 0;
                    foreach ($options as $op)
                        $total += $op["voted"];

                    $color = array("#FF3300", "#009900", "#0C186D", "#cccccc", "#ffee00", "#ff0088", "#000");
                    $tcolor = array("#000", "#000", "#fff", "#000", "#000", "#000", "#fff");

                    $i = 0;
                    foreach ($options as $op) {
                        $v = ($total > 0 ? (($op["voted"] / $total) * 100) : 0);
                        $html_poll .= '<tr><td>' . $op["text"] . '</td><td class="td-label"><p style="color:' . $tcolor[$i] . ';padding:2px 5px;margin-right:5px;width:' . $v . '%;background:' . $color[$i] . '"> ' . round($v) . '%</p></td></tr>';//<td>'.$op["voted"].' phi???u</td></tr>';
                        $i++;
                    }
                    //$html_poll .= '<tr><td colspan="2" class="td-end">T???ng c???ng: '.$total.' Phi???u </td></tr>';
                }
                $html_poll .= '</table>';
                $html_poll .= '</div>';
            }
        }
        return $html_poll;
    }

    function showImgContent($content, $time_created = 0)
    {
        if (substr_count($content, 'jwplayer')) {
            return $content;
        }
        $partern = '/src=\"([^\"]*)\"/';
        preg_match_all($partern, $content, $m);
        $images = $m[1];
        $leng = count($images);
        if ($leng > 0) {
            for ($i = 0; $i < $leng; $i++) {
                $p_height = '#height="(.*?)"#';
                $p_width = '#width="(.*?)"#';
                $content = preg_replace($p_height, "", $content);
                $content = preg_replace($p_width, "", $content);
                $p_width_style = "#width:(.*?)#";
                $p_height_style = "#height:(.*?)#";
                $content = preg_replace($p_width_style, "", $content);
                $content = preg_replace($p_height_style, "", $content);

            }
        }

        $content = str_replace('width', '', $content);
        $content = str_replace('height', '', $content);
        //$content = str_replace('http://congly.com.vn/data/', 'http://congly.vn/resize.320x-/data/', $content);
        //$content = str_replace('data/news/', 'resize.320x-/data/news/', $content);

        return $content;
    }

    public function removeNewsId($id)
    {
        $id = (int)$id;
        $arrayRemove = array(
            294882, 17802, 200618, 218276, 301766, 162584, 11868, 114128, 268422, 308276, 271672, 25732, 1634, 275180, 272078, 213022, 140156, 20920, 4207, 50091, 37195, 29727, 55467, 32690, 79322, 74858, 221094, 275346, 21357, 55664, 21130, 277026, 24403,
            21876, 106882, 1514, 24540, 1504, 195382, 21381, 11980, 17121, 12117, 39058, 211, 38687, 19420, 15181, 173076, 173078, 177918, 96434, 281518, 189134, 211170, 172514, 178990, 197358, 51754, 618, 262084, 258898
        );
        if (in_array($id, $arrayRemove)) {
            return true;
        }
        return false;
    }
}