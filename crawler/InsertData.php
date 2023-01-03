<?php
require_once 'application/news/backend/includes/backend.news.php';
require_once 'crawler/Crawler.php';

class InsertData
{
    protected $cateId;
    protected $userId;
    protected $crawler;
    protected $backendNews;

    public function __construct(Crawler $crawler, BackendNews $backendNews)
    {
        $this->crawler = $crawler;
        $this->backendNews = $backendNews;
    }


    public function insertData()
    {
        $arrData = $this->crawler->getDataCrawler();
        foreach ($arrData as $data) {
            $content = $data['content'];
            unset($data['content']);
            $id = $this->backendNews->insertData('store', $data);
            if ($id) {
                $this->backendNews->insertData('store_content', array('content' => $content, 'nw_id' => $id));
            }
            return;
        }

    }

    public function selectData(){
        $d = $this->backendNews->getNews('Article','*',null,null,10);
        foreach($d as $dd){
            echo $dd['Title'].'<br/>';
        }
    }
}