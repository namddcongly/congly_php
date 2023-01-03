<?php
/**
 * Created by PhpStorm.
 * User: namdd
 * Date: 9/26/2016
 * Time: 4:01 PM
 */
$data = array(
    'mobile' =>array('mobile'=>array(
        'title' => '"Xôn Xao" bài thuốc gia truyền chữa khỏi bệnh gout',
        'link'  => 'https://goo.gl/xnrrfP',
        'img1'   => 'http://congly.vn/adv/qc/11-1035.png',
    )),
    'pc' =>array('pc'=>array(
    'title' => 'Bà lang chữa khỏi bệnh tiểu đường bằng dược liệu núi rừng',
    'link'  => 'https://goo.gl/r7RPkP',
    'img1'   => 'http://congly.vn/adv/qc/BA LANG.png',
    )),
);
echo json_encode($data);