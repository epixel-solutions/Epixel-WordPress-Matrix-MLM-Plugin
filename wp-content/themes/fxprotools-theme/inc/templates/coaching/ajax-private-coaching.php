<table class="table table-striped">
	<thead>
		<tr>
			<td><?php echo $table_heading_date;?></td>
			<td><?php echo $table_heading_time;?></td>
			<td><?php echo $table_heading_title;?></td>
			<td><?php echo $table_heading_join;?></td>
		</tr>
	</thead>
	<tbody>
		<?php if($webinars){ ?>
				<?php foreach($webinars as $k => $v){ ?>
						<tr>
							<td><?php echo date("D, M j, Y", strtotime($v['data']->times[0]->startTime));?></td>
							<td><?php echo date("g:i A", strtotime($v['data']->times[0]->startTime));?> - <?php echo date("g:i A T", strtotime($v['data']->times[0]->endTime));?></td>
							<td><?php echo $v['data']->subject;?> <span class="label label-info">Order # <?php echo $v['order_id'];?></span></td>
							<td>
								<?php if($v['data']->inSession){ ?>
										<a href="<?php echo $v['data']->registrationUrl;?>"><?php echo $insession_join_meeting;?> </a>
								<?php }else{ ?>
										<?php if( date('Y-m-d') < date('Y-m-d', strtotime($v['data']->times[0]->startTime)) ){ ?>
											<?php
											$product_id = $v['product_id'];
											$get_woogotowebinar_scheduling_window_num = $Apyc_Woo_CoachingTemplate->getWoogotowebinarSchedulingWindowNum($product_id);
											$get_woogotowebinar_scheduling_window_date = $Apyc_Woo_CoachingTemplate->getWoogotowebinarSchedulingWindowDate($product_id);
											$get_woogotowebinar_range_time_from = $Apyc_Woo_CoachingTemplate->getWoogotowebinarRangeTimeFrom($product_id);
											$get_woogotowebinar_range_time_from_meridiem = $Apyc_Woo_CoachingTemplate->getWoogotowebinarRangeTimeFromMeridiem($product_id);
											$get_woogotowebinar_range_time_to = $Apyc_Woo_CoachingTemplate->getWoogotowebinarRangeTimeTo($product_id);
											$get_woogotowebinar_range_time_to_meridiem = $Apyc_Woo_CoachingTemplate->getWoogotowebinarRangeTimeToMeridiem($product_id);
											?>
											<a 
												href="#" 
												class="resched-webinar" 
												data-orderid="<?php echo $v['order_id'];?>" 
												data-webinarkey="<?php echo $k;?>"
												data-schednum="<?php echo $get_woogotowebinar_scheduling_window_num;?>"
												data-scheddate="<?php echo $get_woogotowebinar_scheduling_window_date;?>"
												data-timefrom="<?php echo $get_woogotowebinar_range_time_from;?>"
												data-timefrommeridiem="<?php echo $get_woogotowebinar_range_time_from_meridiem;?>"
												data-timeto="<?php echo $get_woogotowebinar_range_time_to;?>"
												data-timetomeridiem="<?php echo $get_woogotowebinar_range_time_to_meridiem;?>"
												data-currentdate="<?php echo date("D, M j, Y", strtotime($v['data']->times[0]->startTime));?>"
												data-currenttime="<?php echo date("g:i A", strtotime($v['data']->times[0]->startTime));?>"
											>
											Re-Sched </a>
										<?php } ?>
								<?php } ?>
							</td>
						</tr>
				<?php } ?>
		<?php } ?>
	</tbody>
</table>