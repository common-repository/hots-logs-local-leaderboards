<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Creating the widget 
class qm_widget extends WP_Widget {

function __construct() {
	parent::__construct(
		// Base ID of your widget
		'hots_logs_qm_leaderboard_widget', 
		
		// Widget name will appear in UI
		__('HOTS Logs | Quick Match Leaderboard', 'qm_widget_domain'), 
		
		// Widget description
		array( 'description' => __( 'Displays a leaderboard of quick match MMRs.', 'qm_widget_domain' ), ) 
	);
	
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	// before and after widget arguments are defined by themes
	echo $args['before_widget'];
	if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];
	
	// This is where you run the code and display the output
	$result = getData();	
	if (sizeof($result != 0)){		
		$filtered_result = array();								// Create an array for our filtered data
		
		foreach($result as $res)
			$filtered_result[] = array('name' => $res->name, 'mmr' => $res->qm_mmr, 'img' => $res->qm_image_src);	
			
		foreach($filtered_result as $key => $row)
			$mmr[$key] = $row['mmr'];
			
		array_multisort($mmr, SORT_DESC, $filtered_result);
		
		$i=1;													// Declare an int to count the positions
		echo __('<table width="100%">', 'qm_widget_domain');	// Write our table headers
		foreach($filtered_result as $res => $val){				// For each filtered result
			echo __('
				<tr>					
					<th>' . $i .'</th>
					<td>' . $val['name'] . '</td>
					<td>' . $val['mmr'] . '</td>
					<td width=10%>
					', 'qm_widget_domain' 
				);
				if ($val['mmr'] != 0)
					echo __('<img id="divLeagueImage" src="'. $val['img'] .'" style="width: 20px;">', 'qm_widget_domain');
				echo __('
					</td>	
				</tr>
				
				', 'qm_widget_domain' 
				);			
			
			$i++;
		}
		echo __('</table>', 'qm_widget_domain');
	}
	echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	} else {
		$title = __( 'Quick Match Leaderboard', 'qm_widget_domain' );
	}
	// Widget admin form
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class qm_widget ends here

// Register and load the widget
function qm_load_widget() {
	register_widget( 'qm_widget' );
}

add_action( 'widgets_init', 'qm_load_widget' );
?>