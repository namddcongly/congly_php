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
        $frontendObj = new FrontendNews();
        $id = SystemIO::get('id', 'int', 0);
        if ($id > 0) {
            $detail = $frontendObj->newsOne($id);
            $content = $frontendObj->detail($id);
            $content = $frontendObj->showContent($content);
            $content = str_replace(array('width', 'height'), array('', ''), $content);
            Page::setHeader($detail['title'], $detail['tag'], $detail['description']);
            joc()->set_var('title', $detail['title']);
            joc()->set_var('src', IMG::show($detail));
            joc()->set_var('time_public_detail', date('d/n/Y H:i', $detail['time_public']));
            joc()->set_var('description', $detail['description']);
            joc()->set_var('detail', $content);
            joc()->set_var('author',
                ($detail['author'] != '' ? $detail['author'] : '') . ' ' . ($detail['origin'] != '' ? $detail['origin'] : ''));
        }
        $html = joc()->output('Detail');
        joc()->reset_var();
        return $html;
    }
}

?>