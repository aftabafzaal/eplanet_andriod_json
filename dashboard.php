<?php
include_once 'connection.php';
include_once 'functions.php';

$jsonArray=array();
$jsonArray['error']=true;
$jsonArray['errorMessage']="";
$valid=true;

$status='SALE';
$date = "call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'";
$sql = "select count(*) as qresult from vicidial_log where status='$status' and $date;";
$query_exec = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($query_exec);
$data=mysql_fetch_assoc($query_exec);
$outbound_today=$data['qresult'];
$outbound_per_hour = number_format($outbound_today/8, 2, '.', '');
$jsonArray['outbound_today']=$outbound_today." Outbound Sales";
$jsonArray['outbound_per_hour']=$outbound_per_hour." OUT Sales / Hour";

$sql = "select count(*) as qresult from vicidial_closer_log where status='$status' and $date;";
$query_exec = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($query_exec);
$data=mysql_fetch_assoc($query_exec);
$inbound_today=$data['qresult'];
$inbound_per_hour = number_format($inbound_today/8, 2, '.', '');

$jsonArray['inbound_today']=$inbound_today." Inbound Sales";
$jsonArray['inbound_per_hour']=$inbound_per_hour." IN Sales / Hour";

$total_sale=$inbound_today+$outbound_today;
$jsonArray['total_sale']=$total_sale." Total Sales";

$date = "call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'";
$sql = "select count(*) as qresult from vicidial_closer_log where status='$status' and $date;";
$query_exec = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($query_exec);
$data=mysql_fetch_assoc($query_exec);
$inbound_today=$data['qresult'];
$jsonArray['inbound_today']=$inbound_today." Inbound Sales";

/// Calls

$live_inbound_today=go_live_inbound_today('ADMIN');
$jsonArray['live_inbound']=$live_inbound_today." Live Inbound";

$live_outbound_today=go_live_outbound_today('ADMIN');
$jsonArray['live_outbound']=$live_outbound_today." Live Outbound";

$calls_ringing_today=go_calls_ringing_today('ADMIN');
$jsonArray['calls_ringing']=$calls_ringing_today." Call(s) Ringing";

$calls_inbound_queue_today=go_calls_inbound_queue_today('ADMIN');
$jsonArray['calls_inbound_queue']=$calls_inbound_queue_today." Call(s) in Incoming Queue";

$total_calls=go_total_calls('ADMIN');
$jsonArray['total_calls']=$total_calls." Total Calls";
////-- Calls

///////Drops Calls

$calls=go_dropped_calls_today('ADMIN');
$calls['drops_today'] = go_dropped_lessthan('ADMIN');
$calls['drops_today'] = ($calls['drops_today'] > 0) ? $calls['drops_today'] : "0";

$dropped_percentage = ( ($calls['drops_today'] / $calls['answers_today']) * 100);
//$calls['drops_today'] = ($calls['drops_today'] > 0) ? $calls['drops_today'] : "0";
$calls['drops_today'] = go_dropped_lessthan('ADMIN');
$calls['drops_today'] = ($calls['drops_today'] > 0) ? $calls['drops_today'] : "0";
$calls['answers_today'] = ($calls['answers_today'] > 0) ? $calls['answers_today'] : "0";
$dropped_percentage = ($dropped_percentage > 0) ? round($dropped_percentage,2) : "0";

$jsonArray['drops_today']=$calls['drops_today']." Dropped Calls";
$jsonArray['answers_today']=$calls['answers_today']." Answered Calls";
$jsonArray['dropped_percentage']=$dropped_percentage." % Dropped Percentage";
////--- Drop Calls


include_once 'agents.php';
//include_once 'archive.php';


$jsonArray['error']=false;
$result['response']=$jsonArray;

header('Content-type: application/json');
echo json_encode($result);