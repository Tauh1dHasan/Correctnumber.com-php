<?php include("../inc-main.php"); ?>
<?php
$file = base64_decode($_GET['img']);
if(exif_imagetype($file) == IMAGETYPE_GIF){
   $img = imagecreatefromgif($file);
}else if(exif_imagetype($file) == IMAGETYPE_JPEG){
   $img = imagecreatefromjpeg($file);
}else if(exif_imagetype($file) == IMAGETYPE_PNG){
   $img = imagecreatefrompng($file);
}

   //Dimensions
   $width = imagesx($img);
   $height = imagesy($img);
   $max_width = $_GET['w'];
   $max_height = 5000;
   $percentage = 1;

   //Image scaling calculations
   if ( $width > $max_width ) { 
      $percentage = ($height / ($width / $max_width)) > $max_height ?
           $height / $max_height :
           $width / $max_width;
   }
   else if ( $height > $max_height) {
      $percentage = ($width / ($height / $max_height)) > $max_width ? 
           $width / $max_width :
           $height / $max_height;
   }
   $new_width = $width / $percentage;
   $new_height = $height / $percentage;

   //scaled image
   $out = imagecreatetruecolor($new_width, $new_height);
   imagecopyresampled($out, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

   header('Content-type: <span class="posthilit">image</span>/jpeg');
//output image
   imagejpeg($out);
?>
