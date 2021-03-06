<div ID="wrap">
<div id="container"> 
    <div id="header">
        <?php if (trim($postMeta['shared']['teaser']) != '') { ?><p class="teaser"><?php echo $postMeta['shared']['teaser']; ?></p><?php } ?>
        <?php if (trim($postMeta['shared']['headline']) != '') { ?><h1><?php echo $postMeta['shared']['headline']; ?></h1><?php } ?>
        <?php if (trim($postMeta['shared']['subHeadline']) != '') { ?><h2><?php echo $postMeta['shared']['subHeadline']; ?></h2><?php } ?>
    </div>
    	<div id="content">
        	<?php 
        	if (have_posts()) {
            	while (have_posts()) {
                	the_post();
                	the_content();
            	}
        	}
        
        // Include opt in list
        require_once(PLUGIN_ROOT.'/views/optin.php'); 
        ?>
       
    
    	</div>
        
    <!-- HTML / Javascript -->
    	<div id="footer-text">
    	<?php if (trim($postMeta['shared']['footerCode']) != '') echo html_entity_decode($postMeta['shared']['footerCode'], ENT_QUOTES); ?>
    	</div>
    	 
    	<div id="footer">
    	</div>
    </div> <!-- End Container -->
    
	<div id="wpspfooternav" role="navigation">
		<?php if (function_exists('wp_nav_menu')) { wp_nav_menu( array( 'container_class' => 'wpsp-footer', 'theme_location' => 'Wpspfooter', 'fallback_cb' => '' ) ); } ?>
	</div>
	<div class="credit">Copyright &copy <?php the_time('Y') ?> <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> All Rights Reserved. <?php if (trim($postMeta['shared']['affiliateLink']) != '') { ?><p>Powered By: <a href="http://<?php echo $postMeta['shared']['affiliateLink']; ?>.pteam.hop.clickbank.net" title="Affiliates">WPSqueezePage Plugin</a></p><?php } ?></div>

</div> <!-- End Wrap -->