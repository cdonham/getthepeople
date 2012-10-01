<?php
/**
* Images Model
* 
* @package Models
* @author Tim Carr
* @version 1
*/

/**
* ImagesModel is a model to deal with resizing images.
* 
* @package Models
* @author Tim Carr
*/
class ImagesModel {    
    /**
    * Resizes the specified image to the apportioned width and height
    * 
    * @param string $filename Filename
    * @param string $dir File Directory
    * @param int $width Width (px)
    * @return bool Success
    */
    function ResizeImage($filename, $dir, $width) {        
        $attributes = getimagesize($dir.'/'.$filename);
        if ($attributes[0] < $width) return true; // No need to resize
        $height = round($attributes[1] * ($width / $attributes[0])); // Work out scaled height
    
        // Scale image
        $newImage = imagecreatetruecolor($width, $height);
        switch ($attributes['mime']) {
            case "image/jpeg":
            case "image/pjpeg":
                $image = imagecreatefromjpeg($dir.'/'.$filename);
                imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $attributes[0], $attributes[1]);
                imagejpeg($newImage, $dir.'/'.$filename);
                break;
            case "image/gif":
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);              
                $image = imagecreatefromgif($dir.'/'.$filename);    
                imagealphablending($image, true);
                imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $attributes[0], $attributes[1]);
                imagegif($newImage, $dir.'/'.$filename);
                break;
            case "image/png":
            case "image/x-png":
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);              
                $image = imagecreatefrompng($dir.'/'.$filename);    
                imagealphablending($image, true);
                imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $attributes[0], $attributes[1]);
                imagepng($newImage, $dir.'/'.$filename);
                break;
        }
        
        return true;
    }
}
?>
