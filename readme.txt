=== Plugin Name ===

Contributors:      Vooders
Plugin Name:       HOTS Logs Leaderboards
Plugin URI:        https://github.com/Vooders/hots-logs-wp-plugin/
Tags:              Heroes of the Storm, hots, leaderboard, mmr, hotslogs
Author URI:        vooders.com
Author:            Kev 'Vooders' Wilson
Donate link:       
Requires at least: 3.0.1
Tested up to:      4.3.1
Stable tag:        1.3
Version:           1.3
License: GPLv2

Display Heroes of the Storm MMR leaderboards on your WordPress site. 

== Description ==
Track your frinds MMRs and display them in leaderboards with data from hotslogs.com

Easy to display leaderboards of your friends and team mates.

Data is provided by hotslogs.com

== Installation ==
= WordPress Installer = 
* Open your WordPress admin page and navigate to Plugins > Add New
* Click the Upload Plugin button near the top
* Navigate to where you saved `hots-logs-local-leaderboards.zip` and install
* Activate the plugin

= Manual Install =
* Unzip the file
* Upload to `wp-content/plugins`
* Activate the plugin

= Displaying the Leaderboards =
* Sidebar widgets - The plugin creates each leaderboard as a sidebar widget.
* Shortcodes - Use the shortcodes `[hero-league]` and `[quick-match]` within a page to display the data.

== Screenshots ==
1. Each leaderboard is a seperate widget for easy placement.
2. Simple back-end makes adding and removing players easy.

== Changelog ==
= 1.3 =
* Added basic shortcodes

= 1.2.2 =
* Removed players who have not competed in Hero League from the Hero League Leaderboard
* Fixed an issue with the league image for calibrating players not displaying

= 1.2.1 =
* Correcting issue with 1.2.0 with league images

= 1.2.0 =
* Better handling of JSON data from hotslogs.com

= 1.1.3 =
* Added an image for players who are not yet calibrated into a league.

= 1.1.2 =
* Security Update

= 1.1.1 =
* Now using hotslogs.com API
* Can now add player using BattleTags

== Frequently Asked Questions ==
= How do I add players? =
* Navigate to `Settings > Hots Logs`
* Enter the hotslogs player ID or BattleTag of the player you want to add.

= How do I remove a player? =
* Navigate to `Settings > Hots Logs`
* Click the delete button next to the person you want to remove

= How do I update the MMR data? =
* MMR data is automaticly updated from hotslogs.com.
* The plugin will check at most once an hour for new data.
* The plugin uses the hotlogs API system which does not update as often as the main site so some changes may take an hour or two to filter through the system.

== Upgrade Notice ==

* Faster fetch time for data.