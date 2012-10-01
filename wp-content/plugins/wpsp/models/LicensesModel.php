<?php
/**
* Licenses Model
* 
* @package Models
* @author Tim Carr
* @version 1
*/

/**
* LicensesModel is a model to deal with checking license keys.
* 
* @package Models
* @author Tim Carr
*/
class LicensesModel {
    /**
    * Checks whether a license key has been specified in the settings table.
    * 
    * @return bool License Key Exists
    */                   
    function CheckLicenseKeyExists() {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT ".$wpdb->prefix."wpsp_settings.value
                                        FROM ".$wpdb->prefix."wpsp_settings
                                        WHERE ".$wpdb->prefix."wpsp_settings.settingKey = 'licenseKey'
                                        LIMIT 1");
        return (trim($results[0]->value) != '') ? true : false;
    }
    
    /**
    * Checks whether the license key stored in the settings table is valid.
    * 
    * @return bool License Key Valid
    */
    function CheckLicenseKeyIsValid() {
        global $wpdb;

        // Read the settings to see when the last and next checks were/will be carried out
        $this->models->settings = new SettingsModel();
        $dates->lastLicenseCheck = $this->models->settings->GetSettingByKey('lastLicenseCheck');
        $dates->nextLicenseCheck = $this->models->settings->GetSettingByKey('nextLicenseCheck');

        if (strtotime($dates->nextLicenseCheck) > strtotime(date('Y-m-d H:i:s'))) return true; // License OK

        if ($dates->lastLicenseCheck == '0000-00-00 00:00:00' OR strtotime($dates->nextLicenseCheck) < strtotime(date('Y-m-d H:i:s'))) {
            // No valid check carried out, or we're due a check
            $licenseKey = $this->models->settings->GetSettingByKey('licenseKey');
            if (!$licenseKey) return false; // No license key specified
            
            // Send license key to remote server, and return code
            $c = curl_init(LICENSING_SERVER_ENDPOINT.'?request=Check&params[]='.$licenseKey);
            curl_setopt($c, CURLOPT_HEADER, false);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($c);
            curl_close($c);
            
            // Try to use SimpleXML, otherwise fallback to basic string read
            if (function_exists('simplexml_load_string')) {
                $xml = simplexml_load_string($response);        
                $code = (int) $xml->code;       
            } else {
                $code = substr($response, (strpos($response, '<code>')+6), 1);
            }
            
            // If successful, update settings
            if ($code) {
                $this->models->settings->SaveSingleSetting('lastLicenseCheck', date('Y-m-d H:i:s'));    
                $this->models->settings->SaveSingleSetting('nextLicenseCheck', date('Y-m-d H:i:s', strtotime('+1 week')));
            }
            
            // Return code
            return $code;
        }
        
        return false; // License key invalid
    }
    
    /**
    * Checks whether any updates are available for this theme.
    * 
    * @param bool $force Force Check (default=false)
    * @return bool Updates Available
    */
    function CheckForUpdates($force = false) {
        if (!$this->CheckLicenseKeyIsValid()) return false; // Invalid license, so no update
        $this->models->settings = new SettingsModel();
        
        // Get local version
        $plugin = get_plugin_data(WP_PLUGIN_DIR.'/'.PLUGIN_NAME.'/'.PLUGIN_NAME.'.php');
        $localVersion = $plugin['Version'];
        
        // Get update checks
        $dates->nextUpdateCheck = $this->models->settings->GetSettingByKey('nextUpdateCheck');
  
        if ($dates->nextUpdateCheck == '' OR strtotime($dates->nextUpdateCheck) <= strtotime(date('Y-m-d H:i:s')) OR !isset($dates->nextUpdateCheck) OR $force == true) {
            // Get latest product version
            $c = curl_init(LICENSING_SERVER_ENDPOINT.'?request=CheckUpdate&params[]='.PLUGIN_NAME);
            curl_setopt($c, CURLOPT_HEADER, false);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($c);
            curl_close($c);
                
            // Parse XML
            $xml = simplexml_load_string($response);      
            $updateVersion = (string) $xml->code;

            // Get Product Download URL
            $c = curl_init(LICENSING_SERVER_ENDPOINT.'?request=GetDownloadURL&params[]='.PLUGIN_NAME);
            curl_setopt($c, CURLOPT_HEADER, false);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($c);
            curl_close($c);
            
            // Parse XML
            $xml = simplexml_load_string($response);      
            $updateURL = (string) $xml->code;
                           
    		// Set last and next update checks
            $lastUpdateCheck = date('Y-m-d H:i:s');
            $nextUpdateCheck = date('Y-m-d H:i:s', strtotime('+1 day'));  
            $updateAvailable = ($updateVersion > $localVersion) ? true : false;

            $this->models->settings->SaveSingleSetting('localVersion', $localVersion, 'Version');
            $this->models->settings->SaveSingleSetting('updateVersion', $updateVersion, 'Update Version');
            $this->models->settings->SaveSingleSetting('updateURL', $updateURL, 'Update URL');
            $this->models->settings->SaveSingleSetting('lastUpdateCheck', $lastUpdateCheck, 'Last Update Check');
            $this->models->settings->SaveSingleSetting('nextUpdateCheck', $nextUpdateCheck, 'Next Update Check');
            $this->models->settings->SaveSingleSetting('updateAvailable', $updateAvailable, 'Update Available');   
        }
        
        return $this->models->settings->GetSettingByKey('updateAvailable');
    }
    
    /**
    * Downloads the latest package and unzips it to the plugins folder.
    * 
    * @return bool Download and Installation Successful
    */
    function DownloadAndInstallUpdate() {
        if (!$this->CheckLicenseKeyIsValid()) return false; // Invalid license, so no update
		$this->models->settings = new SettingsModel();

        // Get ZIP data into string
        $c = curl_init(LICENSING_SERVER_ENDPOINT."?request=DownloadUpdate&params[]=".$this->models->settings->GetSettingByKey('updateURL'));
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        // Write update to file
        if (!$fp = fopen(WP_PLUGIN_DIR.'/'.PLUGIN_NAME.'.zip', 'w+')) return false;
        fwrite($fp, $response);
        fclose($fp);

        // Extract theme zip file
        if (function_exists('exec')) exec('cd ../wp-content/plugins && unzip -o '.PLUGIN_NAME.'.zip');
          
        // Remove downloaded package
        @unlink(WP_PLUGIN_DIR.'/'.PLUGIN_NAME.'.zip');
  
        // Run update check again to correct version numbers
        $this->CheckForUpdates(true);
        
        // Done
        return true;
    }
}
?>