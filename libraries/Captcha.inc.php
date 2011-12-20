<?php

// Prevent to be loaded directly
if (!isset($allowed_ops)) {
    die(_("FORM:ERROR"));
}

require_once "../setup/config.php";
require_once "../libraries/Locale.inc.php";

session_start();

//CAPTCHA GENERATOR
//Created by Rob Valkass 2007
//Edited by QuickSilva
//Feel free to distribute and use as you want
//Just leave this notice and comments intact

//Email: webmaster@rvalkass.co.uk
//Web: www.rvalkass.co.uk


//Set the header to say what sort of information we are giving the browser
Header ("(anti-spam-content-type:) image/png");

//Generate 2 random numbers for use in our encryption
$enc_num = rand(0, 9999); //This number will be encrypted
$key_num = rand(0, 24); //This is used to choose which bit of our string to use in the image

//Use these to get a random string of numbers and letters using MD5
//We then cut the 32 char MD5 down to an 8 char string, starting from a random point
$hash_string = substr(md5($enc_num), $key_num, 8);

//MD5 the $hash_string variable again
$hash_md5 = md5($hash_string);

//Asign it to a session
$_SESSION['captcha'] = $hash_md5;

//Create an array of the images available to us as backgrounds
$bgs = array("themes/$app_theme/images/captcha.png");

//Choose the background image using the handy array_rand function
$background = array_rand($bgs, 1);

//Now to start creating the all important image!
//Set the background as our randomly selected image
$img_handle = imagecreatefrompng($bgs[$background]);

//Set our text colour to white
$text_colour = imagecolorallocate($img_handle, 0, 0, 0);

//Set the font size
$font_size = 5;

//Get the horizontal and vertical dimensions of the background image
$size_array = getimagesize($bgs[$background]);
$img_w = $size_array[0];
$img_h = $size_array[1];

//Work out the horizontal position
$horiz = round(($img_w/2)-((strlen($hash_string)*imagefontwidth(5)
)/2), 1);

//Work out the vertical position
$vert = round(($img_h/2)-(imagefontheight($font_size)/2));

//Put our text onto our image
imagestring($img_handle, $font_size, $horiz, $vert, $hash_string, $text_colour);

//Output the image
imagepng($img_handle);

//Destroy the image to free up resources
imagedestroy($img_handle);

?>
