<div class="wrap">
	<h1>Customer Information and Subscriptions Manager</h1>
	<br/>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
					<li class="active"><a href="#tab-one" data-toggle="tab">Customer Information</a></li>
					<li><a href="#tab-two" data-toggle="tab">ANET - Subscriptions</a></li>
				</ul>
				<div id="my-tab-content" class="tab-content">
					<div class="tab-pane in active" id="tab-one">
						<table id="table-customers" class="dt-table table table-bordred table-striped">
							<thead>
								<th>Profile ID</th>
								<th>Name</th>
								<th>E-mail</th>
								<th>Description</th>
								<th>Action</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="tab-pane fade" id="tab-two">
						<table id="table-contact" class="dt-table table table-bordred table-striped">
							<thead>
								<th>Name</th>
								<th>E-mail</th>
								<th>Contact No.</th>
								<th>Date</th>
								<th>Action</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="view-lead" class="modal fade view-lead-modal" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div id="modal-loading" class="modal-loading text-center"></div>
				<?php /* Applicant Information */ ?>
				<div class="modal-body-content" data-source="form_application">
					<div role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">
							<li class="active"><a href="#tab-m1" role="tab" data-toggle="tab">Personal Information</a></li>
							<li><a href="#tab-m2" role="tab" data-toggle="tab">Educational Background</a></li>
							<li><a href="#tab-m3" role="tab" data-toggle="tab">Employment History</a></li>
							<li><a href="#tab-m4" role="tab" data-toggle="tab">Family Background</a></li>
							<li><a href="#tab-m5" role="tab" data-toggle="tab">National Service</a></li>
							<li><a href="#tab-m6" role="tab" data-toggle="tab">Others</a></li>
						</ul>
						<div class="tab-content">
							<?php /* Personal Information */ ?>
							<div role="tabpanel" class="tab-pane fade in active" id="tab-m1">
								<div class="row">
									<div class="col-md-6">
										<p><strong>Position Applying for:</strong> <span id="position">Optometrist</span></p>
									</div>
									<div class="col-md-6">
										<p><strong>Expected Salary:</strong> <span id="expected_salary">$2800</span></p>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-6">
										<ul>
											<li><strong>Name:</strong> <span id="name"></span></li>
											<li><strong>Gender:</strong> <span id="gender"></span></li>
											<li><strong>Date of Birth:</strong> <span id="birthday"></span></li>
											<li><strong>Country of Birth:</strong> <span id="birth_country"></span></li>
											<li><strong>Marital Status:</strong> <span id="marital_status"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>NRIC/Passport No.:</strong> <span id="nric"></span></li>
											<li><strong>Nationality:</strong> <span id="nationality"></span></li>
											<li><strong>Race:</strong> <span id="race"></span></li>
											<li><strong>Religion:</strong> <span id="religion"></span></li>
											<li><strong>Spoken Language:</strong> <span id="spoken_lang"></span></li>
											<li><strong>Dialect Group:</strong> <span id="dialect_group"></span></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-6">
										<ul>
											<li><strong>Address:</strong> <span id="address"></span></li>
											<li><strong>E-mail:</strong> <span id="email"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>Tel(Home):</strong> <span id="tel_home"></span></li>
											<li><strong>Tel(Mobile):</strong> <span id="tel_mobile"></span></li>
											<li><strong>Tel(Office):</strong> <span id="tel_office"></span></li>
										</ul>
									</div>
								</div>
							</div>
							<?php /* Educational Background */ ?>
							<div role="tabpanel" class="tab-pane fade" id="tab-m2">
								<div class="row">
									<div class="col-md-6">
										<ul>
											<li><strong>School Attended:</strong> <span id="edu_school_01"></span></li>
											<li><strong>Period:</strong> <span id="edu_period_01"></span></li>
											<li><strong>Certificate Acquired:</strong> <span id="edu_certificate_01"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>School Attended:</strong> <span id="edu_school_02"></span></li>
											<li><strong>Period:</strong> <span id="edu_period_02"></span></li>
											<li><strong>Certificate Acquired:</strong> <span id="edu_certificate_02"></span></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-6">
										<ul>
											<li><strong>School Attended:</strong> <span id="edu_school_03"></span></li>
											<li><strong>Period:</strong> <span id="edu_period_03"></span></li>
											<li><strong>Certificate Acquired:</strong> <span id="edu_certificate_03"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>School Attended:</strong> <span id="edu_school_04"></span></li>
											<li><strong>Period:</strong> <span id="edu_period_04"></span></li>
											<li><strong>Certificate Acquired:</strong> <span id="edu_certificate_04"></span></li>
										</ul>
									</div>
								</div>
							</div>
							<?php /* Employment History */ ?>
							<div role="tabpanel" class="tab-pane fade" id="tab-m3">
								<div class="row">
									<div class="col-md-6">
										<ul>
											<li><strong>Employer Name:</strong> <span id="employer_name_01"></span></li>
											<li><strong>Period:</strong> <span id="emp_period_01"></span></li>
											<li><strong>Position Held:</strong> <span id="emp_position_01"></span></li>
											<li><strong>Salary:</strong> <span id="emp_month_salary_01"></span></li>
											<li><strong>Reason for Leaving:</strong> <span id="emp_reason_leave_01"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>Employer Name:</strong> <span id="employer_name_02"></span></li>
											<li><strong>Period:</strong> <span id="emp_period_02"></span></li>
											<li><strong>Position Held:</strong> <span id="emp_position_02"></span></li>
											<li><strong>Salary:</strong> <span id="emp_month_salary_02"></span></li>
											<li><strong>Reason for Leaving:</strong> <span id="emp_reason_leave_02"></span></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-6">
										<ul>
											<li><strong>Employer Name:</strong> <span id="employer_name_03"></span></li>
											<li><strong>Period:</strong> <span id="emp_period_03"></span></li>
											<li><strong>Position Held:</strong> <span id="emp_position_03"></span></li>
											<li><strong>Salary:</strong> <span id="emp_month_salary_03"></span></li>
											<li><strong>Reason for Leaving:</strong> <span id="emp_reason_leave_03"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>Employer Name:</strong> <span id="employer_name_04"></span></li>
											<li><strong>Period:</strong> <span id="emp_period_04"></span></li>
											<li><strong>Position Held:</strong> <span id="emp_position_04"></span></li>
											<li><strong>Salary:</strong> <span id="emp_month_salary_04"></span></li>
											<li><strong>Reason for Leaving:</strong> <span id="emp_reason_leave_04"></span></li>
										</ul>
									</div>
								</div>
							</div>
							<?php /* Family Background */ ?>
							<div role="tabpanel" class="tab-pane fade" id="tab-m4">
								<div class="row">
									<div class="col-md-6">
										<ul>
											<li><strong>Name:</strong> <span id="fam_bg_name_01"></span></li>
											<li><strong>Relation:</strong> <span id="fam_bg_relation_01"></span></li>
											<li><strong>Age:</strong> <span id="fam_bg_age_01"></span></li>
											<li><strong>Occupation:</strong> <span id="fam_bg_occupation_01"></span></li>
											<li><strong>Name of Company/School:</strong> <span id="fam_bg_edu_lvl_01"></span></li>
											<li><strong>Education Level:</strong> <span id="fam_bg_edu_lvl_01"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>Name:</strong> <span id="fam_bg_name_02"></span></li>
											<li><strong>Relation:</strong> <span id="fam_bg_relation_02"></span></li>
											<li><strong>Age:</strong> <span id="fam_bg_age_02"></span></li>
											<li><strong>Occupation:</strong> <span id="fam_bg_occupation_02"></span></li>
											<li><strong>Name of Company/School:</strong> <span id="fam_bg_edu_lvl_02"></span></li>
											<li><strong>Education Level:</strong> <span id="fam_bg_edu_lvl_02"></span></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-6">
										<ul>
											<li><strong>Name:</strong> <span id="fam_bg_name_03"></span></li>
											<li><strong>Relation:</strong> <span id="fam_bg_relation_03"></span></li>
											<li><strong>Age:</strong> <span id="fam_bg_age_03"></span></li>
											<li><strong>Occupation:</strong> <span id="fam_bg_occupation_03"></span></li>
											<li><strong>Name of Company/School:</strong> <span id="fam_bg_edu_lvl_03"></span></li>
											<li><strong>Education Level:</strong> <span id="fam_bg_edu_lvl_03"></span></li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<li><strong>Name:</strong> <span id="fam_bg_name_04"></span></li>
											<li><strong>Relation:</strong> <span id="fam_bg_relation_04"></span></li>
											<li><strong>Age:</strong> <span id="fam_bg_age_04"></span></li>
											<li><strong>Occupation:</strong> <span id="fam_bg_occupation_04"></span></li>
											<li><strong>Name of Company/School:</strong> <span id="fam_bg_edu_lvl_04"></span></li>
											<li><strong>Education Level:</strong> <span id="fam_bg_edu_lvl_04"></span></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-12">
										<ul>
											<li><strong>Name:</strong> <span id="fam_bg_name_05"></span></li>
											<li><strong>Relation:</strong> <span id="fam_bg_relation_05"></span></li>
											<li><strong>Age:</strong> <span id="fam_bg_age_05"></span></li>
											<li><strong>Occupation:</strong> <span id="fam_bg_occupation_05"></span></li>
											<li><strong>Name of Company/School:</strong> <span id="fam_bg_edu_lvl_05"></span></li>
											<li><strong>Education Level:</strong> <span id="fam_bg_edu_lvl_05"></span></li>
										</ul>
									</div>
								</div>
							</div>
							<?php /* National Service */ ?>
							<div role="tabpanel" class="tab-pane fade" id="tab-m5">
								<div class="row">
									<div class="col-md-12">
										<ul>
											<li><strong>Have you ever served National Service in Singapore?</strong> <span id="serve_ns_sing"></span></li>
											<li><strong>National Service Rank:</strong> <span id="ns_rank"></span></li>
											<li><strong>Vocation:</strong> <span id="ns_vocation"></span></li>
											<li><strong>ORD Month:</strong> <span id="ns_ord_month"></span></li>
											<li><strong>ORD Year:</strong> <span id="ns_ord_year"></span></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-12">
										<ul>
											<li><strong>Reservist Rank:</strong> <span id="reservist_rank"> </span></li>
											<li><strong>Vocation:</strong> <span id="reservist_vocation"></span></li>
											<li><strong>ORD Month:</strong> <span id="reservist_ord_month"></span></li>
											<li><strong>ORD Year:</strong> <span id="reservist_ord_year"></span></li>
										</ul>
									</div>
								</div>
							</div>
							<?php /* Others */ ?>
							<div role="tabpanel" class="tab-pane fade" id="tab-m6">
								<div class="row">
									<div class="col-md-12">
										<strong>RELATION WITH OPTICAL LINE</strong>
										<br><br>
										<ul>
											<li><strong>Why did you choose optical line?</strong> <span id="opt_line_reason"></span></li>
											<li><strong>How much do you know about optical line?</strong> <span id="opt_line_knowledge"></span></li>
											<li><strong>Do you have any friends or relatives own or work in the optical line?</strong> <span id="opt_line_references"></span></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-12">
										<strong>REFERENCES PARTICULARS</strong>
										<br><br>
										<div class="row">
											<div class="col-md-6">
												<ul>
													<li><strong>Name:</strong> <span id="ref_name_01"></span></li>
													<li><strong>Relation:</strong> <span id="ref_relation_01"></span></li>
													<li><strong>Occupation:</strong> <span id="ref_occupation_01"></span></li>
													<li><strong>Contact:</strong> <span id="ref_contact_no_01"></span></li>
												</ul>
											</div>
											<div class="col-md-6">
												<ul>
													<li><strong>Name:</strong> <span id="ref_name_02"></span></li>
													<li><strong>Relation:</strong> <span id="ref_relation_02"></span></li>
													<li><strong>Occupation:</strong> <span id="ref_occupation_02"></span></li>
													<li><strong>Contact:</strong> <span id="ref_contact_no_02"></span></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="line-separator"></div>
									<div class="col-md-12">
										<strong>OTHERS</strong>
										<br><br>
										<ul>
											<li><strong>Special Skills or Licenses:</strong> <span id="skills_licenses"></span></li>
											<li><strong>Hobbies/Interests:</strong> <span id="interest"></span></li>
											<li><strong>Favourite TV Programme:</strong> <span id="fave_tv"></span></li>
											<li><strong>General Health Condition:</strong> <span id="health_condition"></span></li>
											<li><strong>Have you ever been convicted under the court of law?</strong> <span id="crime"></span></li>
											<li><strong>Criminal Offense:</strong> <span id="offense"></span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php /* Contact Information */ ?>
				<div class="modal-body-content" data-source="form_contact">
					<div class="row">
						<div class="col-md-6">
							<p><strong>Name:</strong> <span id="contact_name"></span></p>
						</div>
						<div class="col-md-6">
							<p><strong>Date:</strong> <span id="datetime"></span></p>
						</div>
						<div class="clearfix"></div>
						<div class="line-separator"></div>
						<div class="col-md-12">
							<ul>
								<li><strong>Shop Branch:</strong> <span id="branch"></span></li>
								<li><strong>E-mail:</strong> <span id="contact_email"></span></li>
								<li><strong>Contact Number:</strong> <span id="contact_no"></span></li>
							</ul>
						</div>
						<div class="clearfix"></div>
						<div class="line-separator"></div>
						<div class="col-md-12">
							<strong>Message</strong>
							<p id="message"></p>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>