<?php

//CUS API LIBRARY
if (!class_exists('cUsComAPI_CF')) {
    require_once('libs/cusAPI.class.php');
}

//AJAX REQUEST HOOKS
require_once('contactus_form_ajx_request.php');

if (!function_exists('cUsCF_admin_header')) {

    function cUsCF_admin_header() {
        
        global $current_screen;

        if ($current_screen->id == 'toplevel_page_cUs_form_plugin') {
            
            wp_enqueue_style( 'cUsCF_Styles', plugins_url('style/cUsCF_style.css', __FILE__), false, '1');
            wp_enqueue_style( 'fancybox', plugins_url('scripts/fancybox/jquery.fancybox.css', __FILE__), false, '1');
            wp_enqueue_style( 'bxslider', plugins_url('scripts/bxslider/jquery.bxslider.css', __FILE__), false, '1');
            wp_enqueue_style( 'wp-jquery-ui-dialog' );

            wp_register_script( 'cUsCF_Scripts', plugins_url('scripts/cUsCF_scripts.js?pluginurl=' . dirname(__FILE__), __FILE__), array('jquery'), '1.0', true);
            wp_localize_script( 'cUsCF_Scripts', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
            wp_register_script( 'fancybox', plugins_url('scripts/fancybox/jquery.fancybox.pack.js', __FILE__), array('jquery'), '2.0.0', true);
            wp_register_script( 'tooltip', plugins_url('scripts/jquery-ui-custom/jquery-ui-1.10.3.custom.min.js', __FILE__), array('jquery'), '1.10.3', true);
            wp_register_script( 'bxslider', plugins_url('scripts/bxslider/jquery.bxslider.js', __FILE__), array('jquery'), '4.1.1', true);

            wp_enqueue_script('jquery'); //JQUERY WP CORE
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-button');
            wp_enqueue_script('jquery-ui-selectable');
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script('fancybox');
            wp_enqueue_script('tooltip');
            wp_enqueue_script('bxslider');
            
            wp_enqueue_script('cUsCF_Scripts');
        }
    }

}
add_action('admin_enqueue_scripts', 'cUsCF_admin_header'); // cUsCF_admin_header hook
//END CONTACTUS.COM PLUGIN STYLES CSS

function plugin_links($links, $file) {
    $plugin_file = 'contactuscom/contactus_form.php';
    if ($file == $plugin_file) {
        $links[] = '<a target="_blank" style="color: #42a851; font-weight: bold;" href="http://help.contactus.com/">' . __("Get Support", "cus_plugin") . '</a>';
    }
    return $links;
}

add_filter('plugin_row_meta', 'plugin_links', 10, 2);


/**
 * This should create the setting button in plugin CF7 cloud database
 * */
function cUsCF_action_links($links, $file) {
    $plugin_file = 'contactuscom/contactus_form.php';
    //make sure it is our plugin we are modifying
    if ($file == $plugin_file) {
        $settings_link = '<a href="' .
                admin_url('admin.php?page=cUs_form_plugin') . '">' . __('Settings', 'cus_plugin') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter("plugin_action_links", 'cUsCF_action_links', 10, 4);

//Display the validation errors and update messages

/*
 * Admin notices
 */

function cUsCF_admin_notices() {
    settings_errors();
}

add_action('admin_notices', 'cUsCF_admin_notices');

if ( is_admin() ) {
    add_action('media_buttons', 'set_media_cus_forminsert_button', 100);
    function set_media_cus_forminsert_button() {
        $xHtml_mediaButton = '<a href="javascript:;" class="insertShortcode" title="'.__('Insert Contactus.com Form').'">';
            $xHtml_mediaButton .= '<img hspace="5" src="'.plugins_url('style/images/favicon.gif', __FILE__).'" alt="'.__('Insert ContactUs.com Form').'" />';
        $xHtml_mediaButton .= '</a>';
        //print $xHtml_mediaButton;
    }
}


function cUsCF_JS_into_html() {
    if (!is_admin()) {
        
        $pageID = get_the_ID();
        $pageSettings = get_post_meta( $pageID, 'cUsCF_FormByPage_settings', false );
        
        if(is_array($pageSettings) && !empty($pageSettings)){ //NEW VERSION 3.0
            
            $boolTab        = $pageSettings[0]['tab_user'];
            $cus_version    = $pageSettings[0]['cus_version'];
            $form_key       = $pageSettings[0]['form_key'];
            
            if($cus_version == 'tab'){
                
                $userJScode = '<script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/contactus.js"></script>';
            
                echo $userJScode;
            }
            
        }else{ //PREVIOUS VERSIONS 2.5
            
            $formOptions    = get_option('cUsCF_FORM_settings');//GET THE NEW FORM OPTIONS
            $getTabPages    = get_option('cUsCF_settings_tabpages');
            
            $getInlinePages = get_option('cUsCF_settings_inlinepages');
            $form_key       = get_option('cUsCF_settings_form_key');
            $boolTab = $formOptions['tab_user'];
            $cus_version = $formOptions['cus_version'];
            
            if(!empty($getTabPages) && in_array('home', $getTabPages)){
                $getHomePage         = get_option('cUsCF_HOME_settings');
                $boolTab        = $getHomePage['tab_user'];
                $cus_version    = $getHomePage['cus_version'];
                $form_key       = $getHomePage['form_key'];
            }
            
            $userJScode = '<script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/contactus.js"></script>';

            //the theme must have the wp_footer() function included
            //include the contactUs.com JS before the </body> tag
            switch ($cus_version) {
                case 'tab':
                    if (strlen($form_key) && $boolTab){
                        echo $userJScode;
                    }
                    break;
                case 'selectable':
                    if (strlen($form_key) && is_array($getTabPages) && in_array($pageID, $getTabPages)){
                        echo $userJScode;
                    }
                    break;
            }
            
        }
        
        
        
        
    }
}
add_action('wp_footer', 'cUsCF_JS_into_html'); // ADD JS BEFORE BODY TAG

function cUsCF_inline_home() {

    $formOptions    = get_option('cUsCF_FORM_settings');//GET THE NEW FORM OPTIONS
    $form_key       = get_option('cUsCF_settings_form_key');
    $cus_version    = $formOptions['cus_version'];
    if ($cus_version == 'inline' || $cus_version == 'selectable') {
        $inlineJS_output = '<div style="min-height: 300px; width: 350px;clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/inline.js"></script></div>';
    }else{
        $inlineJS_output = '';
    }

    echo $inlineJS_output;
}

function cUsCF_page_settings_cleaner() {
    $aryPages = get_pages();
    foreach ($aryPages as $oPage) {
        delete_post_meta($oPage->ID, 'cUsCF_FormByPage_settings');//reset values
        cUsCF_inline_shortcode_cleaner_by_ID($oPage->ID); //RESET SC
    }
}

function cUsCF_inline_shortcode_cleaner() {
    $aryPages = get_pages();
    foreach ($aryPages as $oPage) {
        $pageContent = $oPage->post_content;
        $pageContent = str_replace('[show-contactus.com-form]', '', $pageContent);
        $aryPage = array();
        $aryPage['ID'] = $oPage->ID;
        $aryPage['post_content'] = $pageContent;
        wp_update_post($aryPage);
    }
}

function cUsCF_inline_shortcode_cleaner_by_ID($inline_req_page_id) {
    $oPage = get_page( $inline_req_page_id );
    
    $pageContent = $oPage->post_content;
    $pageContent = str_replace('[show-contactus.com-form]', '', $pageContent);
    $aryPage = array();
    $aryPage['ID'] = $oPage->ID;
    $aryPage['post_content'] = $pageContent;
    
    wp_update_post($aryPage);
    
}

add_shortcode("show-contactus.com-form", "cUsCF_shortcode_handler"); //[show-contactus.com-form]

function cUsCF_shortcode_handler($aryFormParemeters) {
    
    $cUsCF_credentials = get_option('cUsCF_settings_userCredentials'); //GET USERS CREDENTIALS V3.0 API 1.9
    
    if(!empty($cUsCF_credentials)){ 
        
        $pageID = get_the_ID();
        $pageSettings = get_post_meta( $pageID, 'cUsCF_FormByPage_settings', false );
        $inlineJS_output = '';

        if(is_array($pageSettings) && !empty($pageSettings)){ //NEW VERSION 3.0

            $boolTab        = $pageSettings[0]['tab_user'];
            $cus_version    = $pageSettings[0]['cus_version'];
            $form_key       = $pageSettings[0]['form_key'];

            if(strlen($formkey)) $form_key = $formkey;

            if ($cus_version == 'inline' || $cus_version == 'selectable') {
               $inlineJS_output = '<div style="min-height: 300px; min-width: 340px; clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/inline.js"></script></div>';
            }

        }elseif(is_array($aryFormParemeters)){

            if($aryFormParemeters['version'] == 'tab'){
                $Fkey = $aryFormParemeters['formkey'];
                update_option('cUsCF_settings_FormKey_SC', $Fkey);
                do_action('wp_footer', $Fkey);
                add_action('wp_footer', 'cUsCF_shortcodeTab'); // ADD JS BEFORE BODY TAG
            }else{
                $inlineJS_output = '<div style="min-height: 300px; min-width: 340px; clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $aryFormParemeters['formkey'] . '/inline.js"></script></div>';
            }

        }else{   //OLDER VERSION < 2.5 //UPDATE 
            $formOptions    = get_option('cUsCF_FORM_settings');//GET THE NEW FORM OPTIONS
            $form_key       = get_option('cUsCF_settings_form_key');
            $cus_version    = $formOptions['cus_version'];

            if ($cus_version == 'inline' || $cus_version == 'selectable') {
                $inlineJS_output = '<div style="min-height: 300px; min-width: 340px; clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/inline.js"></script></div>';
            }

        }

        return $inlineJS_output;
    }else{
        
        return '<p>Contact Form by ContactUs.com user Credentials Missing . . . <br/>Please Login Again <a href="'.get_admin_url().'admin.php?page=cUs_form_plugin" target="_blank">here</a>, Thank You.</p>';
        
    }
    
}

function cUsCF_shortcodeTab($Args) {
    
    $form_key = get_option('cUsCF_settings_FormKey_SC');
    
    if ( !is_admin() && strlen($form_key) ) {
        $userJScode = '<script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/contactus.js"></script>';
        echo $userJScode;
    }
}


function cUsCF_inline_shortcode_add($inline_req_page_id) {
    
    if($inline_req_page_id != 'home'){
        $oPage = get_page($inline_req_page_id);
        $pageContent = $oPage->post_content;
        $pageContent = $pageContent . "\n[show-contactus.com-form]";
        $aryPage = array();
        $aryPage['ID'] = $inline_req_page_id;
        $aryPage['post_content'] = $pageContent;
        return wp_update_post($aryPage);
    }
}

$cus_dirbase = trailingslashit(basename(dirname(__FILE__)));
$cus_dir = trailingslashit(WP_PLUGIN_DIR) . $cus_dirbase;
$cus_url = trailingslashit(WP_PLUGIN_URL) . $cus_dirbase;
define('cUsCF_DIR', $cus_dir);
define('cUsCF_URL', $cus_url);

// WIDGET CALL
include_once('contactus_form_widget.php');

function cUsCF_register_widgets() {
    register_widget('contactus_form_Widget');
}

add_action('widgets_init', 'cUsCF_register_widgets');

//CONTACTUS.COM ADD FORM TO PLUGIN PAGE

// Add option page in admin menu
if (!function_exists('cUsCF_admin_menu')) {

    function cUsCF_admin_menu() {
        add_menu_page('Contact Form by ContactUs.com ', 'Contact Form', 'edit_themes', 'cUs_form_plugin', 'cUsCF_menu_render', plugins_url("style/images/favicon.gif", __FILE__));
    }

}
add_action('admin_menu', 'cUsCF_admin_menu'); // cUsCF_admin_menu hook


if (!function_exists('cUsCF_plugin_db_uninstall')) {

    function cUsCF_plugin_db_uninstall() {

        $cUsCF_api = new cUsComAPI_CF();
        $cUsCF_api->resetData(); //RESET DATA
        
        cUsCF_page_settings_cleaner();
        
    }
    
}
register_uninstall_hook(__FILE__, 'cUsCF_plugin_db_uninstall');


/* Display a notice that can be dismissed */
add_action('admin_notices', 'cUsCF_update_admin_notice');
function cUsCF_update_admin_notice() {
	
        global $current_user ;
        $user_id = $current_user->ID;
        
        $aryUserCredentials = get_option('cUsCF_settings_userCredentials');
        $form_key           = get_option('cUsCF_settings_form_key');
        $cUs_API_Account    = $aryUserCredentials['API_Account'];
        $cUs_API_Key        = $aryUserCredentials['API_Key'];
        
	if ( ! get_user_meta($user_id, 'cUsCF_ignore_notice') && !strlen($cUs_API_Account) && !strlen($cUs_API_Key) && strlen($form_key)) {
            echo '<div class="updated"><p>';
            printf(__('Contact Form has been updated to v3.1!. Pleas take time to activate your check our new features <a href="%1$s">here</a>. | <a href="%2$s">Hide Notice</a>'), 'admin.php?page=cUs_form_plugin', '?cUsCF_ignore_notice=0');
            echo "</p></div>";
	}
        
}
add_action('admin_init', 'cUsCF_nag_ignore');
function cUsCF_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        if ( isset($_GET['cUsCF_ignore_notice']) && '0' == $_GET['cUsCF_ignore_notice'] ) {
             add_user_meta($user_id, 'cUsCF_ignore_notice', 'true', true);
	}
}

?>
