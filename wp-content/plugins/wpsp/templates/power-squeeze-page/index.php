<div class="containersp">
	<div class="contentsp">
<?php 
if (have_posts()) {
    while (have_posts()) {
        the_post();
        the_content();
    }
}
?>
    <!-- HTML / Javascript -->
    <?php if (trim($postMeta['shared']['footerCode']) != '') echo html_entity_decode($postMeta['shared']['footerCode'], ENT_QUOTES); ?>
    
	</div><!-- end contentsp -->
</div><!-- End containersp -->
	<div id="footersp">
		<div class="credit">Copyright &copy <?php the_time('Y') ?> <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> All Rights Reserved. <?php if (trim($postMeta['shared']['affiliateLink']) != '') { ?><p>Powered By: <a href="http://<?php echo $postMeta['shared']['affiliateLink']; ?>.pteam.hop.clickbank.net" title="Affiliates">WPSqueezePage Plugin</a></p><?php } ?></div>
    </div>