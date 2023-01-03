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
@file_get_contents('http://congly.com.vn/giavang.php');
try
{
    $text = file_get_contents('http://hn.24h.com.vn/ttcb/tygia/tygia.php');
    //echo $text;
    $data = array();
    $text = preg_replace('/(\s\s+)/', ' ',$text);
    //tên ngoại tệ
    $patern_1 = '/\<table (.*)\>(.*?)\<\/table\>/';
    preg_match_all($patern_1, $text, $m);


	$data =  $m['0']['0'];
	//print_r($m);
	$p = '/\<span (.*?)\>(.*?)\<\/span\>/';
	preg_match_all($p, $data, $m);
	$data_exchange = $m['2'];
	array_shift($data_exchange);
	array_shift($data_exchange);
	$_arrray = array();
	for($i = 0;$i < count($data_exchange);$i = $i+4)
	{
		$j = $i;
		$_arrray[$data_exchange[$j]][]=$data_exchange[$j];
		$_arrray[$data_exchange[$j]][]=$data_exchange[$j+1];
		$_arrray[$data_exchange[$j]][]=$data_exchange[$j+2];
		$_arrray[$data_exchange[$j]][]=$data_exchange[$j+3];
	}
	var_dump($data_exchange);
	writeFile(json_encode($_arrray),$_SERVER['DOCUMENT_ROOT'].'/tmp/tygia.txt','w+');
}
catch (Exception $ex)
{
    writeFile('tmp/tygia.txt','','w+');
}
?>