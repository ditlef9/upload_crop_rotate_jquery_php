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
	echo"	<link rel=\"stylesheet\" href=\"_gfx/file_uploader.css?size="; echo filesize("_gfx/file_uploader.css"); echo"\" type=\"text/css\" >\n";
	echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
	echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
	echo"	<script type=\"text/javascript\" src=\"_javascripts/jquery/jquery.min.js\"></script>\n";

	echo"	<script src=\"_javascripts/rcrop/dist/rcrop.min.js\" ></script>\n";
	echo"	<link href=\"_javascripts/rcrop/dist/rcrop.min.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\">\n";
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
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"-90\"><img src=\"_gfx/icons/24x24/rotate_right_outline_black_24x24.svg\" alt=\"rotate_right_outline_black_24x24.png\" title=\"Roate 90&deg; right\" /></a>
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"90\"><img src=\"_gfx/icons/24x24/rotate_left_outline_black_24x24.png\" alt=\"rotate_left_outline_black_24x24.png\" title=\"Roate 90&deg; left\" /></a>
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"-1\"><img src=\"_gfx/icons/24x24/rotate_right_one_outline_black_24x24.png\" alt=\"rotate_right_one_outline_black_24x24.png\" title=\"Roate 1&deg; right\" /></a>
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"1\"><img src=\"_gfx/icons/24x24/rotate_left_one_outline_black_24x24.png\" alt=\"rotate_left_one_outline_black_24x24.png\" title=\"Roate 1&deg; left\" /></a>
			<a href=\"#\" id=\"ucrjphp_crop\"><img src=\"_gfx/icons/24x24/crop_outline_black_24x24.png\" alt=\"crop_outline_black_24x24.png\" id=\"ucrjphp_crop_icon\" title=\"Crop\" /></a>
			
			<input type=\"text\" name=\"inp_ucrjphp_image_version\" value=\"0\" size=\"1\" autocomplete=\"false\" />
			</p>
		</div>

		<div id=\"ucrjphp_upload_feedback\">
			<div class=\"info_small\">
				<p>Please select a file to upload</p>
			</div>
		</div>

		<div class=\"ucrjphp_upload_form\">
            		<p>
			<input type=\"file\" id=\"ucrjphp_file\" name=\"file\" />
            		</p>
		</div>
	</form>
<!-- //Image upload form -->


<!-- Ajax JavaScript File Upload Logic -->


<script>
$(document).ready(function(){
	\$('#ucrjphp_file').change(function(evt) {
		// Feedback :: Loading
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p><img src='_gfx/icons/18x18/spinner_black_18x18.png' alt='spinner_black_18x18.png' /> Uploading image...</p></div>\");

  		var fd = new FormData();
		var files = $('#ucrjphp_file')[0].files;


        
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
					\$('.ucrjphp_image_preview img').attr('title', uploadedImageSrc); 
					\$('.ucrjphp_image_preview img').attr('alt', uploadedImageSrc); 


					// Display tools
					\$(\".ucrjphp_image_tools\").show();
				\$('[name=\"inp_ucrjphp_image_version\"]').val(0);

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


	$(\".ucrjphp_rotate\").click(function(){
		// Get degree
		var deg = \$(this).attr(\"data-deg\");

		// Feedback :: Loading
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p><img src='_gfx/icons/18x18/spinner_black_18x18.png' alt='spinner_black_18x18.png' /> Rotating \" + deg  + \"&deg;...</p></div>\");
		\$(\"#ucrjphp_upload_feedback\").children().fadeIn(800);

           	// ajax call
		var imagePath = $(\".ucrjphp_image_preview img\").attr('src');
		var lastIndexOf = imagePath.lastIndexOf('/');
		var imageSrc = imagePath.substring(lastIndexOf + 1);


		var imageCounter = imagePath.substring(lastIndexOf - 1);
		var imageCounter = imagePath.split('/').reverse()[1];
		
		var imageVersion = parseInt(\$('[name=\"inp_ucrjphp_image_version\"]').val());
		var newImageVersion = imageVersion+1;
		\$('[name=\"inp_ucrjphp_image_version\"]').val(newImageVersion);

		console.log('Rotate 90deg imagePhat=' + imagePath + ' imageCounter =' + imageCounter + ' src=' + imageSrc + ' version=' + imageVersion);
		var data = 'tool=rotate&deg=' + deg + '&image_counter=' + imageCounter + '&image_src='+ imageSrc + '&image_ver=' + imageVersion;
            	\$.ajax({
                	type: \"GET\",
               		url: \"tools.php\",
                	data: data,
			beforeSend: function(html) { // this happens before actual call
				
			},
               		success: function(html){
				// Feedback
				\$(\"#ucrjphp_upload_feedback\").html(\"<div class='success_small'><p>Rotated \" + deg  + \"&deg; (\" + html + \")</p></div>\");
				\$(\"#ucrjphp_upload_feedback\").children().delay(20000).fadeOut(800);

				// Display image
                    		\$(\"#ucrjphp_img\").attr(\"src\", html); 
				\$('.ucrjphp_image_preview img').attr('title', html);
				\$('.ucrjphp_image_preview img').attr('alt', html);
              		}
            	});
	}); // Rotate x deg

	$(\"#ucrjphp_crop\").click(function(){

		// Change crop icon
		var imagePath = \$('#ucrjphp_crop_icon').attr('src');
		var lastIndexOf = imagePath.lastIndexOf('/');
		var currentCropSrc = imagePath.substring(lastIndexOf + 1);
		var cropIsActive = 1;
		if(currentCropSrc === 'crop_outline_black_24x24.png'){
			var cropIsActive = 1;
                    	\$(\"#ucrjphp_crop_icon\").attr(\"src\", \"_gfx/icons/24x24/crop_remove_outline_black_24x24.png\"); 
		}
		else{
			var cropIsActive = 0;
                    	\$(\"#ucrjphp_crop_icon\").attr(\"src\", \"_gfx/icons/24x24/crop_outline_black_24x24.png\"); 
		}
		
		// Feedback
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p>Crop tool loaded \" + cropIsActive + \"</p></div>\");
		\$(\"#ucrjphp_upload_feedback\").children().delay(20000).fadeOut(800);


		// Activate or deactivate crop
		if(cropIsActive === '1'){
			\$('#ucrjphp_img').rcrop({
				grid : true
			});

		}
		else{
			\$('#ucrjphp_img').rcrop({
				grid : false
			});
		}

	}); // Crop



});
</script>




";


/*- Footer ---------------------------------------------------------------- */
if($show_header_and_footer == "1"){
	echo"</body>\n";
	echo"<html>";
}
?>