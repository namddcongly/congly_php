<?php

session_start();
date_default_timezone_set('Asia/Bangkok');
include 'define.php';
require(KERNEL_PATH.'system.config.php');
require(UTILS_PATH.'io.php');
$function 	= SystemIO::get ( 'fnc', 'def', '' );
$path 		= SystemIO::get ( 'path', 'def', '' );
if (file_exists ( 'ajax/' . $path . '/' . $function . '.php' ))
require_once 'ajax/' . $path . '/' . $function . '.php';
else
echo "File:  ajax/{$path}/{$function}.php don't exist !";
?>