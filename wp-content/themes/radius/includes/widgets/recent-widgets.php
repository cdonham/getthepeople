<?php
/*-----------------------------------------------------------------------------------*/
/* Okay Recent Widgets
/*-----------------------------------------------------------------------------------*/

add_action( 'widgets_init', 'load_okat_recent_widgets' );

function load_okat_recent_widgets() {
	register_widget( 'okay_recent_widgets' );
}

class okay_recent_widgets extends WP_Widget {

	function okay_recent_widgets() {
	$widget_ops = array( 'classname' => 'ok-recent-posts', 'description' => __('Okay Recent Posts Widget', 'ok-recent-posts') );
	$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'ok-recent-widgets' );
	$this->WP_Widget( 'ok-recent-widgets', __('Okay Recent Posts Widget', 'ok-recent-widgets'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract( $args );
		$twitteruser = $instance['twitteruser'];
		$twittercount = $instance['twittercount'];
		$recentcount= $instance['recentcount'];
		$recentcat= $instance['recentcat'];
		$recentcount= $instance['commentcount'];
		
		echo $before_widget;
?>
		
		<!-- Okay Recent Posts Widget -->
		<div class="okay-recent-posts">
			<ul class="tab-wrapper tabs">
				<li><a class="current" href="#"><span><?php _e('Recent Posts','slate'); ?></span>r</a></li>
				<li><a class="" href="#"><span><?php _e('Popular Posts','slate'); ?></span>j</a></li>
				<li><a class="" href="#"><span><?php _e('Popular Tags','slate'); ?></span>J</a></li>
				<li><a class="" href="#"><span><?php _e('Comments','slate'); ?></span>b</a></li>
				<?php if ( $twitteruser ) { ?>
					<li><a class="" href="#"><span><?php _e('Twitter','slate'); ?></span>T</a></li>
				<?php } ?>
			</ul>
			
			<div class="clear"></div>
			
			<div class="panes">
				<!-- Recent Posts -->
				<div class="pane">
					<ul class="recent-posts-widget">
						<?php query_posts('cat='.$instance["recentcat"].'&showposts='.$instance["recentcount"]); ?>
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<li class="recent-posts">
								<?php if ( has_post_thumbnail() ) { ?>
									<a class="recent-posts-thumb" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'sidebar-recent-image' ); ?></a>
								<?php } else { ?>
									<a class="recent-posts-thumb" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/pen-icon.png" alt="pen icon" /></a>
								<?php } ?>
								
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo get_the_date(); ?>  -  <a href="<?php the_permalink(); ?>/#comments" title="comments"><?php comments_number('0 Comments','1 Comment','% Comments'); ?></a></p>
							</li>
						<?php endwhile; ?>
						<?php endif; ?>
					</ul>
				</div><!-- recent posts pane -->
				
				<!-- Popular Posts -->
				<div class="pane">
					<ul class="arrow-list">
						<?php
						global $wpdb;
						$popular_posts = $wpdb->get_results("SELECT id,post_title FROM {$wpdb->prefix}posts ORDER BY comment_count DESC LIMIT 0,5");
						foreach($popular_posts as $post) {
							print "<li><a href='". get_permalink($post->id) ."'>".$post->post_title."</a></li>\n";
						}
						?>
					</ul>
				</div><!-- popular posts -->
				
				<!-- Tag Cloud -->
				<div class="pane">
					<div class="tagcloud">
						<?php wp_tag_cloud( $args ); ?>
					</div>
					<div class="clear"></div>
				</div><!-- tags pane -->
				
				<!-- Recent Comments -->
				<div class="pane">
					<ul class="recent-comments-widget">
						<?php $comments = get_comments('status=approve&number='.$instance["commentcount"]); foreach($comments as $comm) :?>
						<?php
							$url = '<a href="'. get_permalink($comm->comment_post_ID).'#comment-'.$comm->comment_ID .'" title="'.$comm->comment_author .' | '.get_the_title($comm->comment_post_ID).'">' . $comm->comment_author . '</a>';
							
							$length = 100; // adjust to needed length
							$thiscomment = $comm->comment_content;
							if ( strlen($thiscomment) > $length ) {
								$thiscomment = substr($thiscomment,0,$length);
								$thiscomment = $thiscomment .' ...';
							}
						?>
						<li>
							<div class="comment-info">
								<div class="comment-avatar">
									<?php echo get_avatar($comm->comment_author_email, 30); ?>
								</div>
								
								<div class="comment-avatar-right">
									<div class="comment-author">
										<?php echo $url; ?>
									</div>
									
									<div class="comment-date">
										<?php echo $comm->comment_date; ?>
									</div>
								</div>
							</div>
							<div class="clear"></div>
							
							<a class="comment-txt" href="<?php echo get_permalink($comm->comment_post_ID);?>#comment-<?php echo $comm->comment_ID ?>"><?php echo ($thiscomment);?></a>
						</li>
						<?php endforeach;?>
					</ul>
				</div><!-- recent comments -->
				
				<!-- Twitter Feed -->
				<?php if ( $twitteruser ) { ?>
					<div class="pane">
						<div class="twitter-box">
							 <ul class="tweet-list">
							 	<?php print_ok_twitter($instance['twitteruser'], $instance['twittercount']); ?>
							 </ul>
							 
							 <div class="tweets-more"><a href="http://twitter.com/<?php echo $instance['twitteruser']; ?>" target="_blank" title="twitter"><?php _e('Follow','slate'); ?> @<?php echo $instance['twitteruser']; ?> &rarr;</a>
							 </div>
						</div>
					</div><!-- twitter pane -->
				<?php } ?>
			</div><!-- panes -->
		</div><!-- recent post widget -->
			

<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['twitteruser'] = $new_instance['twitteruser'];
		$instance['twittercount'] = $new_instance['twittercount'];
		$instance['recentcount'] = $new_instance['recentcount'];
		$instance['recentcat'] = $new_instance['recentcat'];
		$instance['commentcount'] = $new_instance['commentcount'];		
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'recenttitle' => '', 'recentcount' => '', 'recentcat' => '', 'twitteruser' => '', 'twittercount' => '', 'commentcount' => '') );
		$instance['twitteruser'] = $instance['twitteruser'];
		$instance['twittercount'] = $instance['twittercount'];				
		$instance['recentcount'] = $instance['recentcount'];
		$instance['recentcat'] = $instance['recentcat'];
		$instance['commentcount'] = $instance['commentcount'];
?>

			<p>
				<label for="<?php echo $this->get_field_id('recentcat'); ?>"><?php _e('Recent Posts Category','slate'); ?></label>
				<?php 
				  wp_dropdown_categories( array(
				    'name' => $this->get_field_name( 'recentcat' ),
				    'selected' => $instance["recentcat"],
				    ) );
				
				?>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('recentcount'); ?>"><?php _e('Recent Posts Count','slate'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('recentcount'); ?>" name="<?php echo $this->get_field_name('recentcount'); ?>" type="text" value="<?php echo $instance['recentcount']; ?>" /></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('commentcount'); ?>"><?php _e('Comment Count','slate'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('commentcount'); ?>" name="<?php echo $this->get_field_name('commentcount'); ?>" type="text" value="<?php echo $instance['commentcount']; ?>" /></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('twitteruser'); ?>"><?php _e('Twitter Username','slate'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('twitteruser'); ?>" name="<?php echo $this->get_field_name('twitteruser'); ?>" type="text" value="<?php echo $instance['twitteruser']; ?>" /></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('twittercount'); ?>"><?php _e('Tweet Count','slate'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('twittercount'); ?>" name="<?php echo $this->get_field_name('twittercount'); ?>" type="text" value="<?php echo $instance['twittercount']; ?>" /></label>
			</p>
              
  <?php
	}
}
?>