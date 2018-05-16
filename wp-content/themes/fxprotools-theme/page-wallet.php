<?php 
/*
Template Name: Wallet
*/
get_header(); 
?>

	<?php get_template_part('inc/templates/nav-wallet'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="fx-list-courses">
					<li class="list-item">
						<div class="left">
							<div class="box">
								<span class="sash">Active</span>
								<span class="number">01</span>
							</div>
						</div>
						<div class="right">
							<div class="row">
								<div class="col-md-12">
									<span class="title">Setup & Understanding Your E-Wallet</span>
								</div>
								<div class="col-md-10">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
								</div>
								<div class="col-md-2">
									<a href="<?php bloginfo('url');?>/product/course" class="btn btn-default block">Learn More</a>
								</div>
								<div class="col-md-12">
									<div class="progress">
									 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
											25%
									 	</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
				</ul>
				<br/>
				<div class="fx-header-title">
					<h1>How Can We Pay You?</h1>
					<p>Setup your payment methods for commissions</p>
				</div>
				<div class="panel panel-default epx wallet-form">
					<div class="panel-body">
						<?php echo do_shortcode('[hyper_wallet_acc_form]'); ?>
					</div>
				</div>
				<!-- <div role="tabpanel">
					<div class="fx-tabs-bordered">
						<ul class="nav nav-tabs fx-tabs-bordered" role="tablist">
							<li role="presentation" class="active">
								<a href="#one" aria-controls="one" role="tab" data-toggle="tab">Select Payment Method</a>
							</li>
							<li role="presentation">
								<a href="#two" aria-controls="two" role="tab" data-toggle="tab">Paypal</a>
							</li>
							<li role="presentation">
								<a href="#three" aria-controls="three" role="tab" data-toggle="tab">Bank</a>
							</li>
							<li role="presentation">
								<a href="#four" aria-controls="four" role="tab" data-toggle="tab">Paylution</a>
							</li>
							<li role="presentation">
								<a href="#five" aria-controls="five" role="tab" data-toggle="tab">Bitcoin</a>
							</li>
							<li role="presentation">
								<a href="#six" aria-controls="six" role="tab" data-toggle="tab">Payza</a>
							</li>
							<li role="presentation">
								<a href="#seven" aria-controls="seven" role="tab" data-toggle="tab">I-Payout</a>
							</li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane fade in active" id="one">
								<table class="table">
									<thead>
										<tr>
											<th class="small">Available Payment Methods</th>
											<th class="small">Status</th>
											<th class="small text-center">Configuration</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Paypal</td>
											<td class="toggle-action">
												<input class="fx-slide-toggle" id="cb1" type="radio" name="group1">
												<label class="fx-slide-toggle-btn" for="cb1"></label>
											</td>
											<td class="text-center">
												<a href="#"><i class="fa fa-cog"></i></a>
											</td>
										</tr>
										<tr>
											<td>Bank</td>
											<td class="toggle-action">
												<input class="fx-slide-toggle" id="cb2" type="radio" name="group1">
												<label class="fx-slide-toggle-btn" for="cb2"></label>
											</td>
											<td class="text-center">
												<a href="#"><i class="fa fa-cog"></i></a>
											</td>
										</tr>
										<tr>
											<td>Paylution</td>
											<td class="toggle-action">
												<input class="fx-slide-toggle" id="cb3" type="radio" name="group1">
												<label class="fx-slide-toggle-btn" for="cb3"></label>
											</td>
											<td class="text-center">
												<a href="#"><i class="fa fa-cog"></i></a>
											</td>
										</tr>
										<tr>
											<td>Bitcoin</td>
											<td class="toggle-action">
												<input class="fx-slide-toggle" id="cb4" type="radio" name="group1">
												<label class="fx-slide-toggle-btn" for="cb4"></label>
											</td>
											<td class="text-center">
												<a href="#"><i class="fa fa-cog"></i></a>
											</td>
										</tr>
										<tr>
											<td>Payza</td>
											<td class="toggle-action">
												<input class="fx-slide-toggle" id="cb5" type="radio" name="group1">
												<label class="fx-slide-toggle-btn" for="cb5"></label>
											</td>
											<td class="text-center">
												<a href="#"><i class="fa fa-cog"></i></a>
											</td>
										</tr>
										<tr>
											<td>I-Payout</td>
											<td class="toggle-action">
												<input class="fx-slide-toggle" id="cb6" type="radio" name="group1">
												<label class="fx-slide-toggle-btn" for="cb6"></label>
											</td>
											<td class="text-center">
												<a href="#"><i class="fa fa-cog"></i></a>
											</td>
										</tr>
									</tbody>
								</table>
								<p class="text-center text-italic m-b-none">Note: Only one payment method can be active at a time. </p>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="two">
								<strong>Paypal Settings Goes Here</strong>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="three">
								<strong>Bank Settings Goes Here</strong>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="four">
								<strong>Paylution Settings Goes Here</strong>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="five">
								<strong>Bitcoin Settings Goes Here</strong>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="six">
								<strong>Payza Settings Goes Here</strong>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="seven">
								<strong>I-Payout Settings Goes Here</strong>
							</div>
						</div>-->
					</div>
				</div>
			</div>
		</div>
	</div>





<?php get_footer(); ?>
