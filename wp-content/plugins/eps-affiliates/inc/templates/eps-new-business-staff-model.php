<div id="add_new_member" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New business Staff</h4>
      </div>
      <div class="modal-body">
      <?php 
      	$form = array();
			 	$form['#action'] = $_SERVER['REQUEST_URI'];
			 	$form['#method'] = 'post';
			 	$form['#prefix'] ='<div class="form-group row">';
			 	$form['#suffix'] ='</div>';

			 	$form['first_name'] = array(
			 		'#title' => 'First Name',
			 		'#type' => 'text',
			 		'#name' => 'first name',
			 		'#attributes' => array(
			 			'class' => array(

			 			)
			 		),
			 		'#default_value' => isset($post['first_name']) ? $post['first_name'] : '',
			 		'#prefix'=>'<div class="form-group row">',
			 		'#suffix' =>'</div>'
			 	);
				$form['sur_name'] = array(
			 		'#title' => 'Sur Name',
			 		'#type' => 'text',
			 		'#name' => 'Sur name',
			 		'#attributes' => array(
			 			'class' => array(

			 			)
			 		),
			 		'#default_value' => isset($post['sur_name']) ? $post['sur_name'] : '',
			 		'#prefix'=>'<div class="form-group row">',
			 		'#suffix' =>'</div>'
			 	);
			 	$form['user_name'] = array(
			 		'#title' => 'User Name',
			 		'#type' => 'text',
			 		'#name' => 'User name',
			 		'#attributes' => array(
			 			'class' => array(

			 			)
			 		),
			 		'#default_value' => isset($post['user_name']) ? $post['user_name'] : '',
			 		'#prefix'=>'<div class="form-group row">',
			 		'#suffix' =>'</div>'
			 	);
			 	$form['email'] = array(
			 		'#title' => 'Email address',
			 		'#type' => 'text',
			 		'#name' => 'Email address',
			 		'#attributes' => array(
			 			'class' => array(

			 			)
			 		),
			 		'#default_value' => isset($post['email']) ? $post['email'] : '',
			 		'#prefix'=>'<div class="form-group row">',
			 		'#suffix' =>'</div>'
			 	);
			 	$form['password'] = array(
			 		'#title' => 'Password',
			 		'#type' => 'password',
			 		'#name' => 'Password',
			 		'#attributes' => array(
			 			'class' => array(

			 			)
			 		),
			 		'#prefix'=>'<div class="form-group row">',
			 		'#suffix' =>'</div>'
			 	);
			 	$form['confirm_password'] = array(
			 		'#title' => 'Confirm Password',
			 		'#type' => 'password',
			 		'#name' => 'Confirm Password',
			 		'#attributes' => array(
			 			'class' => array(

			 			)
			 		),
			 		'#prefix'=>'<div class="form-group row">',
			 		'#suffix' =>'</div>'
			 	);
			 	
			 	$form['mobile'] = array(
			 		'#title' => 'Mobile number',
			 		'#type' => 'text',
			 		'#name' => 'Mobile number',
			 		'#attributes' => array(
			 			'class' => array(

			 			)
			 		),
			 		'#prefix'=>'<div class="form-group row">',
			 		'#suffix' =>'</div>'
			 	);
			 		$form['submit'] = array(
				 		'#title' => 'Submit',
				 		'#type' => 'submit',
				 		'#value' => 'Submit',
				 		'#attributes' => array(
				 			'class' => array(
				 				'btn','btn-primary'
				 			)
				 		),
				 		'#prefix'=>'<div class="form-group row">',
				 		'#suffix' =>'</div>'
				 	);
			 	echo afl_render_form($form);
     	?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default">Submit</button>
      </div>
    </div>

  </div>
</div>