<div class="table-responsive">
	<table id="table-recent-activity" class="table table-bordered">
		<thead>
			<tr>
				<th>Page Name</th>
				<th>Page Url</th>
				<th>Time</th>
			</tr>
		</thead>
		<tbody>
			<?php  
			$counter = 1;
			$recent_activity = get_user_meta( get_query_var('acc_id'), "track_user_history", true );

			if( isset( $recent_activity ) ){
				$reverse = array_reverse($recent_activity, true);
				$prev_url = "";
				foreach($reverse as $act_data){
					if($counter <= 10){
						if($act_data['title'] && $prev_url != $act_data['link']){ ?>
							<tr>
								<td><?php echo $act_data['title'] ?></td>
								<td><?php echo $act_data['link'] ?></td>
								<td><?php echo random_checkout_time_elapsed($act_data['time']) ?></td>
							</tr>
						<?php $counter++;
						}
						$prev_url = $act_data['link'];
					}else{
						break;
					}
				}
			}
			?>
		</tbody>
	</table>
</div>