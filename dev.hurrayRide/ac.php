<?php

session_start();

include('functions.php');
include('actions.php');

$proceed = true;

$inputPost = array();

if(isset($_GET['action'])){
	
	$action = trim($_GET['action']);
	
	foreach($_GET as $key=>$value){
		$input[$key] = stripslashes(trim($value));	
	}
	
	if(isset($_POST)){
		foreach($_POST as $key=>$value){
			$inputPost[$key] = stripslashes(trim($value));
		}
	}
	
	switch ($action){
		case 'login':
		{
			loginUser($input['u'], $input['p']);
			break;
		}
		
		case 'signup':
		{
			$required = array("username","password","cpassword","email");
		
			foreach($required as $key){
				if(!isset($inputPost[$key]) && $proceed){
					outputErrorJSON("Missing field: $key");
					$proceed &= false;
				}
			}
			
			if($proceed){
				if(strcmp($inputPost["password"],$inputPost["cpassword"]) !=0){
					outputErrorJSON("Passwords mismatch");
					$proceed &= false;
				}
			}
			
			if($proceed)
				signUpUser($inputPost['username'],$inputPost['password'],$inputPost['email']);
			
			break;
		}
		
		case 'expressInterest':
		{
			/*if(isset($_SESSION['user'])){
				//expressInterest($input['trip_id'],$input['user_id']);
				$output['trip'] = "Interest expressed";
				outputJSON($output);
			}else
			
				outputErrorJSON("Please login to express your interest");*/
				
				$inp_arr = array();
			
				if(!empty($input['from']))
					{
						$inp_arr['from']=$input['from'];
						$from=$input['from'];
					}
					if(!empty($input['to']))
					{
						$inp_arr['to']=$input['to'];
						$to=$input['to'];
					}
					/*if(!empty($input['travel_date']))
					{
						$inp_arr['travel_timestamp']=$input['travel_date'];
						$travel_date=$input['travel_date'];
					}*/
					
			expressInterest($inp_arr/*, $input['user']*/);
			
			break;
		}
		
		case 'createNewTrip':
		{
			//$id = "";
			//if(isset($_SESSION['user']['id'])) $id = $_SESSION['user']['id'];
			newTrip(/*$id,*/ $input['from'], $input['to'], $input['title'], $input['Description'], 
				$input['travel_date']/*, $inputPost['share_gas'], $inputPost['share_toll'], $inputPost['share_fixed'], $inputPost['has_car']*/);
			
			break;		
		}
		case 'countTrips':
		{
					if(!empty($input['from']))
					{
						$inp_arr['from']=$input['from'];
						$from=$input['from'];
					}
					if(!empty($input['to']))
					{
						$inp_arr['to']=$input['to'];
						$to=$input['to'];
					}
					countTrips($inp_arr);
					break;
		}
		
		case 'tripsearch':
		{
			//$_SESSION['searchResults']=	
			tripSearch($input['from_city'], $input['to_city'], $input['travel_date']/*, $input['has_car']*/);
			//header( 'Location: viewTrips.php?'.strtotime($input['travel_date']) ) ;
			break;		
		}
		case 'viewTrips':
		{
			$var=viewTrips();
			
			$dec = json_decode($var);
							
				echo $var;
					//header( 'Location: viewTrips.php') ;
					break;
		}
		case 'usersAllTrips':
		{
			viewUsersAllTrips($input['user']);
			break;
		}
		case 'regex':
		{
			AutoCompleteSearch("City",$input['txt'], $input['offset']);
			break;
		}
		case 'editTrip':
		{
			editTrip($trip_id, $id, $inputPost['from_city'], $inputPost['to_city'], $inputPost['title'], $inputPost['description'], 
				$inputPost['travel_date'], $inputPost['share_gas'], $inputPost['share_toll'], $inputPost['share_fixed'], $inputPost['has_car']);
			break;
		}
		case 'messg':
		{
			insertMessage($input['from'], $input['to'], $input['tripId'], $input['messageText']);
			break;
		}
		case 'viewMessg':
		{
			viewMessages($input['tripId']);
			break;
		
		}
		case 'ac':
		{
			global $TO_DESTINATION;
			AutoCompleteSearch($TO_DESTINATION, $input['query']);
			break;
		
		}
		default:
		{
			outputErrorJSON("No such action found");		
		}
	
	}
	

	
	
	if($action == "editTrip")
	{
	
		
	
	}

	/*
	if($action == "login"){
		
		
	
	}
	
	if($action == "signup"){
	
		$required = array("username","password","cpassword","email");
		
		foreach($required as $key){
			if(!isset($inputPost[$key]) && $proceed){
				outputErrorJSON("Missing field: $key");
				$proceed &= false;
			}
		}
		
		if($proceed){
			if(strcmp($inputPost["password"],$inputPost["cpassword"]) !=0){
				outputErrorJSON("Passwords mismatch");
				$proceed &= false;
			}
		}
		
		if($proceed)
			signUpUser($inputPost['username'],$inputPost['password'],$inputPost['email']);
	
	}
	
	if($action == "interest"){
		
		if(isset($_SESSION['user'])){
			//expressInterest($input['trip_id'],$input['user_id']);
			$output['trip'] = "asdfsa";
			outputJSON($output);
		}else
			outputErrorJSON("Please login");
		
	}
	
	if($action == "newtrip"){
		
		$id = "";
		if(isset($_SESSION['user']['id'])) $id = $_SESSION['user']['id'];
		
		newTrip($id, $inputPost['from_city'], $inputPost['to_city'], $inputPost['title'], $inputPost['description'], 
				$inputPost['travel_date'], $inputPost['share_gas'], $inputPost['share_toll'], $inputPost['share_fixed'], $inputPost['has_car']);
	
	}
	
	if($action =="tripsearch"){
		
		tripSearch($input['from_city'], $input['to_city'], $input['travel_date'], $input['has_car']);
	
	}*/
	
	
}


if(isset($_POST['action'])){
	
	$action = trim($_POST['action']);
	
	foreach($_POST as $key=>$value){
		$input[$key] = stripslashes(trim($value));	
	}
	
	if(isset($_POST)){
		foreach($_POST as $key=>$value){
			$inputPost[$key] = stripslashes(trim($value));
		}
	}
	
	switch ($action){
		case 'login':
		{
			loginUser($input['u'], $input['p']);
			break;
		}
		
		case 'signup':
		{
			$required = array("username","password","cpassword","email");
		
			foreach($required as $key){
				if(!isset($inputPost[$key]) && $proceed){
					outputErrorJSON("Missing field: $key");
					$proceed &= false;
				}
			}
			
			if($proceed){
				if(strcmp($inputPost["password"],$inputPost["cpassword"]) !=0){
					outputErrorJSON("Passwords mismatch");
					$proceed &= false;
				}
			}
			
			if($proceed)
				signUpUser($inputPost['username'],$inputPost['password'],$inputPost['email']);
			
			break;
		}
		
		case 'interest':
		{
			if(isset($_SESSION['user'])){
				//expressInterest($input['trip_id'],$input['user_id']);
				$output['trip'] = "Interest expressed";
				outputJSON($output);
			}else
				outputErrorJSON("Please login to express your interest");
			
			break;
		}
		
		case 'createNewTrip':
		{
			//$id = "";
			//if(isset($_SESSION['user']['id'])) $id = $_SESSION['user']['id'];
			//$var=
			newTrip(/*$id,*/ $inputPost['from_city'], $inputPost['to_city'], $inputPost['title'], $inputPost['Description'], 
				$inputPost['travel_date']/*, $inputPost['share_gas'], $inputPost['share_toll'], $inputPost['share_fixed'], $inputPost['has_car']*/);
			//$_SESSION['createNewTripResponse']=$var;
			//header( 'Location: viewTrips.php') ;
			break;		
		}
		
		case 'tripsearch':
		{
			tripSearch($input['from_city'], $input['to_city'], $input['travel_date'], $input['has_car']);
			break;		
		}
		case 'viewTrips':
		{
					echo var_dump(viewTrips());
					
					break;
		}
		case 'usersAllTrips':
		{
			viewUsersAllTrips($input['user']);
			break;
		}
		case 'regex':
		{
			AutoCompleteSearch("City",$input['txt'], $input['offset']);
			break;
		}
		case 'editTrip':
		{
			editTrip($trip_id, $id, $inputPost['from_city'], $inputPost['to_city'], $inputPost['title'], $inputPost['description'], 
				$inputPost['travel_date'], $inputPost['share_gas'], $inputPost['share_toll'], $inputPost['share_fixed'], $inputPost['has_car']);
			break;
		}
		case 'messg':
		{
			insertMessage($input['from'], $input['to'], $input['tripId'], $input['messageText']);
			break;
		}
		case 'viewMessg':
		{
			viewMessages($input['tripId']);
			break;
		
		}
		case 'ac':
		{
			global $TO_DESTINATION;
			AutoCompleteSearch($TO_DESTINATION, $input['query']);
			break;
		
		}
		default:
		{
			outputErrorJSON("No such action found");		
		}
	
	}
	

	
	
	if($action == "editTrip")
	{
	
		
	
	}

	
	
}


















?>