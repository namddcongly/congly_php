<?php

class Crawler
{
    protected $arrLink = array();
    protected $page = 1;
    protected $url;
    protected $href;
    protected $dataCrawler = array();
    protected $cateId;
    protected $userId;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function setCateId($cateId)
    {
        $this->cateId = $cateId;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getContentGetLink()
    {
        $this->content = file_get_contents($this->url);
        return $this;
    }

    public function getContentDetail()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->href,
            CURLOPT_USERAGENT => 'Congly',
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $this->contentGetDetail = curl_exec($curl);
        return $this;
    }

    public function getLink()
    {
        $content = substr($this->content, strpos($this->content, '<div class="l-main">'));
        $content = substr($content, 0, strpos($content, '<div class="c-paging">'));
        preg_match_all('/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU', $content, $m);

        $link = array_unique($m[1]);

        foreach ($link as $href) {
            if ($href == 'javascript:;' || !substr_count($href,'.html')) {
                continue;
            }
            $this->arrLink[] = $href;
        }

        return $this;
    }

    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    public function getTitle()
    {
        $title = '/<h1\s[^>]*class=\"c-detail-head__title\"[^>]*>(.*)<\/h1>/siU';
        preg_match($title, $this->contentGetDetail, $tt);
        if (isset($tt['1'])) return $tt['1'];
        return '';
    }

    public function getDesc()
    {
        $des = '/<h2\s[^>]*class=\"desc\"[^>]*>(.*)<\/h2>/siU';
        preg_match($des, $this->contentGetDetail, $de);
        if (isset($de['1'])) return $de['1'];
        return '';
    }

    public function getDetail()
    {
        $detail = '/<div\s[^>]*class=\"(content-main-normal|c-news-detail\sis-full)\"[^>]*>(.*)<\/div>/siU';
        preg_match($detail, $this->contentGetDetail, $dt);
        if (isset($dt['0'])) return $dt['0'];
        return '';
    }

    public function getAuthor()
    {
        $author = '/<span\s[^>]*class=\"c-detail-head__author\"[^>]*>(.*)<\/span>/siU';
        preg_match($author, $this->contentGetDetail, $au);
        if (isset($au['1'])) return $au['1'];
        return 'PV';
    }

    public function getDatePublish()
    {
        $date = '/<span\s[^>]*class=\"c-detail-head__time\"[^>]*>(.*)<\/span>/siU';
        preg_match($date, $this->contentGetDetail, $dt);
        if (isset($dt['1'])) return $dt['1'];
        return date('Y-m-d :h:i:s');
    }

    public function getImage()
    {
        $img = '/<img[^>]+src="([^">]+)"/';
        $content = substr($this->contentGetDetail, strpos($this->contentGetDetail, 'class="l-main"'));
        preg_match($img, $content, $img);
        if (isset($img['1'])) return $img['1'];
        return '';
    }

    public function getDataCrawler()
    {

        foreach ($this->arrLink as $href) {
            $this->setHref($href)->getContentDetail();

            $title = $this->getTitle();
            if ($title != '') {
                $this->dataCrawler[] = array(
                    'title' => $this->getTitle(),
                    'description' => $this->getDesc(),
                    'cate_id' => $this->cateId,
                    'cate_path' => ',' . $this->cateId . ',',
                    'user_id' => $this->userId,
                    'censor_id' => $this->userId,
                    'content' => $this->getDetail(),
                    'author' => $this->getAuthor(),
                    'time_created' => time(),
                    'img1' => $this->getImage(),
                    'time_public' => strtotime(str_replace('/', '-', $this->getDatePublish())),
                    'flg_crawler' => 1,
                );
            }

        }

        return $this->dataCrawler;
    }

}