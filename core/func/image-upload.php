<?php

// TODO Delete

include("image-resize.php");

	$file = $_FILES['fileToUpload'];
	
	$fileName = $file['name'];
	$fileTmp = $file['tmp_name'];
	$fileSize = $file['size'];
	$fileError = $file['error'];
	
	$fileExt = explode('.', $fileName);
	$fileExt = strtolower(end($fileExt));
	
	$allowed = array('jpg', 'png', 'jpeg');
	
	if(in_array($fileExt, $allowed))//make sure the file extension is correct
	{
		if($fileSize <= 5242880)//5 Megabytes
		{
			if($fileError === 0)//Make sure there is no errors
			{
				
				$newFileName = $user_id.'.jpg'; //FILE NAME uniqid('', true)
				$targetDir = 'images/profiles/' . $newFileName; // WERE IT WILL GO
				
//				if(move_uploaded_file($fileTmp, $targetDir))
//				{
//					echo $targetDir;
					$resized_fileM = 'images/profiles/'.IMG_MEDIUM.'_'.$newFileName;
					$resized_fileS = 'images/profiles/'.IMG_SMALL.'_'.$newFileName;
					$resized_fileT = 'images/profiles/'.IMG_THUMB.'_'.$newFileName;
					$wmaxM = IMG_MEDIUM;
					$hmaxM = IMG_MEDIUM;
					$wmaxS = IMG_SMALL;
					$hmaxS = IMG_SMALL;
					$wmaxT = IMG_THUMB;
					$hmaxT = IMG_THUMB;
					imageResize($fileTmp, $resized_fileM, $wmaxM, $hmaxM, $fileExt);
					imageResize($fileTmp, $resized_fileS, $wmaxS, $hmaxS, $fileExt);
					imageResize($fileTmp, $resized_fileT, $wmaxT, $hmaxT, $fileExt);
					
//				}
//				else
//				{
//        			echo "Sorry, there was an error uploading your file.";
//    			}
			}
			else
			{
				$_SESSION['form_errors']['profile_image'] = "There was an error uploading you file.";
			}
		}
		else
		{
			$_SESSION['form_errors']['profile_image'] = "Sorry the file size is too large.";
		}
		
	}
	else
	{
		$_SESSION['form_errors']['profile_image'] = "Sorry, only JPG, JPEG & PNG files are allowed.";
	}



?>
