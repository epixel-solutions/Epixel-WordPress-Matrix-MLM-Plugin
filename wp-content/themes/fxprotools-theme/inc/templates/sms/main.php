<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="fx-header-title">
				<h1><?php echo $title;?></h1>
				<p><?php echo $sub_heading;?></p>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3">
									<ul class="fx-inbox-nav">
										<li><a href="#sms-compose" aria-controls="sms-compose" role="tab" data-toggle="tab" title="Compose" class="btn btn-danger block compose-sms-button ">Compose Mail</a></li>
										<li><a href="#sms-inbox" aria-controls="sms-inbox" role="tab" data-toggle="tab"><i class="fa fa-inbox"></i> Inbox <span class="label label-danger pull-right">2</span></a></li>
										<li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
										<li><a href="#"><i class=" fa fa-trash-o"></i> Trash</a></li>
									</ul>
								</div>
								<div class="col-md-9">
									<div class="tab-content">
										<!-- sms inbox -->
										<div role="tabpanel" class="tab-pane active" id="sms-inbox">
											<?php $obj_view->view_theme($view_template . 'sms/inbox.php', array()); ?>
										</div>
										<!-- sms inbox -->
										<div role="tabpanel" class="tab-pane" id="sms-compose"><?php $obj_view->view_theme($view_template . 'sms/compose.php', array()); ?></div>
									</div><!-- tab content -->
									
								</div><!-- col-md-9 -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>