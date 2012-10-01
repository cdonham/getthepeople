<?php get_header(); global $more; ?>

			<!-- If it's the single portfolio post, show this -->
			<?php $portfoliocat = of_get_option('of_portfolio_cat', 'no entry' ); ?>
			<?php if(in_category($portfoliocat)) { ?>
			
				<script type="text/javascript">
					var J = jQuery.noConflict();
	
					J(document).ready(function(){
						//Hide the sidebar toggle
						J("#nav li.sidebar-toggle").css({visibility: "hidden"});
					});
				</script>	
			
				<!-- If it's a video post, don't show the slider -->
				<?php if ( get_post_meta($post->ID, 'okvideo', true) ) { } else { ?>
				
					<!-- Unless specified, show the full width slider -->
					<?php if ( get_post_meta($post->ID, 'slider', true ) == 'sized' ) { } else { ?>
						
						<?php 
							//find images in the content with "wp-image-{n}" in the class name
							preg_match_all('/<img[^>]?class=["|\'][^"]*wp-image-([0-9]*)[^"]*["|\'][^>]*>/i', get_the_content(), $result);  
							//echo '<pre>' . htmlspecialchars( print_r($result, true) ) .'</pre>';
							$exclude_imgs = $result[1];
							
							$args = array(
								'order'          => 'ASC',
								'orderby'        => 'menu_order ID',
								'post_type'      => 'attachment',
								'post_parent'    => $post->ID,
								'exclude'		 => $exclude_imgs, // <--
								'post_mime_type' => 'image',
								'post_status'    => null,
								'numberposts'    => -1,
							);
							
							$attachments = get_posts($args);
							if ($attachments) {
							
							echo "<div id='header-slider' class='flexslider'><ul class='slides'>";
								foreach ($attachments as $attachment) {
									echo "<li class='showcase'><div class='showcase-image'>";
									echo wp_get_attachment_image($attachment->ID, 'full-size', false, false);
									echo "</div></li>";
								}
							echo "</ul></div>"; 
							}
							
							if(count($attachments) == 1) {
								echo "<div class='white-bar'></div>";
							}
						?>			
					
					<?php } ?><!-- end of full width slider -->		
				<?php } ?><!-- show if no video -->	
				
				<!-- contained width images -->
				<div class="container content-portfolio">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					
					<!-- If there is a video, show it. -->
					<?php if ( get_post_meta($post->ID, 'okvideo', true) ) { ?>
						<div class="okvideo-wrap">
							<div class="okvideo">
								<?php echo get_post_meta($post->ID, 'okvideo', true) ?>
							</div>
						</div>
					<?php } else { ?><!-- Otherwise show the images. -->
					
					<!-- If there is a custom field of slider with the value of sized, show the 940px wide slider -->
					<?php if ( get_post_meta($post->ID, 'slider', true ) == 'sized' )  { ?>
					
						<?php 
							//Find images in the content with "wp-image-{n}" in the class name
							preg_match_all('/<img[^>]?class=["|\'][^"]*wp-image-([0-9]*)[^"]*["|\'][^>]*>/i', get_the_content(), $result);  
							//echo '<pre>' . htmlspecialchars( print_r($result, true) ) .'</pre>';
							$exclude_imgs = $result[1];
							
							$args = array(
								'order'          => 'ASC',
								'orderby'        => 'menu_order ID',
								'post_type'      => 'attachment',
								'post_parent'    => $post->ID,
								'exclude'		 => $exclude_imgs, // <--
								'post_mime_type' => 'image',
								'post_status'    => null,
								'numberposts'    => -1,
							);
							
							$attachments = get_posts($args);
							if ($attachments) {
							
							echo "<div class='gallery-wrap sized'><div class='flexslider'><ul class='slides'>";
								foreach ($attachments as $attachment) {
									echo "<li>";
									echo wp_get_attachment_image($attachment->ID, 'full-image', false, false);
									echo "</li>";
								}
							echo "</ul></div></div>";
							} 
							
							if(count($attachments) == 1) {
								echo "<div class='no-nav'></div>";
							}	
						?>
						
						<?php } ?>
					<?php } ?><!-- end sized slider -->
					
					<!-- portfolio content -->
					<div class="content">	
						<div id="post-<?php the_ID(); ?>" <?php post_class('blog-post clearfix'); ?>>
							<div class="blog-inside clearfix">
								<div class="page-title page-title-portfolio">
									<h1><?php the_title(); ?></h1>
								</div>
								
								<div class="blog-entry">
									<div class="blog-content">
										<?php the_content(''); ?>
									</div><!-- blog content -->
								</div><!-- blog entry -->
							</div><!-- blog inside -->
						</div><!-- post -->
					</div><!-- content -->
					
					<!-- portfolio meta details -->
					<div class="portfolio-sidebar">
						<div class="portfolio-meta">
							<h3><?php _e('Project Details','okay'); ?></h3>
							<ul class="portfolio-meta-links">
						    	<li><span><div class="entypo">+</div> <?php the_author_link(); ?></span></li>
						    	<li><span><div class="entypo">P</div> <?php echo get_the_date('m/d/Y'); ?></span></li>
						    	<li><span><div class="entypo">t</div> <div class="tag-wrap"><?php the_category(', ') ?></div></span></li>
						    	<?php the_tags('<li><span><div class="entypo">C</div><div class="tag-wrap">', ', ', '</div></span></li>'); ?>
						    </ul>
						</div><!-- portfolio meta -->
						
						<div class="post-nav clearfix">
							<span class="next-span"><?php next_post_link('%link', __('<span class="next-prev-color">Previous: <span class="previous">%title</span></span>','okay'), TRUE); ?> </span>
							
							<span class="prev-span"><?php previous_post_link('%link', __('<span class="next-prev-color">Next: <span class="previous">%title</span></span>','okay'), TRUE); ?> </span>
						</div>
					</div><!-- sidebar -->
					
					<div class="clear"></div>
					
					<?php endwhile; ?>
					<?php endif; ?>
				</div><!-- container -->
			
			<!-- Otherwise, show this post -->
			<?php } else { ?>
			
			<div class="container">					
				
				<div class="content">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
					<div id="post-<?php the_ID(); ?>" <?php post_class('blog-post clearfix'); ?>>
						<?php if ( get_post_meta($post->ID, 'okvideo', true) ) { ?>
							<div class="okvideo">
								<?php echo get_post_meta($post->ID, 'okvideo', true) ?>
							</div>
						<?php } else { ?>
						
						<?php 
							//find images in the content with "wp-image-{n}" in the class name
							preg_match_all('/<img[^>]?class=["|\'][^"]*wp-image-([0-9]*)[^"]*["|\'][^>]*>/i', get_the_content(), $result);  
							//echo '<pre>' . htmlspecialchars( print_r($result, true) ) .'</pre>';
							$exclude_imgs = $result[1];
							
							$args = array(
								'order'          => 'ASC',
								'orderby'        => 'menu_order ID',
								'post_type'      => 'attachment',
								'post_parent'    => $post->ID,
								'exclude'		 => $exclude_imgs, // <--
								'post_mime_type' => 'image',
								'post_status'    => null,
								'numberposts'    => -1,
							);
							
							$attachments = get_posts($args);
							if ($attachments) {
							
							echo "<div id='header-slider' class='flexslider'><ul class='slides'>";
								foreach ($attachments as $attachment) {
									echo "<li class='showcase'><div class='showcase-image'>";
									echo wp_get_attachment_image($attachment->ID, 'blog-image', false, false);
									echo "</div></li>";
								}
							echo "</ul></div>"; 
							}
							
							if(count($attachments) == 1) {
								echo "<div class='white-bar'></div>";
							}
						?>
						
						<?php } ?>
						
						<div class="blog-inside clearfix">	
							<div class="blog-text">	
								<div class="title-meta">
									<h1><?php the_title(); ?></h1>
								</div>
							
								<div class="blog-entry">
									<div class="blog-content">
										<?php the_content(); ?>
									</div>
									
									<?php if(is_single()) { ?>
										<div class="pagelink">
											<?php wp_link_pages(); ?>
										</div>
									<?php } ?>
								</div><!-- blog entry -->
							</div><!-- blog text -->
							
							
							<!-- Show this on full site -->
							<div class="blog-meta">
								<ul class="meta-links">
							    	<li><span class="meta-list"><span class="entypo">+</span> <?php the_author_link(); ?></span></li>
							    	<li><span class="meta-list"><span class="entypo">P</span> <?php echo get_the_date('m/d/Y'); ?></span></li>
							    	<li><span class="meta-list"><span class="entypo">t</span> <span class="tag-wrap"><?php the_category(', ') ?></span></span></li>
							    	<?php the_tags('<li><span><span class="entypo">C</span><span class="tag-wrap">', ', ', '</span></span></li>'); ?>
							    	<li><span class="meta-list"><span class="entypo">9</span> <a href="<?php the_permalink(); ?>#comments"><?php comments_number(__('No Comments','okay'),__('1 Comment','okay'),__( '% Comments','okay') );?></a></span></li>
							    </ul>
								<div class="clear"></div>
								<ul class="post-share">
									<li class="share-title"><?php _e('Share','okay'); ?></li>
									<li class="twitter">
										<a onclick="window.open('http://twitter.com/home?status=<?php the_title(); ?> - <?php the_permalink(); ?>','twitter','width=450,height=300,left='+(screen.availWidth/2-375)+',top='+(screen.availHeight/2-150)+'');return false;" href="http://twitter.com/home?status=<?php the_title(); ?> - <?php the_permalink(); ?>" title="<?php the_title(); ?>" target="blank"><?php _e('Twitter','okay'); ?></a>
									</li>
									
									<li class="facebook">
										<a onclick="window.open('http://www.facebook.com/share.php?u=<?php the_permalink(); ?>','facebook','width=450,height=300,left='+(screen.availWidth/2-375)+',top='+(screen.availHeight/2-150)+'');return false;" href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" title="<?php the_title(); ?>"  target="blank"><?php _e('Facebook','okay'); ?></a>
									</li>
									
									<li class="googleplus">
										<a href="https://m.google.com/app/plus/x/?v=compose&amp;content=<?php the_title(); ?> - <?php the_permalink(); ?>" onclick="window.open('https://m.google.com/app/plus/x/?v=compose&amp;content=<?php the_title(); ?> - <?php the_permalink(); ?>','gplusshare','width=450,height=300,left='+(screen.availWidth/2-375)+',top='+(screen.availHeight/2-150)+'');return false;"><?php _e('Google+','okay'); ?></a>
									</li>
								</ul>
							</div><!-- blog meta -->
						</div><!-- blog inside -->
					</div><!-- blog post -->
					
					<?php endwhile; ?>
				
					<div class="blog-navigation clearfix">
						<div class="alignleft"><?php next_post_link('%link', '&larr; %title', TRUE); ?></div>
						<div class="alignright"><?php previous_post_link('%link', '%title &rarr;', TRUE); ?></div>
					</div>
					
					<?php if ('open' == $post->comment_status) { ?>
					<div class="comments">
						<?php comments_template(); ?>
					</div>
					<?php } ?>
					
					<?php endif; ?>
				</div><!-- content -->
	
				<?php get_sidebar(); ?>
			</div><!-- container -->	
				
			<?php } ?>

<?php get_footer(); ?>