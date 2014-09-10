<?php
include_once 'connection.php';

//$_POST['username']='admin';
//$_POST['password']='123';
//$_POST['password']='GODisgreat1';
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$jsonArray=array();
$jsonArray['error']=true;
$jsonArray['errorMessage']="";
$valid=true;

if(!$username)
{
	$valid=false;
	$jsonArray['errorMessage']="Enter Username.\n";
}

if(!$password)
{
	$valid=false;
	$jsonArray['errorMessage'].="Enter Password.";
}

if($valid)
{
	$query_search = "select user_id,full_name,user_level,user_group,phone_login,phone_pass,email from vicidial_users where user = '".$username."' AND pass = '".$password. "'";
	$query_exec = mysql_query($query_search) or die(mysql_error());
	$rows = mysql_num_rows($query_exec);

	if($rows == 0)
	{ 
		$jsonArray['errorMessage']="Invalid Username/Password.";
	}
	else
	{
		$data=mysql_fetch_assoc($query_exec);
		
		if($data['user_level']=="9" && $data['user_group']=="ADMIN"){
			
			$jsonArray['error']=false;
			$jsonArray['errorMessage']="User Found";
			
			foreach($data as $key =>$value){
				$jsonArray['data'][$key]=$value;
			}
		}else{
			$jsonArray['errorMessage']="You Dont have enough priviliges.";
		}
		
	}
}
$result['response']=$jsonArray;
header('Content-type: application/json');
echo json_encode($result);