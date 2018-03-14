<?php
// create a new folder in folderpath

if(isset($_POST["foldername"])) {
    
    $except = array('\\', '/', '|', ':', ';', '?', '"', "'",  '<', '>', '@','#','$','%','^','&','*','(',')', ','); 
    
    $fldr = $_POST["foldername"];
    $fldr = str_replace($except, '', $fldr);// no special characters
    $fldr = str_replace(" ", "_", $fldr);// no spaces
    
    
    $output_dir = $_POST["openfolder"];//"../../media/"
    if( mkdir($output_dir . $fldr, octdec(777)) ) {
        echo $fldr.' created.';// write to #message
    } else { echo $fldr.' FAILED!'; }
}

?>