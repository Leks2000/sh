<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.

/**
 * MagePeople Settings API
 * @version 1.0
 *
 */
if (!class_exists('MAGE_Events_Setting_Controls')) :
    class MAGE_Events_Setting_Controls {

        private $settings_api;

        function __construct() {
            $this->settings_api = new MAGE_Setting_API;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function admin_init() {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
        }

        function admin_menu() {
            $event_label = mep_get_option('mep_event_label', 'general_setting_sec', 'Events');
            //add_options_page( 'Event Settings', 'Event Settings', 'delete_posts', 'mep_event_settings_page', array($this, 'plugin_page') );

            add_submenu_page('edit.php?post_type=mep_events', __($event_label . ' Settings', 'mage-eventpress'), __($event_label . ' Settings', 'mage-eventpress'), 'manage_options', 'mep_event_settings_page', array($this, 'plugin_page'));
        }

        function get_settings_sections() {

            $sections = array(
                array(
                    'id' => 'general_setting_sec',
                    'title' => '<i class="fas fa-cogs"></i>'.__('General Settings', 'mage-eventpress')
                ),
                array(
                    'id' => 'event_list_setting_sec',
                    'title' => '<i class="far fa-calendar-alt"></i>'.__('Event List Settings', 'mage-eventpress')
                ),
                array(
                    'id' => 'single_event_setting_sec',
                    'title' => '<i class="far fa-calendar-check"></i>'.__('Single Event Settings', 'mage-eventpress')
                ),                               
                array(
                    'id' => 'email_setting_sec',
                    'title' => '<i class="fas fa-envelope"></i>'.__('Email Settings', 'mage-eventpress')
                ),
                array(
                    'id' => 'style_setting_sec',
                    'title' => '<i class="fas fa-palette"></i>'.__('Style Settings', 'mage-eventpress')
                ),
                array(
                    'id' => 'icon_setting_sec',
                    'title' => '<i class="fab fa-font-awesome"></i>'.__('Icon Settings', 'mage-eventpress')
                ),                
                array(
                    'id' => 'label_setting_sec',
                    'title' => '<i class="fas fa-language"></i>'.__('Translation Settings', 'mage-eventpress')
                ),
                array(
                    'id' => 'carousel_setting_sec',
                    'title' => '<i class="fas fa-sliders-h"></i>'.__('Carousel Settings', 'mage-eventpress')
                )
            );


            return apply_filters('mep_settings_sec_reg', $sections);
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields() {
            $settings_fields = array(
                'general_setting_sec' => apply_filters('mep_settings_general_arr', array(
                        array(
                            'name' => 'mep_disable_block_editor',
                            'label' => __('On/Off Block/Gutenberg Editor', 'mage-eventpress'),
                            'desc' => __('Enable/Disable gutenburg editor.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'yes',
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_multi_lang_plugin',
                            'label' => __('Choose Multilingual Plugin', 'mage-eventpress'),
                            'desc' => __('If you are using a multilingual plugin, Please select the plugin name from the list.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'none',
                            'options' => array(
                                'none' => 'None',
                                'polylang' => 'Polylang',
                                'wpml' => 'WPML'
                            )
                        ),
                        array(
                            'name' => 'mep_event_label',
                            'label' => __('Event Label', 'mage-eventpress'),
                            'desc' => __('It will change the event post type label on the entire plugin.', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'Events'
                        ),

                        array(
                            'name' => 'mep_event_slug',
                            'label' => __('Event Slug', 'mage-eventpress'),
                            'desc' => __('It will change the event slug on the entire plugin. Remember after changing this slug you need to flush permalinks. Just go to <strong>Settings->Permalinks</strong> hit the Save Settings button', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'events'
                        ),

                        array(
                            'name' => 'mep_event_icon',
                            'label' => __('Event Icon', 'mage-eventpress'),
                            'desc' => __('Please enter the icon class name for the event custom post type. Example: dashicons-calendar-alt. Find Icons: <a href="https://developer.wordpress.org/resource/dashicons/">Dashicons</a>', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'dashicons-calendar-alt'
                        ),


                        array(
                            'name' => 'mep_event_cat_label',
                            'label' => __('Event Category Label', 'mage-eventpress'),
                            'desc' => __('It will change the event category label on the entire plugin.', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'Category'
                        ),
                        array(
                            'name' => 'mep_event_cat_slug',
                            'label' => __('Event Category Slug', 'mage-eventpress'),
                            'desc' => __('It will change the category slug on the entire plugin. Remember after changing this slug you need to flush permalinks. Just go to <strong>Settings->Permalinks</strong> hit the Save Settings button.', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'mep_cat'
                        ),
                        array(
                            'name' => 'mep_event_org_label',
                            'label' => __('Event Organizer Label', 'mage-eventpress'),
                            'desc' => __('It will change the event organizer label on the entire plugin.', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'Organizer'
                        ),
                        array(
                            'name' => 'mep_event_org_slug',
                            'label' => __('Event Organizer Slug', 'mage-eventpress'),
                            'desc' => __('It will change the event organizer slug on the entire plugin. Remember after changing this slug you need to flush permalinks. Just go to <strong>Settings->Permalinks</strong> hit the Save Settings button.', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'mep_org'
                        ),


                        array(
                            'name' => 'mep_google_map_type',
                            'label' => __('Google Map Type', 'mage-eventpress'),
                            'desc' => __('Please select the map type you want to show on the front page.<br><strong>Note:</strong> Iframe does Not always show the accurate location where API enabled map has a drag and drop feature. So you can drag the point to the accurate location.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'yes',
                            'options' => array(
                                '' => 'Please Select a Map Type',
                                'api' => 'API',
                                'iframe' => 'Iframe'
                            )
                        ),
                        array(
                            'name' => 'google-map-api',
                            'label' => __('Google Map API Key', 'mage-eventpress'),
                            'desc' => __('Enter Your Google Map API key. <a href=https://developers.google.com/maps/documentation/javascript/get-api-key target=_blank>Get API Key</a>. <br><strong>Note:</strong> You must enter your billing address and information into the Google Maps API account to make it perfectly workable on your website.', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => ''
                        ),
                        array(
                            'name' => 'mep_event_time_format',
                            'label' => __('Event Time Format', 'mage-eventpress'),
                            'desc' => __('Please select what format time you want to display in the event front-end.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'wtss',
                            'options' => array(
                                'wtss' => 'WordPress TimeStamp Settings'
                            )
                        ),
                        array(
                            'name' => 'mep_event_expire_on_datetimes',
                            'label' => __('When will the event expire', 'mage-eventpress'),
                            'desc' => __('Please select when the event will expire', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'mep_event_start_date',
                            'options' => array(
                                'event_start_datetime' => 'Event Start Time',
                                'event_expire_datetime' => 'Event End Time'
                            )
                        ),
                        array(
                            'name' => 'mep_hide_location_from_order_page',
                            'label' => __('Hide Location From Order Details & Email Section', 'mage-eventpress'),
                            'desc' => __('It shows/hides location from the order details section on the thank you page and the confirmation email body. Please choose \'Yes\' to hide or \'No\' to show. By default is \'No\'.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'no',
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_hide_date_from_order_page',
                            'label' => __('Hide Date From Order Details & Email Section', 'mage-eventpress'),
                            'desc' => __('It shows/hides date from the order details section on the thank you page and the confirmation email body. Please choose \'Yes\' to hide or \'No\' to show. By default is \'No\'.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'no',
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_direct_checkout',
                            'label' => __('Redirect Checkout after Booking', 'mage-eventpress'),
                            'desc' => __('It enables/disables redirecting the checkout page after booking an event.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'yes',
                            'options' => array(
                                'yes' => 'Enable',
                                'no' => 'Disable'
                            )
                        ), 
                        array(
                            'name' => 'mep_show_zero_as_free',
                            'label' => __('Show 0 Price as Free', 'mage-eventpress'),
                            'desc' => __('It enables 0 price as a free ticket. By default, it is enabled.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'yes',
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_ticket_expire_time',
                            'label' => __('Event Ticket Expire before minutes', 'mage-eventpress'),
                            'desc' => __('Please enter the minute that you want the attendee can not book/register the ticket before the start of the event.', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => '0',
                            'placeholder' => '15'
                        ),                                               
                    )
                ),
                'event_list_setting_sec' => apply_filters('mep_settings_event_list_arr', array(
                        array(
                            'name' => 'mep_event_price_show',
                            'label' => __('On/Off Event Price in List', 'mage-eventpress'),
                            'desc' => __('It enables/disables the event price in the list. By default, it is enabled.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_price_show', 'general_setting_sec', 'event_list_setting_sec', 'yes'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_date_list_in_event_listing',
                            'label' => __('On/Off Multi Date List in Event listing Page', 'mage-eventpress'),
                            'desc' => __('It enables/disables the full date list for multi date event in the event listing page. By default, it is enabled.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_date_list_in_event_listing', 'general_setting_sec', 'event_list_setting_sec', 'yes'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_organizer_list',
                            'label' => __('Hide Organizer Section from list', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_organizer_list', 'general_setting_sec', 'event_list_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_location_list',
                            'label' => __('Hide Location Section from list', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_location_list', 'general_setting_sec', 'event_list_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_time_list',
                            'label' => __('Hide Full Time Section from list', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_time_list', 'general_setting_sec', 'event_list_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_end_time_list',
                            'label' => __('Hide Only End Time Section from list', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_end_time_list', 'general_setting_sec', 'event_list_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_hide_event_hover_btn',
                            'label' => __('Hide/Show Event Hover Book Now Button', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show. By default is \'No\'.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_hide_event_hover_btn', 'general_setting_sec', 'event_list_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                    )
                ),
                'single_event_setting_sec' => apply_filters('mep_settings_single_event_arr', array(
                        array(
                            'name' => 'mep_enable_speaker_list',
                            'label' => __('On/Off Speaker List', 'mage-eventpress'),
                            'desc' => __('To show the speaker list, please select \'Yes\'. By default, it is disabled.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_enable_speaker_list', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_show_product_cat_in_event',
                            'label' => __('On/Off Product Category in Event', 'mage-eventpress'),
                            'desc' => __('It enables the product category on the event edit page. If you want to set a product category-based coupon code, you have to assign the event to the product category. To enable this feature, please select \'Yes\'.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_show_product_cat_in_event', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_global_single_template',
                            'label' => __('Single Event Page Template', 'mage-eventpress'),
                            'desc' => __('It changes the single event details page template.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_global_single_template', 'general_setting_sec', 'single_event_setting_sec', 'default-theme.php'),
                            'options' => mep_event_template_name()
                        ),
                        array(
                            'name' => 'mep_event_product_type',
                            'label' => __('On/Off Shipping Method on event', 'mage-eventpress'),
                            'desc' => __('Please select The event product type which is used in WooCommerce, By default it is virtual. If you change this type you need to re-save all the events again.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_product_type', 'general_setting_sec', 'single_event_setting_sec', 'yes'),
                            'options' => array(
                                'yes' => 'No',
                                'no' => 'Yes'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_date_from_details',
                            'label' => __('Hide Event Date Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_date_from_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_time_from_details',
                            'label' => __('Hide Event Time Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_time_from_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_location_from_details',
                            'label' => __('Hide Event Location Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_location_from_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_total_seat_from_details',
                            'label' => __('Hide Event Total Seats Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_total_seat_from_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_org_from_details',
                            'label' => __('Hide "Org By" Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_org_from_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_address_from_details',
                            'label' => __('Hide Event Address Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_address_from_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_event_schedule_details',
                            'label' => __('Hide Event Schedule Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_event_schedule_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_share_this_details',
                            'label' => __('Hide Event Share this Section from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_share_this_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_event_hide_calendar_details',
                            'label' => __('Hide Add Calendar Button from Single Event Details page', 'mage-eventpress'),
                            'desc' => __('Please choose \'Yes\' to hide or \'No\' to show.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => mep_change_global_option_section('mep_event_hide_calendar_details', 'general_setting_sec', 'single_event_setting_sec', 'no'),
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),
                    )
                ),
                'email_setting_sec' => apply_filters('mep_settings_email_arr', array(
                        array(
                            'name' => 'mep_email_sending_order_status',
                            'label' => __('Email Sent on order status', 'mage-eventpress'),
                            'desc' => __('Please select when and which order status event confirmation email will send to the customer.', 'mage-eventpress'),
                            'type' => 'multicheck',
                            'default' => array('completed' => 'completed'),
                            'options' => array(
                                'processing' => 'Processing',
                                'completed' => 'Completed'
                            )
                        ),

                        array(
                            'name' => 'mep_email_form_name',
                            'label' => __('Email From Name', 'mage-eventpress'),
                            'desc' => __('Email From Name', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => get_bloginfo( 'name' )
                        ),
                        array(
                            'name' => 'mep_email_form_email',
                            'label' => __('From Email', 'mage-eventpress'),
                            'desc' => __('From Email', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => get_option('admin_email')
                        ),
                        array(
                            'name' => 'mep_email_subject',
                            'label' => __('Email Subject', 'mage-eventpress'),
                            'desc' => __('Email Subject', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => 'Event Notification'
                        ),
                        array(
                            'name' => 'mep_confirmation_email_text',
                            'label' => __('Confirmation Email Text', 'mage-eventpress'),
                            'desc' => __('Confirmation Email Text <b>Usable Dynamic tags:</b><br/> Attendee
                                        Name:<b>{name}</b><br/>
                                        Event Name: <b>{event}</b><br/>
                                        Ticket Type: <b>{ticket_type}</b><br/>
                                        Event Date: <b>{event_date}</b><br/>
                                        Start Time: <b>{event_time}</b><br/>
                                        Full DateTime: <b>{event_datetime}</b>', 'mage-eventpress'),
                            'type' => 'wysiwyg',
                            'default' => 'Hi {name},<br><br>Thanks for joining the event.<br><br>Here are the event details:<br><br>Event Name: {event}<br><br>Ticket Type: {ticket_type}<br><br>Event Date: {event_date}<br><br>Start Time: {event_time}<br><br>Full DateTime: {event_datetime}<br><br>Thanks',
                        ),
                    )
                ),

                'label_setting_sec' => apply_filters('mep_translation_string_arr', array(
                    array(
                        'name' => 'mep_hide_event_hover_btn_text',
                        'label' => __('Book Now', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Book Now.</strong>', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Book Now',
                        'placeholder' => 'Book Now'
                    ),
                    array(
                        'name' => 'event-price-label',
                        'label' => __('Price Starts from:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Price Starts from:</strong>', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Price Starts from:'
                    ),
                    array(
                        'name' => 'event_price_label_single',
                        'label' => __('Price:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Price:</strong>', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Price:'
                    ),                    
                    array(
                        'name' => 'mep_free_price_text',
                        'label' => __('Free', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Free</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Free',
                        'placeholder' => 'Free'
                    ),
                    array(
                        'name' => 'mep_event_ticket_type_text',
                        'label' => __('Ticket Type:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Ticket Type:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Ticket Type:'
                    ),
                    array(
                        'name' => 'mep_event_extra_service_text',
                        'label' => __('Extra Service:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Extra Service:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Extra Service:'
                    ),
                    array(
                        'name' => 'mep_cart_btn_text',
                        'label' => __('Register This Event', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Register This Event</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Register This Event'
                    ),
                    array(
                        'name' => 'mep_calender_btn_text',
                        'label' => __('Add To Your Calender', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Add To Your Calender</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'ADD TO YOUR CALENDAR'
                    ),
                    array(
                        'name' => 'mep_share_text',
                        'label' => __('Share This Event', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Share This Event</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Share This Event'
                    ),
                    array(
                        'name' => 'mep_organized_by_text',
                        'label' => __('Organized By:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Organized By:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_location_text',
                        'label' => __('Location:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Location:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_time_text',
                        'label' => __('Time:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Time:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_event_location_text',
                        'label' => __('Event Location:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Event Location:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_event_date_text',
                        'label' => __('Event Date:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Event Date:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_event_time_text',
                        'label' => __('Event Time:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Event Time:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),

                    array(
                        'name' => 'mep_by_text',
                        'label' => __('By:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>By:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_total_seat_text',
                        'label' => __('Total Seats:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Total Seats:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_register_now_text',
                        'label' => __('Register Now:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Register Now:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_quantity_text',
                        'label' => __('Quantity:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Quantity:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_name_text',
                        'label' => __('Name:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Name:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Name:'
                    ),
                    array(
                        'name' => 'mep_price_text',
                        'label' => __('Price:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Price:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Price:'
                    ),
                    array(
                        'name' => 'mep_event_schedule_text',
                        'label' => __('Event Schedule Details', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Event Schedule Details</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Event Schedule Details'
                    ),
                    array(
                        'name' => 'mep_total_text',
                        'label' => __('Total:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Total:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_ticket_qty_text',
                        'label' => __('Ticket Qty', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Ticket Qty</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_per_ticket_price_text',
                        'label' => __('Per Ticket Price:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Per Ticket Price:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),

                    array(
                        'name' => 'mep_no_ticket_selected_text',
                        'label' => __('No Ticket Selected!', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>No Ticket Selected!</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),

                    array(
                        'name' => 'mep_no_seat_available_text',
                        'label' => __('No Seat Available', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>No Seat Available</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),

                    array(
                        'name' => 'mep_not_available_text',
                        'label' => __('Not Available', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Not Available</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),

                    array(
                        'name' => 'mep_event_expired_text',
                        'label' => __('Event Expired', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Event Expired</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),

                    array(
                        'name' => 'mep_ticket_text',
                        'label' => __('Ticket', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Ticket</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_left_text',
                        'label' => __('Left', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Left</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_attendee_info_text',
                        'label' => __('Attendee info:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Attendee info:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'name' => 'mep_select_ticket_error_message',
                        'label' => __('Select Ticket Error Message:', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Select Ticket Error Message:</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Please select atleast one(1) ticket Quantity !'
                    ),
                    array(
                        'name' => 'mep_event_virtual_label',
                        'label' => __('Virtual Event', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Virtual Event</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Virtual Event'
                    ),
                    array(
                        'name' => 'mep_event_multidate_ribon_text',
                        'label' => __('Multi Date Event', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Multi Date Event</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Multi Date Event'
                    ),
                    array(
                        'name' => 'mep_event_view_more_date_btn_text',
                        'label' => __('View More Date', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>View More Date</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'View More Date'
                    ),
                    array(
                        'name' => 'mep_event_hide_date_list_btn_text',
                        'label' => __('Hide Date Lists', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Hide Date Lists</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Hide Date Lists'
                    ),
                    array(
                        'name' => 'mep_event_recurring_ribon_text',
                        'label' => __('Recurring Event', 'mage-eventpress'),
                        'desc' => __('Enter the translated text of <strong>Recurring Event</strong>.', 'mage-eventpress'),
                        'type' => 'text',
                        'default' => 'Recurring Event'
                    )

                )),

                'style_setting_sec' => apply_filters('mep_settings_styling_arr', array(
                        array(
                            'name' => 'mep_base_color',
                            'label' => __('Base Color', 'mage-eventpress'),
                            'desc' => __('Choose a basic color, it will change the icon background color & border color.', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => ''
                        ),
                        array(
                            'name' => 'mep_title_bg_color',
                            'label' => __('Label Background Color', 'mage-eventpress'),
                            'desc' => __('Choose label background color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#ffbe30'
                        ),
                        array(
                            'name' => 'mep_title_text_color',
                            'label' => __('Label Text Color', 'mage-eventpress'),
                            'desc' => __('Choose label text color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#fff'
                        ),
                        array(
                            'name' => 'mep_cart_btn_bg_color',
                            'label' => __('Cart Button Background Color', 'mage-eventpress'),
                            'desc' => __('Choose cart button background color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#ffbe30'
                        ),
                        array(
                            'name' => 'mep_cart_btn_text_color',
                            'label' => __('Cart Button Text Color', 'mage-eventpress'),
                            'desc' => __('Choose cart button text color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#fff'
                        ),
                        array(
                            'name' => 'mep_calender_btn_bg_color',
                            'label' => __('Calender Button Background Color', 'mage-eventpress'),
                            'desc' => __('Choose calender button background color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#ffbe30'
                        ),
                        array(
                            'name' => 'mep_calender_btn_text_color',
                            'label' => __('Calender Button Text Color', 'mage-eventpress'),
                            'desc' => __('Choose calender button text color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#fff'
                        ),
                        array(
                            'name' => 'mep_faq_title_bg_color',
                            'label' => __('FAQ Title Background Color', 'mage-eventpress'),
                            'desc' => __('Choose FAQ title background color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#ffbe30'
                        ),
                        array(
                            'name' => 'mep_faq_title_text_color',
                            'label' => __('FAQ Title Text Color', 'mage-eventpress'),
                            'desc' => __('Choose FAQ title text color', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#fff'
                        ),
                        array(
                            'name' => 'mep_royal_primary_bg_color',
                            'label' => __('Royal Template Primary Background Color', 'mage-eventpress'),
                            'desc' => __('Choose primary background color for royal template', 'mage-eventpress'),
                            'type' => 'color',
                        ),
                        array(
                            'name' => 'mep_royal_secondary_bg_color',
                            'label' => __('Royal Template Secondary Background Color', 'mage-eventpress'),
                            'desc' => __('Choose secondary background color for royal template', 'mage-eventpress'),
                            'type' => 'color',
                        ),
                        array(
                            'name' => 'mep_royal_icons_bg_color',
                            'label' => __('Royal Template Icons Background Color', 'mage-eventpress'),
                            'desc' => __('Choose icons background color for royal template', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#ffbe30'
                        ),
                        array(
                            'name' => 'mep_royal_border_color',
                            'label' => __('Royal Template Border Color', 'mage-eventpress'),
                            'desc' => __('Choose border color for royal template', 'mage-eventpress'),
                            'type' => 'color',
                        ),
                        array(
                            'name' => 'mep_royal_text_color',
                            'label' => __('Royal Template Text Color', 'mage-eventpress'),
                            'desc' => __('Choose text color for royal template', 'mage-eventpress'),
                            'type' => 'color',
                            'default' => '#000'
                        ),						
                    )
                ),

                'icon_setting_sec' => apply_filters('mep_settings_icon_arr', array(

                    array(
                        'name' => 'mep_event_date_icon',
                        'label' => __('Choose Event Date Icon', 'mage-eventpress'),
                        'desc' => __('Please choose event date icon.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fa fa-calendar',
                    ),
                    array(
                        'name' => 'mep_event_time_icon',
                        'label' => __('Choose Event Time Icon', 'mage-eventpress'),
                        'desc' => __('Please choose event time icon.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fas fa-clock',
                    ),
                    array(
                        'name' => 'mep_event_location_icon',
                        'label' => __('Choose Event Location Icon', 'mage-eventpress'),
                        'desc' => __('Please choose event location icon.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fas fa-map-marker-alt',
                    ),
                    array(
                        'name' => 'mep_event_organizer_icon',
                        'label' => __('Choose Event Organizer Icon', 'mage-eventpress'),
                        'desc' => __('Please choose event organizer icon.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'far fa-list-alt',
                    ),
                    array(
                        'name' => 'mep_event_location_list_icon',
                        'label' => __('Choose Event Sidebar Location List Icon', 'mage-eventpress'),
                        'desc' => __('Please choose event sidebar location list icon.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fa fa-arrow-circle-right',
                    ),
                    array(
                        'name' => 'mep_event_ss_fb_icon',
                        'label' => __('Choose Event Social Share Icon for Facebook', 'mage-eventpress'),
                        'desc' => __('Please choose event social share icon for facebook.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fab fa-facebook-f',
                    ),
                    array(
                        'name' => 'mep_event_ss_twitter_icon',
                        'label' => __('Choose Event Social Share Icon for Twitter', 'mage-eventpress'),
                        'desc' => __('Please choose event social share icon for twitter.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fab fa-twitter',
                    ),
                    array(
                        'name' => 'mep_event_ss_linkedin_icon',
                        'label' => __('Choose Event Social Share Icon for Linkedin', 'mage-eventpress'),
                        'desc' => __('Please choose event social share icon for linkedin.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fab fa-linkedin',
                    ),
                    array(
                        'name' => 'mep_event_ss_whatsapp_icon',
                        'label' => __('Choose Event Social Share Icon for Whatsapp', 'mage-eventpress'),
                        'desc' => __('Please choose event social share icon for whatsapp.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fab fa-whatsapp',
                    ),
                    array(
                        'name' => 'mep_event_ss_email_icon',
                        'label' => __('Choose Event Social Share Icon for Email', 'mage-eventpress'),
                        'desc' => __('Please choose event social share icon for email.', 'mage-eventpress'),
                        'type' => 'iconlib',
                        'default' => 'fa fa-envelope',
                    ),                                                                                                                                                         
                )
                ),

                'carousel_setting_sec' => apply_filters('mep_settings_carousel_arr', array(

                        array(
                            'name' => 'mep_load_carousal_from_theme',
                            'label' => __('Load Owl Carousel From Theme', 'mage-eventpress'),
                            'desc' => __('If your theme Owl Carousel stops working or your theme already loaded the Owl Carousel Library, you can set this yes.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'no',
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            )
                        ),

                        array(
                            'name' => 'mep_autoplay_carousal',
                            'label' => __('Auto Play', 'mage-eventpress'),
                            'desc' => __('Please select Carousel will auto play or not.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'yes',
                            'options' => array(
                                'true' => 'Yes',
                                'false' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_loop_carousal',
                            'label' => __('Infinite Loop', 'mage-eventpress'),
                            'desc' => __('Please select Carousel will Infinite Loop or not.', 'mage-eventpress'),
                            'type' => 'select',
                            'default' => 'yes',
                            'options' => array(
                                'true' => 'Yes',
                                'false' => 'No'
                            )
                        ),
                        array(
                            'name' => 'mep_speed_carousal',
                            'label' => __('Carousel Auto Play Speed', 'mage-eventpress'),
                            'desc' => __('Please Enter Carousel Auto Play Speed. Default is 5000', 'mage-eventpress'),
                            'type' => 'text',
                            'default' => '5000'
                        ),


                    )

                )
            );

            return apply_filters('mep_settings_sec_fields', $settings_fields);
        }

        function plugin_page() {
            echo '<div class="wrap">';
            settings_errors();
            echo '</div>';                  
            echo '<div class="mep_settings_wrapper">';
            echo '<div class="mep_settings_inner_wrapper">';
            echo '<div class="mep_settings_panel_header">';
            echo mep_get_plugin_data('Name');
            echo '<small>'.mep_get_plugin_data('Version').'</small>';
            echo '</div>';            
            echo '<div class="mage_settings_panel_wrap mep_settings_panel">';
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages() {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }

            return $pages_options;
        }
    }
endif;

$settings = new MAGE_Events_Setting_Controls();


function mep_get_option($option, $section, $default = '') {
    $options = get_option($section);

    if (isset($options[$option])) {
        return $options[$option];
    }

    return $default;
}


add_action('wsa_form_bottom_mep_settings_licensing', 'mep_licensing_page', 5);
function mep_licensing_page($form) {
    ?>
    <div class='mep-licensing-page'>
        <h3>Event Manager For Woocommerce Licensing</h3>
        <p>Thanks you for using our Event Manager For Woocommerce plugin. This plugin is free and no license is required. We have some Additional addon to enhace feature of this plugin functionality. If you have any addon you need to enter a valid license for that plugin below. </p>

        <div class="mep_licensae_info"></div>
        <table class='wp-list-table widefat striped posts mep-licensing-table'>
            <thead>
            <tr>
                <th>Plugin Name</th>
                <th width=10%>Order No</th>
                <th width=15%>Expire on</th>
                <th width=30%>License Key</th>
                <th width=10%>Status</th>
                <th width=10%>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php do_action('mep_license_page_addon_list'); ?>
            </tbody>
        </table>
    </div>
    <?php
}

add_action('wsa_form_bottom_mep_settings_templates', 'mep_settings_template_page', 5);
function mep_settings_template_page($form) {
    ?>
    <div class='mep-licensing-page'>
        <h3>Ready Templates For Event Details Page.</h3>


        <div class="mep_licensae_info"></div>


        <div class="mep-template-lists">


            <?php
            $url = 'https://vaincode.com/update/template/template.json';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $data = curl_exec($curl);
            curl_close($curl);
            $obj = json_decode($data, true);

            // print_r($data);

            if (is_array($obj) && sizeof($obj) > 0) {
                ?>
                <div class="mep_ready_template_sec">
                    <ul class="mep_ready_template_list">
                        <?php
                        foreach ($obj as $list) {
                            $name = $list['name'];
                            $banner = $list['banner'];
                            $url = $list['url'];
                            $type = $list['type'];
                            $editor = $list['editor'];
                            $preview = $list['preview'];
                            $name_slug = sanitize_title($name);
                            $count_import = get_option('mep_import_template_' . $name_slug) ? get_option('mep_import_template_' . $name_slug) : 0;
                            ?>
                            <li>
                                <div class="template-thumb"><img src="<?php echo esc_url($banner); ?>" alt=""></div>
                                <h3><?php echo esc_html($name); ?></h3>
                                <?php if ($count_import > 0) { ?>
                                    <p class="mep-template-import-count"> Imported <?php echo esc_html($count_import); ?> times</p>
                                    <?php
                                }

                                if (did_action('elementor/loaded') && $editor == 'elm') {
                                    ?>
                                    <button class='import_template' data-file="<?php echo esc_attr($url); ?>" data-name="<?php echo esc_attr($name); ?>" data-editor="<?php echo esc_attr($editor); ?>" data-type="<?php echo esc_attr($type); ?>">Import</button>
                                    <?php
                                } else {
                                    ?>
                                    <p class='mep-msg mep-msg-warning'>Elementor Not Installed</p>
                                <?php } ?>
                                <a href="<?php echo esc_url($preview); ?>" class='preview-btn btn' target='_blank'>Preview</a>

                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>

        <script>
            (function ($) {
                'use strict';
                jQuery('.import_template').on('click', function () {
                    if (confirm('Are You Sure to Import this Template ? \n\n 1. Ok : To Import . \n 2. Cancel : To Cancel .')) {
                        let file = jQuery(this).data('file');
                        let type = jQuery(this).data('type');
                        let editor = jQuery(this).data('editor');
                        let name = jQuery(this).data('name');
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                "action": "mep_import_ajax_template",
                                "nonce": '<?php echo wp_create_nonce('mep-ajax-import-template-nonce'); ?>',
                                "file": file,
                                "editor": editor,
                                "name": name,
                                "type": type
                            },
                            beforeSend: function () {
                                jQuery('.mep_licensae_info').html('<h5 class="mep-msg mep-msg-process">Please wait.. Importing Template..</h5>');
                            },
                            success: function (data) {
                                jQuery('.mep_licensae_info').html(data);
                                window.location.reload();
                            }
                        });
                    } else {
                        return false;
                    }
                    return false;
                });
            })(jQuery);
        </script>


    </div>
    <?php
}