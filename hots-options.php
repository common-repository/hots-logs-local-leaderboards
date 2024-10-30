<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( is_admin() ){ // Checks user is an admin
	add_action('admin_menu', 'hots_logs_admin_menu');	// Call the html code 
	
	function hots_logs_admin_menu() {	// Add the options page
		add_options_page('Hots Logs', 'Hots Logs', 'administrator',	'hots_logs', 'hots_logs_html_page');
	}
	

	if(isset($_POST['player_id'])){		// PlayerID is a simple int max (9)
		$check_id = intval($_POST['player_id']); 	// Make sure player ID is a number
		if ( strlen( $check_id ) > 9 ) {			
		  $check_id = substr( $check_id, 0, 9 );
		}
		include_once('scraper/api_scraper.php');	// Scraper will return false if an invalid hotslogs id is passed
		$valid_player = add_pid($check_id);			// Scrape the page
		if ($valid_player != false){
			insert_player($valid_player);					// Insert the player
			add_action( 'admin_notices', 'player_added_notice' );	// Show success message
		} else {										// If scraper returns false
			add_action( 'admin_notices', 'error_notice' ); 	// Show error notice take no other action
		}
		
	} elseif(isset($_POST['battle_tag'])){	
		$clean_tag = check_input($_POST['battle_tag'], $_POST['region']); // Check the user input
		if ($clean_tag != false){ // If input is good
			include_once('scraper/api_scraper.php');	
			$valid_player = add_btag($_POST['battle_tag'], $_POST['region']);	// Scrape the page return false if an invalid battletag is passed
			if ($valid_player != false){ // If a valid hotslogs profile is found
				insert_player($valid_player);					// Insert the player
				add_action( 'admin_notices', 'player_added_notice' );	// Show success message
			} else {										// If scraper returns false
				add_action( 'admin_notices', 'error_notice_btag' ); 	// Show error notice take no other action
			}
		} else {										
			add_action( 'admin_notices', 'error_notice_btag' ); 	// Show error notice take no other action
		}
	}
	elseif(isset($_POST['delete'])){
		delete_player($_POST['name']);
		add_action( 'admin_notices', 'player_deleted_notice' );
	}
}
function check_input($tag, $reg){
		// BattleTag format : NAME#1234
		$tag = explode('#', $tag); // Split the battle tag 
		if ((sizeof($tag) == 2) && ($reg == 1 || 2 )){ // Check we have 2 parts to the tag and a valid region
			$tag[0] = sanitize_text_field($tag[0]); // Clean the name
			$tag[1] = intval($tag[1]); // Clean the ID number
			return $tag;
		} else {
			return false;	
		}
	}
function hots_logs_html_page() {
?>
<div>
<h1>Hots Logs Options</h1>
<p> Add your friends to create your own leaderboards from data scraped from hotslogs.com</p>
<?php 
	settings_fields('hots_logs'); 
	do_settings_sections('hots_logs');
?>
<table width="100%">
<thead>
<tr>
	<td>
    	<h2>Add a Player by Player ID</h2>
		<p>Enter the HOTS Logs player ID of the player you want to add.</p>
    </td>
    <td>
    	<h2>Add a Player by BattleTag</h2>
		<p>Enter the BattleTag and region of the player you want to add.</p>
    </td>
</tr>
</thead>
<tbody>
<th>
<tr>
<td>
    <table width='400px'>
        <tr valign='top'>
            <th width='20%' scope='row'>Player ID</th>
            <td width='60%'>
            	<form method='post' action=''>
                <input name='player_id' type='text' id='player_data' value='' maxlength='10'/>
            </td>
            <td width="20%">
                <?php submit_button('Add', 'primary', 'playerID'); ?>
                <input type='hidden' name='action' value='update' />
				<input type='hidden' name='page_options' value='player_id' />
                </form>
            </td>
        </tr>  
    </table>
</td>
<td>
	<table width='400px'>
        <tr valign='top'>
            <th width='20%' scope='row'>BattleTag</th>
            <td width='60%'>
            	<form method='post' action=''>
                <input name='battle_tag' type='text' id='battle_tag' value='' maxlength='20'/><br>
                <input type="radio" name="region" value="1">US
				<input type="radio" name="region" value="2" checked>EU
            </td>
            <td width="20%">
                <?php submit_button('Add', 'primary', 'playerID'); ?>
                <input type='hidden' name='action' value='update' />
				<input type='hidden' name='page_options' value='battle_tag' />
                </form>
            </td>
        </tr>  
 
    </table>
</td>
</tr>
</tbody>
</table>
<h2>Current Players</h2>
<p>These are the players currently in the database.</p>
<table class="widefat" cellspacing="0">
<thead>
<tr> 
    <th align="left" width="30%">Player ID</th>
    <th align="left" width="60%">Name</th>
    <th align="left" width="10%">Delete</th> 
</tr>
</thead>
<tbody>
<?php
	$result = getData();
	$i = 1;
	foreach($result as $res){
		echo 	
		"<tr> 
		<form method='post' action=''>
			<td>" . $res->player_id . "</td>
			<td>" . $res->name . "</td>
			<td><input type='hidden' name='name' value=".$res->player_id." />" . 
			'<input type="submit" name="delete" id="del" class="button delete" value="X">' . "</td>
		</form>
		</tr>";	
		$i++;
	}
?>
</tbody>
</table>
</div>
<div>
	<p align="right">
    	The plugin will refresh <a href="http://hotslogs.com">hotslogs.com</a> data no more than once per hour
    <br /><small>
    	Last scraped hotslogs.com | <?php echo gmdate("D d M H:i", get_option('hots_logs_last_scrape')) ; ?>
    <br>
    	Next scrape after | <?php echo gmdate("D d M H:i", (get_option('hots_logs_last_scrape')+3600)) . '&nbsp'; ?>
    </small></p>
</div>
<?php
}
function player_added_notice() {
    ?>
    <div class="updated">
        <p><?php _e( 'New player has been added', 'my-text-domain' ); ?></p>
    </div>
    <?php
}

function player_deleted_notice() {
    ?>
    <div class="updated">
        <p><?php _e( 'Player removed from database', 'my-text-domain' ); ?></p>
    </div>
    <?php
}

function error_notice() {
	$class = "error";
	$message = "Error! You entered an invalid HOTS Logs player ID.";
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
}

function error_notice_btag() {
	$class = "error";
	$message = "Error! You entered an invalid BattleTag.";
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
}


?>