<?php
/*
  The Contact Form Plugin by ContactUs.com.
 */

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
};

function contactuscom_form($args = array()) {
    extract($args);
    $cUs_form_key = get_option('cUsCF_settings_form_key'); //get the saved form key
    
    if(strlen($cUs_form_key)){
        $xHTML  = '<div id="cUsCF_form_widget" style="clear:both;min-height:530px;margin:10px auto;">';
        $xHTML .= '<script type="text/javascript" src="//cdn.contactus.com/cdn/forms/'. $cUs_form_key .'/inline.js"></script>';
        $xHTML .= '</div>';
        
        echo $xHTML;
    }
}