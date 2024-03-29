<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.

add_action('mep_event_list_shortcode', 'mep_display_event_loop_list', 10, 5);
if (!function_exists('mep_display_event_loop_list')) {
    function mep_display_event_loop_list($event_id, $columnNumber='', $style='',$width='', $unq_id ='')
    {
        $now                    = current_time('Y-m-d H:i:s');
        $show_price             = mep_get_option('mep_event_price_show', 'event_list_setting_sec', 'yes');
        $price_count            = mep_event_list_price($event_id, 'count');
        // event_price_label_single
        $show_price_label       = $price_count == 1 ? mep_get_option('event_price_label_single', 'general_setting_sec', __('Price:','mage-eventpress'))  : mep_get_option('event-price-label', 'general_setting_sec', __('Price Starts from:','mage-eventpress'));
        
        $event_meta             = get_post_custom($event_id);
        $author_terms           = get_the_terms($event_id, 'mep_org') ? get_the_terms($event_id, 'mep_org') : [];
        $time                   = strtotime($event_meta['event_start_date'][0] . ' ' . $event_meta['event_start_time'][0]);
        $newformat              = date_i18n('Y-m-d H:i:s', $time);
        $tt                     = get_the_terms($event_id, 'mep_cat');
        $torg                   = get_the_terms($event_id, 'mep_org');
        $org_class              = mep_get_term_as_class($event_id, 'mep_org',$unq_id);
        $cat_class              = mep_get_term_as_class($event_id, 'mep_cat',$unq_id);
        $event_multidate        = array_key_exists('mep_event_more_date', $event_meta) ? maybe_unserialize($event_meta['mep_event_more_date'][0]) : array();
        $available_seat         = mep_get_total_available_seat($event_id, $event_meta);
        // $available_seat         = 1;
        $hide_org_list          = mep_get_option('mep_event_hide_organizer_list', 'event_list_setting_sec', 'no');
        $hide_location_list     = mep_get_option('mep_event_hide_location_list', 'event_list_setting_sec', 'no');
        $hide_time_list         = mep_get_option('mep_event_hide_time_list', 'event_list_setting_sec', 'no');
        $hide_only_end_time_list = mep_get_option('mep_event_hide_end_time_list', 'event_list_setting_sec', 'no');
        $recurring              = get_post_meta($event_id, 'mep_enable_recurring', true) ? get_post_meta($event_id, 'mep_enable_recurring', true) : 'no';
        $event_type             = get_post_meta(get_the_id(), 'mep_event_type', true) ? get_post_meta(get_the_id(), 'mep_event_type', true) : 'offline';
        ob_start();
        require(mep_template_file_path('list/default.php'));
        do_action('mep_event_list_loop_end', $event_id); ?>
        </div>
        <?php
        $content = ob_get_clean();
        echo apply_filters('mage_event_loop_list_shortcode', $content, $event_id, $style, $unq_id);
    }
}