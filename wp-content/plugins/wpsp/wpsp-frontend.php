<?php
/**
* WPSP Frontend
* 
* @package WPSP
* @author Tim Carr
* @version 1.71
* @copyright n7 Studios
*/

ob_start();
define("DOCUMENT_ROOT", substr(str_replace("\\", "/", dirname(__FILE__)), 0, strpos(str_replace("\\", "/", dirname(__FILE__)), "/wp-content")));
require_once(DOCUMENT_ROOT.'/wp-content/plugins/wpsp/configs/default.php');

// Models
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

// Get template name and field data
$template = explode('.', get_post_meta($post->ID, '_wp_page_template', true));
$templateFolder = $template[0];
$templateID = $models->templates->GetTemplateIDByFolderName(str_replace('wpsp-', '', $templateFolder));
$postMeta = array(  'shared' => get_post_meta($post->ID, PLUGIN_NAME.'-template', true),
                    'unique' => get_post_meta($post->ID, PLUGIN_NAME.'-template'.$templateID, true));
$postMeta['optin'] = $models->lists->GetFormData($postMeta['shared']['optInProvider'], $postMeta['shared']['optInListID'], $postMeta['shared']['optInUsername'], $postMeta['shared']['optInThankYouURL'], $postMeta['shared']['optInErrorURL']);

// Build unique field styles
$uniqueFields = $models->templates->GetAllUniqueFieldsByTemplateID($templateID); 
foreach ($uniqueFields as $key=>$uniqueField) {
    if ($uniqueField->htmlTag != '' AND $uniqueField->cssProperty != '' AND $postMeta['unique'][$uniqueField->name] != '') {
        // Depending on the css property, we may need to append the field data stored for this page
        switch ($uniqueField->cssProperty) {
            case 'background-color':
            case 'color':
                $uniqueCss[$uniqueField->htmlTag][$uniqueField->cssProperty] = '#'.$postMeta['unique'][$uniqueField->name];
                break;
            case 'background-image':
                $uniqueCss[$uniqueField->htmlTag][$uniqueField->cssProperty] = 'url('.get_bloginfo('wpurl').PLUGIN_URL.'/uploads/'.$postMeta['unique'][$uniqueField->name].')';
                break;
            case 'font':
                $uniqueCss[$uniqueField->htmlTag][$uniqueField->cssProperty] = $models->templates->GetFontByID($postMeta['unique'][$uniqueField->name]);
                break;
            default:
                $uniqueCss[$uniqueField->htmlTag][$uniqueField->cssProperty] = $postMeta['unique'][$uniqueField->name];
                break;
        }                       
    }
    
    if ($uniqueField->name == 'javascript' AND trim($postMeta['unique'][$uniqueField->name]) != '') $javascript = $postMeta['unique'][$uniqueField->name]; 
    if ($uniqueField->name == 'css' AND trim($postMeta['unique'][$uniqueField->name]) != '') $css = strip_tags($postMeta['unique'][$uniqueField->name]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>> 
<head>

    <!-- Metadata -->
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="distribution" content="global" />
    <meta name="robots" content="follow, all" />
    <meta name="keywords" content="<?php echo $postMeta['shared']['keywords']; ?>" />
    <meta name="description" content="<?php echo $postMeta['shared']['description']; ?>" />
    
    <!-- Title -->
    <?php
    // Check if title needs wrapping in <title> element
    global $post; 
    $title = wp_title('&raquo;', false, 'right');
    if ($title == '') $title = $post->post_title;
    echo (strpos($title, '<title>') === false) ? '<title>'.$title.'</title>' : $title;
    
    // Enable wp_head if selected
    if ($postMeta['shared']['wpHeaderFooterHooks'] == 1) wp_head();
    ?>
        
    <!-- RSS -->        
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <!-- Stylesheets -->
    <!-- Get optinhead CSS -->
	<style type="text/css">@import url(<?php bloginfo('wpurl'); ?><?php echo PLUGIN_URL; ?>/css/optin/<?php echo strtolower($postMeta['shared']['optInHeadColor']); ?>_head_optin.css); }</style>
	<!-- Get optinbutton CSS -->
	<style type="text/css">@import url(<?php bloginfo('wpurl'); ?><?php echo PLUGIN_URL; ?>/css/optin/<?php echo strtolower($postMeta['shared']['buttonImage']); ?>_optin_button.css); }</style>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('wpurl'); ?><?php echo PLUGIN_URL; ?>/css/frontend-base.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('wpurl'); ?><?php echo PLUGIN_URL; ?>/templates/<?php echo str_replace('wpsp-', '', $templateFolder); ?>/styles.css" media="all" />
    <?php if (trim($postMeta['shared']['extracss']) != '') { ?><style type="text/css"><?php echo $postMeta['shared']['extracss']; ?></style><?php } ?>
        
    <!-- Shared Field Styles -->
    <style type="text/css">
        body { font-family: <?php echo $models->templates->GetFontByID($postMeta['shared']['bodyTextFont']); ?>; }
        #header h1 { font-family: <?php echo $models->templates->GetFontByID($postMeta['shared']['headlineFont']); ?>; color: #<?php echo $postMeta['shared']['headlineColor']; ?>; }
        #header h2 { font-family: <?php echo $models->templates->GetFontByID($postMeta['shared']['subHeadlineFont']); ?>; color: #<?php echo $postMeta['shared']['subHeadlineColor']; ?>; }
        #content ul, #footer-text { list-style: square outside url(<?php bloginfo('url'); ?><?php echo PLUGIN_URL; ?>/images/<?php echo strtolower($postMeta['shared']['bulletImage']); ?>_wps.png); }
        #sidebar { font-family: <?php echo $models->templates->GetFontByID($postMeta['unique']['rightBodyTextFont']); ?>; }
            </style>
    
    <?php if (count($uniqueCss) > 0) { ?>
        <!-- Unique Field Styles -->
        <style type="text/css">
            <?php        
            // Output unique field styles
            foreach ($uniqueCss as $tag=>$attributes) {
                foreach ($attributes as $attribute=>$value) {
                    echo $tag." { ".$attribute.": ".$value."; }\n";
                }
            }
            ?>                        
        </style>
    <?php } ?>
    
    <?php if (isset($javascript)) { ?>
    <!-- Unique Javascript -->
    <script type="text/javascript">
        <?php echo $javascript; ?>
    </script>
    <?php } ?>
    
    <?php if (isset($css)) { ?>
    <!-- Unique CSS -->
    <style type="text/css">
        <?php echo $css; ?>
    </style>
    <?php } ?>
    
</head>

<body>
    <?php   
    // Include template
    require_once(PLUGIN_ROOT.'/templates/'.str_replace('wpsp-', '', $template[0]).'/index.php');
    ?>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('wpurl'); ?><?php echo PLUGIN_URL; ?>/js/frontend.js"></script>
 
    <?php if ($postMeta['shared']['wpHeaderFooterHooks'] == 1) wp_footer(); ?>    
</body>
</html>
