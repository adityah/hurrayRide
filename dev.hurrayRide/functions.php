<?php

function get_data_curl($url)
{
   $ch = curl_init();
   curl_setopt($ch,CURLOPT_URL,$url);
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
   curl_setopt ($c, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   $data = curl_exec($ch);
   curl_close($ch);
   return json_decode($data,true);
}

function get_data_http($url)
{
   $data = file_get_contents($url);
   return json_decode($data,true);
}

/* return standard JSON Output */
function returnJSON($response){
	$output['is_error'] = false;
	$output['timestamp'] = time();
	$output['response'] = $response;
	header('Content-Type: text/javascript');
	return json_encode($output);
	
}

/* return standard Error JSON */
function returnErrorJSON($error){
	$output['is_error'] = true;
	$output['timestamp'] = time();
	$output['error'] = $error;
	header('Content-Type: text/javascript');
	return json_encode($output);
	
}

/* Generate standard JSON Output */
function outputJSON($response){
	$output['is_error'] = false;
	$output['timestamp'] = time();
	$output['response'] = $response;
	header('Content-Type: text/javascript');
	echo json_encode($output);
	
}

/* Output standard Error JSON */
function outputErrorJSON($error){
	$output['is_error'] = true;
	$output['timestamp'] = time();
	$output['error'] = $error;
	header('Content-Type: text/javascript');
	echo json_encode($output);
	//return json_encode($output);
}

function ajaxCall($query, $response)
{
	$output['query'] = $query;
	$output['suggestions'] =$response;
	header('Content-Type: text/javascript');
	echo json_encode($output);
}

?>