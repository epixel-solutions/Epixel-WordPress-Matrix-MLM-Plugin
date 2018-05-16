<table class="table table-striped">
	<thead>
		<tr>
			<td><?php echo $table_heading_date;?></td>
			<td><?php echo $table_heading_time;?></td>
			<td><?php echo $table_heading_title;?></td>
		</tr>
	</thead>
	<tbody>
		<?php if($webinars){ ?>
				<?php foreach($webinars as $k => $v){ ?>
						<tr>
							<td><?php echo date("D, M j, Y", strtotime($v['raw']->times[0]->startTime));?></td>
							<td><?php echo date("g:i A", strtotime($v['raw']->times[0]->startTime));?> - <?php echo date("g:i A T", strtotime($v['raw']->times[0]->endTime));?></td>
							<td><?php echo $v['raw']->subject;?></td>
						</tr>
				<?php } ?>
		<?php } ?>
	</tbody>
</table>