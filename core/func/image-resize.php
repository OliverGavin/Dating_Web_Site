<?php

//SHOULD BE COMPLETELY FINISHED

function imageResize($target, $newcopy, $w, $h, $ext)
{
	// Function for resizing jpg, gif, or png image files
   /* list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
	if($ext =="png")
	{
      $img = imagecreatefrompng($target);
    }
	else
	{
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 80);

	*/




	$img = "";
    $ext = strtolower($ext);
	if($ext =="png")
	{
      $img = imagecreatefrompng($target);
    }
	else
	{
      $img = imagecreatefromjpeg($target);
    }

//$filename = 'imagestest/cropped_whatever.jpg'; //this is newcopy

$width = imagesx($img);
$height = imagesy($img);

$originalAspect = $width / $height;
$thumbAspect = $w / $h;

if ( $originalAspect >= $thumbAspect )
{
   // If image is wider than thumbnail (in aspect ratio sense)
   $new_height = $h;
   $new_width = $width / ($height / $h);
}
else
{
   // If the thumbnail is wider than the image
   $new_width = $w;
   $new_height = $height / ($width / $w);
}

$thumb = imagecreatetruecolor( $w, $h );

// Resize and crop
imagecopyresampled($thumb, $img,
                   0 - ($new_width - $w) / 2, // Center the image horizontally
                   0 - ($new_height - $h) / 2, // Center the image vertically
                   0, 0,
                   $new_width, $new_height,
                   $width, $height);
imagejpeg($thumb, $newcopy, 80);

}

?>
