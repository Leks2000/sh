<?php
/**
 * Plugin Name: Event Manager and Tickets Selling Plugin for WooCommerce
 * Plugin URI: http://mage-people.com
 * Description: A Complete Event Solution for WordPress by MagePeople..
 * Version: 3.6.6
 * Author: MagePeople Team
 * Author URI: http://www.mage-people.com/
 * Text Domain: mage-eventpress
 * Domain Path: /languages/
 * WC requires at least: 3.0.9
 * WC tested up to: 6.5
 */

if (!defined('ABSPATH')) {
  die;
} // Cannot access pages directly.

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('woocommerce/woocommerce.php')) {
  function appsero_init_tracker_mage_eventpress()
  {
    if (!class_exists('Appsero\Client')) {
      require_once __DIR__ . '/lib/appsero/src/Client.php';
    }
    $client = new Appsero\Client('08cd627c-4ed9-49cf-a9b5-1536ec384a5a', 'Event Manager For Woocommerce ', __FILE__);
    $client->insights()->init();
  }

  function mep_event_activation_redirect($plugin)
  {
    if ($plugin == plugin_basename(__FILE__)) {
      exit(wp_redirect(admin_url('edit.php?post_type=mep_events&page=mep_event_welcome_page')));
    }
  }
  add_action('activated_plugin', 'mep_event_activation_redirect');
  require_once(dirname(__FILE__) . "/inc/mep_file_include.php");

    // Get Plugin Data
    if(!function_exists('mep_get_plugin_data')) {
      function mep_get_plugin_data($data) {
        $get_mep_plugin_data = get_plugin_data( __FILE__ );
        $mep_data = $get_mep_plugin_data[$data];
        return $mep_data;
      }
    }
  
  // Added Settings link to plugin action links
  add_filter( 'plugin_action_links', 'mep_plugin_action_link', 10, 2 );
  
  function mep_plugin_action_link( $links_array, $plugin_file_name ){
  
    if( strpos( $plugin_file_name, basename(__FILE__) ) ) {
  
      array_unshift( $links_array, '<a href="'.esc_url(admin_url()).'edit.php?post_type=mep_events&page=mep_event_settings_page">'.__('Settings','mage-eventpress').'</a>');
    }
    
    return $links_array;
  }
  
  // Added links to plugin row meta
  add_filter( 'plugin_row_meta', 'mep_plugin_row_meta', 10, 2 );
   
  function mep_plugin_row_meta( $links_array, $plugin_file_name ) {
  
      if( strpos( $plugin_file_name, basename(__FILE__) ) ) {
  
          if(!is_plugin_active('woocommerce-event-manager-pdf-ticket/tickets.php') || !is_plugin_active('woocommerce-event-manager-addon-form-builder/addon-builder.php')){
              $wbbm_links = array(
                  'docs'    => '<a href="'.esc_url("https://docs.mage-people.com/woocommerce-event-manager/").'" target="_blank">'.__('Docs','mage-eventpress').'</a>',
                  'support' => '<a href="'.esc_url("https://mage-people.com/my-account").'" target="_blank">'.__('Support','mage-eventpress').'</a>',
                  'get_pro' => '<a href="'.esc_url("https://mage-people.com/product/mage-woo-event-booking-manager-pro/").'" target="_blank" class="mep_plugin_pro_meta_link">'.__('Upgrade to PRO Version','mage-eventpress').'</a>'             
                  );            
          }else{
              $wbbm_links = array(
                  'docs'    => '<a href="'.esc_url("https://docs.mage-people.com/woocommerce-event-manager/").'" target="_blank">'.__('Docs','mage-eventpress').'</a>',
                  'support' => '<a href="'.esc_url("https://mage-people.com/my-account").'" target="_blank">'.__('Support','mage-eventpress').'</a>'            
                  );            
          }        
          $links_array = array_merge( $links_array, $wbbm_links );
      }
       
      return $links_array;
  }

} else {

  require_once(dirname(__FILE__) . "/lib/classes/class-mep-required-plugins.php");
  
}