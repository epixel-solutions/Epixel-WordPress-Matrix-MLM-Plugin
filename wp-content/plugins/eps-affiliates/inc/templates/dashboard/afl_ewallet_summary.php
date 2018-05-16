
<div class=" overview panel panel-white">
  <div class="panel-heading clearfix">
    <h4 class="font-thin-bold m-t-none m-b text-primary-lt pull-left"><?php print __('E-Wallet Summary', 'affiliates-eps'); ?></h4>
  </div>
  <div class="panel-body">
    <ul class="overview-inner clearfix">
    <?php
    if(empty($ewallet_summary)){
      echo '<li class="clearfix text-primary">'.__("Earning not showing or not start yet!!! ").'</li>';
    }
    else{
      $i=0;
      $classes = array('btn-info', 'btn-primary', 'btn-success', 'btn-info', 'btn-danger');
      foreach ($ewallet_summary as $value) {

        $class = $classes[$i%5];
        $category = isset($variable_list[$value->category]) ? $variable_list[$value->category] : $value->category;
        $category = urldecode($value->category);
        //
        ?>
          <li class="clearfix">
            <h4>
              <span class="overview-bg-icon">
                <i class="fa fa-money" aria-hidden="true"></i>
              </span>
              <?php echo __(ucwords(strtolower($category))); ?>
            </h4>
            <div class="overview-btn">

                <?php
                //$symbol = afl_get_multi_currency_symbol();

                if(afl_variable_get('afl_multi_currency_module', FALSE) ){
                $symbol = afl_get_multi_currency_symbol();
                  $val = afl_multi_currency_rate_conversion(abs($value->Amount),TRUE);
                }else{
                  $val = afl_format_payment_amount(abs($value->Amount) );
                  $symbol = afl_currency();
                  }
                ?>
              <?php
               $button = '<button class="btn btn-rounded btn-sm '. $class .'">'.$symbol.' '.$val.'</button>';
              print __($button, 'afl/ewallet-transactions', array('query' => array('category' => $category), 'html' => TRUE));
               //print $button; ?>
              </div>
          </li>
        <?php $i++; } }?>

      </ul>
    </div>


</div>
