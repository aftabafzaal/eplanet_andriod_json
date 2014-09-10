<?php 

function go_live_inbound_today($groupId='ADMIN')
{
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = go_getall_allowed_campaigns($groupId);
		$ul = " and campaign_id IN ('$stringv') ";
	}
	$sql = "select count(*) AS inbound from vicidial_live_agents as vla,vicidial_users as vu where vla.user=vu.user and status = 'INCALL' and comments = 'INBOUND' $ul";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	$inbound=$data['inbound'];
	
	return $inbound;
}

function go_live_outbound_today($groupId='ADMIN')
{
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = go_getall_allowed_campaigns($groupId);
		$ul = " and campaign_id IN ('$stringv') ";
	}
	$sql = "select count(*) AS outbound from vicidial_live_agents as vla,vicidial_users as vu where vla.user=vu.user and status = 'INCALL' and (comments IN ('MANUAL','AUTO') or length(comments) < '1') $ul";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	$outbound=$data['outbound'];

	return $outbound;
}

function go_calls_ringing_today($groupId='ADMIN')
{
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = $this->go_getall_allowed_campaigns();
		$ul = " and campaign_id IN ('$stringv') ";
	}
	
	$sql = "select count(*) AS ringing from vicidial_auto_calls where status NOT IN('XFER') and call_type = 'OUT' $ul";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['ringing'];
}

function go_calls_inbound_queue_today($groupId='ADMIN')
{
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = $this->go_getall_inbound_groups();
		$ul = " and campaign_id IN ('$stringv') ";
	}

	//$sql = "SELECT count(*) AS queue FROM vicidial_auto_calls where status NOT IN('XFER') and (call_type='IN' $ul)";
	  $sql = "SELECT count(*) AS queue FROM vicidial_auto_calls where status NOT IN('XFER','CLOSER') and (call_type='IN' $ul)";
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['queue'];
}


function go_total_calls($groupId='ADMIN')
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
	
	$sql ="SELECT count(*) as totcalls from vicidial_users as us, vicidial_log as vlog, vicidial_list as vl where us.user=vlog.user and vl.phone_number=vlog.phone_number and vl.lead_id=vlog.lead_id and vlog.call_date between '$query_date 00:00:00' and '$query_date 23:59:59'";
	
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	
	return $data['totcalls'];
}


function go_dropped_calls_today($groupId='ADMIN')
{
	global $query_date;
	
	if ($groupId == 'ADMIN' || $groupId == 'admin')
	{
		$ul='';
	}
	else
	{
		$stringv = $this->go_getall_allowed_campaigns();
		$ul = " where campaign_id IN ('$stringv') ";
	}
	
	$sql = "select sum(calls_today) as calls_today,sum(drops_today) as drops_today,sum(answers_today) as answers_today from vicidial_campaign_stats where update_time BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'";
	
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data;
}


function go_getall_allowed_campaigns($groupId="ADMIN")
{
	$query_date =  date('Y-m-d');
	$query = $this->db->query("select trim(allowed_campaigns) as qresult from vicidial_user_groups where user_group='$groupId'");
	$resultsu = $query->row();

	if(count($resultsu) > 0){
		$fresults = $resultsu->qresult;
		$allowedCampaigns = explode(",",str_replace(" ",',',rtrim(ltrim(str_replace('-','',$fresults)))));

		$allAllowedCampaigns = implode("','",$allowedCampaigns);

	}else{
		$allAllowedCampaigns = '';
	}
	return $allAllowedCampaigns;
}





function go_dropped_lessthan($groupId="ADMIN")
{
	global $query_date;
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
         $sql="select count(*) as total_drops  from vicidial_closer_log  where `status`='DROP' and length_in_sec>30 and call_date BETWEEN '$NOW 00:00:00' AND '$NOW 23:59:59'";
       // $query = $this->db->query($sql);
         //   $resultsu = $query->row();
         //$resultsu
          //  return $resultsu;
	$query_exec = mysql_query($sql) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);
	$data=mysql_fetch_assoc($query_exec);
	return $data['total_drops'];

}
