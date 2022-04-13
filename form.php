<?php
/**
*
* File: upload_image.php
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
$upload_js_file = "_javascripts/upload_crop_rotate_jquery_php/upload_js.js";

// Config
$show_header_and_footer = "1";

/*- Header ---------------------------------------------------------------- */
if($show_header_and_footer == "1"){
	echo"<!DOCTYPE html>\n";
	echo"<html lang=\"en\">\n";
	echo"<head>\n";
	echo"	<title>Upload image</title>\n";
	echo"	<link rel=\"icon\" href=\"_gfx/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />\n";
	echo"	<link rel=\"icon\" href=\"_gfx/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />\n";
	echo"	<link rel=\"icon\" href=\"_gfx/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />\n";
	echo"	<link rel=\"stylesheet\" href=\"_gfx/file_uploader.css\" type=\"text/css\" >\n";
	echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
	echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
	echo"	<script type=\"text/javascript\" src=\"$jquery_file\"></script>\n";
	echo"</head>\n";
	echo"<body>\n";
}

/*- Scriptstart ----------------------------------------------------------- */
echo"

  <!-- HTML5 Input Form Elements -->
<div class=\"container\">
    <form method=\"post\" action=\"\" enctype=\"multipart/form-data\" id=\"myform\">
        <div class='preview'>
            <img src=\"\" id=\"img\" width=\"100\" height=\"100\">
        </div>
        <div >
            <input type=\"file\" id=\"file\" name=\"file\" />
            <input type=\"button\" class=\"button\" value=\"Upload\" id=\"but_upload\">
        </div>
    </form>
</div>

  <!-- Ajax JavaScript File Upload Logic -->
  <script>
  $(document).ready(function(){

    $(\"#but_upload\").click(function(){

        var fd = new FormData();
        var files = $('#file')[0].files;
        
        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);

           $.ajax({
              url: 'upload.php',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                 if(response != 0){
                    $(\"#img\").attr(\"src\",response); 
                    $(\".preview img\").show(); // Display image element
                 }else{
                    alert('file not uploaded');
                 }
              },
           });
        }else{
           alert(\"Please select a file.\");
        }
    });
});
  </script>




";


/*- Footer ---------------------------------------------------------------- */
if($show_header_and_footer == "1"){
	echo"</body>\n";
	echo"<html>";
}
?>