<div class="bg-light lter b-b wrapper-md">
  <div class="row">
    <div class="col-sm-6 col-xs-12">
      <h1 class="m-n h3"><?= get_admin_page_title(); ?></h1>
    </div>
    <div class="col-sm-6 text-right hidden-xs">
      <div class="region region-title-extra">
        
        <?php if( eps_is_admin()): ?>
          
        <div id="block-afl-widgets-afl-bwallet-income" class="block block-afl-widgets contextual-links-region clearfix">
        </div> 

  	    <div id="block-afl-widgets-afl-bwallet-expenses" class="block block-afl-widgets contextual-links-region clearfix">
        </div> <!-- /.block -->

    	  <div id="block-afl-widgets-afl-bwallet-balance" class="block block-afl-widgets contextual-links-region clearfix">
        </div>

        <?php endif;?>
        
        <div id="block-afl-widgets-afl-member-rank" class="block block-afl-widgets contextual-links-region clearfix">
        </div>

      </div>
    </div>
  </div>
</div>