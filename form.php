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

/*- Form Settings --------------------------------------------------------- */
// Paths
$jquery_file = "_javascripts/jquery/jquery.min.js";

// Config
$show_header_and_footer = "1";

// Image
$image_preview_width = 500;
$image_preview_height = 500;

/*- Header ---------------------------------------------------------------- */
if($show_header_and_footer == "1"){
	echo"<!DOCTYPE html>\n";
	echo"<html lang=\"en\">\n";
	echo"<head>\n";
	echo"	<title>Upload image</title>\n";
	echo"	<link rel=\"icon\" href=\"_gfx/favicon/ucrjphp_favicon_16x16.png\" type=\"image/png\" sizes=\"16x16\" />\n";
	echo"	<link rel=\"icon\" href=\"_gfx/favicon/ucrjphp_favicon_32x32.png\" type=\"image/png\" sizes=\"32x32\" />\n";
	echo"	<link rel=\"icon\" href=\"_gfx/favicon/ucrjphp_favicon_260x260.png\" type=\"image/png\" sizes=\"260x260\" />\n";
	$size = filesize("_gfx/file_uploader.css");
	echo"	<link rel=\"stylesheet\" href=\"_gfx/file_uploader.css?size=$size\" type=\"text/css\" >\n";
	echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
	echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
	echo"	<script type=\"text/javascript\" src=\"$jquery_file\"></script>\n";
	echo"</head>\n";
	echo"<body>\n";
}

/*- Scriptstart ----------------------------------------------------------- */
echo"

<!-- Image upload form -->
	<form method=\"post\" action=\"\" enctype=\"multipart/form-data\" id=\"ucrjphp_myform\">

		<div class=\"ucrjphp_image_preview\">
			<img src=\"_gfx/favicon/ucrjphp_favicon_512x512.png\" alt=\"ucrjphp_favicon_512x512.png\" id=\"ucrjphp_img\" width=\"$image_preview_width\" height=\"$image_preview_height\">
		</div>

		<div class=\"ucrjphp_image_tools\">
			<p>
			<a href=\"#\" id=\"rotate_right_ninety\"><img src=\"_gfx/icons/24x24/rotate_right_outline_black_24x24.svg\" alt=\"rotate_right_outline_black_24x24.png\" title=\"Roate 90&deg; right\" /></a>
			<a href=\"#\" id=\"rotate_left_ninety\"><img src=\"_gfx/icons/24x24/rotate_left_outline_black_24x24.png\" alt=\"rotate_left_outline_black_24x24.png\" title=\"Roate 90&deg; left\" /></a>
			<a href=\"#\" id=\"rotate_right_one\"><img src=\"_gfx/icons/24x24/rotate_right_one_outline_black_24x24.png\" alt=\"rotate_right_one_outline_black_24x24.png\" title=\"Roate 1&deg; right\" /></a>
			<a href=\"#\" id=\"rotate_left_one\"><img src=\"_gfx/icons/24x24/rotate_left_one_outline_black_24x24.png\" alt=\"rotate_left_one_outline_black_24x24.png\" title=\"Roate 1&deg; left\" /></a>
			<a href=\"#\" id=\"crop\"><img src=\"_gfx/icons/24x24/crop_outline_black_24x24.png\" alt=\"crop_outline_black_24x24.png\" title=\"Crop\" /></a>
			
			</p>
		</div>

		<div id=\"ucrjphp_upload_feedback\">
			<div class=\"info_small\">
				<p>Please select a file to upload</p>
			</div>
		</div>

		<div class=\"ucrjphp_upload_form\">
            		<p>
			<input type=\"file\" id=\"file\" name=\"file\" />
			<input type=\"hidden\" id=\"ucrjphp_image_version\" name=\"inp_ucrjphp_image_version\" value=\"0\" />
            		<input type=\"button\" class=\"button\" value=\"Upload\" id=\"ucrjphp_but_upload\">
            		</p>
		</div>
	</form>
<!-- //Image upload form -->


<!-- Ajax JavaScript File Upload Logic -->
<script>
$(document).ready(function(){
	$(\"#ucrjphp_but_upload\").click(function(){

		// Feedback :: Loading
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p><img src='_gfx/icons/18x18/spinner_black_18x18.png' alt='spinner_black_18x18.png' /> Uploading image...</p></div>\");

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
                 		if(response.charAt(0) == 1){
					// Feedback
					\$(\"#ucrjphp_upload_feedback\").html(\"<div class='success_small'><p>Uploaded</p></div>\");
					\$(\"#ucrjphp_upload_feedback\").children().delay(5000).fadeOut(800);

					// Remove first string
					var uploadedImageSrc = response.substring(1, response.length);
                    			\$(\"#ucrjphp_img\").attr(\"src\", uploadedImageSrc); 

					// Display image
                    			\$(\".ucrjphp_image_preview img\").show(); // Display image element
					\$(\".ucrjphp_image_preview\").show();

					// Display tools
					\$(\".ucrjphp_image_tools\").show();

					// Hide upload form
					\$(\"#ucrjphp_upload_form\").children().delay(5000).fadeOut(800);
					
                 		}
				else{
					// Feedback :: Error
					\$(\"#ucrjphp_upload_feedback\").html(response);
                 		}
          		},
			});
		}
		else{
			// Feedback :: Warning
			\$(\"#ucrjphp_upload_feedback\").html(\"<div class='warning_small'><p>Please select a file</p></div>\");
		}
	}); // Upload


	$(\"#rotate_right_ninety\").click(function(){
		// Feedback :: Loading
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p><img src='_gfx/icons/18x18/spinner_black_18x18.png' alt='spinner_black_18x18.png' /> Rotating right 90&deg;...</p></div>\");
		\$(\"#ucrjphp_upload_feedback\").children().fadeIn(800);

           	// ajax call
		var imagePath = $(\".ucrjphp_image_preview img\").attr('src');
		var n = imagePath.lastIndexOf('/');
		var imageSrc = imagePath.substring(n + 1);
		
		var imageVersion = \$('[name=\"inp_ucrjphp_image_version\"]').val();


		console.log('Rotate 90deg ' + imageSrc + ' version= ' + imageVersion);
		var data = 'tool=rotate&deg=90&image_src='+ imageSrc + '&image_ver=' + imageVersion;
            	\$.ajax({
                	type: \"GET\",
               		url: \"tools.php\",
                	data: data,
			beforeSend: function(html) { // this happens before actual call
				
			},
               		success: function(html){
				\$(\"#ucrjphp_upload_feedback\").html(html);
				\$(\"#ucrjphp_upload_feedback\").children().delay(5000).fadeOut(800);
              		}
            	});
	}); // Rotate 90


});
</script>




";


/*- Footer ---------------------------------------------------------------- */
if($show_header_and_footer == "1"){
	echo"</body>\n";
	echo"<html>";
}
?>