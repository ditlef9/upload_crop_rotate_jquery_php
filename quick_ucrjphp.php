<?php
/**
*
* File: quick_ucrjphp.php
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

$target_width = "1280";
$target_height = "1280";

/*- Functions -------------------------------------------------------------- */
include("_functions/get_extension.php");
include("_functions/resize_crop_image.php");


/*- Variables ------------------------------------------------------------------------- */
$time = time();

$tool = "";
if(isset($_GET['tool'])){
	$tool = $_GET['tool'];
	$tool = strip_tags(stripslashes($tool));
}

$image_counter = -1;
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

$image_file = "";
if(isset($_GET['image_file'])){
	$image_file = $_GET['image_file'];
	$image_file = strip_tags(stripslashes($image_file));
	if (strpos($image_file, '/') !== false OR strpos($image_file, '\\') !== false OR strpos($image_file, '?') !== false OR strpos($image_file, '..') !== false) {
		echo"<div class=\"info_error\"><p>Invalid image_file $image_file</p></div>";
		die;
	}

	// Check that file exists
	if(!(file_exists("$upload_path/ucrjphp/$image_counter/$image_file"))){
		echo"<div class=\"info_error\"><p>Image not found (<a href=\"$upload_path/ucrjphp/$image_counter/$image_file\">$upload_path/ucrjphp/$image_counter/$image_file</a>)</p></div>";
		die;
	}

	// Check file date
	$last_modified = filemtime("$upload_path/ucrjphp/$image_counter/$image_file");
	$diff = $time - $last_modified;
	if($diff > "43200"){
		echo"<div class=\"info_error\"><p>Image has been locked because it is has not been modified for over 12 hours</p></div>";
		die;
	}

	// Check that this is a image
	$imagesize = getimagesize("$upload_path/ucrjphp/$image_counter/$image_file");
	if($imagesize[0] == "" OR $imagesize[1] == ""){
		echo"<div class=\"info_error\"><p>Not a image</p></div>";
		die;
	}
}

$image_ver = -1;
if(isset($_GET['image_ver'])){
	$image_ver = $_GET['image_ver'];
	$image_ver = strip_tags(stripslashes($image_ver));
	if(!(is_numeric($image_ver))){
		echo"<div class=\"info_error\"><p>image_ver not numeric</p></div>";
		die;
	}
}

/*- Upload file ------------------------------------------------------------ */
if($tool == "rotate"){
	if($image_counter == "-1" OR  $image_file == "" OR $image_ver == "-1"){
		echo"<div class=\"info_error\"><p>Invalid input variables</p></div>";
		die;
	}
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
	$ext = get_extension($image_file);

	// Delete old original
	unlink("$upload_path/ucrjphp/$image_counter/$image_file");

	// Names
	$image_file_without_ext = str_replace("_$image_ver.$ext", "", $image_file);
	$new_version = $image_ver+1;
	$new_image_name_version	 = $image_file_without_ext . "_" . $new_version . ".$ext";

	if($ext == "jpg"){
		// Load (cache file)
		$source = imagecreatefromjpeg("$cache_path/ucrjphp_tmp/$image_counter/$image_file");

		// Bg
		$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

		// Rotate
		$rotate = imagerotate($source, $deg, $bgColor);

		// Save to original
		imagejpeg($rotate, "$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	}
	elseif($ext == "png"){
		// Load
		$source = imagecreatefrompng("$cache_path/ucrjphp_tmp/$image_counter/$image_file");

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


	// We now have the file saved at upload path
	// We need to copy it to temp
	// Copy original to new version
	copy("$upload_path/ucrjphp/$image_counter/$new_image_name_version", "$cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version");

	// Resize target
	$imagesize = getimagesize("$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	$width = $imagesize[0];
	$height = $imagesize[1];
	if($width > $target_width OR $height > $target_height){
		// Resize and crop target to desired width and height
		resize_crop_image($target_width, $target_height, "$upload_path/ucrjphp/$image_counter/$new_image_name_version", "$upload_path/ucrjphp/$image_counter/$new_image_name_version", $quality = 80);
	}



	// Give feedback
	// echo"Ext: $ext<br />
	// Original: $upload_path/ucrjphp/$image_counter/$image_file<br />
	// New original: $upload_path/ucrjphp/$image_counter/$new_image_name_version<br />
	// Temp: $cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version ";
	echo"$upload_path/ucrjphp/$image_counter/$new_image_name_version?width=$width&height=$height&target_width=$target_width&target_height=$target_height&max_temp_width=$max_temp_width&max_temp_height=$max_temp_height";

} // rotate
elseif($tool == "load_image"){
	if($image_counter == "-1" OR  $image_file == "" OR $image_ver == "-1"){
		echo"<div class=\"info_error\"><p>Invalid input variables</p></div>";
		die;
	}
	echo"$upload_path/ucrjphp/$image_counter/$image_file";
} // load image
elseif($tool == "crop"){
	if($image_counter == "-1" OR  $image_file == "" OR $image_ver == "-1"){
		echo"<div class=\"info_error\"><p>Invalid input variables</p></div>";
		die;
	}
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
	$ext = get_extension($image_file);


	// Names
	$image_file_without_ext = str_replace("_$image_ver.$ext", "", $image_file);
	$new_version = $image_ver+1;
	$new_image_name_version	 = $image_file_without_ext . "_" . $new_version . ".$ext";

	if($ext == "jpg"){
		// Load (cache file)
		$im = imagecreatefromjpeg("$cache_path/ucrjphp_tmp/$image_counter/$image_file");

		// Size
		$size = min(imagesx($im), imagesy($im));

		// Crop
		$im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

		// Save to original
		imagejpeg($im2, "$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	}
	elseif($ext == "png"){
		// Load
		$source = imagecreatefrompng("$cache_path/ucrjphp_tmp/$image_counter/$image_file");


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



	// We now have the file saved at upload path
	// We need to copy it to temp
	// Copy original to new version
	copy("$upload_path/ucrjphp/$image_counter/$new_image_name_version", "$cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version");

	// Resize target
	$imagesize = getimagesize("$upload_path/ucrjphp/$image_counter/$new_image_name_version");
	$width = $imagesize[0];
	$height = $imagesize[1];
	if($width > $target_width OR $height > $target_height){
		// Resize and crop target to desired width and height
		resize_crop_image($target_width, $target_height, "$upload_path/ucrjphp/$image_counter/$new_image_name_version", "$upload_path/ucrjphp/$image_counter/$new_image_name_version", $quality = 80);
	}



	// Give feedback
	// echo"Ext: $ext<br />
	// Original: $upload_path/ucrjphp/$image_counter/$image_file<br />
	// New original: $upload_path/ucrjphp/$image_counter/$new_image_name_version<br />
	// Temp: $cache_path/ucrjphp_tmp/$image_counter/$new_image_name_version ";
	echo"$upload_path/ucrjphp/$image_counter/$new_image_name_version?width=$width&height=$height&target_width=$target_width&target_height=$target_height&max_temp_width=$max_temp_width&max_temp_height=$max_temp_height";
} // crop
else{
	if(isset($_FILES['file']['name'])){

		// Make directories
		if(!(is_dir("$cache_path"))){
			mkdir("$cache_path");
		}
		if(!(is_dir("$cache_path/ucrjphp_tmp"))){
			mkdir("$cache_path/ucrjphp_tmp");
		}
		if(!(is_dir("$upload_path"))){
			mkdir("$upload_path");
		}
		if(!(is_dir("$upload_path/ucrjphp"))){
			mkdir("$upload_path/ucrjphp");
		}

		// Counter
		$counter = 0;
		if(file_exists("$upload_path/ucrjphp/counter.txt")){
			$myfile = fopen("$upload_path/ucrjphp/counter.txt", "r") or die("Unable to open file!");
			$counter = fread($myfile,filesize("$upload_path/ucrjphp/counter.txt"));
			fclose($myfile);
		}
		$counter++;
		$myfile = fopen("$upload_path/ucrjphp/counter.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $counter);
		fclose($myfile);

		if(!(is_dir("$cache_path/ucrjphp_tmp/$counter"))){
			mkdir("$cache_path/ucrjphp_tmp/$counter");
		}
		if(!(is_dir("$upload_path/ucrjphp/$counter"))){
			mkdir("$upload_path/ucrjphp/$counter");
		}

		// Getting file name
		$filename = stripslashes($_FILES['file']['name']);
		$ext = get_extension($filename);
		$filename_without_ext = str_replace(".$ext", "", $filename);

		// Location
		$uploaded_file_tmp 	= "$cache_path/ucrjphp_tmp/$counter/" . $filename_without_ext . "_0." . $ext;
		$uploaded_file_target 	= "$upload_path/ucrjphp/$counter/" . $filename_without_ext . "_0." . $ext;

		$image_file_type = pathinfo($uploaded_file_tmp, PATHINFO_EXTENSION);
		$image_file_type = strtolower($image_file_type);

		// Valid extensions
		$valid_extensions = array("jpg", "jpeg", "png");

		// Feedback
		$fm_image = "?";
		$ft_image = "";

		// Check file extension
		if(in_array(strtolower($image_file_type), $valid_extensions)) {
			// Upload file
			if(move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file_tmp)){

				// Get image size
				$file_size = filesize($uploaded_file_tmp);
						
				// Check with and height
				list($width,$height) = getimagesize($uploaded_file_tmp);
		
				if($width == "" OR $height == ""){
					$ft_image = "warning";
					$fm_image = "getimagesize_failed";
					unlink($uploaded_file);
					echo"<div class=\"$fm_image\"><p>$ft_image</p></div>";

				}
				else{
					// Resize to tmp max width and height
					if($width > $max_temp_width OR $height > $max_temp_height){
						resize_crop_image($max_temp_width, $max_temp_height, $uploaded_file_tmp, $uploaded_file_tmp, $quality = 80);
					}

					// Copy to target 
					if($width > $target_width OR $height > $target_height){
						// Resize and crop target to desired width and height
						resize_crop_image($target_width, $target_height, $uploaded_file_tmp, $uploaded_file_target, $quality = 80);
					}
					else{
						// Copy
						copy($uploaded_file_tmp, $uploaded_file_target);
					}


					// Give feedback
					echo"1$uploaded_file_tmp?width=$width&height=$height&target_width=$target_width&target_height=$target_height&max_temp_width=$max_temp_width&max_temp_height=$max_temp_height";
					exit;
				} // width and height ok
			}
			else{ // move uploaded file
				switch ($_FILES["$inp_names_array[$x]"]['error']) {
					case UPLOAD_ERR_OK:
      						$fm_image = "There is no error, the file uploaded with success.";
						$ft_image = "info_small";
						break;
					case UPLOAD_ERR_NO_FILE:
      						// $fm_image = "no_file_uploaded";
						$ft_image = "info_small";
						break;
					case UPLOAD_ERR_INI_SIZE:
      						$fm_image = "to_big_size_in_configuration";
						$ft_image = "info_small";
					break;
					case UPLOAD_ERR_FORM_SIZE:
      						$fm_image = "to_big_size_in_form";
						$ft_image = "info_small";
					break;
					default:
      						$fm_image = "unknown_error";
						$ft_image = "info_small";
					break;
				} // switch
				echo"<div class=\"$fm_image\"><p>$ft_image</p></div>";
				exit;
			} // move uploaded file failed
		} // valid extension
	}
	else{
		echo"<div class=\"info_small\"><p>No image selected</p></div>";
	}
} // upload

?>