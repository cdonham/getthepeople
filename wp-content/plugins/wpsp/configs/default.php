<?php
/**
* Configuration Document.  Defines document roots, plugin name, base URL and other shared settings.
* 
* Also includes all models and sets up their constructs.
* 
* @package Configs
* @author Tim Carr
* @version 1.7
*/

// Sets up constants used for the plugin
define("PLUGIN_NAME", 'wpsp');
define("DOCUMENT_ROOT", substr(str_replace("\\", "/", dirname(__FILE__)), 0, strpos(str_replace("\\", "/", dirname(__FILE__)), "/wp-content")));
define("PLUGIN_ROOT", substr(str_replace("\\", "/", dirname(__FILE__)), 0, strpos(str_replace("\\", "/", dirname(__FILE__)), "/".PLUGIN_NAME))."/".PLUGIN_NAME);
define("PLUGIN_URL", str_replace(get_bloginfo('url'), '', WP_PLUGIN_URL.'/'.PLUGIN_NAME));
define("LICENSING_SERVER_ENDPOINT", "http://www.wpsqueezepage.com/wpsqp/licensing/licensing.php");

// Includes and constructs all models in the /models folder
if ($handle = opendir(PLUGIN_ROOT.'/models')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            if (strpos($file, 'Model') > 0) {
                require_once(PLUGIN_ROOT.'/models/'.$file);
                $modelName = explode('.', $file);
                $model = strtolower(substr($modelName[0], 0, strpos($modelName[0], 'Model')));
                $models->$model = new $modelName[0];
            }
        }
    }
    closedir($handle);
}