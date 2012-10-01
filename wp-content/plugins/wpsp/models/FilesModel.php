<?php
/**
* Files Model
* 
* @package Models
* @author Tim Carr
* @version 1
*/

/**
* FilesModel is a model to deal with uploading and deleting images
* 
* @package Models
* @author Tim Carr
*/
class FilesModel {    
    /**
    * Uploads a form posted file to the specified directory on the server, creating a unique filename,
    * and the destination folder (if required)
    * 
    * @param string $fileObjName File Object Name 
    * @param string $destDir Destination Directory on Server to save file
    * @param bool $isShared Is Shared Field (default=false)
    * @return string Filename
    */
    function UploadFileToServer($templateID, $fieldName, $destDir) {                
        $filename = date("YmdHis")."_".ereg_replace("[^[:alnum:].-_]", "", strtolower($_FILES[PLUGIN_NAME]['name']['template'.$templateID][$fieldName]['file'])); // Generate unique filename
        
        // Create upload directory on server, if it doesn't exist
        if (!is_dir($destDir)) {
            if (@!mkdir($destDir, 0755)) return false;   
        }

        // Upload file
        if(@!move_uploaded_file($_FILES[PLUGIN_NAME]['tmp_name']['template'.$templateID][$fieldName]['file'], $destDir."/".$filename)) return false;                              
        
        // Set file permissions, and return filename
        chmod ($destDir."/".$filename, 0644);
        return $filename;
    }
}
?>
