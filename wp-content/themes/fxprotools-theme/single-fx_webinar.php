<?php 

get_header();

?>

<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="fx-header-title">
            <h1>Coaching / Webinars</h1>
            <p>Check Below For Your Coaching Webinars</p>
         </div>
      </div>
      <div class="col-md-12">
         <div class="fx-coaching-tab">
            <a href="https://copyprofitsuccess.com/product/1-on-1-coaching/" class="btn btn-danger no-border-radius pull-right">Schedule Private Coaching</a>
            <div role="tabpanel">
               <ul class="nav nav-tabs" id="coachingTabs" role="tablist">
                  <li role="presentation" class="active"> <a href="#upcoming" aria-controls="upcoming" role="tab" data-toggle="tab" aria-expanded="true">Upcoming Sessions</a></li>
                  <li role="presentation" class=""> <a href="#past" aria-controls="past" role="tab" data-toggle="tab" aria-expanded="false">Past Sessions</a></li>
               </ul>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane padding-md active" id="upcoming">
                     <div class="webinar-content" style="background-color: #FFF; padding: 30px;">
                        <?php 
                           if(is_single( '48127' )) :
                        ?>
                           <div class="webinar-summary">
                              <h4 style="margin-top:0;">Webinar Title:</h4>
                              <h1 style="margin-bottom: 30px; margin-top: 10px;">Free Weekly Q&amp;A</h1>

                              <h4>Description</h4>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                           </div>
                           <div class="webinar-details">
                              <div class="webinar-details-block">
                                 <h4>Webinar Date:</h4>
                                 <h5>2018-02-08</h5>
                              </div>
                              <div class="webinar-details-block">
                                 <h4>Start Time:</h4>
                                 <h5>17:00</h5>
                              </div>
                              <div class="webinar-details-block">
                                 <h4>End Time:</h4>
                                 <h5>18:00</h5>
                              </div>
                           </div>
                           <div class="webinar-button">
                              <a href="http://google.com" class="btn btn-danger btn-lg btn-lg-w-text scroll-to">Registration Link for Webinar</a>
                           </div>

                        <?php 
                           else :
                        ?>
                           <div class="webinar-summary">
                              <h4 style="margin-top:0;">Webinar Title:</h4>
                              <h1 style="margin-bottom: 30px; margin-top: 10px;">Free Weekly Q&amp;A</h1>

                              <h4>Description</h4>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                           </div>
                           <div class="webinar-details">
                              <div class="webinar-details-block">
                                 <h4>Webinar Date:</h4>
                                 <h5>2018-02-08</h5>
                              </div>
                              <div class="webinar-details-block">
                                 <h4>Start Time:</h4>
                                 <h5>16:00</h5>
                              </div>
                              <div class="webinar-details-block">
                                 <h4>End Time:</h4>
                                 <h5>17:00</h5>
                              </div>
                           </div>
                           <div class="webinar-button">
                              <a href="http://google.com" class="btn btn-danger btn-lg btn-lg-w-text scroll-to">Watch the Webinar Replay Below</a>
                           </div>
                           <div class="webinar-video" style="margin-top: 20px;">
                              <img style="max-width: 100%;" src="/wp-content/themes/fxprotools-theme/assets/img/video-placeholder.png" />
                           </div>

                        <?php 
                           endif;
                        ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php 
get_footer(); 
?>
