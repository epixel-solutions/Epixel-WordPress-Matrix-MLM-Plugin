<div class="time-range">
	<div class="row">
		<div class="col-lg-6 col-sm-12">
			<div class="list-group">
				<p>Morning</p>
				<?php if(isset($date_range['am']) ){ ?>
						<?php foreach($date_range['am'] as $k => $v){ ?>
								<a href="#" class="webinar_time list-group-item" data-time="<?php echo $v;?>"><?php echo $v;?></a>
						<?php } ?>
				<?php } ?>
			</div>
		</div>
		<div class="col-lg-6 col-sm-12">
			<div class="list-group">
				<p>Afternoon / Evening</p>
				<ul>
				<?php if(isset($date_range['pm']) ){ ?>
						<?php foreach($date_range['pm'] as $k => $v){ ?>
								<a href="#" class="webinar_time list-group-item" data-time="<?php echo $v;?>"><?php echo $v;?></a>
						<?php } ?>
				<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>