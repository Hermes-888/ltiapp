<?php
/*
	called from viewStudent.php
	return a media item for id# (path only?) in table
	-loads into view student display
	Example: var content='/aviation/media/1010/mod11/orbits_trajectories.html';
	
	http://code.tutsplus.com/tutorials/php-database-access-are-you-doing-it-correctly--net-25338
*/
require_once('connect.php');

$id = intval($_POST['mediaid']);// string to int 
$table = $_POST['table'];

try {
	$conn = new PDO($dsna, $dbuser, $dbpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
	
	$stmt = $conn->prepare('SELECT m_path FROM '.$table.' WHERE m_id = :id');
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
 
	$result = $stmt->fetchAll();
	//echo $stmt->rowCount().'<br>';//1

	if ( count($result) ) { 
		foreach($result as $row) {
			echo($row['m_path']);
		} 
		//echo $result[['m_path'];  
	} else {
		echo 'No rows returned.';
	}
	
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}
$conn = null;// empty connection
?>
