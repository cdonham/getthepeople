<?php
/**
Plugin Name: WPSP
Plugin URI: http://www.wpsqueezepage.com
Version: 1.72
Author: <a href="http://www.wpsqueezepage.com/">Ron Chamberlain</a>
Description: Wordpress Squeeze Page Plugin
*/

/**
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
* WP Squeeze Page Class
* 
* This is the controller in our semi-MVC setup.
* 
* See /models and /views, both of which are called by this class when required
* 
* @package WPSqueezePage
* @author Tim Carr
*/
class WPSP {    
    /**
    * Construct routine, covering:
    * - Install and Uninstall Hooks
    * - Admin Menu
    * - Admin Panel
    * - Frontend Templates
    * - Frontend Titles
    * - Admin Javascript
    */
    function __construct() { 
        global $wpdb;
        
        // Constants and Models
        require('configs/default.php');
        $this->models = $models; // Map $models in configs/default.php to local object 
        
        // Install and Uninstall Functions
        register_activation_hook(__FILE__, array(&$this, 'Install'));
        register_deactivation_hook(__FILE__, array(&$this, 'Uninstall'));      
        
        if (is_admin()) {
            // Backend
            add_action('admin_menu', array(&$this, 'CustomWritePanels'));
            add_action('save_post', array(&$this, 'Save'));
            add_action('init', array(&$this, 'InitPlugin'), 99);
            
            // Check we have a valid license key.  If not, display a message in admin
            if (!$this->models->licenses->CheckLicenseKeyExists()) {
                add_action('admin_notices', array(&$this, 'AdminNoticeNoLicenseKey'));
            } else {
                if (!$this->models->licenses->CheckLicenseKeyIsValid()) {
                    add_action('admin_notices', array(&$this, 'AdminNoticeInvalidLicenseKey'));
                }
            }
            
            // Check if the active theme has changed
            $this->models->settings->CheckIfThemeHasChanged();
        } else {
        	// Frontend
        	add_action('init', array(&$this, 'InitPluginFrontend'), 99);
        }
    }
    
    /**
    * Admin notice: No license key (w/ link to settings).
    * Doesn't display on the settings page (as on the settings page we are going to enter/change the license key)
    */
    function AdminNoticeNoLicenseKey() {
        if ($_GET['page'] != PLUGIN_NAME) echo ('<div class="updated"><p>WP Squeeze Page requires a license key in order to function.  Please visit the <a href="admin.php?page='.PLUGIN_NAME.'" title="Settings">settings page</a> to enter your license key.</p></div>');
    }
    
    /**
    * Admin notice: Invalid license key (w/ link to settings)
    * Doesn't display on the settings page (as on the settings page we are going to enter/change the license key)
    */
    function AdminNoticeInvalidLicenseKey() {
        if ($_GET['page'] != PLUGIN_NAME) echo ('<div class="updated"><p>WP Squeeze Page license key is invalid.  Please visit the <a href="admin.php?page='.PLUGIN_NAME.'" title="Settings">settings page</a> to change your license key.</p></div>');
    }
    
    /**
    * Admin notice: Save successful
    */
    function AdminNoticeSaveSuccessful() {
        echo ('<div class="updated fade"><p>Save Successful</p></div>');
    }
        
    /**
    * Install Routine
    */
    function Install() {
        // Install and populate database tables
        $this->models->settings->Install();
        $this->models->templates->Install();
    }
    
    /**
    * Uninstall Routine
    */
    function Uninstall() {
        // Uninstall database tables
        $this->models->settings->Uninstall();
        $this->models->templates->Uninstall();
    }
    
    /**
    * Enqueues CSS and Javascript for Administration, sets up a button and plugin
    * for the TinyMCE Editor
    */
    function InitPlugin() {
        // Stylesheets
        wp_register_style('wpsp-admin-css', WP_PLUGIN_URL.'/'.PLUGIN_NAME.'/css/admin.css');
        wp_register_style('jquery-colorpicker-css', WP_PLUGIN_URL.'/'.PLUGIN_NAME.'/css/colorpicker.css');
        wp_enqueue_style('wpsp-admin-css');
        wp_enqueue_style('jquery-colorpicker-css');
        wp_enqueue_style('thickbox');
        
        // Javascript
        wp_register_script('jquery-colorpicker', WP_PLUGIN_URL.'/'.PLUGIN_NAME.'/js/colorpicker.js');
        wp_register_script('wpsp-admin-js', WP_PLUGIN_URL.'/'.PLUGIN_NAME.'/js/admin.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery-colorpicker');
        wp_enqueue_script('wpsp-admin-js');
       
        // TinyMCE
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;
		if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array(&$this, 'AddTinyMCEPlugin'));
        	add_filter('mce_buttons', array(&$this, 'AddTinyMCEButton'));
    	}
    }
   
    /**
    * Registers and enables shortcodes
    */
    function InitPluginFrontend() {
        // Shortcodes
        add_shortcode('graybox', array(&$this, 'GrayBox'));
        add_shortcode('whitebox', array(&$this, 'WhiteBox'));
        add_shortcode('whiteblkbox', array(&$this, 'WhiteBlkBox'));
        add_shortcode('blkbox', array(&$this, 'BlkBox'));
        add_shortcode('box', array(&$this, 'Box'));
        add_shortcode('yellowbox', array(&$this, 'YellowBox'));
        add_shortcode('bquote', array(&$this, 'BQuote'));

    }

	/**
	* Gray Box Shortcode Output
	*/    
	function GrayBox($atts, $content = null) {
    	return '<div class="graybox">'.$content.'</div>';	
    }
    
    /**
	* White Box Shortcode Output
	*/
    function WhiteBox($atts, $content = null) {
    	return '<div class="whitebox">'.$content.'</div>';	
    }
    
    /**
	* White and Black Box Shortcode Output
	*/
	function WhiteBlkBox($atts, $content = null) {
    	return '<div class="whiteblkbox">'.$content.'</div>';	
    }
	
	/**
	* Black Box Shortcode Output
	*/
	function BlkBox($atts, $content = null) {
    	return '<div class="blkbox">'.$content.'</div>';	
    }        
	
	/**
	* Box Shortcode Output
	*/
	function Box($atts, $content = null) {
    	return '<div class="box">'.$content.'</div>';	
    }
	 
	/**
	* Yellow Box Shortcode Output
	*/        
	function YellowBox($atts, $content = null) {
    	return '<div class="yellowbox">'.$content.'</div>';	
    }
    
    /**
	* Blockquote Box Shortcode Output
	*/
    function BQuote($atts, $content = null) {
    	return '<div class="bquote">'.$content.'</div>';	
    }

	/**
    * Adds a button to the TinyMCE Editor for shortcode inserts
    */
	function AddTinyMCEButton($buttons) {
	    array_push($buttons, "|", PLUGIN_NAME);
	    return $buttons;
	}
	
	/**
    * Adds a plugin to the TinyMCE Editor for shortcode inserts
    */
	function AddTinyMCEPlugin($plugin_array) {
	    $plugin_array[PLUGIN_NAME] = WP_PLUGIN_URL.'/'.PLUGIN_NAME.'/js/editor_plugin.js';
	    return $plugin_array;
	}
    
    /**
    * Adds menu, submenu and custom write panels to the Pages functionality in Wordpress
    */
    function CustomWritePanels() {
        add_menu_page('WP Squeeze Page', 'WP Squeeze Page', 9, PLUGIN_NAME, array(&$this, 'AdminPanel')); // Main Menu
        add_submenu_page(PLUGIN_NAME, 'Settings', 'Settings', 9, PLUGIN_NAME, array(&$this, 'AdminPanel')); // Sub Menu
        
        if (function_exists('add_meta_box')) {
            // Only show if we have a valid license key
            if ($this->models->licenses->CheckLicenseKeyIsValid()) {
                add_meta_box('wpsp-shared-fields', 'WP Squeeze Page: Fields (All Templates)', array(&$this, 'OutputSharedFields'), 'page', 'normal', 'high');
                add_meta_box('wpsp-squeeze-fields', 'WP Squeeze Page: Fields (Squeeze Page Template)', array(&$this, 'OutputSqueezePageFields'), 'page', 'normal', 'high');
                add_meta_box('wpsp-column-fields', 'WP Squeeze Page: Fields (Column Squeeze Template)', array(&$this, 'OutputColumnSqueezePageFields'), 'page', 'normal', 'high');
                add_meta_box('wpsp-power-fields', 'WP Squeeze Page: Fields (Power Squeeze Template)', array(&$this, 'OutputPowerSqueezePageFields'), 'page', 'normal', 'high');
            }
        }
    }
    
    /**
    * Wordpress Admin Panel for Settings
    * 
    * This is where most of the work is carried out
    */
    function AdminPanel() { 
    	$data->hiddenSettings = $this->models->settings->GetAllHiddenSettings();
    
    	// Save Settings
        if (isset($_POST['submit'])) {
        	if ($this->models->settings->Save($_POST)) {
        		$data->settings = $this->models->settings->GetAllSettings();
                $this->RenderView('admin-settings', $data);
        	} else {
        		// Error saving - show editor form again
                $data->settings = $_POST;
                $data->error = 'An error occurred saving the settings form.';
        	}
        } else {
        	// Show editor form
            $data->settings = $this->models->settings->GetAllSettings();
        }
        
        // Validate license key
        if ($this->models->licenses->CheckLicenseKeyIsValid()) {
            $data->message = 'WP Squeeze Page license key validated - thank you.';
        } else {
            $data->error = 'The license key entered, if any, is invalid.';
        }
        
        // Download and Install Update, if requested
        if (isset($_GET['doUpdate']) AND $_GET['doUpdate'] == 1) {
            if ($this->models->licenses->DownloadAndInstallUpdate()) {
                $data->message .= 'Update successful.';
                $this->models->licenses->CheckForUpdates(); // Force re-check to ensure new version installed
                $data->hiddenSettings = $this->models->settings->GetAllHiddenSettings();
            } else {
                $data->error .= __('An error occurred when attempting to automatically update.');
            }
        } 
        
        // Check for updates if required
        if (isset($_GET['checkUpdates']) AND $_GET['checkUpdates'] == 1) {
            $this->models->licenses->CheckForUpdates(true); // Force Update Check
            $data->hiddenSettings = $this->models->settings->GetAllHiddenSettings();
        }
        
        // Render view
        $this->RenderView('admin-settings', $data);
    }
    
    /**
    * Includes the specified view file, and sends data to it
    * 
    * @param string $file View File (in views directory)
    * @param array $data Data for View
    * @return string HTML
    */
    private function RenderView($file, $data = '') {
        $this->data = $data;
        require_once("views/".$file.".php");
    }
    
    /**
    * Outputs shared fields
    */
    function OutputSharedFields() {
        global $post;
        $data = get_post_meta($post->ID, PLUGIN_NAME.'-template', true);
        
        echo (' <div class="wpsp-fields">
                    <input type="hidden" name="'.PLUGIN_NAME.'_wpnonce" id="'.PLUGIN_NAME.'_wpnonce" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" />');
        foreach ($this->models->templates->GetAllSharedFields() as $key=>$field) {
            echo (' <p>
                        <label for="'.PLUGIN_NAME.'[template]['.$field->name.']">'.__($field->displayName, $field->name).'</label>');
            $this->OutputField($field, $data[$field->name]);
            echo (' </p>');
        }
        echo ('</div>');
        unset($data);
    }
    
    /**
    * Outputs squeeze page template unique fields
    */
    function OutputSqueezePageFields() {
        global $post;
        $data = get_post_meta($post->ID, PLUGIN_NAME.'-template2', true);
        
        echo (' <div class="wpsp-fields">');
        foreach ($this->models->templates->GetAllUniqueFieldsByTemplateID(2) as $key=>$field) {
            echo (' <p>
                        <label for="'.PLUGIN_NAME.'[template2]['.$field->name.']">'.__($field->displayName, $field->name).'</label>');
            $this->OutputField($field, $data[$field->name]);
            echo (' </p>');
        }
        echo ('</div>');
        unset($data);
    }
    
    /**
    * Outputs squeeze page template unique fields
    */
    function OutputColumnSqueezePageFields() {
        global $post;
        $data = get_post_meta($post->ID, PLUGIN_NAME.'-template3', true);
        
        echo (' <div class="wpsp-fields">');
        foreach ($this->models->templates->GetAllUniqueFieldsByTemplateID(3) as $key=>$field) {
            echo (' <p>
                        <label for="'.PLUGIN_NAME.'[template3]['.$field->name.']">'.__($field->displayName, $field->name).'</label>');
            $this->OutputField($field, $data[$field->name]);
            echo (' </p>');
        }
        echo ('</div>');
        unset($data);
    }
    
    /**
    * Outputs squeeze page template unique fields
    */
    function OutputPowerSqueezePageFields() {
        global $post;
        $data = get_post_meta($post->ID, PLUGIN_NAME.'-template4', true);
        
        echo (' <div class="wpsp-fields">');
        foreach ($this->models->templates->GetAllUniqueFieldsByTemplateID(4) as $key=>$field) {
            echo (' <p>
                        <label for="'.PLUGIN_NAME.'[template4]['.$field->name.']">'.__($field->displayName, $field->name).'</label>');
            $this->OutputField($field, $data[$field->name]);
            echo (' </p>');
        }
        echo ('</div>');
        unset($data);
    }
    
    
    /**
    * Outputs an individual field, depending on the given field attributes
    * 
    * @param object $field Field Attributes
    * @param string $value Field Value
    * @return string Field HTML
    */
    function OutputField($field, $value) {
        $fieldName = PLUGIN_NAME.(isset($field->templateID) ? '[template'.$field->templateID.']['.$field->name.']' : '[template]['.$field->name.']');
        $value = ($value == '' ? $field->defaultValue : $value);
        
        switch ($field->type) {
            case 'text':
                echo ('<input type="text" name="'.$fieldName.'" class="text" value="'.$value.'" />');
                break;
            case 'textarea':
                echo ('<textarea name="'.$fieldName.'">'.$value.'</textarea>');
                break;
            case 'color':
                echo (' <div class="wpsp-color-holder">
                            <input type="hidden" name="'.$fieldName.'" class="wpsp-hex" value="'.$value.'" />
                        </div>');
                break;
            case 'font':                                        
                echo ('<select name="'.$fieldName.'" size="1">');
                foreach ($this->models->templates->GetAllFonts() as $key=>$font) {
                    echo (' <option value="'.$font->fontID.'"'.($value == $font->fontID ? ' selected' : '').'>'.$font->name.'</option>');
                }
                echo ('</select>');
                break;
            case 'select':
                echo ('<select name="'.$fieldName.'" size="1">');
                foreach (explode(',', $field->choices) as $cKey=>$option) {
                    echo (' <option value="'.$option.'"'.($value == $option ? ' selected' : '').'>'.$option.'</option>');
                }
                echo ('</select>');
                break;
            case 'checkbox':
                echo ('<input type="checkbox" name="'.$fieldName.'" value="1"'.($value == 1 ? ' checked' : '').'/>');
                break;
            case 'image':
            case 'backgroundImage':
                if ($value != '') {
                    // Existing Image
                    echo ('<input type="radio" class="radio" name="'.$fieldName.'[option]" value="existing" checked /> Existing Image</p>');
                    echo ('<p><label for="'.$fieldName.'[option]">&nbsp;</label><input type="radio" class="radio" name="'.$fieldName.'[option]" value="new" /> New Image: <input type="file" name="'.$fieldName.'[file]" /></p>');
                    echo ('<p><label for="'.$fieldName.'[option]">&nbsp;</label><input type="radio" class="radio" name="'.$fieldName.'[option]" value="none" /> No Image');
                    echo ('<input type="hidden" name="'.$fieldName.'[existing]" value="'.$value.'" /><br />');
                } else {
                    // No Image
                    echo ('<input type="radio" class="radio" name="'.$fieldName.'[option]" value="new" /> New Image: <input type="file" name="'.$fieldName.'[file]" /></p>');
                    echo ('<p><label for="'.$fieldName.'[option]">&nbsp;</label><input type="radio" class="radio" name="'.$fieldName.'[option]" value="none" checked /> No Image');
                }
                break;
        }    
    }
    
    /**
    * Saves custom page fields
    * 
    * @param int $pageID Page ID
    * @return Success
    */
    function Save($post_id) {
        global $saveDone; // save_post seems to run multiple times, we only want to do this once.
        
        if ($saveDone) return $post_id;
        if (!wp_verify_nonce($_POST[PLUGIN_NAME.'_wpnonce'], plugin_basename(__FILE__))) return $post_id;
        if (!current_user_can('edit_post', $post_id)) return $post_id;
        
        // Shared Fields
        foreach ($this->models->templates->GetAllSharedFields() as $key=>$field) {
            switch ($field->type) {
                case 'text':
                case 'textarea':
                    // $data[$field->name] = htmlentities($_POST[PLUGIN_NAME]['template'][$field->name], ENT_QUOTES);
                    $data[$field->name] = esc_attr($_POST[PLUGIN_NAME]['template'][$field->name]);
                    break;
                case 'image':
                case 'backgroundImage':
                    switch ($_POST[PLUGIN_NAME]['template'][$field->name]['option']) {
                        case 'existing':
                            $fieldData = $_POST[PLUGIN_NAME]['template'][$field->name]['existing'];
                            break;
                        case 'new':
                            $fieldData = $this->models->files->UploadFileToServer('', $field->name, PLUGIN_ROOT.'/uploads');
                            if ($field->type == 'image') $this->models->images->ResizeImage($data[$field->name], PLUGIN_ROOT.'/uploads', $field->characterLimit); // Resize image to specified width
                            break;
                        case 'none':
                            $fieldData = '';
                            break;
                    }
                    break;
                default:
                    $data[$field->name] = $_POST[PLUGIN_NAME]['template'][$field->name];    
                    break;                     
            }
        }

        // Update post meta
        update_post_meta($post_id, PLUGIN_NAME.'-template', $data);
        unset($data);
        
        // Unique Fields
        foreach ($this->models->templates->GetAllTemplates() as $cKey=>$template) {
            $fields = $this->models->templates->GetAllUniqueFieldsByTemplateID($template->templateID);
            
            if (count($fields) > 0) {
                foreach ($fields as $key=>$field) {
                    if ($field->type == 'image' OR $field->type == 'backgroundImage') {
                        switch ($_POST[PLUGIN_NAME]['template'.$template->templateID][$field->name]['option']) {
                            case 'existing':
                                $data[$field->name] = $_POST[PLUGIN_NAME]['template'.$template->templateID][$field->name]['existing'];
                                break;
                            case 'new':
                                $data[$field->name] = $this->models->files->UploadFileToServer($template->templateID, $field->name, PLUGIN_ROOT.'/uploads');
                                if ($field->type == 'image') $this->models->images->ResizeImage($data[$field->name], PLUGIN_ROOT.'/uploads', $field->characterLimit); // Resize image to specified width
                                break;
                            case 'none':
                                $data[$field->name] = '';
                                break;
                        }
                    } else {
                        $data[$field->name] = $_POST[PLUGIN_NAME]['template'.$template->templateID][$field->name];
                    }
                }
                
                // Update post meta
                update_post_meta($post_id, PLUGIN_NAME.'-template'.$template->templateID, $data);
                unset($data);
            }
            unset($fields);
        }
        
        $saveDone = true;
    }
}

$wpsg = new WPSP(); // Initialise Class

/**
* Adds a Footer Menu into the Squeeze Pages
*/
add_action( 'init', 'register_my_menu' );

function register_my_menu() {
	    if ( function_exists( 'register_nav_menu' ) ) {
        register_nav_menu('Wpspfooter', __('WPSP Footer Menu', 11 ));
    }
}

?>