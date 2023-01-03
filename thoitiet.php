<?php
function writeFile($data,$path=LOG_PATH,$mode = 'a+')
{
	if ( ! $fp = @fopen($path, $mode))
	{
		return false;
	}
	
	flock($fp, LOCK_EX);
	fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);
	return true;
}
try 
{
    $text = file_get_contents('http://www.nchmf.gov.vn/web/vi-VN/81/Default.aspx');
    
    $data = array();
    $text = str_replace('à', 'af', $text);
    $text = preg_replace('/(\s\s+)/', ' ',$text);
    
    //địa điểm
    $patern_1 = '/\<td width=\"20%\" align=left class=\"thoitiet_hientai rightline\"\>(.*?)\<\/td\>/';
    
    preg_match_all($patern_1, $text, $m);
    
    $length = count($m[1]);
    if($length > 0)
        for( $i = 0 ; $i < $length ; $i++ )    
            $data[$i]["location"] = str_replace('af', 'à', trim(strip_tags($m[1][$i])));
            
    //anh thoi tiet
    $patern_2 = '/\<td width=\"30%\" align=\"center\" class=\"thoitiet_hientai rightline\"\>(.*?)\<\/td\>/';
    preg_match_all($patern_2, $text, $m2);
    
    $length = count($m2[1]);
    if($length > 0)
        for( $j = 0 ; $j < $length ; $j++ )   
        {
            $p = '/src=\"(.*?)\"/';
            preg_match_all($p, $m2[1][$j], $tmp );
            $arr = explode("/", $tmp[1][0]);
            $image = file_get_contents($tmp[1][0]);
            if(!file_exists('tmp/image_weather/'.$arr[count($arr) -1]))
                file_put_contents('tmp/image_weather/'.$arr[count($arr) -1], $image);
            $src = 'tmp/image_weather/'.$arr[count($arr) -1];
            
            $data[$j]['image'] = array("src" => $src, "text" => str_replace('af', 'à', trim(strip_tags($m2[1][$j]))));
        }
            
    //nhiet do
    $patern_3 = '/\<td width=\"10%\" align=\"center\" class=\"thoitiet_hientai rightline\"\>\<strong\>(.*?)\<sup\>/';
    preg_match_all($patern_3, $text, $m3);
    
    $length = count($m3[1]);
    if($length > 0)
        for( $i = 0 ; $i < $length ; $i++ )    
            $data[$i]["temperature"] = trim(strip_tags($m3[1][$i]));
    //do am
    $patern_4 = '/\<td width=\"15%\" align=\"center\" class=\"thoitiet_hientai rightline\"\>(.*?)\<\/td\>/';
    preg_match_all($patern_4, $text, $m4);
    $length = count($m4[1]);
    if($length > 0)
        for( $i = 0 ; $i < $length ; $i++ )    
            $data[$i]["wet"] = trim(strip_tags($m4[1][$i]));
    //note
    $patern_5 = '/\<td width=\"30%\" align=\"left\" class=\"thoitiet_hientai\" style=\"Display:Yes\"\>(.*?)\<\/td\>/';
    preg_match_all($patern_5, $text, $m5);
    $length = count($m5[1]);
    if($length > 0)
        for( $i = 0 ; $i < $length ; $i++ )    
            $data[$i]["quote"] = trim(strip_tags($m5[1][$i]));
    
	
	var_dump(writeFile(json_encode($data),'tmp/thoitiet.txt','w+'));
	
}
catch (Exception $ex)
{
    writeFile('','tmp/thoitiet.txt','w+');
}
?>