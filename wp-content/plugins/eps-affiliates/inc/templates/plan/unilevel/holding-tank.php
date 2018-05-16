<?php 
    /* get all the downlines of this user */
    $uid = get_current_user_id();
    if (current_user_can('administrator')) {
      $uid = afl_root_user();
    }

    if (!empty($_GET['uid'])) {
      $uid = $_GET['uid'];
    }

    $query = array();
    $query['#select'] = _table_name('afl_unilevel_user_holding_tank');
    $query['#join']  = array(
      _table_name('users') => array(
        '#condition' => '`'._table_name('users').'`.`ID`=`'._table_name('afl_unilevel_user_holding_tank').'`.`uid`'
      ),
    );
    $query['#fields']  = array(
      _table_name('users') => array(
        'display_name'
      ),
      _table_name('afl_unilevel_user_holding_tank') => array(
        'parent_uid','uid','created','day_remains','remote_sponsor_mlmid'
      )
    );
   	$query['#where'] = array(
      '`'._table_name('afl_unilevel_user_holding_tank').'`.`referrer_uid`='.$uid.'',
    );
   	$query['#order_by'] = array(
      '`level`' => 'ASC',
      // '`uid`'   => 'ASC'
    );
    
    $tank_users = db_select($query, 'get_results');
    $count = count($tank_users);
    
    $default_img = EPSAFFILIATE_PLUGIN_ASSETS.'/images/avathar.png';

if ( !function_exists('_check_remote_mlmid_exist')) {
  require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/api-remote-user-embedd-cron-callback.php';
}

if ( $tank_users ) : ?>
  <div class="panel panel-default">
    <div class="panel-body text-center">
      <b>Holding Users : <?php echo $count; ?></b>
    </div>
  </div>
	<section class="holding-tank-warpper">
		<div class="holding-tank-wrapper">
			<div class="holding-tank-profiles">
				<ul class="row">
					<?php foreach ($tank_users as $key => $value) : ?>
							<li class="col-md-2 col-sm-3" data-user-id = "<?=$value->uid;?>">
					      <div class="person">
	                <img src="<?php print $default_img ?>" alt="">
		              <p class="name"><?= $value->display_name; ?></p>
                  <span class=""><?= $value->day_remains;?> Day remains</span>
		              
                  <p style="color:red;">
                    <?php 
                      if($sp_id = _check_remote_mlmid_exist($value->remote_sponsor_mlmid)) 
                        echo 'Sponsor Available : '.$sp_id; 
                    ?>
                  </p>

	              </div>
					    </li>
					<?php endforeach; ?>
				    
				</ul>
			 </div>
		</div>
	</section>
	
	<div class="modal fade" id="holding-tank-change-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User Placement</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" name="" id="current-user-id" value="<?= $uid;?>">
        <input type="hidden" name="" id="seleted-user-id" value="">
        <input type="hidden" name="" id="tree-mode" value="unilevel">

  			<div class="form-group row">
  				<label for="choose-parent" class="form-label">Choose Parent</label>
  				<!-- <input name="choose_parent" id="choose-parent" data-path="member_downlines_autocomplete" class="auto_complete form-control " value="" type="text"> -->
          <select class="form-control " id="choose-parent">
            <?php echo _get_member_downline_users_as_option('unilevel'); ?>
          </select>
  			</div>
        
        <div class="form-group row" id="available-free-spaces">
        </div>
  			

        <div class="progress-outer"><div class="progress"></div></div>
        
        <div class="form-group row">
          <span class="notification"></span>
        </div>
      </div>
      <div class="modal-footer">
        <div class="form-group row">
          <p class="pull-left"><b>Auto Place :</b>Automatically place user in the available position of current user or the selected user</p>
          <p class="pull-left"><b>Place User :</b>You can choose the position of selected user</p>
        </div>
        <button type="button" class="btn btn-primary pull-left" id="auto-place-user">Auto place</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="place-user">Place user</button>
      </div>
    </div>
  </div>
</div>

<?php  else : ?>
	<div class="panel panel-default">
		<div class="panel-body">
			No users currently in your Holding Tank
		</div>
	</div>

<?php endif; ?>
