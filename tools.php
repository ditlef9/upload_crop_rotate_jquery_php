<?php
/**
*
* File: tools.php
* Version 1.0
* Date 17:56 13.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Upload Settings --------------------------------------------------------- */
// Paths
$cache_path = "_cache";
$upload_path = "_uploads";

// Image config
$max_temp_width = "5000";
$max_temp_height = "5000";

/*- Functions -------------------------------------------------------------- */
include("_functions/get_extension.php");


/*- Variables ------------------------------------------------------------------------- */
$time = time();

if(isset($_GET['tool'])){
	$tool = $_GET['tool'];
	$tool = strip_tags(stripslashes($tool));
}
else{
	echo"<div class=\"info_error\"><p>Missing tool</p></div>";
	die;
}

if(isset($_GET['image_src'])){
	$image_src = $_GET['image_src'];
	$image_src = strip_tags(stripslashes($image_src));
	if (strpos($image_src, '/') !== false OR strpos($image_src, '\\') !== false OR strpos($image_src, '?') !== false OR strpos($image_src, '..') !== false) {
		echo"<div class=\"info_error\"><p>Invalid image_src</p></div>";
		die;
	}

	// Check that file exists
	if(!(file_exists("$upload_path/$image_src"))){
		echo"<div class=\"info_error\"><p>Image not found</p></div>";
		die;
	}

	// Check file date
	$last_modified = filemtime("$upload_path/$image_src");
	$diff = $time - $last_modified;
	if($diff > "43200"){
		echo"<div class=\"info_error\"><p>Image has been locked because it is has not been modified for over 12 hours</p></div>";
		die;
	}

	// Check that this is a image
	$imagesize = getimagesize("$upload_path/$image_src");
	if($imagesize[0] == "" OR $imagesize[1] == ""){
		echo"<div class=\"info_error\"><p>Not a image</p></div>";
		die;
	}
}
else{
	echo"<div class=\"info_error\"><p>Missing image_src</p></div>";
	die;
}

if(isset($_GET['image_ver'])){
	$image_ver = $_GET['image_ver'];
	$image_ver = strip_tags(stripslashes($image_ver));
	if(!(is_numeric($image_ver))){
		echo"<div class=\"info_error\"><p>image_ver not numeric</p></div>";
		die;
	}
}
else{
	echo"<div class=\"info_error\"><p>Missing image_ver</p></div>";
	die;
}
$next_image_version = $image_ver+1;

/*- Upload file ------------------------------------------------------------ */
if($tool == "rotate"){
	// Degree
	if(isset($_GET['deg'])){
		$deg = $_GET['deg'];
		$deg = strip_tags(stripslashes($deg));
		if(!(is_numeric($deg))){
			echo"<div class=\"info_error\"><p>degree not numeric</p></div>";
			die;
		}
	}
	else{
		echo"<div class=\"info_error\"><p>Missing degree</p></div>";
		die;
	}


	// Versions
	$ext = get_extension($image_src);
	$image_src_without_ext = str_replace("$ext", "", $image_src);
	$image_src_tmp = $image_src_without_ext . "_" . $next_image_version . ".$ext";

	if($ext == "jpg"){
		// Load
		$source = imagecreatefromjpeg("$upload_path/$image_src");

		// Rotate
		$rotate = imagerotate($source, $deg, 0);

		// Save
		imagejpeg($rotate, "$upload_path/$image_src");
	}
	elseif($ext == "png"){
		// Load
		$source = imagecreatefrompng("$upload_path/$image_src");

		// Bg
		$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

		// Rotate
		$rotate = imagerotate($source, $deg, $bgColor);
	
		// Save
		imagesavealpha($rotate, true);
		imagepng($rotate, "$upload_path/$image_src");
	}
	else{
		echo"Unknown extension";
		die;
	}
		
	// Free the memory
	imagedestroy($source);


} // rotate
else{

}



?>