<?php
// require 'vendor/autoload.php';
	
	// $m = new MongoClient("mongodb://djisse:paela95@nagalive-shard-00-00-7a83m.mongodb.net:27017,nagalive-shard-00-01-7a83m.mongodb.net:27017,nagalive-shard-00-02-7a83m.mongodb.net:27017/heroku_pkl108wc?ssl=true&replicaSet=Nagalive-shard-0&authSource=admin");
	$m = new MongoClient("mongodb://djisse:djibril95@cluster0-shard-00-00.mdfvk.mongodb.net:27017,cluster0-shard-00-01.mdfvk.mongodb.net:27017,cluster0-shard-00-02.mdfvk.mongodb.net:27017/heroku_pkl108wc?ssl=true&replicaSet=atlas-otmrhr-shard-0&authSource=admin&w=majority");

	$db = $m->selectDB("heroku_pkl108wc");

?>