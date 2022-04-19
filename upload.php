<?php
/**
*
* File: upload.php
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
include("_functions/resize_crop_image.php");
include("_functions/get_extension.php");

/*- Upload file ------------------------------------------------------------ */


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
	if(file_exists("$cache_path/ucrjphp_tmp/counter.txt")){
		$myfile = fopen("$cache_path/ucrjphp_tmp/counter.txt", "r") or die("Unable to open file!");
		$counter = fread($myfile,filesize("$cache_path/ucrjphp_tmp/counter.txt"));
		fclose($myfile);
	}
	$counter++;
	$myfile = fopen("$cache_path/ucrjphp_tmp/counter.txt", "w") or die("Unable to open file!");
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
				copy($uploaded_file_tmp, $uploaded_file_target);

				// Give feedback
				echo"1$uploaded_file_tmp";

			
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


?>