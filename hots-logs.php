<?php
/*
Plugin Name: Hots Logs - Leaderboards
Plugin URI: http://vooders.com/
Description: A simple plugin to compare Hots Logs player data.
Version: 1.3.0
Author: Vooders
Author URI: http://vooders.com
License: GPL
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include('hots-options.php'); 	// Load the admin page code
include('widgets/hl-leaderboard.php');		// Load the hero league widget code
include('widgets/qm-leaderboard.php');		// Load the quick mach widget code
include_once('scraper/api_scraper.php');			// Load the scraper
include('shortcodes.php');	// Load the shortcodes 

/* Runs when plugin is activated */
register_activation_hook( __FILE__, 'hots_logs_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'hots_logs_uninstall' );

add_action('wp_loaded', 'update_hotslogs_data');
add_action('init', 'quick_match_register_shortcode');
add_action('init', 'hero_league_register_shortcode');

/*
* The installation function
*/
function hots_logs_install() {
	add_option('hots_logs_last_scrape', '0', '', 'yes');	
	hots_logs_make_db();
}

/* The uninstall function */
function hots_logs_uninstall() {
	delete_option('hots_logs_last_scrape');
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";
	$sql = "DROP TABLE ". $table_name;
	$wpdb->query($sql);
}


/*
* Creates the database table to store our player data 
*/
global $jal_db_version;
$jal_db_version = '1.0';
function hots_logs_make_db(){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin"; 
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		player_id int(9) NOT NULL,
		name tinytext NOT NULL,
		hl_mmr int(5) NOT NULL,
		qm_mmr int(5) NOT NULL,
		hl_image_src VARCHAR(2083) NOT NULL,
		qm_image_src VARCHAR(2083) NOT NULL,
		UNIQUE KEY player_id (player_id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version );
}

/*
* Returns the player data table as an array
*/
function getData(){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin"; 
	$result = $wpdb->get_results( "SELECT * FROM $table_name "); 
	return $result;	 
}

/*
* Inserts a new player into the database
* If player allready exists
* Updates the player information
*/
function insert_player($playerArray){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";
	$wpdb->replace(
		$table_name,
		array(
			'player_id' => $playerArray['pid'], 
			'name' => $playerArray['name'], 
			'hl_mmr' => $playerArray['heroLeague'], 
			'qm_mmr' => $playerArray['quickMatch'],
			'hl_image_src' => $playerArray['hl_image'],
			'qm_image_src' => $playerArray['qm_image']
		),
		array (
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		)
	);	
}

/*
* Updates the hotslogs.com data for all players in the database
* Slows down page load when run!
* Limited to 1 run every 60 min
*/
function update_hotslogs_data(){
	$last_scrape = get_option('hots_logs_last_scrape');
	if ((current_time('timestamp')-$last_scrape) >= 3600 ){ 
		global $wpdb;
		$table_name = $wpdb->prefix . "hots_logs_plugin";	
		$pids = $wpdb->get_col("SELECT player_id FROM $table_name");
		foreach ($pids as $pid){
			insert_player(add_pid($pid));
		}
		$last_scrape = current_time('timestamp');
		update_option('hots_logs_last_scrape', $last_scrape);
	}	
}

/*
* Deletes a player from the database
*/
function delete_player($pid){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";
	$wpdb->delete($table_name, array('player_id' => $pid));
}

?>