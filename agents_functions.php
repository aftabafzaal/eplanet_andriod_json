<?php

function go_total_agents_call($groupId='ADMIN')
{
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul=' and user_level != 4';
	}
	else
	{
		$stringv = $this->go_getall_allowed_users();
		$ul = " and user IN ($stringv) and user_level != 4";
	}
	
	$sql = "select count(*) as qresult from vicidial_live_agents where status IN ('INCALL','QUEUE','3-WAY','PARK') $ul";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['qresult'];
}

function go_total_agents_paused($groupId='ADMIN')
{
	
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul=' and user_level != 4';
	}
	else
	{
		$stringv = $this->go_getall_allowed_users();
		$ul = " and user IN ($stringv) and user_level != 4";
	}
	
	$sql = "select count(*) as qresult from vicidial_live_agents where status IN ('PAUSED') $ul";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['qresult'];
}


function go_total_agents_wait_calls($groupId='ADMIN')
{
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul=' and user_level != 4';
	}
	else
	{
		$stringv = $this->go_getall_allowed_users();
		$ul = " and user IN ($stringv) and user_level != 4";
	}
	
	$sql = "select count(*) as qresult from vicidial_live_agents where status IN ('READY','CLOSER') $ul";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['qresult'];
}

function go_total_agents_online($groupId='ADMIN')
{
	
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul=' where user_level != 4';
	}
	else
	{
		$stringv = $this->go_getall_allowed_users();
		$ul = " where user IN ($stringv) and user_level != 4";
	}

	$sql = "select count(*) as qresult from vicidial_live_agents $ul";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['qresult'];
}



/* function go_getall_allowed_users()
{
	$groupId = $this->go_get_groupid();
	if ($groupId=='ADMIN' || $groupId=='admin')
	{
		$query = $this->db->query("select user as userg from vicidial_users");
	}
	else
	{
		$query = $this->db->query("select user as userg from vicidial_users where user_group='$groupId'");
	}
	$fresults = $query->result();
	$callfunc = $this->go_dashboard->go_total_agents_callv();
	$v = $callfunc - 1;
	$allowed_users='';
	$i=0;
	foreach($fresults as $item):
	$users = $item->userg;
	if ($i==$v)
	{
		$allowed_users .= "'" . $users. "'";
	}
	else
	{
		$allowed_users .= "'" . $users. "'" . ',';
	}
	$i++;
	endforeach;

	return $allowed_users;
} */