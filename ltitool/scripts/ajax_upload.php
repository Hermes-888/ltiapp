<?php
include("resize-class.php");
// validate if image && < Approx. 900kb files can be uploaded.
if(isset($_FILES["uploadedfile"]["type"]))
{
	$validextensions = array("jpeg", "jpg", "png");
	$temporary = explode(".", $_FILES["uploadedfile"]["name"]);
	$randnum = rand(100, 999);
	$file_name = current($temporary).$randnum.'.jpg';
	$file_extension = end($temporary);
	
	if ((($_FILES["uploadedfile"]["type"] == "image/png") || ($_FILES["uploadedfile"]["type"] == "image/jpg") || ($_FILES["uploadedfile"]["type"] == "image/jpeg")) && ($_FILES["uploadedfile"]["size"] < 9000000) && in_array($file_extension, $validextensions)) {
		
		if ($_FILES["uploadedfile"]["error"] > 0) {
			echo "Return Code: " . $_FILES["uploadedfile"]["error"] . "<br/><br/>";
		} else {
			$sourcePath = $_FILES['uploadedfile']['tmp_name']; // Storing source path of the file in a variable
			$targetPath = "../../thumbs/".basename( $_FILES['uploadedfile']['name']);// Target path where file is to be stored
			move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
			// success data #message
			$mssg="<span id='success'>Image Uploaded Successfully</span><br/><b>File Name:</b> " . $_FILES["uploadedfile"]["name"];
			
			echo $mssg.'&'.$file_name;
			/*
			echo "<br/><b>Type:</b> " . $_FILES["uploadedfile"]["type"] . "<br>";
			echo "<b>Size:</b> " . ($_FILES["uploadedfile"]["size"] / 1024) . " kB<br>";
			echo "<b>Temp file:</b> " . $_FILES["uploadedfile"]["tmp_name"] . "<br>";
			*/
			//http://code.tutsplus.com/tutorials/image-resizing-made-easy-with-php--net-10362
			// *** Include the class-resize
			$file1=$targetPath;
			// *** 1) Initialize / load image
			$resizeObj = new resize($file1);
			 
			// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
			$resizeObj -> resizeImage(220, 154, 'crop');
			 
			// *** 3) Save image  (tool/scripts)
            chmod('../../thumbs/', octdec(777));
			$resizeObj -> saveImage('../../thumbs/'.$file_name, 100);//best quality
            chmod('../../thumbs/', octdec(755));// read only
			// *** 4) delete the original image
			unlink($targetPath);
		}
		
	} else {
		echo "<span id='invalid'>***Invalid file Size or Type***<span>";
	}
}

if(isset($_POST["imagepath"])) {
    
    // get name from path
    $temp = explode("/", $_POST["imagepath"]);
    $temporary = explode(".", end($temp));
	$randnum = rand(100, 999);
	$file_name = current($temporary).$randnum.'.jpg';
    
    $sourcePath = $_POST["imagepath"];
	$targetPath = "../../thumbs/".basename( end($temp) );// path where file is to be stored
    //move_uploaded_file($sourcePath,$targetPath) ;
    if(!copy($_POST["imagepath"], $targetPath)) {
        echo"failed to copy ".$_POST["imagepath"];// /extools/seconded/media/miscimages/ethics-quiz.pngethics-quiz411.jpg
    } else { echo $file_name; }
    
    $file1=$targetPath;//$_POST["imagepath"];//
    // *** 1) Initialize / load image
    $resizeObj = new resize($file1);

    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj -> resizeImage(220, 154, 'crop');

    // *** 3) Save image  (tool/scripts)
    chmod('../../thumbs/', octdec(777));
    $resizeObj -> saveImage('../../thumbs/'.$file_name, 100);//best quality
    chmod('../../thumbs/', octdec(755));
    unlink($targetPath);
    
}
?>