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

   /* Getting file name */
   $filename = $_FILES['file']['name'];

   /* Location */
   $location = "_cache/".$filename;
   $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
   $imageFileType = strtolower($imageFileType);

   /* Valid extensions */
   $valid_extensions = array("jpg","jpeg","png");

   $response = 0;
   /* Check file extension */
   if(in_array(strtolower($imageFileType), $valid_extensions)) {
      /* Upload file */
      if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
         $response = $location;
      }
   }

   echo $response;
   exit;
}

echo 0;


?>