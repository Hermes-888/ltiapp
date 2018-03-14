<?php
/*
	called from mediabuilder
	UPDATE a media item for id# with itm obj
	
	http://code.tutsplus.com/tutorials/php-database-access-are-you-doing-it-correctly--net-25338
*/

if(isset($_POST["table"])) {
    require_once('connect.php');
    
    $table = $_POST['table'];
    $newdata = $_POST['item'][0];// json object 

    $id= intval($newdata['id']);// string to int
    $course = $newdata['course'];

    $filepath = $newdata['filename'];///'?filename=';
    if($newdata['extra'] != ''){
        $filepath=$filepath.'?filename='.$newdata['extra'];
    }

    $filetype = $newdata['type'];
    $thumbnail=$newdata['thumb'];
    $today = date("Y-m-d H:i:s");//Also Update datetime
    //echo $id.', '.$course.', '.$filepath.', '.$filetype.', '.$thumbnail;

    try {
        $conn = new PDO($dsna, $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE ".$table." SET m_course=:course, m_path=:path, m_type=:type, m_thumb=:thumb, m_date_created=:date WHERE m_id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->bindParam(':path', $filepath, PDO::PARAM_STR);
        $stmt->bindParam(':type', $filetype, PDO::PARAM_STR);
        $stmt->bindParam(':thumb', $thumbnail, PDO::PARAM_STR);
        $stmt->bindParam(':date', $today);
        $stmt->execute();

        echo 'success';

    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    $conn = null;// empty connection
}
?>
