<?php
// Load Wordpress Environment
require(substr(str_replace("\\", "/", dirname(__FILE__)), 0, strpos(str_replace("\\", "/", dirname(__FILE__)), "/wp-content")).'/wp-blog-header.php');
if (!have_posts()) header('HTTP/1.1 200 OK'); // Force 200 OK to replace 404 error

// Format FunctionName => array(label,image)
// Must be a class function in wpsp.php
// Image must be stored in images/admin
$shortcodes = array(
	'GrayBox' => array(
		'label' => 'Gray Box',
		'image' => 'graybox.png'
	),
	'WhiteBox' => array(
		'label' => 'White w/ Red Dash Border Box',
		'image' => 'whitebox.png'
	),
	'WhiteBlkBox' => array(
		'label' => 'White w/ Black Dash Border Box',
		'image' => 'whiteblashbox.png'
	),
	'BlkBox' => array(
		'label' => 'White Box solid black border',
		'image' => 'blkbox.png'
	),
	'Box' => array(
		'label' => 'Bluish Box',
		'image' => 'box.png'
	),
	'YellowBox' => array(
		'label' => 'Yellow Box',
		'image' => 'yellbox.png'
	),
	'BQuote' => array(
		'label' => 'Blockquote',
		'image' => 'bquote.png'
	)
);
?>
<!DOCTYPE html>
<head>
	<title>Insert Shortcode</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var output = '';
			var highlightedContent = tinyMCEPopup.getWindowArg('highlightedContent');

			$('form#wpsp-shortcodes').bind('submit', function(e) {
				e.preventDefault();
				shortcode = $('input[name=shortcode]:checked').val().toLowerCase();
				output = '['+shortcode+']'+highlightedContent+'[/'+shortcode+']';

				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
			});
		});	
	</script>
	
	<style type="text/css">
		/* Box Styling */
		form#wpsp-shortcodes ul { float: left; list-style: none; margin: 0; padding: 0; }
		form#wpsp-shortcodes ul li { float: left; width: 60px; margin: 0 10px 10px 0; }
		form#wpsp-shortcodes ul li label { float: left; width: 60px; height: 70px; }
		form#wpsp-shortcodes ul li img { clear: both; float: left; width: 16px; height: 16px; margin: 0 17px 10px 17px; }
		form#wpsp-shortcodes ul li input { clear: both; float: left; margin: 0 0 0 19px; }
	</style>
</head>
<body>
	<form id="wpsp-shortcodes">
		<ul>
			<?php
			foreach ($shortcodes as $key=>$codeArr) {
				?>
				<li>
					<label for="shortcode"><?php _e($codeArr['label']); ?></label>
					<img src="<?php echo WP_PLUGIN_URL.'/'.PLUGIN_NAME; ?>/images/admin/<?php echo $codeArr['image']; ?>" width="16" height="16" alt="Gray Box" />
					<input type="radio" name="shortcode" value="<?php echo $key; ?>" />
				</li>
				<?php
				}
			?>
		</ul>
		
		<p>
			<input type="submit" name="add" value="<?php echo _e('Insert Shortcode'); ?>" />
		</p>
	</form>
</body>
</html>