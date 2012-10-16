<?php

require('db.php');
require('constants.php');
require('Connection.php');
/***************
	From db.php:
	
	$API_URL = "https://api.mongohq.com/databases/amgoingto/";
	$API_KEY = "_apikey=sm3rt19ow0kj763gkrtq";
	
	--------------
		
	URL Formation:
	
	$url = $API_URL."collections/blah/blah/?safs=asdf&".$API_KEY;
****************/




//getMongoConnector will take two arguments. One db name to select, second collection


//User Login
function loginUser($username,$password){

	$connection= getMongoConnector("users","users");
	$response=$connection->findOne(array("username" => $username, "password" => $password));
	if (count($response)==null)
	{
		outputErrorJSON("Invalid Userid Password Combination");
	}
	else 
	{
		$output['count']=count($response);	
		$output['id']=$response['_id']."";
		$output['username'] = $response['username'];
		$output['email'] = $response['emailAddress'];
		$output['type'] = "to.amgoing.loggedUser";
		$_SESSION['user']=$output;
		outputJSON($output);
	}
}

//Express interest in all the available trips


function expressInterest($inp_arr/*,$user*/)
{
	//Get DB Connector
	$conn= new Connection;
	$connection = $conn->getMongoConnector("hurray_dev","trips");	
	//$connection= getMongoConnector("hurray_dev","trips");
	//Find All trips
	
	//for Each trip, add the interested user
	$response=$connection->update($inp_arr,array('$addToSet'=>array('interest_parties'=>"user5")),array('multiple' => true, 'upsert'=> false));
	$response=$connection->find($inp_arr);
	
	foreach($response as $doc)
	{
		$output[]=$doc;
	}
	outputJSON($output);

}


function expressInterest_old_needtoMod($trip_id, $user_id)
{
	$connection= getMongoConnector("playground","trips");
	$theObjId = new MongoId($trip_id);
	$response=$connection->findOne(array("_id" => $theObjId, "interested_parties" =>$user_id));
	if(empty($response))
	{
		//$response['interested_parties']=array($user_id);
		$response['interested_parties']=$user_id;
		try 
		{
			$response=$connection->update(array("_id"=>$theObjId),array('$push'=> $response));
			//($response,array("safe" => true));
			outputJSON("Your Interest is conveyed");
		}
		catch (MongoCursorException $e) 
		{
				//echo "error message: ".$e->getMessage()."\n";
				//echo "error code: ".$e->getCode()."\n";
				outputErrorJSON("error message: ".$e->getMessage()."\n"."error code: ".$e->getCode()."\n");
		}
	}
	else{
		if(empty($response['interested_parties'][$user_id]))
		{
			outputErrorJSON("The Interest is already conveyed");
		}
	}
}

//Search trip
function tripSearch($from, $to, $date/*, $has_car,$offset*/)
{
	$query= array();
	if(!empty($from))
	{
		$query["from"]=$from;
	}
	if(!empty($to))
	{
		$query["to"]=$to;
	}
	
	$newDate = new MongoDate(strtotime($date));
	$query["travel_timestamp"]=$newDate;
	
	$conn= new Connection;
	$connection = $conn->getMongoConnector("hurray_dev","trips");
	
	//$connection=getMongoConnector("hurray_dev", "trips");
	//$query=array('from'=>$from, 'to'=>$to, "has_car"=> $has_car);

	$cursor=$connection->find($query);
	
		$response = array();
		foreach ($cursor as $doc) 
		{
			$doc['travel_timestamp']=date('m/d/y', $doc['travel_timestamp']->sec);
			$doc['create_timestamp']=date('m/d/y', $doc['create_timestamp']->sec);
			$response[] = $doc;
		}
		
		
		/*$tempDate = $response["travel_timestamp"]->sec;
		$newDate = date('Y-M-d h:i:s',$tempDate);
		$response->travel_timestamp = $newDate;*/
		
		//var_dump($response);
		//$newDate=date('Y-M-d h:i:s', $response['travel_timestamp']->sec); 
		
		//$response['travel_timestamp']=$newDate;
		
		if(count($response)<1)
		{
			//$response['error']=true;
			//return null;
			outputJSON(null);
			
		}
		else
		{
		
			//$response["tofrom"]=$to."  ".$from;
		outputJSON($response);
			//return $response;
		}
		
		

}	

//User Signup
function signUpUser($u,$p,$e)
{
	$input['username']=$u;
	$input['password']=$p;
	$input['emailAddress']=$e;
	
	//$input['lastName']=$l;

	$connection=getMongoConnector("users","users");
	
	$response=$connection->findOne(array("username" => $u));
	
	if(empty($response))
	{
	try 
	{
		$response=$connection->insert($input, array("safe" => true));
		outputJSON("You are signed In");
	}
	catch (MongoCursorException $e) 
	{
			//echo "error message: ".$e->getMessage()."\n";
			//echo "error code: ".$e->getCode()."\n";
			outputErrorJSON("error message: ".$e->getMessage()."\n"."error code: ".$e->getCode()."\n");
	}
	}
	else
	{
		outputErrorJSON("Username already taken. Please choose different one.");
		
	}	
	//$jsonInput=json_encode($input);
	
	//get_data_curl($GLOBALS['API_URL']."users/".$GLOBALS['API_KEY']."&d={document:{".$jsonInput."}safe:true}	");
	return;

}


//view all trips in db
function viewTrips()
{
	$connection= getMongoConnector("hurray_dev","trips");
	$response=$connection->find();
	foreach ($response as $doc) 
		{
			$doc['travel_timestamp']=date('h:i:s m/d/y', $doc['travel_timestamp']->sec);
			$output[]=$doc;
			
		}
		return outputJSON($output);
}
//Count Trips
function countTrips($inp_arr)
{
	//$connection= getMongoConnector("hurray_dev","trips");
	//$mongoObject=new Mongo();
		//chooding DB (in this csse its users
	//$db = $mongoObject->selectDB('hurray_dev');
		//return $db->$collection;
		//$connection = new MongoCollection($db, "trips");	
	
	$conn= new Connection;
	$connection = $conn->getMongoConnector("hurray_dev","trips");
	
	$response=$connection->find($inp_arr);
	foreach ($response as $doc) 
		{
			$count_arr[]=$doc;
		}
		//$output['count']=$response;
		outputJSON($count_arr);
}

//view all trips of a particular user
function viewUsersAllTrips($user_id)
{
	$connection = getMongoConnector("playground","trips");
	$response=$connection->find();
	foreach($response as $doc)
	{
		$output[]=$doc;
	}
	outputJSON($output);

}

//edit trip created by user

function editTrip($trip_id, $user_id, $from_city, $to_city, $title, $description, $travel_timestamp, $share_gas, $share_toll, $share_fix, $has_car)
{
	$tripId=new MongoId($trip_id);
	/*if(!(strtotime($travel_timestamp))
	{
		outputErrorJSON("Invalid Time Stamp. Please enter again");	
	}*/
	
	$input['creator']=$user_id;
	$input['from']=$from_city;
	$input['to']=$to_city;
	$input['title']=$title;
	$input['description']=$description;
	$input['travel_timestamp']=time();
	//remember to set above to $travel_timestamp;
	$input['will_share']=array("gas_price"=>$share_gas, "toll_price"=>$share_toll, "fix_price"=>$share_fix);
	$input['create_timestamp']=time();	
	$input['has_car']=$has_car;
		
		$connection=getMongoConnector("playground","trips");
	
	try 
	{
		$response=$connection->update(array("_id"=>	$tripId),$input, array("safe" => true));
		outputJSON("Trip Updated Successfully !!! ");
	}
	catch (MongoCursorException $e) 
	{
			//echo "error message: ".$e->getMessage()."\n";
			//echo "error code: ".$e->getCode()."\n";
			outputErrorJSON("error message: ".$e->getMessage()."\n"."error code: ".$e->getCode()."\n");
	}
	

}

//create a new trip
function newTrip(/*$user_id,*/ $from_city, $to_city, $title, $description, $travel_timestamp/*, $share_gas, $share_toll, $share_fix, $has_car*/)
{
	
	$input['from']=$from_city;
	$input['to']=$to_city;
	$input['title']=$title;
	$input['description']=$description;
	$input['create_timestamp']=new MongoDate(strtotime(time()));
	$input['travel_timestamp']=new MongoDate(strtotime($travel_timestamp));
	//remember to set above to $travel_timestamp;
	/*$input['will_share']=array("gas_price"=>$share_gas, "toll_price"=>$share_toll, "fix_price"=>$share_fix);
		
	$input['has_car']=$has_car;
	$input['status']=0;
	$input['is_deleted']=0;*/
		$conn= new Connection;
		$connection = $conn->getMongoConnector("hurray_dev","trips");
	
	try 
	{
		$response=$connection->insert($input, array("safe" => true));
		outputJSON("Trip Created Successfully !!! ");
	}
	catch (MongoCursorException $e) 
	{
			//echo "error message: ".$e->getMessage()."\n";
			//echo "error code: ".$e->getCode()."\n";
			$serializedArray=outputErrorJSON("error message: ".$e->getMessage()."\n"."error code: ".$e->getCode()."\n");
	}
	
	//return $serializedArray;
}


function AutoCompleteSearch($columnName, $searchString)
{
	//$fieldName, $regexString
	$regexString = new MongoRegex("/^$searchString/i"); 
	global $AJAX_LIMIT_TRIPSEARCH;
	$query=array($columnName=>$regexString);
	//$query=array("City"=>$regexString)
	$connection=getMongoConnector("cities","cities");
	$response=$connection->find($query)->sort(array('City' => 1))->limit($AJAX_LIMIT_TRIPSEARCH)->sort(array('City' => 1, 'State' => 1));
	$output=array();
	$setarray=array();
		//$response->sort(array('City' => 1))->limit($AUTO_COMPLETE_LIMIT);
		if(!empty($offset))
		{
			$response->skip($offset)->limit($AJAX_LIMIT_TRIPSEARCH)->sort(array('City' => 1));
		}
		foreach ($response as $doc) 
		//{
			//while ($resultset->hasNext()) 
			{
				$entry = array();
				$entry =$doc['City'].",".$doc['State'];
				
				
				$output[] = $entry;
				unset($entry);
			}
			
			if(count($output)==null or count($output)<1)
			{
				outputErrorJSON("No Results Found");
			}
			else
			{
				ajaxCall($searchString, $output);
				//echo json_encode($output);
			}
}

//Insert Message

function insertMessage($from, $to, $tripId, $messageText)
{
	$input['fromUser']=$from;
	$input['toUser']=$to;
	$input['tripId']=$tripId;
	$input['messageTimestamp']=time();
	$input['messageText']=$messageText;
	//$input['']=$from;
	$connection=getMongoConnector("playground","messages");
	try 
	{
		$response=$connection->insert($input, array("safe" => true));
		outputJSON("Message Delivered !!! ");
	}
	catch (MongoCursorException $e) 
	{
			//echo "error message: ".$e->getMessage()."\n";
			//echo "error code: ".$e->getCode()."\n";
			outputErrorJSON("error message: ".$e->getMessage()."\n"."error code: ".$e->getCode()."\n");
	}


}

//View  Messages

function viewMessages($tripId)
{
	$connection = getMongoConnector("playground","messages");
	$response=$connection->find(array("tripId"=>$tripId))->sort(array('$natural' => 1) );
	foreach ($response as $doc) 
		{
			$output[]=$doc;
		}
		outputJSON($output);

}


?>