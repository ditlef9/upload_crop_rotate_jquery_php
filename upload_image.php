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
	echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
	echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
	echo"	<script type=\"text/javascript\" src=\"$jquery_file\"></script>\n";
	echo"</head>\n";
	echo"<body>\n";
}

/*- Scriptstart ----------------------------------------------------------- */
echo"

<form method=\"POST\" action=\"#uploader\" enctype=\"multipart/form-data\">

<p><img src=\"_gfx/icons/18x18/image_outline_black_18dp.png\" alt=\"image_outline_black_18dp.png\" />
<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
<input type=\"image\" src=\"_gfx/icons/18x18/file_upload_outline_black_18dp.png\" border=\"0\" alt=\"Submit\" tabindex=\"2\" />

</p>

</form>
";


/*- Footer ---------------------------------------------------------------- */
if($show_header_and_footer == "1"){
	echo"</body>\n";
	echo"<html>";
}
?>