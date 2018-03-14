<?php
/*
	called from mediabuilder 
	construct a JSON string of all media records to create 
	selectable list of items for resource_selection
	-can filter by Course [AVSC-2150] it was created for
	-includes a thumbnail of the activity for visual reference
	-loads into preview panel when selected
	-m_active=0 replaces DELETE record default=1
	
	http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
	http://code.tutsplus.com/tutorials/php-database-access-are-you-doing-it-correctly--net-25338
*/
require_once('connect.php');

if(isset($_POST['match'])) {
    $match = $_POST['match'];
    $table='';
    try {
        $conn = new PDO($dsna, $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

        $stmt = $conn->query('SELECT * FROM consumers');
        $result = $stmt->fetchAll();

        if ( count($result) ) {
            
            foreach($result as $row) {
                
                if($row['config_url'] == $match) {
                    $table = $row['media_table'];
                    break;
                }
            }
        }
            // get allmedia in $table & return table
        if($table != '') {
            
            $stmt = $conn->query('SELECT * FROM '.$table);
            $result = $stmt->fetchAll();

            if ( count($result) ) {
                $obj='{"items":[';// construct JSON string
                foreach($result as $row) {
                    //if item is active add to items object
                    if($row['m_active'] == 1) {
                        $obj .= '{"id":"'.$row['m_id'].'",';
                        $obj .= '"course":"'.$row['m_course'].'",';

                        // split path for filename,extra
                        $path = $row['m_path'];
                        $chop = explode("?filename=",$path);
                        $obj .= '"filename":"'.$chop[0].'",';
                        if(count($chop)>1){
                            $obj .= '"extra":"'.$chop[1].'",'; 
                        } else { $obj .= '"extra":"",'; }

                        $obj .= '"type":"'.$row['m_type'].'",';
                        $obj .= '"thumb":"'.$row['m_thumb'].'"';
                        $obj .= '},';
                    }
                } 
                $obj = substr($obj, 0, -1);//remove last , comma
                $obj .= '], "table":"'.$table.'"}';// finish items
                // and table
                echo $obj;
                //echo json_encode($obj);
            } else { echo "No rows"; }
                
        } else { echo "No TABLE found"; }
           
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    $conn = null;// empty connection
}
?>
