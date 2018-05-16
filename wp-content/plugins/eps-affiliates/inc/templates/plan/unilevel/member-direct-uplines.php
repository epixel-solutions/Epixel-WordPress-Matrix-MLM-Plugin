<div class="container">
<div class="row">
    <div class="col-lg-12">
    <?php 
      $uplines  = afl_unilevel_get_upline_users(get_uid());

      if (!empty($uplines)) : 
        $uplines = array_reverse($uplines);
        ?>
      <ul class="timeline">
        <?php foreach ( $uplines as $key => $value) { 
            if ($key % 2 == 0)
              $class = '';
            else 
              $class = 'timeline-inverted';
          ?>
           <li class="<?= $class;?>">
            <div class="timeline-image">
              <img class="img-circle img-responsive" src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
            </div>
            <div class="timeline-panel">
              <div class="timeline-heading">
                <h4 class="subheading"><?= $value->user_login; ?></h4>
                <h4 class="">
                    Rank : <?= apply_filters('afl_member_current_rank_name',$value->uid); ?>
                </h4>
              </div>
              <div class="timeline-body">
                <p class="text-muted">
                  Genealogy : View
                </p>
                <p class="text-muted">
                  Joined On : <?= afl_system_date_format($value->created, TRUE);?>
                </p>
              </div>
            </div>
            <?php if ( !empty($uplines[$key+1]) ) :  ?>
            <div class="line"></div>
          <?php endif; ?>
          </li>
        <?php } ?>
      </ul>
    <?php endif; ?>
    </div>
  </div>
</div>