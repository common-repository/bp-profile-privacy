<?php
/*
Plugin Name: BuddyPress Profile Privacy
Plugin URI: http://www.jfarthing.com/extend/plugins/bp-profile-privacy
Description: Allows "permissions" to be set for xprofile fields.
Version: 0.2
Requires at least: WP 2.8, BuddyPress 1.2.1
Tested up to: WP 3.0-alpha, BuddyPress 1.2.1
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Jeff Farthing
Author URI: http://www.jfarthing.com
Network: true
*/

/* Only load the component if BuddyPress is loaded and initialized. */
function bp_profile_privacy_init() {
	// Check if xprofile is active
	if ( !function_exists( 'xprofile_install' ) )
		return;
		
	if ( !$admin_settings = get_option( 'bp_profile_privacy' ) )
		bp_profile_privacy_install();

	require( dirname( __FILE__ ) . '/bp-profile-privacy-core.php' );
}
add_action( 'bp_init', 'bp_profile_privacy_init' );

function bp_profile_privacy_install() {
	// Check if xprofile is active
	if ( !function_exists( 'xprofile_install' ) )
		return false;
		
	if ( $admin_settings = get_option( 'bp_profile_privacy' ) )
		return true;
		
	$groups = BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
	
	$fields = array();
	foreach ( $groups as $group ) {
		if ( isset( $group->fields ) && is_array( $group->fields ) ) {
			foreach ( $group->fields as $field ) {
				$fields[$field->id] = 0;
			}
		}
	}
	
	return update_option( 'bp_profile_privacy', $fields );
}
register_activation_hook( __FILE__, 'bp_profile_privacy_install' );

function bp_profile_privacy_uninstall() {
	delete_option( 'bp_profile_privacy' );
}
register_uninstall_hook( __FILE__, 'bp_profile_privacy_uninstall' );

?>