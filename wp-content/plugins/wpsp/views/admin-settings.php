<div class="wrap">
    <div id="icon-edit" class="icon32"></div>
    <h2>WP Squeeze Page &raquo; Settings</h2>
    
    <?php
    // Error and Success Messages
    if ($data->error != '') {
        echo ('<div class="error wpsp-fade"><p>'.$data->error.'</p></div>');
    }        
    if ($data->message != '') {
        echo ('<div class="updated wpsp-fade"><p>'.$data->message.'</p></div>');
    }
    if ($data->error == '' AND $data->message == '') {
        echo ('<p>&nbsp;</p>');
    }
    ?>
    
	<div class="postbox">
    	<div class="inside" style="padding: 10px;">
    		<!-- Dev license holders can edit below here and insert their personal support links -->
			<p style="text-align:center;"><strong>Thank You for your purchase of WP Squeeze page plugin!</strong></p>
    		<p style="text-align:center;margin:15px 0px;"><a target="_blank" href="http://www.wpsqueezepage.com/members/faqs">FAQs</a> | <a target="_blank" href="http://www.wpsqueezepage.com/members/login">Members Login</a> | <a target="_blank" href="http://wpsqueezepage.com/members/category/videos">How To Videos</a> | <a target="_blank" href="http://www.pegasusteam.com/support/">Support</a></p>
			<!-- Dev license holders STOP EDITING HERE -->
    	</div>
	</div>
    
    <form id="post" name="post" method="post" action="admin.php?page=<?php echo PLUGIN_NAME; ?>">
        <div id="poststuff" class="metabox-holder has-right-sidebar"> 
            <!-- Sidebar -->
            <div id="side-info-column" class="inner-sidebar">
                <div id="side-sortables" class="meta-box-sortables">                    
                    <!-- About -->
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle"><br /></div>
                        <h3 class="hndle"><span><?php _e('About'); ?></span></h3>
                        <div class="inside">
                        	<?php
                        	// Version
                        	if (is_array($data->hiddenSettings) AND is_array($data->hiddenSettings['localVersion'])) {
                        		?>
                        		<p><strong><?php _e($data->hiddenSettings['localVersion']['label']); ?></strong> <?php echo $data->hiddenSettings['localVersion']['value']; ?></p>
                        		<?php
                        	}
                        	
                        	// Update Available
                        	if (is_array($data->hiddenSettings) AND is_array($data->hiddenSettings['localVersion']) AND is_array($data->hiddenSettings['updateVersion']) AND $data->hiddenSettings['localVersion']['value'] < $data->hiddenSettings['updateVersion']['value']) {
                        		?>
                        		<p><strong><?php echo __('Version').' '.$data->hiddenSettings['updateVersion']['value'].' '.__('is available').'.'; ?></strong></p>
                                <p><a href="admin.php?page=<?php echo PLUGIN_NAME; ?>&doUpdate=1" class="button"><?php _e('Update Now'); ?></a></p>
                                <p>&nbsp;</p>
                                <p>
                                	<?php 
                                	_e('Sometimes the update routine doesn\'t work, depending on your server configuration.  If you receive an error, you\'ll need to manually download 
                                	the updated package by clicking the below button, and then upload via FTP.'); 
                                	?>
                                </p>
                                <p><a href="<?php echo $data->hiddenSettings['updateURL']['value']; ?>" class="button"><?php _e('Download Now'); ?></a></p>
                        		<?php
                        	}
                        	?>
                        </div>  
                    </div>
                                        
                    <!-- Updates -->
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle"><br /></div>
                        <h3 class="hndle"><span><?php _e('Updates'); ?></span></h3>
                        <div class="inside">
                            <p><strong><?php _e('Last Update Check'); ?></strong> <?php echo date('dS F H:i:s', strtotime($data->hiddenSettings['lastUpdateCheck']['value'])); ?></p>
                            <p><strong><?php _e('Next Update Check'); ?></strong> <?php echo date('dS F H:i:s', strtotime($data->hiddenSettings['nextUpdateCheck']['value'])); ?></p>
                            <p><a href="admin.php?page=<?php echo PLUGIN_NAME; ?>&checkUpdates=1" class="button"><?php _e('Check for Updates Now'); ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div id="post-body" class="has-sidebar">
                <div id="post-body-content" class="has-sidebar-content">
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable" style="position: relative;">                        
                        <?php
                        // Go through settings fields
                        foreach ($data->settings as $row=>$setting) {
                            ?>
                            <div class="postbox">
                                <h3 class="hndle"><?php echo $setting->label; ?></h3>
                                <div class="inside">
                                    <label class="hidden" for="<?php echo $setting->settingKey; ?>"><?php echo $setting->settingKey; ?></label>
                                    <input type="text" name="<?php echo $setting->settingKey; ?>" value="<?php echo $setting->value; ?>" style="width: 95%;" />
                                    <p><?php echo $setting->description; ?></p>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        
                        <div class="postbox">
                            <h3 class="hndle">Debug Information</h3>
                            <div class="inside">
                                <p><strong>Last License Check: </strong><?php echo date('dS F Y, H:i', strtotime($this->models->settings->GetSettingByKey('lastLicenseCheck'))); ?> (server time)</p>
                                <p><strong>Next License Check: </strong><?php echo date('dS F Y, H:i', strtotime($this->models->settings->GetSettingByKey('nextLicenseCheck'))); ?> (server time)</p>
                            </div>
                        </div>
                        
                        <?php                        
                        $plugins = get_plugins();
                        foreach (get_plugins() as $key=>$plugin) {    
                            if ($key == 'wpsp/wpsp.php' AND $plugin['Version'] == '1.5' AND file_exists(DOCUMENT_ROOT.'/wp-content/themes/'.get_template().'/column-squeeze-page.php')) {
                                // Show button to upgrade from 1.x --> 1.5
                                ?>
                                <div class="postbox">
                                    <h3 class="hndle">Upgrade to v1.5</h3>
                                    <div class="inside">
                                        <label class="hidden" for="doUpgrade">Upgrade to v1.5</label>
                                        <input type="checkbox" name="doUpgrade" value="1" />
                                        <p>Run required actions to complete upgrade to version 1.5</p>
                                    </div>
                                </div> 
                                <?php
                            }
                        }
                        ?>

                        <!-- Save -->
                        <div class="submit">
                            <input type="submit" name="submit" value="<?php _e('Save'); ?>" /> 
                        </div>
                        
                        <!-- Text -->    
    					<div class="postbox">
          					<div class="inside" style="padding: 10px;">
								<h4>WPSqueeze Page Plugin Quick Start Guide</h4>
								<p> This plugin utilizes specially designed templates for your site rather than your typical blog themes layout.</p>
								<p><strong>Follow the steps below to get started:</strong></p>
								<p>
									1) Enter your License Key Above then click Save Settings<br />
									2) Click the Pages tab in the left menu to expand it<br />
									3) Click "Add New" to create a new sales or squeeze page<br />
									4) Select a Template from the templates drop down on the right side of your screen<br />
									5) Use the built in text fields to customize your page (please refer to the User guide for more information)<br />
									5) Enter your text and/or video embed code into the main editor just like you were making a blog post<br />
									7) Click Publish to save and publish your page<br />
									8) You're done! Start driving traffic to your new squeeze page
								</p>
								
								<h4>To make your squeeze page the main page of your site:</h4>
								<p>Go To Settings > Reading > Select the static page option and select your squeeze page from the dropdown menu for your home page</p>
								<p>If you require additional support please refer to the User Guide, How To Videos and FAQ (accessed in the members area)</p>
								
								<h4>Thank you for choosing WPSqueeze Page Plugin!</h4>
          					</div>
    					</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>