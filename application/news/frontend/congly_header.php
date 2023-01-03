<?php
if (defined(IN_JOC)) {
    die("Direct access not allowed!");
}
require_once 'application/news/frontend/includes/class.xml.php';
require(APPLICATION_PATH . 'news' . DS . 'frontend' . DS . 'includes' . DS . 'frontend.news.php');

class Congly_Header
{

    public function index()
    {
        joc()->set_file('Header', Module::pathTemplate('news') . "frontend/header.htm");
        Page::registerFile('tinyscrollbar.css',
            'webskins' . DS . 'skins' . DS . 'news' . DS . 'css' . DS . 'tinyscrollbar.css', 'header', 'css');
        if (SystemIO::get('print')) {
            joc()->set_var('action_print', 'window.print();');
        } else {
            joc()->set_var('action_print', '');
        }
        joc()->set_var('uri', $_SERVER['REQUEST_URI']);
        $frontendObj = new FrontendNews();
        $page = SystemIO::get('page', 'def', 'congly_home');
        global $list_category;
        global $list_category_alias;
        global $info_news;
        $list_category = $frontendObj->getCategory();
        $list_category_alias = SystemIO::arrayToOption($list_category, 'id', 'alias');
        $id = SystemIO::get('id', 'int', 0);
        $cate_id = SystemIO::get('cate_id', 'int', 0);
        if ($id && $cate_id == 0) {
            $detail = $frontendObj->newsOne($id);
            $detail_content = $frontendObj->detail($id);
            $info_news = $detail;
            $info_news['content'] = $detail_content;
            $cate_id = $info_news['cate_id'];
        }

        $cate_current = '';

        if ($page == 'congly_home') {
            joc()->set_var('current_home', 'mn-active');
            joc()->set_var('show_menu', 'display:block');
            joc()->set_var('google_adsence', 'display:none');
            //joc()->set_var('logo',
            //  '<h1 style="margin:0px;padding:0px"><a href="/"><img src="webskins/skins/news/images/logo-congly.png" alt="Báo Công lý" /></a></h1>');
            joc()->set_var('logo', '<h1 style="margin:0px;padding:0px"><a href="/"><img src="adv/logo_tet2019.png" alt="Báo Công lý" /></a></h1>');
        } else {
            joc()->set_var('show_menu', 'display:none');
            joc()->set_var('current_home', '');
            joc()->set_var('google_adsence', 'display:block');
            joc()->set_var('logo', '<a href="/"><img src="adv/logo_tet2019.png" alt="Báo Công lý" /></a>');
            joc()->set_var('google_adsence', 'display:none');

        }

        $text_menu = '';
        $j = 1;

        foreach ($list_category as $row) {
            if ($row['property'] & 1 == 1) {
                if (($row['property'] & 32) && ($row['cate_id1'] == 0)) {// thuoc tin hien thi tren menu
                    if ($cate_current == $row['id']) {
                        $class = 'mn-active';
                    } else {
                        $class = '';
                    }

                    $href_parent = Url::link_cate($row);
                    $text_menu .= ' <li class="' . $class . '">';
                    $text_menu .= '<a href="' . $href_parent . '">' . $row['name'] . '</a><ul>';
                    $k = 0;
                    foreach ($list_category as $_temp) {
                        if ($_temp['property'] & 1) {
                            if (($_temp['cate_id1'] == $row['id']) && $_temp['cate_id2'] == 0 && ($_temp['property'] & 32)) {
                                $href_child = Url::link_cate($_temp);
                                $text_menu .= '<li><a href="' . $href_child . '">' . $_temp['name'] . '</a></li>';
                                ++$k;
                            }
                        }
                    }
                    $text_menu .= '</ul>';
                    $text_menu .= '</li>';
                    ++$j;
                }
            }
        }

        joc()->set_var('text_menu', $text_menu);
        $date = date('w', time());
        $array = array(2 => 'Hai', 3 => 'Ba', 4 => 'Tư', 5 => 'Năm', 6 => 'Sáu', 7 => 'Bảy');
        if ($date == '0') {
            $text_date = 'Chủ nhật, ' . date('d/n/Y', time());
        } else {
            $text_date = 'Thứ ' . ($array[$date + 1]) . ', ' . date('d/n/Y', time());
        }

        joc()->set_var('text_date', $text_date);
        joc()->set_var('root_url', ROOT_URL);
        joc()->set_var('page', $page);
        /**
         * show image of CA
         */

        $topic_id = SystemIO::get('topic_id', 'int', '0');
        if ($topic_id == 476) {
            joc()->set_var('img_chanhan', '<img src="https://congly.vn/chanhan/chanhannguyenhoabinh.jpg" />');
        } else {
            joc()->set_var('img_chanhan', '');
        }

        $html = joc()->output('Header');
        joc()->reset_var();
        return $html;
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
}
