<?php
error_reporting(1);
$hostname_localhost ="localhost";
$database_localhost ="asterisk";
$username_localhost ="root";
$password_localhost ="";
$localhost = mysql_connect($hostname_localhost,$username_localhost,$password_localhost)
or
trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_localhost, $localhost);

$query_date =  date('Y-m-d');
$query_date =  '2014-07-24';

$groupId="ADMIN";