<?php
//error_reporting(E_ALL); ini_set('display_errors',1);

function add_pid($pid){
	$api_url = 'https://www.hotslogs.com/API/Players/';
	$u = $api_url . $pid;
	return scrape($u);
}

function add_btag($tag, $reg){
	$api_url = 'https://www.hotslogs.com/API/Players/';
	$battle_tag = implode('_', explode('#', $tag));
	$u = $api_url . $reg . '/' . $battle_tag;
	return scrape($u);
}

function scrape($url){
	
	$img_url = WP_PLUGIN_URL . '/hots-logs-local-leaderboards/images/' ;
	
	$player_data = array(
			'name' => null,
			'pid' => '0',
			'heroLeague' => '0',
			'quickMatch' => '0',
			'hl_image' => '0',
			'qm_image' => '0'
		);
	
	$data = json_decode(file_get_contents($url), true);
	$player_data['name'] = $data['Name'];
	$player_data['pid'] = $data['PlayerID'];
	$leagues = $data['LeaderboardRankings'];
	
	foreach ($leagues as $league){
		$league_id = $league['LeagueID'];
		if ($league_id === NULL)
			$league_id = 'null';
		if ($league['GameMode'] == 'QuickMatch'){
			$player_data['quickMatch'] = $league['CurrentMMR'];
			$player_data['qm_image'] = $img_url . $league_id . '.png';
		}
		elseif ($league['GameMode'] == 'HeroLeague'){
			$player_data['heroLeague'] = $league['CurrentMMR'];
			$player_data['hl_image'] = $img_url . $league_id . '.png';
		}
	}
	
	if ($player_data['name']!=null)
		return $player_data;
	else
		return false;
}	
?>