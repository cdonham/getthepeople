<div id="container">
        <?php if (trim($postMeta['unique']['headerImage']) != '') { ?>
            <div id="headerImage">
                <?php $imageAtts = getimagesize(DOCUMENT_ROOT.'/'.PLUGIN_URL.'/uploads/'.$postMeta['unique']['headerImage']); ?>
                <img src="<?php bloginfo('url'); ?><?php echo PLUGIN_URL; ?>/uploads/<?php echo $postMeta['unique']['headerImage']; ?>" 
                width="<?php echo $imageAtts[0]; ?>" height="<?php echo $imageAtts[1]; ?>" alt="Header Image" />        
            </div>
        <?php } ?>
        
        <div id="header">
            <?php if (trim($postMeta['shared']['teaser']) != '') { ?><p class="teaser"><?php echo $postMeta['shared']['teaser']; ?></p><?php } ?>
            <?php if (trim($postMeta['shared']['headline']) != '') { ?><h1><?php echo $postMeta['shared']['headline']; ?></h1><?php } ?>
            <?php if (trim($postMeta['shared']['subHeadline']) != '') { ?><h2><?php echo $postMeta['shared']['subHeadline']; ?></h2><?php } ?>
        </div>
        
        <div id="main">
            <div id="content">
                <?php 
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        the_content();
                    }
                }
                ?>
                
                
            </div>
            <div id="sidebar">
                <?php 
                if ($postMeta['shared']['optInProvider'] != '(none)') {
                    require_once(PLUGIN_ROOT.'/views/optin.php'); 
                    ?>
                    <p><img src="<?php bloginfo('url'); ?><?php echo PLUGIN_URL; ?>/templates/column-squeeze-page/images/arrowup.png" width="175" height="150" alt="Up Arrow" class="arrow" /></p>
                    <?php
                }
                echo $postMeta['unique']['rightBodyText'];    
                ?>
            </div>
        </div>
        
        
        <!-- HTML / Javascript -->
        <div id="footer-text"><?php if (trim($postMeta['shared']['footerCode']) != '') echo html_entity_decode($postMeta['shared']['footerCode'], ENT_QUOTES); ?></div>
    
        <div id="footer">
        
		<div id="wpspfooternav" role="navigation">
			<?php if (function_exists('wp_nav_menu')) { wp_nav_menu( array( 'container_class' => 'wpsp-footer', 'theme_location' => 'Wpspfooter', 'fallback_cb' => '' ) ); } ?>
		</div>
            <div class="credit">Copyright &copy <?php the_time('Y') ?> <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> All Rights Reserved. <?php if (trim($postMeta['shared']['affiliateLink']) != '') { ?><p>Powered By: <a href="http://<?php echo $postMeta['shared']['affiliateLink']; ?>.pteam.hop.clickbank.net" title="Affiliates">WPSqueezePage Plugin</a></p><?php } ?></div>
        </div>
        
        <?php if (trim($postMeta['unique']['footerImage']) != '') { ?>
            <div id="footerImage">
                <?php $imageAtts = getimagesize(DOCUMENT_ROOT.'/'.PLUGIN_URL.'/uploads/'.$postMeta['unique']['footerImage']); ?>
                <img src="<?php bloginfo('url'); ?><?php echo PLUGIN_URL; ?>/uploads/<?php echo $postMeta['unique']['footerImage']; ?>" 
                width="<?php echo $imageAtts[0]; ?>" height="<?php echo $imageAtts[1]; ?>" alt="Footer Image" />        
            </div>
        <?php } ?>
</div>