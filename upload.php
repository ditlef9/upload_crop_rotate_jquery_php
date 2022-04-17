<?php
/**
*
* File: upload_image_uploader.php
* Version 1.0
* Date 17:56 13.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Settings -------------------------------------------------------------- */
// Paths
$cache_path = "_cache";
$upload_path = "_uploads";
$jquery_file = "_javascripts/jquery/jquery.min.js";
$ucrjp_file = "_javascripts/upload_crop_rotate_jquery_php/upload_crop_rotate_jquery_php.js";

// Config
$show_header_and_footer = "1";


/*- Upload file ------------------------------------------------------------ */


if(isset($_FILES['file']['name'])){

	// Make directories
	if(!(is_dir("$cache_path"))){
		mkdir("$cache_path");
	}
	if(!(is_dir("$upload_path"))){
		mkdir("$upload_path");
	}

	/* Getting file name */
	$filename = $_FILES['file']['name'];

	/* Location */
	$location = "$cache_path/".$filename;
	$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	$imageFileType = strtolower($imageFileType);

	/* Valid extensions */
	$valid_extensions = array("jpg","jpeg","png");

	// Feedback
	$fm_image = "?";
	$ft_image = "";

	/* Check file extension */
	if(in_array(strtolower($imageFileType), $valid_extensions)) {
      	/* Upload file */
		if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
			echo"1$location";
			exit;
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
		}
	

	}
	echo"<div class=\"$fm_image\"><p>$ft_image</p></div>";
	exit;
}

echo"<div class=\"info_small\"><p>No image selected</p></div>";


?>