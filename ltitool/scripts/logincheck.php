<?php
// check if user exists in db
require_once('connect.php');

session_start();
$old_sessionid = session_id();
//echo "Old Session: $old_sessionid<br />";

//session_regenerate_id();
//$new_sessionid = session_id();
//echo "New Session: $new_sessionid<br />";

$usr = $_POST["username"];//stripslashes();
$pas = $_POST["password"];

try {
	$conn = new PDO($dsna, $dbuser, $dbpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
	
	$stmt = $conn->prepare('SELECT * FROM members WHERE username = :usr and password = :pas');
	$stmt->bindParam(':usr', $usr);
	$stmt->bindParam(':pas', $pas);
	$stmt->execute();
 
	$result = $stmt->rowCount();
	if ($result > 0) {
		
		$_SESSION["usr"]=$usr;
		header("location:../mediabuilder.php");
		//echo $result.' success';  
	} else {
		echo 'INVALID PASSWORD...<a href="../index.php"> TRY AGAIN</a>';
	}
	
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}
$conn = null;// empty connection
?>