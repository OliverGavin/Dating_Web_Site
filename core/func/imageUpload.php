<?php

//FILE LOCATIONS NEED TO BE CHANGED

include("resizeImage.php");

if(isset($_FILES['fileToUpload'])){
	$file = $_FILES['fileToUpload'];
	
	$fileName = $file['name'];
	$fileTmp = $file['tmp_name'];
	$fileSize = $file['size'];
	$fileError = $file['error'];
	
	$fileExt = explode('.', $fileName);
	$fileExt = strtolower(end($fileExt));
	
	$allowed = array('jpg', 'png', 'jpeg');
	
	if(in_array($fileExt, $allowed))//make sure the file extention is correct
	{
		if($fileSize <= 5242880)//5 Megabytes
		{
			if($fileError === 0)//Make sure there is no errors
			{
				
				$newFileName = '1123'.'.'.$fileExt; //FILE NAME uniqid('', true)
				$targetDir = 'images/' . $newFileName; // WERE IT WILL GO
				
				if(move_uploaded_file($fileTmp, $targetDir))
				{
					echo $targetDir;
					
				}
				else
				{
        			echo "Sorry, there was an error uploading your file.";
    			}
			}
			else
			{
				echo "There was an error uploading you file.";
			}
		}
		else
		{
			echo "Sorry the file size is too large.";	
		}
		
	}
	else
	{
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	}
}
else{
	echo "Your file was not uploaded please try again";
}

include_once("resize.php");
$resized_fileL = "images/resized250_$newFileName";
$resized_fileM = "images/resized150_$newFileName";
$resized_fileS = "images/resized60_$newFileName";
$wmaxL = 250;
$hmaxL = 250;
$wmaxM = 150;
$hmaxM = 150;
$wmaxS = 60;
$hmaxS = 60;
imageResize($targetDir, $resized_fileL, $wmaxL, $hmaxL, $fileExt);
imageResize($targetDir, $resized_fileM, $wmaxM, $hmaxM, $fileExt);
imageResize($targetDir, $resized_fileS, $wmaxS, $hmaxS, $fileExt);


?>
