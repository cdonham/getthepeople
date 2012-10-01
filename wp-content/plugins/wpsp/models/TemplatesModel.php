<?php
/**
* Templates Model
* 
* @package Models
* @author Tim Carr
* @version 1.71
*/

/**
* TemplatesModel is a model to deal with getting available templates
* 
* @package Models
* @author Tim Carr
*/
class TemplatesModel {
    /**
    * Creates necessary tables for WPSqueezePage to function
    * - wp_wpsp_templates: contains the available templates.  Add records here to add new templates.  viewName should correspond with /templates/viewName - see existing templates for structure.
    * - wp_wpsp_templates_shared_fields: contains field definitions for all templates.  Add records here to add new global template fields
    * - wp_wpsp_templates_custom_fields: contains template-specific field definitions.  Add records here to add template-specific fields
    * - wp_wpsp_fonts: contains a list of available web safe fonts.  Add records here to extend the available font list.
    * 
    * Templates require a specific file structure, and are stored in the /templates folder:
    * /templates/viewName/index.php
    * /templates/viewName/styles.css
    * /templates/viewName/images/screenshot.jpg - 260px width x 230px height 
    * 
    * @return bool Success
    */
    function Install() {
        global $wpdb;
        
        // Templates
        $wpdb->query("  CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpsp_templates (
                            templateID int(10) NOT NULL AUTO_INCREMENT,
                            name varchar(200) NOT NULL,
                            folderName varchar(200) NOT NULL,
                            useSharedFields tinyint(1) NOT NULL default 1,
                            PRIMARY KEY (`templateID`)
                        ) 
                        ENGINE=MyISAM
                        DEFAULT CHARSET=utf8
                        AUTO_INCREMENT=1");
        $wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_templates (templateID, name, folderName, useSharedFields) VALUES 
                        (1, 'Sales Page', 'sales-page', 1),
                        (2, 'Squeeze Page', 'squeeze-page', 1),
                        (3, 'Column Squeeze Page', 'column-squeeze-page', 1),
                        (4, 'Power Squeeze Page', 'power-squeeze-page', 0)");
                        
        // Template Shared Fields
        $wpdb->query("  CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpsp_templates_shared_fields (
                            templateFieldID int(10) NOT NULL AUTO_INCREMENT,
                            hierarchy int(10) NOT NULL,
                            name varchar(200) NOT NULL,
                            displayName varchar(200) NOT NULL,
                            description text,
                            type varchar(200) NOT NULL,
                            choices varchar(200) NOT NULL,
                            defaultValue varchar(200) NOT NULL,
                            characterLimit int(10) NOT NULL,
                            alwaysInclude tinyint(1) NOT NULL default 1,
                            PRIMARY KEY (`templateFieldID`)
                        ) 
                        ENGINE=MyISAM
                        DEFAULT CHARSET=utf8
                        AUTO_INCREMENT=1");
        $wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_templates_shared_fields (templateFieldID, hierarchy, name, displayName, description, type, choices, defaultValue, characterLimit, alwaysInclude) VALUES 
                        (1, 1, 'description', 'Meta Description', 'Description used in search engine results.', 'textarea', '', '', 160, 1),
                        (2, 2, 'keywords', 'Meta Keywords', 'Comma seperated keywords used in search engine results.', 'text', '', '', 0, 1),
                        (3, 3, 'extracss', 'Custom CSS - (optional)', '', 'textarea', '', '', 0, 1),
                        (4, 4, 'teaser', 'Teaser', 'Text at the top of the page.', 'text', '', '', 0, 0),
                        (5, 5, 'headline', 'Headline', '', 'text', '', '', 0, 0),
                        (6, 6, 'headlineColor', 'Headline Font Color', '', 'color', '', '990000', 6, 0),
                        (7, 7, 'headlineFont', 'Headline Font Family', '', 'font', '', '6', 0, 0),
                        (8, 8, 'subHeadline', 'Sub Headline', '', 'text', '', '', 0, 0),
                        (9, 9, 'subHeadlineColor', 'Sub Headline Font Color', '', 'color', '', '000000', 6, 0),
                        (10, 10, 'subHeadlineFont', 'Sub Headline Font Family', '', 'font', '', '8', 0, 0),
                        (11, 11, 'bodyTextFont', 'Body Text Font', '', 'font', '', '8', 0, 0),
                        (12, 12, 'bulletImage', 'List Images and Color', '', 'select', '(None),Black_checkmark,Blue_checkmark,Green_checkmark,Red_checkmark,Black_arrow,Blue_arrow,Green_arrow,Red_arrow,Green_circle_check,Red_x_circle', '1', 0, 0),
                        (13, 13, 'affiliateLink', 'Your Clickbank Aff ID', 'If blank, no affiliate link in the footer is shown.', 'text', '', '', 0, 0),
                        (14, 14, 'optInProvider', 'Opt In Provider', '', 'select', '(none),aWeber,MailChimp,GetResponse', '(none)', 0, 0),
                        (15, 15, 'optInUsername', 'Opt In Login Username', 'The username you use to login to your optin providers web site.', 'text', '', '', 0, 0),
                        (16, 16, 'optInListID', 'Opt In List Name or ID', 'aWeber: Your list / campaign name.<br />Mailchimp: Your list / ID Number<br />GetResponse: Your list / campaign name.', 'text', '', '', 0, 0),
                        (17, 17, 'optInThankYouURL', 'Opt In Thank You URL - (optional)', 'Specify a URL to take the user to on a successful subscription.', 'text', '', '', 0, 0),
                        (18, 18, 'optInErrorURL', 'Opt In Error URL - (optional)', 'Specify a URL to take the user to on an error with the subscription.', 'text', '', '', 0, 0),
                        (19, 19, 'optInHeaderText', 'Opt In Form Headline - (optional)', 'Headline Text above opt-in form - Subscribe Now - Free Download etc.', 'text', '', '', 0, 0),
                        (20, 20, 'TextAboveoptIn', 'Text Above Opt In - (optional)', 'Optional text above opt-in form', 'textarea', '', '', 0, 1),
                        (21, 21, 'optInHeadColor', 'Optin Form Header Color', '', 'select', 'Red_Dashed,Plain_White,Google,Black,Black-Mesh,Blue,Green,Red', '1', 0, 0),
    					(22, 22, 'ButtonText', 'Submit Button Text', 'Text of the submit button on the opt-in form.', 'text', '', 'Get Instant Access!', 35, 0),
                        (23, 23, 'buttonImage', 'Submit Button Color', '', 'select', 'Standard,Blue,Green,Orange,Red', '1', 0, 0),
                        (24, 24, 'footerCode', 'Footer HTML / Javascript - Analytics - (optional)', 'Any HTML / Javascript i.e. Google Analytics code.', 'textarea', '', '', 0, 1),
                        (25, 25, 'wpHeaderFooterHooks', 'Enable wp_head and wp_footer hooks', 'Many themes and plugins require these to be enabled.', 'checkbox', '', '', 0, 0)");
                                                                                     
        // Template Unique Fields
        $wpdb->query("  CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpsp_templates_unique_fields (
                            templateFieldID int(10) NOT NULL AUTO_INCREMENT,
                            templateID int(10) NOT NULL,
                            hierarchy int(10) NOT NULL,
                            name varchar(200) NOT NULL,
                            displayName varchar(200) NOT NULL,
                            description varchar(200) NOT NULL,
                            type varchar(200) NOT NULL,
                            choices varchar(200) NOT NULL,
                            defaultValue varchar(200) NOT NULL,
                            characterLimit int(10) NOT NULL,
                            htmlTag varchar(200) NOT NULL,
                            cssProperty varchar(200) NOT NULL,
                            PRIMARY KEY (`templateFieldID`),
                            KEY `templateID` (`templateID`)
                        ) 
                        ENGINE=MyISAM
                        DEFAULT CHARSET=utf8
                        AUTO_INCREMENT=1");
        $wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_templates_unique_fields (templateFieldID, templateID, hierarchy, name, displayName, description, type, choices, defaultValue, characterLimit, htmlTag, cssProperty) VALUES                
        				(1, 2, 1, 'background-color', 'Background Color', '', 'color', '', 'ffffff', 0, 'body', 'background-color'),
                        (2, 2, 2, 'background-image', 'Background Image', '', 'backgroundImage', '', '', 0, 'body', 'background-image'),
                        (3, 2, 3, 'background-repeat', 'Background Image Repeat', '', 'select', 'repeat,repeat-x,repeat-y,no-repeat', 'repeat', 0, 'body', 'background-repeat'),
                        (4, 2, 4, 'headerImage', 'Header Image', 'Resized to 780px width.', 'image', '', '', 770, '#header', ''),
                        (5, 2, 5, 'footerImage', 'Footer Image', 'Resized to 780px width.', 'image', '', '', 770, '#footer', ''),
                        
                        (6, 3, 1, 'background-color', 'Background Color', '', 'color', '', 'ffffff', 0, 'body', 'background-color'),
                        (7, 3, 2, 'background-image', 'Background Image', '', 'backgroundImage', '', '', 0, 'body', 'background-image'),
                        (8, 3, 3, 'background-repeat', 'Background Image Repeat', '', 'select', 'repeat,repeat-x,repeat-y,no-repeat', 'repeat', 0, 'body', 'background-repeat'),
                        (9, 3, 4, 'headerImage', 'Header Image', 'Resized to 920px width.', 'image', '', '', 920, '#header', ''),
                        (10, 3, 5, 'footerImage', 'Footer Image', 'Resized to 920px width.', 'image', '', '', 920, '#footer', ''),
                        (11, 3, 6, 'rightBodyText', 'Right Column Text', '', 'textarea', '', '', 0, '#sidebar', ''),
                        (12, 3, 7, 'rightBodyTextFont', 'Right Column Text Font', '', 'font', '', '8', 0, '#sidebar', 'font-family'),
                        (13, 3, 8, 'footerBackgroundImage', 'Footer Background Image', 'Repeats horizontally', 'backgroundImage', '', '', 0, '#footer', 'background-image'),
                                                
                        (14, 4, 1, 'javascript', 'Javascript', '', 'textarea', '', '', 0, '', ''),
                        (15, 4, 2, 'css', 'CSS', '', 'textarea', '', '', 0, '', '')");
        
        // Font Options
        $wpdb->query("  CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpsp_templates_fonts (
                            fontID int(10) NOT NULL AUTO_INCREMENT,
                            name varchar(200) NOT NULL,
                            PRIMARY KEY (`fontID`)
                        ) 
                        ENGINE=MyISAM
                        DEFAULT CHARSET=utf8
                        AUTO_INCREMENT=1");
        $wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_templates_fonts (fontID, name) VALUES 
                        (1, 'Arial, Helvetica, sans-serif'),
                        (2, '\"Courier New\", Courier, monospace'),
                        (3, 'Geneva, \"MS Sans Serif\", sans-serif'),
                        (4, 'Georgia, serif'),
                        (5, '\"MS Sans Serif\", Geneva, sans-serif'),
                        (6, 'Tahoma, Arial, Helvetica, sans-serif'),
                        (7, '\"Times New Roman\", Times, serif'),
                        (8, 'Verdana, Arial, Helvetica, sans-serif')");
        
        // Copy each template file into the default, classic and 'current' theme directories
        $this->CopyTemplateStubFiles(get_template());
        
        return true;
    }
    
    /**
    * Upgrades necessary tables and data.
    * 
    * Note that $oldVersion may be used if there is a specific change only required for an upgrade from a specific version to a specific version
    * 
    * @param decimal $oldVersion Old Version of Plugin
    * @param decimal $newVersion New Version of Plugin
    * @return bool Success
    */
    function Upgrade($oldVersion = 0, $newVersion) {
        global $wpdb;
        
        switch ($newVersion) {
            case '1.5':
                $this->RemoveLegacyTemplateStubFiles(get_template());
                $this->CopyTemplateStubFiles(get_template());
                
                $wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_templates_shared_fields (templateFieldID, hierarchy, name, displayName, description, type, choices, defaultValue, characterLimit, alwaysInclude) VALUES 
                                (19, 19, 'footerCode', 'Footer HTML / Javascript / Analytics', 'Any HTML / Javascript i.e. Google Analytics code.', 'textarea', '', '', 0, 1)");
                             //   (20, 20, 'googleOptimizerControlScript', 'Google Optimizer Control Script', 'Your Google Optimizer Control Script. Used for original page only.', 'textarea', '', '', 0, 1),
                             //   (21, 21, 'googleOptimizerTrackingScript', 'Google Optimizer Tracking Script', 'Your Google Optimizer Tracking Script. Used for original / variation pages only.', 'textarea', '', '', 0, 1),
                             //   (22, 22, 'googleOptimizerConversionScript', 'Google Optimizer Conversion Script', 'Your Google Optimizer Conversion Script. Used for conversion page only.', 'textarea', '', '', 0, 1)"); 
                break;
           case '1.6':
                // Always run this to ensure up to date template files are in the current theme
                $this->RemoveLegacyTemplateStubFiles(get_template());
                $this->CopyTemplateStubFiles(get_template());
                
                // Get ID we need to start from for new fields for an upgrade
                $results = $wpdb->get_results(" SELECT * FROM ".$wpdb->prefix."wpsp_templates_shared_fields");
                $id = count($results) + 1;
                
                // Add new fields
                $wpdb->query("  INSERT INTO ".$wpdb->prefix."wpsp_templates_shared_fields (templateFieldID, hierarchy, name, displayName, description, type, choices, defaultValue, characterLimit, alwaysInclude) VALUES 
                                (".$id.", 17, 'optInThankYouURL', 'Opt In Thank You URL - (optional)', 'Specify a URL to take the user to on a successful subscription.', 'text', '', '', 0, 0),
                                (".($id+1).", 17, 'optInErrorURL', 'Opt In Error URL - (optional)', 'Specify a URL to take the user to on an error with the subscription.', 'text', '', '', 0, 0),
                                (".($id+2).", ".$id.", 'wpHeaderFooterHooks', 'Enable wp_head and wp_footer hooks', 'Many themes and plugins require these to be enabled.', 'checkbox', '', '', 0, 0)");

                break; 
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
        
        $wpdb->query("  DROP TABLE IF EXISTS ".$wpdb->prefix."wpsp_templates");
        $wpdb->query("  DROP TABLE IF EXISTS ".$wpdb->prefix."wpsp_templates_shared_fields");
        $wpdb->query("  DROP TABLE IF EXISTS ".$wpdb->prefix."wpsp_templates_unique_fields");
        $wpdb->query("  DROP TABLE IF EXISTS ".$wpdb->prefix."wpsp_templates_fonts");
        $this->RemoveTemplateStubFiles(get_template());
        
        return true;
    }
    
    /**
    * Copies template stub files to the given theme folder
    * 
    * @param string $themeFolder Theme Folder
    */
    function CopyTemplateStubFiles($themeFolder) {
        if (is_dir(WP_CONTENT_DIR.'/themes/'.$themeFolder)) {
            foreach ($this->GetAllTemplates() as $key=>$template) {
            	copy(WP_PLUGIN_DIR.'/'.PLUGIN_NAME.'/configs/wpsp-'.$template->folderName.'.php', WP_CONTENT_DIR.'/themes/'.$themeFolder.'/wpsp-'.$template->folderName.'.php');      
            }
        }
    }
    
    /**
    * Copies template stub files to the given theme folder if they do not exist
    * 
    * @param string $themeFolder Theme Folder
    */
    function CopyTemplateStubFilesIfMissing($themeFolder) {
		if (is_dir(WP_CONTENT_DIR.'/themes/'.$themeFolder)) {
            foreach ($this->GetAllTemplates() as $key=>$template) {
            	if (!file_exists(WP_CONTENT_DIR.'/themes/'.$themeFolder.'/wpsp-'.$template->folderName.'.php')) {
                	copy(WP_PLUGIN_DIR.'/'.PLUGIN_NAME.'/configs/wpsp-'.$template->folderName.'.php', WP_CONTENT_DIR.'/themes/'.$themeFolder.'/wpsp-'.$template->folderName.'.php');
                }
            }
        }
    }
    
    /**
    * Removes template stub files from the given theme folder
    * 
    * @param string $themeFolder Theme Folder
    */
    function RemoveTemplateStubFiles($themeFolder) {
        if (is_dir(DOCUMENT_ROOT.'/wp-content/themes/'.$themeFolder)) {
            foreach ($this->GetAllTemplates() as $key=>$template) {
                unlink(DOCUMENT_ROOT.'/wp-content/themes/'.$themeFolder.'/'.$template->folderName.'.php');
            }
        }
    }
    
    /**
    * Removes legacy template stub files (i.e. without wpsp- prefix) from the active theme and wpsp/configs folder
    * 
    * @param string $themeFolder Theme Folder
    */
    function RemoveLegacyTemplateStubFiles($themeFolder) {
        // Remove old templates from theme and wpsp/configs folder
        if (is_dir(DOCUMENT_ROOT.'/wp-content/themes/'.$themeFolder)) {
            foreach ($this->GetAllTemplates() as $key=>$template) {
                if (file_exists(DOCUMENT_ROOT.'/wp-content/themes/'.$themeFolder.'/'.$template->folderName.'.php')) {
                    unlink(DOCUMENT_ROOT.'/wp-content/themes/'.$themeFolder.'/'.$template->folderName.'.php');
                }
            }
        }        
        if (is_dir(WP_PLUGIN_DIR.'/wpsp/configs')) {
            foreach ($this->GetAllTemplates() as $key=>$template) {
                if (file_exists(WP_PLUGIN_DIR.'/wpsp/configs/'.$template->folderName.'.php')) {
                    unlink(WP_PLUGIN_DIR.'/wpsp/configs/'.$template->folderName.'.php');
                }
            }
        }    
    }
    
    /**
    * Returns a list of all available templates
    * 
    * @return array Templates
    */
    function GetAllTemplates() {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT *
                                        FROM ".$wpdb->prefix."wpsp_templates
                                        ORDER BY ".$wpdb->prefix."wpsp_templates.templateID ASC");
        return $results;
    }
    
    /**
    * Gets all shared fields (used across all templates)
    * 
    * @return array Shared Fields
    */
    function GetAllSharedFields() {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT *
                                        FROM ".$wpdb->prefix."wpsp_templates_shared_fields
                                        ORDER BY ".$wpdb->prefix."wpsp_templates_shared_fields.hierarchy ASC");
        return $results;
    }
    
    /**
    * Gets all unique fields for the given template ID
    * 
    * @param int $templateID Template ID
    * @return array Unique Fields
    */
    function GetAllUniqueFieldsByTemplateID($templateID) {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT *
                                        FROM ".$wpdb->prefix."wpsp_templates_unique_fields
                                        WHERE ".$wpdb->prefix."wpsp_templates_unique_fields.templateID = ".mysql_real_escape_string($templateID)."
                                        ORDER BY ".$wpdb->prefix."wpsp_templates_unique_fields.hierarchy ASC");
        
        return $results;
    }
    
    /**
    * Gets all fonts
    * 
    * @return array Fonts
    */
    function GetAllFonts() {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT *
                                        FROM ".$wpdb->prefix."wpsp_templates_fonts
                                        ORDER BY ".$wpdb->prefix."wpsp_templates_fonts.name ASC");
        return $results;
    }
    
    /**
    * Gets the font name for the specified font ID
    * 
    * @param int $fontID Font ID
    * @return string Font Name
    */
    function GetFontByID($fontID) {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT ".$wpdb->prefix."wpsp_templates_fonts.name
                                        FROM ".$wpdb->prefix."wpsp_templates_fonts
                                        WHERE ".$wpdb->prefix."wpsp_templates_fonts.fontID = ".mysql_real_escape_string($fontID)."
                                        LIMIT 1");
        return $results[0]->name;    
    }
    
    /**
    * Gets the template folder name for the specified template ID
    * 
    * @param int $templateID Template ID
    * @return string View Name
    */
    function GetFolderNameByTemplateID($templateID) {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT ".$wpdb->prefix."wpsp_templates.folderName
                                        FROM ".$wpdb->prefix."wpsp_templates
                                        WHERE ".$wpdb->prefix."wpsp_templates.templateID = ".mysql_real_escape_string($templateID)."
                                        LIMIT 1");
        return $results[0]->folderName;    
    }
    
    /**
    * Get template information for the given template ID
    * 
    * @param int $templateID Template ID
    * @return array Template Data
    */
    function GetTemplateByID($templateID) {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT *
                                        FROM ".$wpdb->prefix."wpsp_templates
                                        WHERE ".$wpdb->prefix."wpsp_templates.templateID = ".mysql_real_escape_string($templateID)."
                                        LIMIT 1");
        return $results[0]; 
    }
    
    /**
    * Get template ID for the given folder name
    * 
    * @param string $folderName Template Folder Name
    * @return int Template ID
    */
    function GetTemplateIDByFolderName($folderName) {
        global $wpdb;
        
        $results = $wpdb->get_results(" SELECT ".$wpdb->prefix."wpsp_templates.templateID
                                        FROM ".$wpdb->prefix."wpsp_templates
                                        WHERE ".$wpdb->prefix."wpsp_templates.folderName = '".mysql_real_escape_string($folderName)."'
                                        LIMIT 1");
        return $results[0]->templateID; 
    }
}
?>
