<?php
if (!current_user_can('administrator')) {
    die();
}

function page_content() {
    
    ?>
    <script>
    </script>
    <div id="compose">
        <h2>Compose New Email</h2>
        <form name="compose-email" class="compose-email clearfix" method="post">
        	<div class="form-group">
        	   <label for="email_recipient_type">Recipient Type</label>
        	   <select id="email_recipient_type" name="email_recipient_type" class="form-control">
        	      <option value="all">All Users</option>
        	      <option value="group">Group</option>
        	      <option value="product">Product</option>
        	      <option value="individual">Individual</option>
        	   </select>
        	</div>
        	<div class="form-group">
        	   <label for="recipient_group">Group Type</label>
        	   <select id="recipient_group" name="recipient_group" class="form-control">
        	      <option value="customer">Customers</option>
        	      <option value="distributor">Distributors</option>
        	      <option value="both">Both</option>
        	   </select>
        	</div>
        	<div class="form-group">
        	   <label for="recipient_product">Product</label>
        	   <div style="width: 100%;">
        		   <select id="recipient_product" name="recipient_product" style="width: 100%;">
		                <?php
                        foreach (get_posts(array('posts_per_page' => -1, 'post_type' => 'product')) as $product) {
                        ?>
                            <option value="<?php echo $product->ID; ?>"><?php echo esc_html($product->post_title); ?></option>
                        <?php
                        }
                		?>
        		   </select>
        	   </div>
        	</div>
        	<div class="form-group">
        	   <label for="recipient_individual_type">Individual Type</label>
        	   <select id="recipient_individual_type" name="recipient_individual_type" class="form-control">
        	      <option value="email">Specified Email</option>
        	      <option value="user">User</option>
        	   </select>
        	</div>
        	<div class="form-group">
        	   <label for="recipient_individual_name">Individual Name</label>
        	   <input id="recipient_individual_name" name="recipient_individual_name" class="form-control" />
        	</div>
        	<div class="form-group">
        	   <label for="recipient_individual_email">Individual Email</label>
        	   <input id="recipient_individual_email" name="recipient_individual_email" class="form-control" />
        	</div>
        	<div class="form-group">
        	   <label for="recipient_individual_user">Individual User</label>
        	   <div style="width: 100%;">
        		   <select id="recipient_individual_user" name="recipient_individual_user" style="width: 100%;">
        		        <?php
                        foreach (get_users() as $user) {
	           		    ?>
    		                <option value="<?php echo $user->ID; ?>"><?php echo esc_html($user->display_name); ?></option>
	           		    <?php
	           		    }
    		            ?>
        		   </select>
        	   </div>
        	</div>
        	<div class="form-group">
        		<label for="subject">Subject</label>
        		<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
        	</div>
        	<div class="form-group">
        		<label for="lastName">Body</label>
        		<textarea type="text" class="form-control" name="body" id="body"></textarea>
        	</div>
        	<button type="button" class="btn btn-default pull-right">Send</button>
        </form>
    </div>
    <?php
}

include(__DIR__ . '/inc/templates/email.php');