<?php
/*
    Early Alert button tracking
    gatest.js in canvas
    
    sends button clicks from canvas with post
    track which page the click came from.
    
    trackbtns fields:
    id, page button is on,
    courseid, userid, server
    
    [server] could be uvu.instructure.com or uvu.test.
    
    use data sent, userid courseid, for api call ???
    http://hayageek.com/cross-domain-ajax-request-jquery/
    
    To view the db fields:
    http://mediafiles.uvu.edu/common/support/alertTracker.html
*/

    header('Access-Control-Allow-Origin: *');//https://uvu.instructure.com POST
    //header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    //header('Access-Control-Allow-Origin: https://uvu.test.instructure.com');
    header('Access-Control-Allow-Methods:POST, OPTIONS');

    if($_POST["coursetitle"])
    {
        require_once('includes/connect.php');
        //{userid:userid, coursetitle:courseTitle, courseid: courseID, domain: domain, dept:dept}
        // Course Title name
        $coursename = $_POST["coursetitle"];
        // course id #
        $courseid = $_POST["courseid"];
        // could be useful across courses
        $userid = $_POST["userid"];
        // sort by dept buttons
        $dept = $_POST["dept"];
        //uvu.instructure or .beta, .test
        $domain = $_POST["domain"];
        
        $today = date("Y-m-d H:i:s");//mysql datetime format
        // update db field
        $table = "trackcourses";// database table name
        
        try {
            $conn = new PDO($dsna, $dbuser, $dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO ".$table." (t_page, t_course, t_user, t_server, t_dept, t_date_created) VALUES (:page, :course, :user, :server, :dept, :date)");
            
            $stmt->bindParam(':page', $coursename);
            $stmt->bindParam(':course', $courseid);
            $stmt->bindParam(':user', $userid);
            $stmt->bindParam(':server', $domain);
            $stmt->bindParam(':dept', $dept);
            $stmt->bindParam(':date', $today);

            // insert a new row
            $stmt->execute();
            echo "Tracked: ". $coursename ." for ".$userid; // respond back

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        $conn = null;// empty connection     
        
    }

?>