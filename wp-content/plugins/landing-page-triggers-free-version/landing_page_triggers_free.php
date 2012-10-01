<?php
/*
Plugin Name: Landing page triggers free
Plugin URI: http://www.tacticaltechnique.com/wordpress/landing-page-triggers-free-version/
Description: Redirects users to a landing page after publishing a post, logout, or both.
Author: Corey Salzano
Version: 0.101111
Author URI: http://twitter.com/salzano
*/

// no edits required for documented features
// 1. upload and activate this plugin
// 2. visit the "Landing pages" options page under Settings to operate

// enhanced version available with redirects after posting comments and logging in
// visit http://www.tacticaltechnique.com/landing-page-triggers-wordpress-plugin/ for details


//load the saved or default options
function get_admin_options() {
	$optionName = "thank_you_page_plugin_settings";
	$wpurl = get_bloginfo('wpurl');
	$thankYouOptions = array('thankYouCommentTF' => false,
								'thankYouPostTF' => false,
								'thankYouLoginTF' => false,
								'thankYouLogoutTF' => false,
								'thankYouLoginURLStr' => $wpurl.'/#Thanks',
								'thankYouLogoutURLStr' => $wpurl.'/#Thanks',
								'thankYouCommentURLStr' => $wpurl.'/#Thanks!',
								'thankYouPostURLStr' => $wpurl.'/#Thanks!!',);
	$savedOptions = get_option($optionName);
	if(!empty($savedOptions)){
		foreach ($savedOptions as $key => $option) $thankYouOptions[$key] = $option;
	}
	update_option($optionName, $thankYouOptions);
	return $thankYouOptions;
}

//build the admin page to change the way this works
function print_admin() {
	$optionName = "thank_you_page_plugin_settings";
	$thankYouOptions = get_admin_options();
	$thankYouPostTF = $thankYouOptions['thankYouPostTF'];
	$thankYouLogoutTF = $thankYouOptions['thankYouLogoutTF'];
	$thankYouPostURLStr = $thankYouOptions['thankYouPostURLStr'];
	$thankYouLogoutURLStr = $thankYouOptions['thankYouLogoutURLStr'];
	if(isset($_POST['update_options'])){
		$thankYouPostURLStr = $_POST['$thankYouPostURLStr'];
		$thankYouLogoutURLStr = $_POST['$thankYouLogoutURLStr'];
		if( $_POST['thankYouPostTF']=="1"){
			$thankYouPostTF = true;
		} else{
			$thankYouPostTF = false;
		}
		if( $_POST['thankYouLogoutTF']=="1"){
			$thankYouLogoutTF = true;
		} else{
			$thankYouLogoutTF = false;
		}
		$thankYouOptions['thankYouPostTF'] = $thankYouPostTF;
		$thankYouOptions['thankYouLogoutTF'] = $thankYouLogoutTF;

		$thankYouOptions['thankYouPostURLStr'] = $_POST['thankYouPostURLStr'];
		$thankYouOptions['thankYouLogoutURLStr'] = $_POST['thankYouLogoutURLStr'];
		update_option($optionName, $thankYouOptions);
		function curPageURL() {
			$pageURL = 'http';
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}
?>
<div class="updated"><p><strong><?php _e("Settings saved. <a href=\"" . curPageURL() . "\">Click here to return to plugin settings</a>.");?></strong></p></div>
<?php
	} else { //end if post options
?>
<script type="text/javascript" src="thank_you_page.js"></script>
<div class=wrap>
<form method="post" action=""><h2>Landing page triggers</h2>
<p><input type="checkbox" id="thankYouPostTF" value="1" name="thankYouPostTF"<?php if($thankYouPostTF){ ?> checked="checked"<?php } ?>><label for="thankYouPostTF"> Go to </label><input type="text" id="thankYouPostURLStr" name="thankYouPostURLStr" size="66" value="<?php echo $thankYouPostURLStr; ?>" onfocus="wash(this);" onblur="checkWash(this);"> after publishing a new post</p>
<p><input type="checkbox" id="thankYouLogoutTF" value="1" name="thankYouLogoutTF"<?php if($thankYouLogoutTF){ ?> checked="checked"<?php } ?>><label for="thankYouLogoutTF"> Go to </label><input type="text" id="thankYouLogoutURLStr" name="thankYouLogoutURLStr" size="66" value="<?php echo $thankYouLogoutURLStr; ?>" onfocus="wash(this);" onblur="checkWash(this);"> after logging out</p>
<input type="submit" name="update_options" value="<?php _e('Save', 'Thank you page') ?>" />

<p>&nbsp;</p>
<p><a href="http://www.tacticaltechnique.com/landing-page-triggers-wordpress-plugin/">Buy the full plugin</a> to enable these features:</p>
<p style="color: #666;"><input disabled="disabled" type="checkbox" id="thankYouLoginTF" value="1" name="thankYouLoginTF"<?php if($thankYouLoginTF){ ?> checked="checked"<?php } ?>><label for="thankYouLoginTF"> Go to </label><input disabled="disabled" type="text" id="thankYouLoginURLStr" name="thankYouLoginURLStr" size="66" value="<?php echo $thankYouLoginURLStr; ?>" onfocus="wash(this);" onblur="checkWash(this);"> after logging in</p>
<p style="color: #666;"><input disabled="disabled" type="checkbox" id="thankYouCommentTF" value="1" name="thankYouCommentTF"<? if($thankYouCommentTF){ ?> checked="checked"<?php } ?>><label for="thankYouCommentTF"> Go to </label><input disabled="disabled" type="text" id="thankYouCommentURLStr" name="thankYouCommentURLStr" size="66" value="<?php echo $thankYouCommentURLStr; ?>" onfocus="wash(this);" onblur="checkWash(this);"> after posting a comment</p>

<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
<h2>Instructions</h2>
<p>Use the check boxes above to direct users who have just submitted a comment or new post to any web page.</p>
<h2>Get help</h2>
<ul>
	<li>My wordpress.org forum username is salzano. <a href="http://wordpress.org/extend/plugins/profile/salzano">Visit my profile</a></li>
	<li>Visit this plugin's page on <a href="http://www.tacticaltechnique.com/wordpress/landing-page-triggers-free-version/">my website</a>, and leave a comment.</li>
</ul>
</div>
<?php
	}
}  //end print_admin function

if (!function_exists("thank_you_page_admin")) {
    function thank_you_page_admin() {
   		add_options_page('Landing page triggers', 'Landing pages', 'manage_options', 'thank_you_options', 'print_admin');
    }
}
add_action('admin_menu', 'thank_you_page_admin');
$thankYouOptions = get_admin_options();
$thankYouPostTF = $thankYouOptions['thankYouPostTF'];
$thankYouLogoutTF = $thankYouOptions['thankYouLogoutTF'];

//add the action to show the page
if( $thankYouPostTF ){
	if( !function_exists('get_thank_you_post_url')) :
		function get_thank_you_post_url( ){
			$thankYouOptions = get_admin_options();
			return $thankYouOptions['thankYouPostURLStr'];
		}
	endif;
	function redirect_after_post_publish_or_save($location){
		if (isset($_POST['publish'])) {
				wp_redirect(get_thank_you_post_url());
		}
	}
	add_filter('redirect_post_location', 'redirect_after_post_publish_or_save');
}
if( $thankYouLogoutTF ){
	function get_thank_you_logout_url($redirect = '' ){
		$thankYouOptions = get_admin_options();
		$redirect = "&redirect_to=".$thankYouOptions['thankYouLogoutURLStr'];
		return wp_nonce_url( site_url("wp-login.php?action=logout$redirect", 'login'), 'log-out' );
	}
	add_filter('logout_url', 'get_thank_you_logout_url');
}
?>