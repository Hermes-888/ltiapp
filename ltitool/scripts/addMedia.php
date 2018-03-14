<?php
/*
	called from mediabuilder to add a record to
	media table
	-id: auto inc
	-course: for filtering item selection list
	-path: to media file with[extra]?filename=param
	-type: may not need this but filter for swf, html5, video
	-thumb: thumbnail for item list (scripts/thumbs/unique name)
	-date: added to media table
	
	VALIDATE at minimum, the file path is set
	echo success or failed
*/
if(isset($_POST["table"])) {
    require_once('connect.php');

    $table = $_POST['table'];
    $course = $_POST['coursename'];// to filter list only
    $filepath = $_POST['thepath'];// includes parameter : used in student view
    $filetype = $_POST['radio'];// media type html,flash,video to filter
    $thumbnail = $_POST['thumbfile'];// visual of item for list only
    // 220x154 jpg all are stored at /scripts/thumbs/
    $today = date('Y-m-d H:i:s');//mysql datetime format

    if(isset($_POST['thepath'])) {
        try {
            $conn = new PDO($dsna, $dbuser, $dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO ".$table." (m_course, m_path, m_type, m_thumb, m_date_created) VALUES (:course, :path, :type, :thumb, :date)");
            $stmt->bindParam(':course', $course);
            $stmt->bindParam(':path', $filepath);
            $stmt->bindParam(':type', $filetype);
            $stmt->bindParam(':thumb', $thumbnail);
            $stmt->bindParam(':date', $today);

            // insert a row
            $stmt->execute();
            //$newId = $conn->lastInsertId();// last auto inc value
            echo "success";

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
    $conn = null;// empty connection
}
?>
