<?php

/**
 * 
 * CONTACT FORM BY CONTACTUS.COM
 * 
 * Initialization Contact Form Widget Class from WP_Widget
 * @since 3.0 First time this was introduced into Contact Form plugin.
 * @author ContactUs.com <support@contactus.com>
 * @copyright 2013 ContactUs.com Inc.
 * Company      : contactus.com
 * Updated  	: 20142102
 **/

//Contact Subscribe Box widget extend 
class contactus_form_Widget extends WP_Widget {

	function contactus_form_Widget() {
		$widget_ops = array( 
			'description' => __('Displays Contact Form by ContactUs.com', 'contactus_form')
		);
		$this->WP_Widget('contactus_form_Widget', __('Contact Form by ContactUs.com', 'contactus_form'), $widget_ops);
	}

	function widget( $args, $instance ) {
		if (!is_array($instance)) {
			$instance = array();
		}
		contactuscom_form(array_merge($args, $instance));
	}
}
/*
* Method in charge to retrive ContactUs.com user's Default Form Key and render widget
* @param array $args Widget options 
* @since 3.0
* @return string HTML into the widget area
*/
function contactuscom_form($args = array()) {
    extract($args);
    $cUs_form_key = get_option('cUsCF_settings_form_key'); //get the saved form key
    
    if(strlen($cUs_form_key)){
        $xHTML  = '<div id="cUsCF_form_widget" style="clear:both;width:100%;min-height:530px;margin:10px auto;">';
        $xHTML .= '<script type="text/javascript" src="' . cUsCF_ENV_URL . $cUs_form_key . '/inline.js"></script>';
        $xHTML .= '</div>';
        
        echo $xHTML;
    }
}