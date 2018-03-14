<?php


$output_dir = "../../media/";
//send: folderpath in formData
if(isset($_POST["folderpath"])) {
    $output_dir = $_POST["folderpath"];
}
chmod($output_dir, 0777);

if(isset($_FILES["file"]))
{
	$ret = array();
/*
// http://hayageek.com/docs/jquery-upload-file.php
//	This is for custom errors;	
	$custom_error= array();
	$custom_error['jquery-upload-file-error']="File already exists";
	echo json_encode($custom_error);
	die();
*/
	$error =$_FILES["file"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["file"]["name"])) //single file
	{
 	 	$fileName = $_FILES["file"]["name"];
 		move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$fileName);
    	$ret[]= $fileName;
		
        //If zip/compressed File
        // http://php.net/manual/en/class.ziparchive.php
        $type = $_FILES["file"]["type"];
        $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
        if(in_array($type,$accepted_types)) {

            $zip = new ZipArchive();
            $x = $zip->open($output_dir.$fileName);  // open the zip file to extract
            if ($x === true) {
                $zip->extractTo($output_dir); // place in the directory
                $zip->close();
                unlink($output_dir.$fileName); //Delete the .zipped file
            }
            // else error? echo
        }
	}
	else  //Multiple files, file[]
	{
        $fileCount = count($_FILES["file"]["name"]);
        for($i=0; $i < $fileCount; $i++)
        {
            $fileName = $_FILES["file"]["name"][$i];
            move_uploaded_file($_FILES["file"]["tmp_name"][$i],$output_dir.$fileName);
            $ret[]= $fileName;

            //If zip/compressed File
            $type = $_FILES["file"]["type"];
            $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
            if(in_array($type,$accepted_types)) {

                $zip = new ZipArchive();
                $x = $zip->open($output_dir.$fileName);  // open the zip file to extract
                if ($x === true) {
                    $zip->extractTo($output_dir); // place in the directory
                    $zip->close();
                    unlink($output_dir.$fileName); //Delete the .zip file
                }
                // else error? echo
            }  
        }
	}
    echo json_encode($ret);
 }

?>