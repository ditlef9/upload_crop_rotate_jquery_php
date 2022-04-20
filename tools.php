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

if(isset($_GET['image_counter'])){
	$image_counter = $_GET['image_counter'];
	$image_counter = strip_tags(stripslashes($image_counter));
	if(!(is_numeric($image_counter))){
		echo"<div class=\"info_error\"><p>image_counter not numeric</p></div>";
		die;
	}
	if(!(is_dir("$upload_path/ucrjphp/$image_counter"))){
		echo"<div class=\"info_error\"><p>image_counter not directory</p></div>";
		die;
	}
}
else{
	echo"<div class=\"info_error\"><p>Missing image_dir</p></div>";
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
	if(!(file_exists("$upload_path/ucrjphp/$image_counter/$image_src"))){
		echo"<div class=\"info_error\"><p>Image not found (<a href=\"$upload_path/ucrjphp/$image_counter/$image_src\">$upload_path/ucrjphp/$image_counter/$image_src</a>)</p></div>";
		die;
	}

	// Check file date
	$last_modified = filemtime("$upload_path/ucrjphp/$image_counter/$image_src");
	$diff = $time - $last_modified;
	if($diff > "43200"){
		echo"<div class=\"info_error\"><p>Image has been locked because it is has not been modified for over 12 hours</p></div>";
		die;
	}

	// Check that this is a image
	$imagesize = getimagesize("$upload_path/ucrjphp/$image_counter/$image_src");
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

	// EXT
	$ext = get_extension($image_src);

	// Delete old original
	unlink("$upload_path/ucrjphp/$image_counter/$image_src");

	// Names
	$image_src_without_ext = str_replace("_$image_ver.$ext", "", $image_src);
	$new_version = $image_ver+1;
	$new_image_name_version	 = $image_src_without_ext . "_" . $new_version . ".$ext";

	if($ext == "jpg"){
		// Load (cache file)
		$source = imagecreatefromjpeg("$cache_path/ucrjphp_tmp/$image_counter/$image_src");

		// Bg
		$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

		// Rotate
		$rotate = imagerotate($source, $deg, $bgColor);

		// Save to original
		imagejpeg($rotate, "$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	}
	elseif($ext == "png"){
		// Load
		$source = imagecreatefrompng("$cache_path/ucrjphp_tmp/$image_counter/$image_src");

		// Bg
		$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

		// Rotate
		$rotate = imagerotate($source, $deg, $bgColor);
	
		// Save to original
		imagesavealpha($rotate, true);
		imagepng($rotate, "$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	}
	else{
		echo"Unknown extension";
		die;
	}
		
	// Free the memory
	imagedestroy($source);

	// Copy original to new version
	copy("$upload_path/ucrjphp/$image_counter/$new_image_name_version", "$cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version");

	// Give feedback
	// echo"Ext: $ext<br />
	// Original: $upload_path/ucrjphp/$image_counter/$image_src<br />
	// New original: $upload_path/ucrjphp/$image_counter/$new_image_name_version<br />
	// Temp: $cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version ";
	echo"$upload_path/ucrjphp/$image_counter/$new_image_name_version";

} // rotate
elseif($tool == "load_image"){
	echo"$upload_path/ucrjphp/$image_counter/$image_src";
} // load image
elseif($tool == "crop"){

	if(isset($_GET['x'])){
		$x = $_GET['x'];
		$x = strip_tags(stripslashes($x));
		if(!(is_numeric($x))){
			echo"<div class=\"info_error\"><p>x not numeric</p></div>";
			die;
		}
	}
	else{
		echo"<div class=\"info_error\"><p>Missing x</p></div>";
		die;
	}

	if(isset($_GET['y'])){
		$y = $_GET['y'];
		$y = strip_tags(stripslashes($y));
		if(!(is_numeric($y))){
			echo"<div class=\"info_error\"><p>y not numeric</p></div>";
			die;
		}
	}
	else{
		echo"<div class=\"info_error\"><p>Missing y</p></div>";
		die;
	}

	if(isset($_GET['width'])){
		$width = $_GET['width'];
		$width = strip_tags(stripslashes($width));
		if(!(is_numeric($width))){
			echo"<div class=\"info_error\"><p>width not numeric</p></div>";
			die;
		}
	}
	else{
		echo"<div class=\"info_error\"><p>Missing width</p></div>";
		die;
	}

	if(isset($_GET['height'])){
		$height = $_GET['height'];
		$height = strip_tags(stripslashes($height));
		if(!(is_numeric($height))){
			echo"<div class=\"info_error\"><p>height not numeric</p></div>";
			die;
		}
	}
	else{
		echo"<div class=\"info_error\"><p>Missing height</p></div>";
		die;
	}


	// EXT
	$ext = get_extension($image_src);


	// Names
	$image_src_without_ext = str_replace("_$image_ver.$ext", "", $image_src);
	$new_version = $image_ver+1;
	$new_image_name_version	 = $image_src_without_ext . "_" . $new_version . ".$ext";

	if($ext == "jpg"){
		// Load (cache file)
		$im = imagecreatefromjpeg("$cache_path/ucrjphp_tmp/$image_counter/$image_src");

		// Size
		$size = min(imagesx($im), imagesy($im));

		// Crop
		$im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

		// Save to original
		imagejpeg($im2, "$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	}
	elseif($ext == "png"){
		// Load
		$source = imagecreatefrompng("$cache_path/ucrjphp_tmp/$image_counter/$image_src");


		// Size
		$size = min(imagesx($im), imagesy($im));

		// Crop
		$im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
	
		// Save to original
		imagesavealpha($im2, true);
		imagepng($im2, "$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	}
	else{
		echo"Unknown extension";
		die;
	}
		
	// Free the memory
	imagedestroy($im);


	// Copy original to new version
	copy("$upload_path/ucrjphp/$image_counter/$new_image_name_version", "$cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version");

	// Give feedback
	// echo"Ext: $ext<br />
	// Original: $upload_path/ucrjphp/$image_counter/$image_src<br />
	// New original: $upload_path/ucrjphp/$image_counter/$new_image_name_version<br />
	// Temp: $cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version ";
	echo"$upload_path/ucrjphp/$image_counter/$new_image_name_version";
} // crop
else{
	echo"Please select a tool!";
}

?>