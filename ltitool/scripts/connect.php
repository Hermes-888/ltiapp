<?php
	$dbhost='mediafiles.uvu.edu';
	$dbuser='mediaadmin';
	$dbpass='+8?hooraY3';
	$dbname='aviationmedia';
	$dsn='mysql:dbname=aviationmedia;host=mediafiles.uvu.edu;port=3306';
	$dsna='mysql:dbname=aviationmedia;host=mediafiles.uvu.edu';
	//http://code.tutsplus.com/tutorials/php-database-access-are-you-doing-it-correctly--net-25338
	
	//http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
	//$db = new PDO('mysql:host=mediafiles.uvu.edu;dbname=aviationmedia;charset=utf8',$dbuser,$dbpass);
	//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	
	//http://codular.com/php-pdo-how-to
	/*
	try {
		// also allows an extra parameter of configuration
    	$conn = new PDO($dsn, $dbuser, $dbpass, array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
	} catch(PDOException $e) {
		die('Could not connect to the database:<br/>' . $e);
	}
	*/
?>