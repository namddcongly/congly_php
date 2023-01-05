<?php
defined ( 'IN_JOC' ) or die ( 'Restricted Access' );
$host='localhost';
$hot_reserve='localhost';
return array (
	'default'=>'db',
	'database' => array (
		'db' => array (
			'username' => 'root',
			'password' => 'Root@123',
			'host' =>$host,
			'host_reserve'=>$hot_reserve, 
			'dbname' => 'conglyv2_platform', 
			'object' => 'MySQLi'),
		'news' => array (
			'username' => 'root',
			'password' => 'Root@123',
			'host' => $host, 
			'host_reserve'=>$hot_reserve,	
			'dbname' => 'congly_news', 
			'object' => 'MySQLi')
)
);
