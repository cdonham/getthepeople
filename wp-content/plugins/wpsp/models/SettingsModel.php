<?php
/**
* Settings Model
* 
* @package Models
* @author Tim Carr
* @version 1.71
*/

/**
* SettingsModel is a model to deal with getting and saving settings
* 
* @package Models
* @author Tim Carr
*/
class SettingsModel {
    /**
    * Creates necessary tables for WPSqueezePage to function
    * - wp_wpsp_settings: contains a list of available settings.  Add records here to extend the available settings.
    * 
    * @return bool Success
    */
    function Install() {
        global $wpdb;
        
        // Settings
        $wpdb->query("  CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpsp_settings (
                            settingID int(10) NOT NULL AUTO_INCREMENT,
                            settingKey varchar(200) NOT NULL,
                            label varchar(200) NOT NULL,
                            description text,
                            value varchar(200) NOT NULL,
                            isHidden tinyint(1) NOT NULL,
                            PRIMARY KEY (`settingID`)
                        ) 
                        ENGINE=MyISAM
                        DEFAULT CHARSET=utf8
                        AUTO_INCREMENT=1");
        $wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_settings (settingID, settingKey, label, description, value, isHidden) VALUES 
                        (1, 'licenseKey', 'License Key', 'Your license key.  If you need to purchase one, please visit <a href=\"http://www.wpsqueezepage.com\" target=\"_blank\">http://www.wpsqueezepage.com</a>', '', '0'),
                        (2, 'version', 'Version', 'WP Squeeze Page Plugin Version', '1.7', '1'),
                        (3, 'lastLicenseCheck', 'Last License Check', 'Last date/time the license key was checked with the licensing server.', '0000-00-00 00:00:00', '1'),
                        (4, 'nextLicenseCheck', 'Next License Check', 'Next date/time the license key will be checked with the licensing server.', '1970-01-01 00:00:00', '1'),
                        (5, 'currentTheme', 'Current Wordpress Theme', 'Current Wordpress Theme containing plugin templates.', '".get_template()."', '1'),
                        (6, 'updateVersion', 'Update Version', 'Update version available', '1.7', '1'),
                        (7, 'updateURL', 'Update URL', 'Update URL', '', '1'),
                        (8, 'lastUpdateCheck', 'Last Update Check', 'Last Update Check', '0000-00-00 00:00:00', '1'),
                        (9, 'nextUpdateCheck', 'Next Update Check', 'Last Update Check', '1970-01-01 00:00:00', '1'),
                        (10, 'updateAvailable', 'Update Available', 'Update is Available', '0', '1')");
                        
        return true;
    }
    
    /**
    * Upgrades necessary tables and data
    * 
    * @param decimal $oldVersion Old Version of Plugin
    * @param decimal $newVersion New Version of Plugin
    * @return bool Success
    */
    function Upgrade($oldVersion, $newVersion) {
        global $wpdb;
        
        // This is just an outline of the oldVersion & newVersion comparison
        // You can use any suitable version numbers
        switch ($oldVersion) {
            case '1.0':
                switch ($newVersion) {
                    case '1.1':
                        // 1.0 to 1.1 Upgrade Routine
                        break;
                    case '1.2':
                        // 1.0 to 1.2 Upgrade Routine
                        break;
                    case '2.0':
                        // 1.0 to 2.0 Upgrade Routine
                        break;
                    case '2.1':
                        // 1.0 to 2.2 Upgrade Routine
                        break;
                    case '2.2':
                        // 1.0 to 2.2 Upgrade Routine
                        break;
                }
                break;
            case '2.0':
                switch ($newVersion) {
                    case '2.1':
                        // 2.0 to 2.1 Upgrade Routine
                        break;
                    case '2.2':
                        // 2.0 to 2.2 Upgrade Routine
                        break;
                }
        }
        
        return true;
    }
    
    /**
    * Removes necessary tables and table data created by Install() routine
    * 
    * @return bool Success
    */
    function Uninstall() {
        global $wpdb;
        
        $wpdb->query("  DROP TABLE IF EXISTS ".$wpdb->prefix."wpsp_settings");
        
        return true;
    }    
    
    /**
    * Gets all visible settings
    * 
    * @return array Settings Data
    */                   
    function GetAllSettings() {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT *
                                        FROM ".$wpdb->prefix."wpsp_settings
                                        WHERE ".$wpdb->prefix."wpsp_settings.isHidden = 0
                                        ORDER BY ".$wpdb->prefix."wpsp_settings.label ASC
                                        LIMIT 1");
        return $results;
    }
    
    /**
    * Gets all hidden settings
    * 
    * @return array Settings Data
    */                   
    function GetAllHiddenSettings() {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT *
                                        FROM ".$wpdb->prefix."wpsp_settings
                                        WHERE ".$wpdb->prefix."wpsp_settings.isHidden = 1
                                        ORDER BY ".$wpdb->prefix."wpsp_settings.label ASC");
        
        foreach ($results as $key=>$result) {
        	$settings[$result->settingKey] = array(
        		'label' => $result->label,
        		'value' => $result->value
        	);
        }
        
        return $settings;
    }
    
    /**
    * Gets an individual setting by its key
    * 
    * @return string Setting Value
    */
    function GetSettingByKey($settingKey) {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT ".$wpdb->prefix."wpsp_settings.value
                                        FROM ".$wpdb->prefix."wpsp_settings
                                        WHERE ".$wpdb->prefix."wpsp_settings.settingKey = '".mysql_real_escape_string($settingKey)."'
                                        LIMIT 1");
        if (count($results) == 0) return false;
        return $results[0]->value;        
    }
    
    /**
    * Saves all settings
    * 
    * @param array $data Post Data
    * @return bool Success
    */
    function Save($data) {
        global $wpdb;
        
        foreach ($data as $settingKey=>$value) {
            $wpdb->query("  UPDATE ".$wpdb->prefix."wpsp_settings
                            SET value = '".$value."'
                            WHERE settingKey = '".$settingKey."'
                            LIMIT 1");
        }
        
        return true;
    }
    
    /**
    * Saves a single setting for the given key / value pair
    * 
    * @param string $settingKey Setting Key
    * @param string $settingValue Setting Value
    * @param string $label Label
    * @return bool Success
    */
    function SaveSingleSetting($settingKey, $settingValue, $label = '') {
        global $wpdb;

        // Check setting exists
        $results = $wpdb->get_results("	SELECT *
        								FROM ".$wpdb->prefix."wpsp_settings
				                        WHERE settingKey = '".mysql_real_escape_string($settingKey)."'
				                        LIMIT 1");
        if (count($results) == 0) {
        	// Insert
        	$wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_settings (settingKey, value, label, isHidden)
        					VALUES ('".mysql_real_escape_string($settingKey)."',
        					'".mysql_real_escape_string($settingValue)."',
        					'".mysql_real_escape_string($label)."',
        					'1')");
        } else {
        	// Update
	        $wpdb->query("  UPDATE ".$wpdb->prefix."wpsp_settings
	                        SET value = '".mysql_real_escape_string($settingValue)."'
	                        WHERE settingKey = '".mysql_real_escape_string($settingKey)."'
	                        LIMIT 1");
        }

        return true;    
    }
    
    /**
    * Compares the stored template with the current active template.
    * If there's a difference, calls the TemplatesModel to remove template stubs from the old theme, and put them into the new theme.
    */
    function CheckIfThemeHasChanged() {
        global $wpdb;
        
        $this->models->templates = new TemplatesModel();
        
        $results = $wpdb->get_results(" SELECT ".$wpdb->prefix."wpsp_settings.value
                                        FROM ".$wpdb->prefix."wpsp_settings
                                        WHERE ".$wpdb->prefix."wpsp_settings.settingKey = 'currentTheme'
                                        LIMIT 1");
        if ($results[0]->value != get_template()) {
            $this->models->templates->RemoveTemplateStubFiles($results[0]->value); // Remove from old theme directory
            $this->models->templates->CopyTemplateStubFiles(get_template()); // Copy into new theme directory
            
            // Update currentTheme setting
            $wpdb->query("  UPDATE ".$wpdb->prefix."wpsp_settings
                            SET value = '".get_template()."'
                            WHERE settingKey = 'currentTheme'
                            LIMIT 1");
        }
        
        // Another case may be where the current theme is updated, so the existing WPSP files in the theme folder are removed
        $this->models->templates->CopyTemplateStubFilesIfMissing(get_template());        
    }
}
?>