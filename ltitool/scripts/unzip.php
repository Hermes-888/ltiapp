<?php
/*
    post path and server+filepath to
    http://stackoverflow.com/questions/11401706/php-zip-archive-open-or-extractto-is-not-working
*/
if(isset($_POST['filename'])) {
    
    $zipfile = $_POST['filename'];
    $output_dir = $_POST['path'];
    
    //chmod($zipfile, 0775);// set permissions of zip file
    $zip = new ZipArchive();
    //$x = $zip->open($zipfile);  // open the zip file to extract
    $x = $zip->open($_SERVER['DOCUMENT_ROOT'].$zipfile);  // open the zip file to extract
    if ($x === true) {
        $result = $zip->extractTo($output_dir); // in the selected directory
        if ($result === false) {
            error_log("Could NOT extract zip file: $result", 1, "tjones@uvu.edu");// send errors to me
        }
        $zip->close();
        //unlink($zipfile); //Delete the .zipped file

    } else {
        error_log("Could NOT open zip file: $x $zipfile", 1, "tjones@uvu.edu");// send errors to me
    }
    
}

?>