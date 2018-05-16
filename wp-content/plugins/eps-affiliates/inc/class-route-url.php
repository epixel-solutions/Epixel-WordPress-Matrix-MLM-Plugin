<?php
/*
 * --------------------------------------------------------
 * All the routes 
 * --------------------------------------------------------
*/
  class Afl_route_url {
    public function __construct(){
      add_action( 'init', array($this,'add_eps_afl_dashboard_menu_rules'));
    }
    /*
     * --------------------------------------------------------
     * Add rules for afl-eps dashboard menus
     * --------------------------------------------------------
    */
    public function add_eps_afl_dashboard_menu_rules() {
     // add_rewrite_rule('^(network)/([^/]*)/?', 'index.php?name=$matches[1]','top');
     // flush_rewrite_rules();
    }
  }

  $obj = new Afl_route_url;



