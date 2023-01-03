<?php

class IMG
{
    protected $host = 'congly.vn';
    protected $http = 'https';

    public function show($row, $type = 1)
    {
        if (substr_count($row['img1'], 'cly.1cdn.vn')) {
            return $row['img1'];
        }


        return 'https://congly.vn/resize.800x500/data/news/' . date('Y/n/j/', (int)$row['time_created']) . $row['img' . $type];
    }

    function thumb($row)
    {
        if (substr_count($row['img1'], 'cly.1cdn.vn')) {
            return $row['img1'];
        }

        if(!substr_count($row['img1'],'.jpg')){
            return 'https://' . $_SERVER['HTTP_HOST'] . '/data/news/' . date('Y/n/j', $row['time_created']) . '/' . $row['img1'];
        }

        return 'https://' . $_SERVER['HTTP_HOST'] . '/resize.460x300/data/news/' . date('Y/n/j', $row['time_created']) . '/' . $row['img1'];

    }
}