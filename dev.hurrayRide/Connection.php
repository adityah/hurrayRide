<?php

class Connection{
	
	
	public function getMongoConnector($db, $collection)
	{
		$mongoObject=new Mongo();
		//choosing DB (in this csse its users
		$db = $mongoObject->selectDB($db);
		//return $db->$collection;
		return new MongoCollection($db, $collection);	
	}
}

?>