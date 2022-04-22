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

// Finish URL
$finish_url = "form.php";

/*- Send file ------------------------------------------------------------ */
if(isset($_GET['ucrjphp_action'])){
	echo"Here you can do something when the user presses <b>Finish</b>..";
	die;
}

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

	<div class=\"ucrjphp_wrapper\" style=\"width: $image_preview_width";echo"px;\">

	<form method=\"get\" action=\"$finish_url\" enctype=\"multipart/form-data\" id=\"ucrjphp_myform\">

		<div class=\"ucrjphp_image_preview\">
			<p>
			<img src=\"_gfx/favicon/ucrjphp_favicon_512x512.png\" alt=\"ucrjphp_favicon_512x512.png\" id=\"ucrjphp_img\" width=\"$image_preview_width\" height=\"$image_preview_height\">
	
		</div>

		<div class=\"ucrjphp_image_tools\">
			<div class=\"ucrjphp_image_tools_info\">
				<p>
				<input type=\"hidden\" name=\"ucrjphp_action\" value=\"send\" />
				<input type=\"hidden\" name=\"process\" value=\"1\" />
				<b>File:</b> <input type=\"text\" name=\"inp_ucrjphp_image_file\"  value=\"0\" size=\"10\" autocomplete=\"false\" />
				&nbsp;
				<b>Counter:</b> <input type=\"text\" name=\"inp_ucrjphp_image_counter\"  value=\"0\" size=\"3\" autocomplete=\"false\" />
				&nbsp;
				<b>Version:</b> <input type=\"text\" name=\"inp_ucrjphp_image_version\" id=\"inp_ucrjphp_image_version\" value=\"0\" size=\"1\" autocomplete=\"false\" />
				<br />
				
				<b>Dimensions:</b>
				<span id=\"uploaded_image_width\">a</span>
				<span>x</span>
				<span id=\"uploaded_image_height\">b</span>
				&nbsp;
				<b>Target:</b>
				<span id=\"uploaded_image_target_width\">a</span>
				<span>x</span>
				<span id=\"uploaded_image_target_height\">b</span>
				&nbsp;
				<b>Temp:</b>
				<span id=\"uploaded_image_temp_width\">a</span>
				<span>x</span>
				<span id=\"uploaded_image_temp_height\">b</span>
				</p>
			</div> <!-- //ucrjphp_image_tools_info -->

			<p>
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"-90\"><img src=\"_gfx/icons/24x24/rotate_right_outline_black_24x24.svg\" alt=\"rotate_right_outline_black_24x24.png\" title=\"Roate 90&deg; right\" /></a>
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"90\"><img src=\"_gfx/icons/24x24/rotate_left_outline_black_24x24.png\" alt=\"rotate_left_outline_black_24x24.png\" title=\"Roate 90&deg; left\" /></a>
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"-1\"><img src=\"_gfx/icons/24x24/rotate_right_one_outline_black_24x24.png\" alt=\"rotate_right_one_outline_black_24x24.png\" title=\"Roate 1&deg; right\" /></a>
			<a href=\"#\" class=\"ucrjphp_rotate\" data-deg=\"1\"><img src=\"_gfx/icons/24x24/rotate_left_one_outline_black_24x24.png\" alt=\"rotate_left_one_outline_black_24x24.png\" title=\"Roate 1&deg; left\" /></a>
			<a href=\"#\" id=\"ucrjphp_crop\"><img src=\"_gfx/icons/24x24/crop_outline_black_24x24.png\" alt=\"crop_outline_black_24x24.png\" id=\"ucrjphp_crop_icon\" title=\"Crop\" /></a>

			</p>

			<div class=\"ucrjphp_image_tools_crop\">
				<p>
				<input type=\"text\" name=\"inp_width\" id=\"width\" size=\"2\">
				x
				<input type=\"text\" name=\"inp_height\" id=\"height\" size=\"2\">
				<input type=\"text\" id=\"x\">
				<input type=\"text\" id=\"y\">
				<input id=\"ucrjphp_btn_crop_image\" type=\"button\" value=\"Crop\">
				</p>
			</div> <!-- //ucrjphp_image_tools_crop -->

		</div> <!-- //ucrjphp_image_tools_col -->

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
		
		<div class=\"ucrjphp_finish\">
			<p><input type=\"submit\" value=\"Finish\" /></p>
		</form>
	</form>
	</div> <!-- //ucrjphp_wrapper -->
<!-- //Image upload form -->


<!-- Ajax JavaScript File Upload Logic -->


<script>
\$(document).ready(function(){

	// On load
	\$(\"#x\").hide();
	\$(\"#y\").hide();
	\$(\".ucrjphp_image_tools_crop\").hide();


	// - Upload ------------------------------------------------------------------------------------------------------------
	\$('#ucrjphp_file').change(function(evt) {
		// Feedback :: Loading
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p><img src='_gfx/icons/18x18/spinner_black_18x18.png' alt='spinner_black_18x18.png' /> Uploading image...</p></div>\");

  		var fd = new FormData();
		var files = $('#ucrjphp_file')[0].files;


        
		// Check file selected or not
		if(files.length > 0 ){
           		fd.append('file',files[0]);

           		$.ajax({
              		url: 'quick_ucrjphp.php',
              		type: 'post',
              		data: fd,
              		contentType: false,
              		processData: false,
              		success: function(response){
                 		if(response.charAt(0) == 1){

					// Remove first string
					var uploadedImageString = response.substring(1, response.length); // We now have something like _cache/ucrjphp_tmp/37/2_0.jpg?width=1280&height=1280&5000=5000&5000=5000&target_width=1280&target_height=1280
                    			
					// Get image src
					var uploadedImageSrc = uploadedImageString.substr(0, uploadedImageString.indexOf('?'));
					\$(\"#ucrjphp_img\").attr(\"src\", uploadedImageSrc); 

					// Get image file
					var lastIndexOf = uploadedImageSrc.lastIndexOf('/');
					var imageFile = uploadedImageSrc.substring(lastIndexOf + 1);
					\$('[name=\"inp_ucrjphp_image_file\"]').val(imageFile);
					var imageFileLen = \$('[name=\"inp_ucrjphp_image_file\"]').val().length;
					var imageFileLenChar = imageFileLen*2; // average width of a char
					imageFileLenChar = imageFileLen*3;
					imageFileLenChar = imageFileLen+1;
					\$('[name=\"inp_ucrjphp_image_file\"]').attr('size', imageFileLenChar);

					// Get counter
					var imageCounter = uploadedImageSrc.substring(lastIndexOf - 1);
					var imageCounter = uploadedImageSrc.split('/').reverse()[1];
					\$('[name=\"inp_ucrjphp_image_counter\"]').val(imageCounter);

					// Version
					\$('[name=\"inp_ucrjphp_image_version\"]').val(0);

					// Get width, height, max_temp_width, max_temp_height, target_width, target_height
					var uploadParameters = uploadedImageString.substr(uploadedImageString.indexOf(\"?\") + 1);
					var parametersArray = uploadParameters.split('&');
					var uploadedImageWidth  = parametersArray[0].replace(\"width=\", \"\");
					var uploadedImageHeight  = parametersArray[1].replace(\"height=\", \"\");
					var uploadedImageTargetWidth  = parametersArray[2].replace(\"target_width=\", \"\");
					var uploadedImageTargetHeight  = parametersArray[3].replace(\"target_height=\", \"\");
					var uploadedImageTempWidth  = parametersArray[4].replace(\"max_temp_width=\", \"\");
					var uploadedImageTempHeight  = parametersArray[5].replace(\"max_temp_height=\", \"\");

					\$(\"#uploaded_image_width\").text(uploadedImageWidth); 
					\$(\"#uploaded_image_height\").text(uploadedImageHeight); 
					\$(\"#uploaded_image_target_width\").text(uploadedImageTargetWidth); 
					\$(\"#uploaded_image_target_height\").text(uploadedImageTargetHeight); 
					\$(\"#uploaded_image_temp_width\").text(uploadedImageTempWidth); 
					\$(\"#uploaded_image_temp_height\").text(uploadedImageTempHeight); 

					// Feedback
					\$(\"#ucrjphp_upload_feedback\").html(\"<div class='success_small'><p>Uploaded \" + imageFile + \". <br />\" +
										\"Dimensions=\" + uploadedImageWidth + \"x\" + uploadedImageHeight + \". <br />\" +
										\"Target dimensions=\" + uploadedImageTargetWidth + \"x\" + uploadedImageTargetHeight + \". <br />\" +
										\"Temp dimensions=\" + uploadedImageTempWidth + \"x\" + uploadedImageTempHeight + \". <br />\" +
										 \"</p></div>\");
					\$(\"#ucrjphp_upload_feedback\").children().delay(10000).fadeOut(800);

					// Display image
                    			\$(\".ucrjphp_image_preview img\").show(); // Display image element
					\$(\".ucrjphp_image_preview\").show();
					\$('.ucrjphp_image_preview img').attr('title', uploadedImageSrc); 
					\$('.ucrjphp_image_preview img').attr('alt', uploadedImageSrc); 

					// Display tools and Add values to input form
					\$(\".ucrjphp_image_tools\").show();
					\$(\".ucrjphp_finish\").show();

					// Hide upload form
					\$(\".ucrjphp_upload_form\").children().delay(5000).fadeOut(800);
					
					

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

	// - Rotate ------------------------------------------------------------------------------------------------------------
	$(\".ucrjphp_rotate\").click(function(){
		// Get degree
		var deg = \$(this).attr(\"data-deg\");

		// Feedback :: Loading
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p><img src='_gfx/icons/18x18/spinner_black_18x18.png' alt='spinner_black_18x18.png' /> Rotating \" + deg  + \"&deg;...</p></div>\");
		\$(\"#ucrjphp_upload_feedback\").children().fadeIn(800);

           	// Ajax call
		var imagePath = $(\".ucrjphp_image_preview img\").attr('src');
		var lastIndexOf = imagePath.lastIndexOf('/');
		var imageSrc = imagePath.substring(lastIndexOf + 1);


		var imageCounter = imagePath.substring(lastIndexOf - 1);
		var imageCounter = imagePath.split('/').reverse()[1];
		
		var imageVersion = parseInt(\$('[name=\"inp_ucrjphp_image_version\"]').val());
		var newImageVersion = imageVersion+1;
		\$('[name=\"inp_ucrjphp_image_version\"]').val(newImageVersion);

		var data = 'tool=rotate&deg=' + deg + '&image_counter=' + imageCounter + '&image_file='+ imageSrc + '&image_ver=' + imageVersion;
            	\$.ajax({
                	type: \"GET\",
               		url: \"quick_ucrjphp.php\",
                	data: data,
			beforeSend: function(html) { // this happens before actual call
				
			},
               		success: function(html){
				// Get image
				var uploadedImageString = html; // We now have something like _cache/ucrjphp_tmp/37/2_0.jpg?width=1280&height=1280&5000=5000&5000=5000&target_width=1280&target_height=1280
				var uploadedImageSrc  = uploadedImageString.substr(0, uploadedImageString.indexOf('?'));


				// Get image file
				var lastIndexOf = uploadedImageSrc.lastIndexOf('/');
				var imageFile = uploadedImageSrc.substring(lastIndexOf + 1);
				\$('[name=\"inp_ucrjphp_image_file\"]').val(imageFile);

				// Get counter
				var imageCounter = uploadedImageSrc.substring(lastIndexOf - 1);
				var imageCounter = uploadedImageSrc.split('/').reverse()[1];
				\$('[name=\"inp_ucrjphp_image_counter\"]').val(imageCounter);



				// Get width, height, max_temp_width, max_temp_height, target_width, target_height
				var uploadParameters = uploadedImageString.substr(uploadedImageString.indexOf(\"?\") + 1);
				var parametersArray = uploadParameters.split('&');
				if(typeof parametersArray[1] != 'undefined'){
					var uploadedImageWidth  = parametersArray[0].replace(\"width=\", \"\");
					var uploadedImageHeight  = parametersArray[1].replace(\"height=\", \"\");
					var uploadedImageTargetWidth  = parametersArray[2].replace(\"target_width=\", \"\");
					var uploadedImageTargetHeight  = parametersArray[3].replace(\"target_height=\", \"\");
					var uploadedImageTempWidth  = parametersArray[4].replace(\"max_temp_width=\", \"\");
					var uploadedImageTempHeight  = parametersArray[5].replace(\"max_temp_height=\", \"\");

					\$(\"#uploaded_image_width\").text(uploadedImageWidth); 
					\$(\"#uploaded_image_height\").text(uploadedImageHeight); 
					\$(\"#uploaded_image_target_width\").text(uploadedImageTargetWidth); 
					\$(\"#uploaded_image_target_height\").text(uploadedImageTargetHeight); 
					\$(\"#uploaded_image_temp_width\").text(uploadedImageTempWidth); 
					\$(\"#uploaded_image_temp_height\").text(uploadedImageTempHeight); 

					// Feedback
					\$(\"#ucrjphp_upload_feedback\").html(\"<div class='success_small'><p>Rotated \" + deg  + \"&deg; (\" + imageFile + \")<br /> \" +
										\"Dimensions=\" + uploadedImageWidth + \"x\" + uploadedImageHeight + \". \" +
										\"Target dimensions=\" + uploadedImageTargetWidth + \"x\" + uploadedImageTargetHeight + \". \" +
										\"Temp dimensions=\" + uploadedImageTempWidth + \"x\" + uploadedImageTempHeight + \". \" +
										 \"</p></div>\");
					\$(\"#ucrjphp_upload_feedback\").children().delay(10000).fadeOut(800);


					// Display image
                    			\$(\"#ucrjphp_img\").attr(\"src\", uploadedImageSrc); 
					\$('.ucrjphp_image_preview img').attr('title', uploadedImageSrc);
					\$('.ucrjphp_image_preview img').attr('alt', uploadedImageSrc);
				}
				else{
					// Some error occured
					\$(\"#ucrjphp_upload_feedback\").html(\"<div class='error_small'><p>\" + html + \"</p></div>\");
					\$(\"#ucrjphp_upload_feedback\").children().delay(10000).fadeOut(800);
					
				}
              		}
            	});
	}); // Rotate x deg

	// - Crop --------------------------------------------------------------------------------------------------------------
	\$(\"#ucrjphp_crop\").click(function(){


		// Change crop icon
		var imagePath = \$('#ucrjphp_crop_icon').attr('src');
		var lastIndexOf = imagePath.lastIndexOf('/');
		var currentCropSrc = imagePath.substring(lastIndexOf + 1);
		var cropIsActive = true;
		if(currentCropSrc === 'crop_outline_black_24x24.png'){
			var cropIsActive = true;
                    	\$(\"#ucrjphp_crop_icon\").attr(\"src\", \"_gfx/icons/24x24/crop_remove_outline_black_24x24.png\"); 
		}
		else{
			var cropIsActive = false;
                    	\$(\"#ucrjphp_crop_icon\").attr(\"src\", \"_gfx/icons/24x24/crop_outline_black_24x24.png\"); 
		}
		


		// Activate or deactivate crop
		if(currentCropSrc === 'crop_outline_black_24x24.png'){
			// Feedback
			\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p>Crop tool loaded</p></div>\");
			\$(\"#ucrjphp_upload_feedback\").children().delay(20000).fadeOut(800);

			// Show crop tools
			\$(\".ucrjphp_image_tools_crop\").fadeIn(800);

			// Crop
			var \$ucrjphp_img = \$('#ucrjphp_img'),
				\$update = \$('#update'),
				inputs = {
					x : \$('#x'),
					y : \$('#y'),
					width : \$('#width'),
					height : \$('#height')
					},
					fill = function(){
					var values = \$ucrjphp_img.rcrop('getValues');
					for(var coord in inputs){
						inputs[coord].val(values[coord]);
					}
				};

			// Initilize
			\$ucrjphp_img.rcrop();

			// Fill inputs when Responsive Cropper is ready and when crop area is being resized or dragged 
			\$ucrjphp_img.on('rcrop-changed rcrop-ready', fill);

			// Call resize method when button is clicked. And then fill inputs to fix invalid values.

			\$(\"#ucrjphp_btn_crop_image\").click(function(){
				// Crop the image!

				// Get info
				var imagePath = $(\".ucrjphp_image_preview img\").attr('src');
				var lastIndexOf = imagePath.lastIndexOf('/');
				var imageSrc = imagePath.substring(lastIndexOf + 1);

				var imageCounter = imagePath.substring(lastIndexOf - 1);
				var imageCounter = imagePath.split('/').reverse()[1];
		
				var imageVersion = parseInt(\$('[name=\"inp_ucrjphp_image_version\"]').val());

					
				// Feedback :: Loading
				\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p><img src='_gfx/icons/18x18/spinner_black_18x18.png' alt='spinner_black_18x18.png' /> Cropping...</p></div>\");
				\$(\"#ucrjphp_upload_feedback\").children().fadeIn(800);

           			// Ajax call
				var newImageVersion = imageVersion+1;
				\$('[name=\"inp_ucrjphp_image_version\"]').val(newImageVersion);

				var data = 'tool=crop&image_counter=' + imageCounter + '&image_file='+ imageSrc + '&image_ver=' + imageVersion + '&x=' + inputs.x.val() + '&y=' + inputs.y.val() + '&width='+ inputs.width.val() + '&height=' + inputs.height.val();
            			\$.ajax({
                			type: \"GET\",
               				url: \"quick_ucrjphp.php\",
                			data: data,
					beforeSend: function(html) { // this happens before actual call
				
					},
               				success: function(html){


						// Get image
						var uploadedImageString = html; // We now have something like _cache/ucrjphp_tmp/37/2_0.jpg?width=1280&height=1280&5000=5000&5000=5000&target_width=1280&target_height=1280
						var uploadedImageSrc  = uploadedImageString.substr(0, uploadedImageString.indexOf('?'));

						// Get width, height, max_temp_width, max_temp_height, target_width, target_height
						var uploadParameters = uploadedImageString.substr(uploadedImageString.indexOf(\"?\") + 1);
						var parametersArray = uploadParameters.split('&');
						var uploadedImageWidth  	= parametersArray[0].replace(\"width=\", \"\");
						if(typeof parametersArray[1] != 'undefined'){
							var uploadedImageHeight  	= parametersArray[1].replace(\"height=\", \"\");
							var uploadedImageTargetWidth  	= parametersArray[2].replace(\"target_width=\", \"\");
							var uploadedImageTargetHeight  	= parametersArray[3].replace(\"target_height=\", \"\");
							var uploadedImageTempWidth  	= parametersArray[4].replace(\"max_temp_width=\", \"\");
							var uploadedImageTempHeight  	= parametersArray[5].replace(\"max_temp_height=\", \"\");

							\$(\"#uploaded_image_width\").text(uploadedImageWidth); 
							\$(\"#uploaded_image_height\").text(uploadedImageHeight); 
							\$(\"#uploaded_image_target_width\").text(uploadedImageTargetWidth); 
							\$(\"#uploaded_image_target_height\").text(uploadedImageTargetHeight); 
							\$(\"#uploaded_image_temp_width\").text(uploadedImageTempWidth); 
							\$(\"#uploaded_image_temp_height\").text(uploadedImageTempHeight); 

							// Feedback
							\$(\"#ucrjphp_upload_feedback\").html(\"<div class='success_small'><p>Cropped \" + uploadedImageSrc + \"<br /> \" +
										\"Dimensions=\" + uploadedImageWidth + \"x\" + uploadedImageHeight + \". \" +
										\"Target dimensions=\" + uploadedImageTargetWidth + \"x\" + uploadedImageTargetHeight + \". \" +
										\"Temp dimensions=\" + uploadedImageTempWidth + \"x\" + uploadedImageTempHeight + \". \" +
										 \"</p></div>\");
							\$(\"#ucrjphp_upload_feedback\").children().delay(10000).fadeOut(800);


							// Display image
                    					\$(\"#ucrjphp_img\").attr(\"src\", uploadedImageSrc); 
							\$('.ucrjphp_image_preview img').attr('title', uploadedImageSrc);
							\$('.ucrjphp_image_preview img').attr('alt', uploadedImageSrc);
						}
						else{
							// Error
							// Feedback
							\$(\"#ucrjphp_upload_feedback\").html(html);
							\$(\"#ucrjphp_upload_feedback\").children().delay(10000).fadeOut(800);
						}
              				}
            			});
			});

		}
		else{
			// Hide crop tools
			\$(\".ucrjphp_image_tools_crop\").fadeOut(800);

			// Destroy session
			\$('#ucrjphp_img').rcrop('destroy');

			// Feedback
			\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p>Crop tool deactivated</p></div>\");
			\$(\"#ucrjphp_upload_feedback\").children().delay(20000).fadeOut(800);
		} // Close crop

	}); // Crop

	";

	// - Load image --------------------------------------------------------------------------------------------------------
	if(isset($_GET['image_counter']) && isset($_GET['image_file']) && isset($_GET['image_version'])){
		$image_counter = $_GET['image_counter'];
		$image_counter = strip_tags(stripslashes($image_counter));
		if(!(is_numeric($image_counter))){
			echo"	\$(\"#ucrjphp_upload_feedback\").html(\"<div class='error_small'><p>image_counter not numeric</p></div>\");\n";
			die;
		}
		$image_file = $_GET['image_file'];
		$image_file = strip_tags(stripslashes($image_file));
		if (strpos($image_file, '/') !== false OR strpos($image_filesrc, '\\') !== false OR strpos($image_file, '?') !== false OR strpos($image_file, '..') !== false) {
			echo"	\$(\"#ucrjphp_upload_feedback\").html(\"<div class='error_small'><p>invalid image_file</p></div>\");\n";
			die;
		}
		$image_version = $_GET['image_version'];
		$image_version = strip_tags(stripslashes($image_version));
		if(!(is_numeric($image_version))){
			echo"	\$(\"#ucrjphp_upload_feedback\").html(\"<div class='error_small'><p>image_version not numeric</p></div>\");\n";
			die;
		}
		echo"
		\$('[name=\"inp_ucrjphp_image_version\"]').val($image_version);

		// Feedback
		\$(\"#ucrjphp_upload_feedback\").html(\"<div class='info_small'><p>Loading image!</p></div>\");
		\$(\"#ucrjphp_upload_feedback\").children().delay(20000).fadeOut(800);

		// Load image
		var data = 'tool=load_image&image_counter=$image_counter&image_file=$image_file&image_ver=$image_version';
		\$.ajax({
			type: \"GET\",
			url: \"quick_ucrjphp.php\",
			data: data,
			beforeSend: function(html) { // this happens before actual call
				
			},
			success: function(html){
				// Feedback
				\$(\"#ucrjphp_upload_feedback\").html(\"<div class='success_small'><p>Loaded $image_file</p></div>\");
				\$(\"#ucrjphp_upload_feedback\").children().delay(20000).fadeOut(800);

				// Display image
                    		\$(\".ucrjphp_image_preview img\").show(); // Display image element
				\$(\".ucrjphp_image_preview\").show();
                    		\$(\"#ucrjphp_img\").attr(\"src\", html); 
				\$('.ucrjphp_image_preview img').attr('title', html);
				\$('.ucrjphp_image_preview img').attr('alt', html);


				// Display tools
				\$(\".ucrjphp_image_tools\").show();

			}
		});
		";
	}
	echo"
	

});
</script>




";


/*- Footer ---------------------------------------------------------------- */
if($show_header_and_footer == "1"){
	echo"</body>\n";
	echo"<html>";
}
?>