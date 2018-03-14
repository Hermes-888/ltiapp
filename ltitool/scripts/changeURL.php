<?php 
/*
    changeURL.php
    
	called from viewInstructor.php to change the assignment url
	post the Canvas API url with course and assignment ids
	
    check consumers[0]token
        if none get request one and store it there then retry?
	
	Need to get a token for the instructor 
		check into /blti/trivialOAuthDataStore lookup_token ???
		to replace $atoken
	GET returns the Assignment json object
	PUT updates the Assignment External_tool_tag_attributes: url
		with data posted
	
	https://canvas.instructure.com/doc/api/file.oauth.html
    
    
    
    send new media_id & api url
    
    look up token at consumers[1]token
    if token,
        change url
    else 
        get token
        
        change url
    
    return success or fail
*/

if(isset($_GET['api'])) {
   require_once('connect.php');
    
    try {
        $conn = new PDO($dsna, $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

        $stmt = $conn->query('SELECT * FROM consumers ORDER BY id ASC LIMIT 1');
        $result = $stmt->fetchAll();

        if ( count($result) ) {
        
            foreach($result as $row) {
                $token = $row['token'];
                //$obj .= 'dept: '.$row['dept'];
            }
            
            if($token == '') {
                echo "getoken";// go get one first
                
            } else {
                //echo "HAS ONE";
                $canvasapi = $_GET['api'];
                $mediaid = $_GET['mediaid'];
                
                $tokenHeader = array("Authorization: Bearer ".$token);// USE instead of url
                //$asgn = getAssignment();
                //echo $asgn;
                
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $canvasapi,
                    CURLOPT_HTTPHEADER=>$tokenHeader
                ));

                $result = curl_exec($ch); 
                curl_close($ch);
                //echo $result;// original assignment
                
                // change media=$mediaid then put it back
                $temp = explode(',', $result);

                //split external_tool_tag_attributes at ? strip media=##
                $sp1 = explode('?', $temp[36]);
                //split again at : to get mediafiles...
                $tmp = explode(':', $sp1[0]);
                // re-combine the new url
                $data = 'assignment[external_tool_tag_attributes][url]=https:'.$tmp[3].'?media='.$mediaid;
                //echo $data;
                // assignment[external_tool_tag_attributes][url]=https://mediafiles.uvu.edu/extools/tooltest/ltitool/mediatool.php?media=1
                
                // only update the url
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $canvasapi,
                    CURLOPT_HTTPHEADER=>$tokenHeader,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_RETURNTRANSFER => true,
                ));

                $updated = curl_exec($ch);
                curl_close($ch);
                echo 'successfully updated media';//$updated;// assignment
            }
            
            
        } else {
            echo "No rows";
        }

    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    $conn = null;// empty connection
    
}
/* Assignment obj

{"id":2656171,"description":"","due_at":null,"unlock_at":null,"lock_at":null,"points_possible":1,"grading_type":"points","assignment_group_id":555958,"grading_standard_id":null,"created_at":"2016-05-11T22:19:27Z","updated_at":"2016-05-11T22:28:31Z","peer_reviews":false,"automatic_peer_reviews":false,"position":1,"grade_group_students_individually":false,"anonymous_peer_reviews":false,"group_category_id":null,"post_to_sis":false,"moderated_grading":false,"course_id":410340,"name":"tooltest","submission_types":["external_tool"],"has_submitted_submissions":false,"turnitin_enabled":false,"turnitin_settings":{"originality_report_visibility":"immediate","s_paper_check":true,"internet_check":true,"journal_check":true,"exclude_biblio":true,"exclude_quoted":true,"exclude_small_matches_type":null,"exclude_small_matches_value":null,"submit_papers_to":true},"muted":false,"html_url":"https://uvu.instructure.com/courses/410340/assignments/2656171","has_overrides":false,

"external_tool_tag_attributes":{"url":"https://mediafiles.uvu.edu/extools/tooltest/ltitool/mediatool.php?media=4",

"new_tab":false,"resource_link_id":"2be0e5db404b700af08e5a015276f107c32af245"},"url":"https://uvu.instructure.com/api/v1/courses/410340/external_tools/sessionless_launch?assignment_id=2656171\u0026launch_type=assessment","needs_grading_count":0,"published":false,"unpublishable":true,"only_visible_to_overrides":false,"locked_for_user":false,"submissions_download_url":"https://uvu.instructure.com/courses/410340/assignments/2656171/submissions?zip=1"}

*/

?>
