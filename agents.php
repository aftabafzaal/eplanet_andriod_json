<?php
include_once 'connection.php';
include_once 'functions.php';
include_once 'agents_functions.php';

$total_agents_call = go_total_agents_call();
$jsonArray['total_agents_call'] = $total_agents_call." Agent(s) on Call";

$total_agents_paused = go_total_agents_paused();
$jsonArray['total_agents_paused'] = $total_agents_paused." Agent(s) on Paused";

$total_agents_wait = go_total_agents_wait_calls();
$jsonArray['total_agents_wait'] = $total_agents_wait." Agent(s) Waiting";

$total_agents_online = go_total_agents_online();
$jsonArray['total_agents_online'] = $total_agents_online." Total Agents Online";