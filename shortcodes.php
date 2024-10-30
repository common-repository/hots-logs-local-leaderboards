<?php
	function quick_match_shortcode(){
	$result = getData();	
	if (sizeof($result != 0)){		
		$filtered_result = array();								// Create an array for our filtered data
		
		foreach($result as $res)
			$filtered_result[] = array('name' => $res->name, 'mmr' => $res->qm_mmr, 'img' => $res->qm_image_src);	
			
		foreach($filtered_result as $key => $row)
			$mmr[$key] = $row['mmr'];
			
		array_multisort($mmr, SORT_DESC, $filtered_result);
		
		$i=1;		// Declare an int to count the positions
		echo __('<h2>Quick Match Leaderboard</h2>');													
		echo __('<table width="30%">');	// Write our table headers
		foreach($filtered_result as $res => $val){				// For each filtered result
			echo __('
				<tr>					
					<td>' . $i .'</td>
					<td>' . $val['name'] . '</td>
					<td>' . $val['mmr'] . '</td>
					<td width=10%>
					'
				);
				if ($val['mmr'] != 0)
					echo __('<img id="divLeagueImage" src="'. $val['img'] .'" style="width: 20px;">');
				echo __('
					</td>	
				</tr>
				
				');			
			
			$i++;
		}
		echo __('</table>');
	}	
}
function quick_match_register_shortcode(){
	add_shortcode('quick-match','quick_match_shortcode');
}

function hero_league_shortcode(){
	$title = apply_filters( 'widget_title', $instance['title'] );
	// before and after widget arguments are defined by themes
	echo $args['before_widget'];
	if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];
	
	// This is where you run the code and display the output
	$result = getData();
	if (sizeof($result != 0)){							 	
		$filtered_result = array();								
		
		foreach($result as $res)
			$filtered_result[] = array('name' => $res->name, 'mmr' => $res->hl_mmr, 'img' => $res->hl_image_src);	
			
		foreach($filtered_result as $key => $row)
			$mmr[$key] = $row['mmr'];
			
		array_multisort($mmr, SORT_DESC, $filtered_result);
		
		$i=1;	
		echo __('<h2>Hero League Leaderboard</h2>');												
		echo __('<table width="100%">');	
		foreach($filtered_result as $res => $val){				
			echo __('
				<tr>					
					<td>' . $i .'</td>
					<td>' . $val['name'] . '</td>
					<td>' . $val['mmr'] . '</td>
					<td width=10%>
					');
					if ($val['mmr'] != 0)
						echo __('<img id="divLeagueImage" src="'. $val['img'] .'" style="width: 20px;">');
					echo __('
					</td>	
				</tr>
				');			
			
			$i++;
		}
		echo __('</table>');
	}
}
function hero_league_register_shortcode(){
	add_shortcode('hero-league','hero_league_shortcode');
}
?>