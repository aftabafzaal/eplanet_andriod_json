<?php
include_once 'connection.php';
include_once 'functions.php';




///////Drops Calls Yesterday

$fromdate=date("Y-m-d", strtotime("yesterday"));
//$fromdate="2014-04-02";
$todate=date("Y-m-d", strtotime("yesterday"));

$calls=go_dropped_calls_by_date('ADMIN',$fromdate,$todate);

$calls['drops'] = go_dropped_lessthan_by_date('ADMIN',$fromdate,$todate);
$calls['drops'] = ($calls['drops'] > 0) ? $calls['drops'] : "0";
$calls['answers'] = ($calls['answers'] > 0) ? $calls['answers'] : "0";

print_r($calls);
exit;
$dropped_percentage = ( ($calls['drops'] / $calls['answers']) * 100);
$dropped_percentage = ($dropped_percentage > 0) ? round($dropped_percentage,2) : "0";

$jsonArray['drops_yesterday']=$calls['drops']." Dropped Calls";
$jsonArray['answers_yesterday']=$calls['answers']." Answered Calls";
$jsonArray['dropped_percentage_yesterday']=$dropped_percentage." % Dropped Percentage";

$jsonArray['total_calls_yesterday']=go_total_calls_by_date('ADMIN',$fromdate,$todate)." Total Calls";

///////Drops Calls Yesterday

///////Drops Calls last Week


$Current = Date('N');
$DaysToSunday = 7 - $Current;
$DaysFromMonday = $Current - 1;
$date = Date('Y/m/d', strtotime("- {$DaysFromMonday} Days"));
$mod_date = strtotime($date."- 7 days");

$last_week_monday=date("Y-m-d",$mod_date);
$mod_date = strtotime($last_week_monday."+ 6 days");
$last_week_sunday=date("Y-m-d",$mod_date);


$jsonArray['last_week_dates']=" (".date("d/m/Y",strtotime($last_week_monday))." - ".date("d/m/Y",strtotime($last_week_sunday)).")";

$calls=go_dropped_calls_by_date('ADMIN',$last_week_monday,$last_week_sunday);


$calls['drops'] = go_dropped_lessthan_by_date('ADMIN',$last_week_monday,$last_week_sunday);
$calls['drops'] = ($calls['drops'] > 0) ? $calls['drops'] : "0";

$dropped_percentage = ( ($calls['drops'] / $calls['answers']) * 100);

$calls['answers'] = ($calls['answers'] > 0) ? $calls['answers'] : "0";
$dropped_percentage = ($dropped_percentage > 0) ? round($dropped_percentage,2) : "0";

$jsonArray['drops_last_week']=$calls['drops']." Dropped Calls";
$jsonArray['answers_last_week']=$calls['answers']." Answered Calls";
$jsonArray['dropped_percentage_last_week']=$dropped_percentage." % Dropped Percentage";
$jsonArray['total_calls_last_week']=go_total_calls_by_date('ADMIN',$last_week_monday,$last_week_sunday)." Total Calls";
///////Drops Calls Drops Calls last Week


function go_dropped_lessthan_by_date($groupId="ADMIN",$from_date,$to_date)
{
	//$groupId = $this->go_get_groupid();
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = $this->go_getall_allowed_campaigns();
		$ul = " where campaign_id IN ('$stringv') ";
	}
	//$NOW = date("Y-m-d");

	//$query = $this->db->query("select sum(calls_today) as calls_today,sum(drops_today) as drops_today,sum(answers_today) as answers_today from vicidial_campaign_stats where update_time BETWEEN '$NOW 00:00:00' AND '$NOW 23:59:59'");
	$sql="select count(*) as drops from vicidial_closer_log  where `status`='DROP' and length_in_sec>30 and call_date BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'";
	// $query = $this->db->query($sql);
	//   $resultsu = $query->row();
	//$resultsu
	//  return $resultsu;
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['total_drops'];

}


function go_total_calls_by_date($groupId='ADMIN',$from_date,$to_date)
{
	global $query_date;
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = $this->go_getall_allowed_campaigns();
		$ul = " and campaign_id IN ('$stringv') ";
	}

	$sql ="SELECT count(*) as totcalls from vicidial_users as us, vicidial_log as vlog, vicidial_list as vl where us.user=vlog.user and vl.phone_number=vlog.phone_number and vl.lead_id=vlog.lead_id and vlog.call_date between '$from_date 00:00:00' and '$to_date 23:59:59'";

	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);

	return $data['totcalls'];
}


function go_dropped_calls_by_date($groupId='ADMIN',$from_date,$to_date)
{

	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = $this->go_getall_allowed_campaigns();
		$ul = " where campaign_id IN ('$stringv') ";
	}

	echo $sql = "select sum(calls_today) as calls,sum(drops_today) as drops,sum(answers_today) as answers from vicidial_campaign_stats where update_time BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'";

	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data;
}