<?php
/*
Plugin Name: Hide Toolbar
Plugin URI: http://www.fahdmurtaza.com/wordpress/plugins/hide-toolbar/
Description: This plugin simply does one job nicely, hides the toolbar for logged in users if they want. Simply go edit your profile and check the option for "Show Toolbar when viewing admin". As in wordpress 3.3, the Header + Admin Bar = Toolbar. In wordpress 3.3, you can use the option "Show Toolbar when viewing site"  in user profile edit screen but there is no option to hide it from admin. This plugin fills in that gap. 
Version: 1.2
Author: Fahd Murtaza
Author URI: http://www.fahdmurtaza.com
Donate link: http://www.fahdmurtaza.com/wordpress-plugins/donate/
License: GPL2

Copyright 2011  Fahad Murtaza aka Fahad Murtaza  (email : info@fahdmurtaza.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
I wanted to do this for so long and I got kick started. <br />

This plugin is inspired and uses code from the WP Tuts article. The article can be found at:

http://wp.tutsplus.com/tutorials/how-to-disable-the-admin-bar-in-wordpress-3-3/
*/

if (!function_exists('disableAdminBar')) {

	function disableAdminBar(){

  	remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 ); // for the admin page
    remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 ); // for the front end

    function remove_admin_bar_style_backend() {  // css override for the admin page
      echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
    }

    add_filter('admin_head','remove_admin_bar_style_backend');

    function remove_admin_bar_style_frontend() { // css override for the frontend
      echo '<style type="text/css" media="screen">
      html { margin-top: 0px !important; }
      * html body { margin-top: 0px !important; }
      </style>';
    }

    add_filter('wp_head','remove_admin_bar_style_frontend', 99);

  }

}


// Add a custom field to the form in "Profile"
function _user_setting_show_admin_toolbar($user) {
?>
    <tr>
        <th scope="row">Add Alert Box</th>
        <td><label for="_show_admin_toolbar">
        <input name="_show_admin_toolbar" type="checkbox" id="_show_admin_toolbar" value="1" <?php checked('1', $user->_show_admin_toolbar); ?> /> Show Toolbar when viewing admin
        </label></td>
    </tr>
<?php
}
 
// Handle data that's posted and sanitize before saving it
function _save_user_setting_show_admin_toolbar( $user_id ) {
    $_show_admin_toolbar = ( !empty($_POST['_show_admin_toolbar']) ? 1 : 0 );
    update_user_meta( $user_id, '_show_admin_toolbar', $_show_admin_toolbar );
}
 
// Now, the per user behavior itself: add the silly alert box if applicable
function _get_user_setting_show_admin_toolbar() {
    if(!get_user_option('_show_admin_toolbar') ) {
		disableAdminBar();
    }
}
 

 
// Hook everything
add_action('personal_options', '_user_setting_show_admin_toolbar');
add_action('personal_options_update', '_save_user_setting_show_admin_toolbar');
add_action('init', '_get_user_setting_show_admin_toolbar');


?>