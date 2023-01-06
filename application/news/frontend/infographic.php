<?php
require_once(APPLICATION_PATH . 'news' . DS . 'frontend' . DS . 'includes' . DS . 'frontend.news.php');

class Infographic
{
    function __construct()
    {
    }

    function index()
    {
        joc()->set_file('Detail', Module::pathTemplate() . 'frontend' . DS . 'infographic.htm');
        global $list_category;
        $frontendObj = new FrontendNews();
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
        $id = SystemIO::get('id', 'int', 0);
        if ($id > 0) {
            $detail = $frontendObj->newsOne($id);
            $content = $frontendObj->detail($id);
            $content = $frontendObj->showContent($content);
            $content = str_replace(array('width', 'height'), array('', ''), $content);
            Page::setHeader($detail['title'], $detail['tag'], $detail['description']);
            joc()->set_var('title', $detail['title']);
            joc()->set_var('src',
                'https://congly.vn/data/news/' . date('Y/n/j/', (int)$detail['time_created']) . $detail['img1']);
            joc()->set_var('time_public_detail', date('d/n/Y H:i', $detail['time_public']));
            joc()->set_var('description', $detail['description']);
            joc()->set_var('detail', $content);
            joc()->set_var('author',
                ($detail['author'] != '' ? $detail['author'] : '') . ' ' . ($detail['origin'] != '' ? $detail['origin'] : ''));
        }

        /* Set tin liên quan */
        $relate = array();
        if ($detail['relate'] != '') {
            $relate = $frontendObj->getNews("store", "id,title,cate_id,time_public",
                "id IN(" . rtrim($detail['relate'], ",") . ")", "time_public DESC", "0,5", "id", true);
        }

        $html_r = '';
        if (count($relate) > 0) {
            foreach ($relate as $r) {
                if ($list_category[$r['cate_id']]['alias'] != "") {
                    $html_r .= '<li><a class="title5" href="' . Url::link_detail($r,
                            $list_category) . '" title="' . htmlspecialchars($r['title']) . '">' . $r['title'] . '</a></li>';
                }
            }
        }
        if ($html_r != "") {
            joc()->set_var('related', '<div class="news-related"><ul>' . $html_r . '</ul></div>');
        } else {
            joc()->set_var('related', '');
        }

        //Các tin mới khác danh mục
        $detail['cate_id'] = isset($detail['cate_id']) ? $detail['cate_id'] : 1;
        $newsest = $frontendObj->getNews("store", "id,title,cate_id,img1,time_public,time_created",
            "cate_id != '269' AND cate_path NOT LIKE '%,338,%' AND cate_id!=" . (int)$detail['cate_id'],
            "time_public DESC", "0,8", "id", true);
        joc()->set_block('Detail', 'Other', 'Other');
        $html_newest = '';
        if (count($newsest) > 0) {
            foreach ($newsest as $key => $n) {

                $link = Url::link_detail($n, $list_category);
                $img = IMG::thumb($n);
                joc()->set_var('other_link', $link);
                joc()->set_var('other_title', $n['title']);
                joc()->set_var('other_html_title', htmlspecialchars($n['title']));
                joc()->set_var('other_img', $img);
                joc()->set_var('other_date', date('d/n', $n['time_public']));
                $html_newest .= joc()->output('Other');
            }
        }
        joc()->set_var('Other', $html_newest);

        //Các tin mới cùng danh mục
        $detail['cate_id'] = isset($detail['cate_id']) ? $detail['cate_id'] : 1;
        $newsest_same = $frontendObj->getNews("store", "id,title,cate_id,img1,time_public,time_created",
            "id != {$detail['id']} AND time_public < " . $detail['time_public'] . " AND cate_id=" . (int)$detail['cate_id'],
            "time_public DESC", "0,8", "id", true);
        joc()->set_block('Detail', 'Same', 'Same');
        $html_newest = '';
        if (count($newsest) > 0) {
            foreach ($newsest_same as $key => $n) {

                $link = Url::link_detail($n, $list_category);
                $img = IMG::thumb($n);
                joc()->set_var('same_link', $link);
                joc()->set_var('same_title', $n['title']);
                joc()->set_var('same_html_title', htmlspecialchars($n['title']));
                joc()->set_var('same_img', $img);
                joc()->set_var('same_date', date('d/n', $n['time_public']));
                $html_newest .= joc()->output('Same');
            }
        }
        joc()->set_var('Same', $html_newest);

        $html = joc()->output('Detail');
        joc()->reset_var();
        return $html;
    }
}

?>