<?php
/*
  Plugin Name: Contact Form by ContactUs.com
  Version: 3.0
  Plugin URI:  http://help.contactus.com/entries/23229688-Adding-the-ContactUs-com-Plugin-for-WordPress
  Description: Contact Form by ContactUs.com Plugin for Wordpress.
  Author: contactus.com
  Author URI: http://www.contactus.com/
  License: GPLv2 or later
 */

/*
  Copyright 2013  ContactUs.com  ( email: support@contactuscom.zendesk.com )
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


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

            wp_register_script( 'cUsCF_Scripts', plugins_url('scripts/cUsCF_scripts.js?pluginurl=' . dirname(__FILE__), __FILE__), array('jquery'), '1.0', true);
            wp_localize_script( 'cUsCF_Scripts', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
            wp_register_script( 'fancybox', plugins_url('scripts/fancybox/jquery.fancybox.pack.js', __FILE__), array('jquery'), '2.0.0', true);
            wp_register_script( 'bxslider', plugins_url('scripts/bxslider/jquery.bxslider.js', __FILE__), array('jquery'), '4.1.1', true);

            wp_enqueue_script('jquery'); //JQUERY WP CORE
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-button');
            wp_enqueue_script('jquery-ui-selectable');
            wp_enqueue_script('fancybox');
            wp_enqueue_script('bxslider');
            
            wp_enqueue_script('cUsCF_Scripts');
        }
    }

}
add_action('admin_enqueue_scripts', 'cUsCF_admin_header'); // cUsCF_admin_header hook
//END CONTACTUS.COM PLUGIN STYLES CSS

function plugin_links($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $links[] = '<a target="_blank" style="color: #42a851; font-weight: bold;" href="http://help.contactus.com/">' . __("Get Support", "cus_plugin") . '</a>';
    }
    return $links;
}

add_filter('plugin_row_meta', 'plugin_links', 10, 2);


/*
 * Register the settings
 */
add_action('admin_init', 'cUsCF_register_settings');

function cUsCF_register_settings() {
    return false;
}

function cUsCF_settings_validate($args) {

    //make sure you return the args
    return $args;
}

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
        
        if(is_array($pageSettings) && !empty($pageSettings)): //NEW VERSION 3.0
            
            $boolTab        = $pageSettings[0]['tab_user'];
            $cus_version    = $pageSettings[0]['cus_version'];
            $form_key       = $pageSettings[0]['form_key'];
            
            if($cus_version == 'tab'):
                
                $userJScode = '<script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/contactus.js"></script>';
            
                echo $userJScode;
            endif;
            
        else: //PREVIOUS VERSIONS 2.5
            
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
                    if (strlen($form_key) && $boolTab):
                        echo $userJScode;
                    endif;
                    break;
                case 'selectable':
                    if (strlen($form_key) && is_array($getTabPages) && in_array($pageID, $getTabPages)):
                        echo $userJScode;
                    endif;
                    break;
            }
            
        endif;
        
        
        
        
    }
}
add_action('wp_footer', 'cUsCF_JS_into_html'); // ADD JS BEFORE BODY TAG

function cUsCF_inline_home() {

    $formOptions    = get_option('cUsCF_FORM_settings');//GET THE NEW FORM OPTIONS
    $form_key       = get_option('cUsCF_settings_form_key');
    $cus_version    = $formOptions['cus_version'];
    if ($cus_version == 'inline' || $cus_version == 'selectable') :
        $inlineJS_output = '<div style="min-height: 300px; width: 350px;clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/inline.js"></script></div>';
    else:
        $inlineJS_output = '';
    endif;

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
    
    if(!empty($cUsCF_credentials)): 
        
        $pageID = get_the_ID();
        $pageSettings = get_post_meta( $pageID, 'cUsCF_FormByPage_settings', false );
        $inlineJS_output = '';

        if(is_array($pageSettings) && !empty($pageSettings)): //NEW VERSION 3.0

            $boolTab        = $pageSettings[0]['tab_user'];
            $cus_version    = $pageSettings[0]['cus_version'];
            $form_key       = $pageSettings[0]['form_key'];

            if(strlen($formkey)) $form_key = $formkey;

            if ($cus_version == 'inline' || $cus_version == 'selectable') :
               $inlineJS_output = '<div style="min-height: 300px; min-width: 340px; clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/inline.js"></script></div>';
            endif;

        elseif(is_array($aryFormParemeters)):

            if($aryFormParemeters['version'] == 'tab'):
                $Fkey = $aryFormParemeters['formkey'];
                update_option('cUsCF_settings_FormKey_SC', $Fkey);
                do_action('wp_footer', $Fkey);
                add_action('wp_footer', 'cUsCF_shortcodeTab'); // ADD JS BEFORE BODY TAG
            else:
                $inlineJS_output = '<div style="min-height: 300px; min-width: 340px; clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $aryFormParemeters['formkey'] . '/inline.js"></script></div>';
            endif;

        else:   //OLDER VERSION < 2.5 //UPDATE 
            $formOptions    = get_option('cUsCF_FORM_settings');//GET THE NEW FORM OPTIONS
            $form_key       = get_option('cUsCF_settings_form_key');
            $cus_version    = $formOptions['cus_version'];

            if ($cus_version == 'inline' || $cus_version == 'selectable') :
                $inlineJS_output = '<div style="min-height: 300px; min-width: 340px; clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/inline.js"></script></div>';
            endif;

        endif;

        return $inlineJS_output;
    else:
        
        return '<p>Contact Form by ContactUs.com user Credentials Missing . . . <br/>Please Login Again <a href="'.get_admin_url().'admin.php?page=cUs_form_plugin" target="_blank">here</a>, Thank You.</p>';
        
    endif;
    
}

function cUsCF_shortcodeTab($Args) {
    
    $form_key = get_option('cUsCF_settings_FormKey_SC');
    
    if ( !is_admin() && strlen($form_key) ) {
        $userJScode = '<script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/contactus.js"></script>';
        echo $userJScode;
    }
}


function cUsCF_inline_shortcode_add($inline_req_page_id) {
    
    if($inline_req_page_id != 'home'):
        $oPage = get_page($inline_req_page_id);
        $pageContent = $oPage->post_content;
        $pageContent = $pageContent . "\n[show-contactus.com-form]";
        $aryPage = array();
        $aryPage['ID'] = $inline_req_page_id;
        $aryPage['post_content'] = $pageContent;
        return wp_update_post($aryPage);
    endif;
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

if (!function_exists('cUsCF_menu_render')) {

    function cUsCF_menu_render() {
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        $aryUserCredentials = get_option('cUsCF_settings_userCredentials'); //get the values, wont work the first time
        $cUs_API_Account    = $aryUserCredentials['API_Account'];
        $cUs_API_Key        = $aryUserCredentials['API_Key'];
        
        $options        = get_option('cUsCF_settings_userData'); //get the values, wont work the first time
        $old_options    = get_option('contactus_settings'); //GET THE OLD OPTIONS
        $formOptions    = get_option('cUsCF_FORM_settings');//GET THE NEW FORM OPTIONS
        
        $form_key       = get_option('cUsCF_settings_form_key');
        $cus_version    = $formOptions['cus_version'];
        $boolTab        = $formOptions['tab_user'];
        
        if(strlen(!$form_key)) $form_key = $old_options['form_key'];
        if(strlen(!$cus_version)) $cus_version = $old_options['cus_version'];
        if(strlen(!$boolTab)) $boolTab = $old_options['tab_user'];
        
        if (!is_array($options)) {
            settings_fields('cUsCF_settings_userData');
            $options = get_option('cUsCF_settings'); //get the values, wont work the first time
            settings_fields('cUsCF_FORM_settings');
            settings_fields('cUsCF_settings_form_key');
            do_settings_sections(__FILE__);
        }
        
        if(isset($_REQUEST['option'])):
            switch ( $_REQUEST['option'] ):

                case 'settings': //SAVING FORM SETTINGS TAB - INLINE - SELECTION ?>
                    <script>jQuery(document).ready(function($) { try{  jQuery( "#cUsCF_tabs" ).tabs({ active: 0 })  }catch(err){console.log(err);} });</script><?php
                    if( strlen($form_key) ): //ALREADY LOGGED
                        $settingsMessage = '<div id="message" class="updated fade notice_done"><p>Done! Your configuration has been saved correctly.</p></div>';
                        $boolTab    = $_REQUEST['tab_user'];

                        $aryFormOptions = array(
                            'tab_user'          => $boolTab,
                            'cus_version'       => $_REQUEST['cus_version']
                        );

                        delete_option( 'cUsCF_FORM_settings' );
                        delete_option( 'cUsCF_settings_inlinepages' );
                        delete_option( 'cUsCF_settings_tabpages' );
                        update_option( 'cUsCF_FORM_settings', $aryFormOptions );//UPDATE FORM SETTINGS
                        
                        cUsCF_page_settings_cleaner();
                        

                    endif;
                break;

            endswitch;
        endif;
        
        ?>
                    
        <script>var posturl = '<?php echo plugins_url('ajx-request.php', __FILE__) ;  ?>';</script>
        <div class="plugin_wrap">
            <div class="cUsCF_header">
                <h2>Contact Form <span> by</span><a href="http://www.contactus.com" target="_blank"><img src="<?php echo plugins_url('style/images/header-logo.png', __FILE__) ;  ?>"/></a> </h2>
                <div class="social_shares">
                    <a href="https://www.facebook.com/ContactUscom" target="_blank" title="Follow Us on Facebook for new product updates"><img src="<?php echo plugins_url('style/images/cu-facebook-m.png', __FILE__) ;  ?> " alt="Follow Us on Facebook for new product updates"/></a>
                    <a href="https://plus.google.com/u/0/117416697174145120376" target="_blank" title="Follow Us on Google+"><img src="<?php echo plugins_url('style/images/cu-googleplus-m.png', __FILE__) ;  ?> " /></a>
                    <a href="http://www.linkedin.com/company/2882043" target="_blank" title="Follow Us on LinkedIn"><img src="<?php echo plugins_url('style/images/cu-linkedin-m.png', __FILE__) ;  ?> " /></a>
                    <a href="https://twitter.com/ContactUsCom" target="_blank" title="Follow Us on Twitter"><img src="<?php echo plugins_url('style/images/cu-twitter-m.png', __FILE__) ;  ?> " /></a>
                    <a href="http://www.youtube.com/user/ContactUsCom" target="_blank" title="Find tutorials on our Youtube channel"><img src="<?php echo plugins_url('style/images/cu-youtube-m.png', __FILE__) ;  ?> " alt="Find tutorials on our Youtube channel" /></a>
                </div>
            </div> 
            <div class="cUsCF_formset">
                <div id="cUsCF_tabs">
                    <ul>
                        <?php if ( !strlen($form_key) ): ?><li><a href="#tabs-1">Contact Form Plugin</a></li><?php endif; ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ): ?><li><a href="#tabs-1">Form Settings</a></li><?php endif; ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ): ?><li><a href="#tabs-2">Templates Library</a></li><?php endif; ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ): ?><li><a href="#tabs-3">Advanced</a></li><?php endif; ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ): ?><li><a href="#tabs-4">Documentation</a></li><?php endif; ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ): ?><li><a href="#tabs-5">Account</a></li><?php endif; ?>
                    </ul>

                    <?php
                    if (!strlen($form_key))://NOT LOGGED
                        
                        global $current_user;
                        get_currentuserinfo();
                        ?>
                        <div id="tabs-1">
                            
                            <div class="left-content">
                                
                                <div class="first_step">
                                    <h2>Are You Already a ContactUs.com User?</h2>
                                    <button id="cUsCF_yes" class="btn" type="button" ><span>Yes</span> Get Me My Form</button>
                                    <button id="cUsCF_no" class="btn mc_lnk"><span>No</span>Signup Free Now</button>
                                    <p>
                                    <h3>Note:</h3>
                                    The  Contact Form by ContactUs.com is designed for existing ContactUs.com users. If you are not yet a Contact Form user, click on the "No, Signup Free Now" button above.</p>
                                </div>
                                
                                <div id="cUsCF_settings">

                                    <div class="loadingMessage"></div>
                                    <div class="advice_notice">Advices....</div>
                                    <div class="notice">Ok....</div>

                                    <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_loginform" name="cUsCF_loginform" class="steps login_form" onsubmit="return false;">
                                        <h3>ContactUs.com Login</h3>

                                        <table class="form-table">

                                            <tr>
                                                <th><label class="labelform" for="login_email">Email</label><br>
                                                <td><input class="inputform" name="cUsCF_settings[login_email]" id="login_email" type="text"></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="user_pass">Password</label></th>
                                                <td><input class="inputform" name="cUsCF_settings[user_pass]" id="user_pass" type="password"></td>
                                            </tr>
                                            <tr><th></th>
                                                <td>
                                                    <input id="loginbtn" class="btn lightblue cUsCF_LoginUser" value="Login" type="submit">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <td>
                                                    <a href="https://www.contactus.com/client-login.php" target="_blank">I forgot my password</a>
                                                </td>
                                            </tr>

                                        </table>
                                    </form>

                                    <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_userdata" name="cUsCF_userdata" class="steps step1" onsubmit="return false;">
                                        <h3 class="step_title">Register for your ContactUs.com Account</h3>

                                        <table class="form-table">
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_first_name">* First Name</label></th>
                                                <td><input type="text" class="inputform text" placeholder="First Name" name="cUsCF_first_name" id="cUsCF_first_name" value="<?php echo (isset($_POST['cUsCF_first_name']) && strlen($_POST['cUsCF_first_name'])) ? $_POST['cUsCF_first_name'] : $current_user->user_firstname; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="lname">* Last Name</label></th>
                                                <td><input type="text" class="inputform text" placeholder="Last Name" name="cUsCF_last_name" id="cUsCF_last_name" value="<?php echo (isset($_POST['cUsCF_last_name']) && strlen($_POST['cUsCF_last_name'])) ? $_POST['cUsCF_last_name'] : $current_user->user_lastname; ?>"/></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="remail">* Email</label></th>
                                                <td><input type="text" class="inputform text" placeholder="Email" name="cUsCF_email" id="cUsCF_email" value="<?php echo (isset($_POST['cUsCF_email']) && strlen($_POST['cUsCF_email'])) ? $_POST['cUsCF_email'] : $current_user->user_email; ?>"/></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_web">* Website</label></th>
                                                <td><input type="text" class="inputform text" placeholder="Website (http://www.example.com)" name="cUsCF_web" id="cUsCF_web" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>"/></td>
                                            </tr>
                                            <tr>
                                                <th></th><td><input id="cUsCF_CreateCustomer" class="btn orange" value="Next >>" type="submit" /></td>
                                            </tr>
                                            <tr>
                                                <th></th><td>By clicking Create my account, you agree to <a href="http://www.contactus.com/terms-of-service/" target="_blank">the ContactUs.com Terms of Service.</a></td>
                                            </tr>
                                        </table>
                                    </form>

                                    <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_templates" name="cUsCF_templates" class="steps step2" onsubmit="return false;">
                                        <h3 class="step_title">Form Settings</h3>
                                       
                                        <div class="signup_templates">
                                            <h4>Select your Form Template</h4>

                                            <div>
                                                <div class="terminology_c Template_Contact_Form">
                                                    <?php
                                                    
                                                    $contacFormTemplates = $cUsCF_api->getTemplatesAndTabsAll('0', 'Template_Desktop_Form');
                                                    $contacFormTemplates = json_decode($contacFormTemplates);
                                                    $contacFormTemplates = $contacFormTemplates->data;
                                                    
                                                    if(is_array($contacFormTemplates)) :
                                                        
                                                    ?>
                                                    <ul id="sortable" class="selectable_cf">
                                                        <?php 
                                                        
                                                        foreach ($contacFormTemplates as $formTpl) : 
                                                            
                                                            $aryAllowed = array('template1', 'template2', 'template3', 'template4', 'template5', 'template6', 'template7');
                                                            
                                                            if(in_array($formTpl->id, $aryAllowed)) :
                                                            
                                                            ?>
                                                                <li class="ui-state-default" id="<?php echo $formTpl->id; ?>"><img src="<?php echo $formTpl->thumbnail; ?>" alt="<?php echo $formTpl->name; ?>" id="<?php echo $formTpl->id; ?>"/></li>
                                                        
                                                            <?php
                                                            
                                                            endif;
                                                            
                                                        endforeach; 
                                                        
                                                        ?>
                                                    </ul>
                                                    
                                                    <?php  endif; ?>
                                                    
                                                </div>
                                                
                                            </div>
                                            <h4>Select your Tab Template</h4>
                                            <div>
                                                <div class="terminology_c Template_Contact_Form">
                                                    <?php
                                                    $contacFormTabTemplates = $cUsCF_api->getTemplatesAndTabsAll('0', 'Template_Desktop_Tab');
                                                    $contacFormTabTemplates = json_decode($contacFormTabTemplates);
                                                    $contacFormTabTemplates = $contacFormTabTemplates->data;
                                                    
                                                    if(is_array($contacFormTabTemplates)) :
                                                    ?>
                                                    <ul id="sortable" class="tabs selectable_tabs_cf">
                                                        <?php foreach ($contacFormTabTemplates as $formTpl) : ?>
                                                            <li class="ui-state-default" id="<?php echo $formTpl->id; ?>"><img src="<?php echo $formTpl->thumbnail; ?>" alt="<?php echo $formTpl->name; ?>" id="<?php echo $formTpl->id; ?>"/></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <?php  endif; ?>
                                                </div>
                                                
                                            </div>

                                        </div> 
                                        <table class="form-table">
                                            <tr>
                                                <th></th><td><input id="cUsCF_SendTemplates" class="btn orange" value="Create my account" type="submit" /></td>
                                            </tr>
                                            <tr>
                                                <th></th><td>By clicking Create my account, you agree to <a href="http://www.contactus.com/terms-of-service/" target="_blank">the ContactUs.com Terms of Service.</a></td>
                                            </tr>
                                            <input type="hidden" value="" name="Template_Desktop_Form" id="Template_Desktop_Form" />
                                            <input type="hidden" value="" name="Template_Desktop_Tab" id="Template_Desktop_Tab" />
                                        </table>
                                    </form>

                                </div>
                            </div><!-- // TAB LEFT -->
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom?rate=5#postform" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ; ?> " /></a><br/><br/>
                                    <h3>Share the plugin with your friends over <a href="mailto:yourfriend@mail.com?subject=Great new WordPress plugin for contact forms" class="email">email</a></h3>
                                    <h3>Share the plugin on:</h3>
                                    <div class="social_shares">
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://wordpress.org/plugins/contactuscom/'), 
                                                      'facebook-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Facebook<img src="<?php echo plugins_url('style/images/facebook_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://twitter.com/intent/tweet?url=http://bit.ly/1688yva&amp;text=Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form.', 
                                                      'twitter-tweet-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Twitter<img src="<?php echo plugins_url('style/images/twitter_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'http://www.linkedin.com/cws/share?url=http://wordpress.org/plugins/contactuscom/&original_referer=http://wordpress.org/plugins/contactuscom/&token=&isFramed=false&lang=en_US', 
                                                      'linkedin-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >LinkedIn<img src="<?php echo plugins_url('style/images/linkedin_link.png', __FILE__) ;  ?> " /></a>
                                    </div><br/>
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://www.contactus.com/pricing-plans/" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->

                        </div> <!-- // TAB 1 -->
                    <?php else:
                        
                        global $current_user;
                        get_currentuserinfo();
                        
                        $cUsCF_API_getFormKeys = $cUsCF_api->getFormKeysAPI($cUs_API_Account, $cUs_API_Key); //api hook;
                        
                        ?>
                    
                    <?php if(strlen($cUs_API_Account)){ //UPDATE OLD USERS ?>    
                        
                    <div id="tabs-1">
                            
                            <div class="left-content">
                                <h2>Form Settings</h2>
                                <?php echo $settingsMessage ; ?>
                                <div id="message" class="updated fade notice_success"></div>
                                <div class="advice_notice"></div>
                                <div class="loadingMessage"></div>
                                
                                <div class="versions_options">
                                   
                                    
                                    <button class="form_version btn tab_button <?php echo ( $cus_version == 'tab' )?'green':'gray'; ?>" value="tab_version">TAB</button> 
                                    <button class="form_version btn custom <?php echo ( $cus_version == 'selectable' )?'green':'gray'; ?>" value="select_version">CUSTOM</button>
                                    
                                    
                                </div>

                                <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_button" class="cus_versionform tab_version <?php echo ( strlen($cus_version) && $cus_version != 'tab')?'hidden':''; ?>" name="cUsCF_button">
                                   
                                    <input type="hidden" class="tab_user" name="tab_user" value="1" />
                                    <input type="hidden" name="cus_version" value="tab" />
                                    <input type="hidden" value="settings" name="option" />
                                    
                                </form>


                                <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_selectable" class="cus_versionform select_version <?php echo ( !strlen($cus_version) || $cus_version == 'tab')?'hidden':''; ?>" name="cUsCF_selectable">
                                    <h3 class="form_title">Page Selection  <a href="post-new.php?post_type=page">Create a new page <span>+</span></a></h3> 
                                    <div class="pageselect_cont">
                                    <?php $mypages = get_pages( array( 'parent' => 0, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) ); 
                                        if( is_array($mypages) ) : 
                                            
                                            $getTabPages = get_option('cUsCF_settings_tabpages');
                                            //print_r($getTabPages);
                                            $getInlinePages = get_option('cUsCF_settings_inlinepages');
                                            //print_r($getInlinePages);
                                            
                                            if(!empty($getTabPages) && in_array('home', $getTabPages)){
                                                $getHomePage         = get_option('cUsCF_HOME_settings');
                                                $home_boolTab        = $getHomePage['tab_user'];
                                                $home_cus_version    = $getHomePage['cus_version'];
                                                $home_form_key       = $getHomePage['form_key'];
                                            }
                                            
                                            ?>
                                        <ul class="selectable_pages">
                                            
                                            <li class="ui-widget-content">
                                                 
                                                <div class="page_title">
                                                    <span class="title">Home Page</span>
                                                    <span class="bullet ui-icon ui-icon-circle-zoomin">
                                                        <a target="_blank" href="<?php echo get_option('home'); ?>" title="Home Preview">&nbsp;</a>
                                                    </span>
                                                </div>

                                                <div class="options home">
                                                    <input type="radio" name="pages[home]" class="home-page" id="pageradio-home" value="tab" <?php echo (is_array($getTabPages) && in_array('home', $getTabPages) || $home_cus_version == 'tab') ? 'checked' : '' ?> />
                                                    <label class="label-home" for="pageradio-home">Tab</label>
                                                    <input type="radio" name="pages[home]" value="inline" id="pageradio-home-2" class="home-page" <?php echo (is_array($getInlinePages) && in_array('home', $getInlinePages) || $home_cus_version == 'inline') ? 'checked' : '' ?> />
                                                    <label class="label-home" for="pageradio-home-2">Inline</label>
                                                    <a class="ui-state-default ui-corner-all pageclear-home" href="javascript:;" title="Clear Home page settings"><label class="ui-icon ui-icon-circle-close">&nbsp;</label></a>
                                                </div>
                                                
                                                <div class="form_template form-templates-home">
                                                    <h4>Pick which form you would like on this page</h4>
                                                    <div class="template_slider slider-home">
                                                        <?php 
                                                        if($cUsCF_API_getFormKeys){
                                                                $cUs_json = json_decode($cUsCF_API_getFormKeys);

                                                                switch ( $cUs_json->status  ) :
                                                                    case 'success':
                                                                        foreach ($cUs_json as $oForms => $oForm) {
                                                                            if ($oForms !='status' && $oForm->form_type == 0){//GET DEFAULT CONTACT FORM KEY ?>
                                                                            <span class="<?php echo (strlen($home_form_key)  && $home_form_key == $oForm->form_key)?'default':'tpl'?> item template-home" rel="<?php echo $oForm->form_key ?>">
                                                                                <img src="https://admin.contactus.com/popup/tpl/<?php echo $oForm->template_desktop_form ?>/scr.png" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                <span class="captions">
                                                                                    <p>
                                                                                        Form Name:<?php echo $oForm->form_name ?><br>
                                                                                        Form Key: <?php echo $oForm->form_key ?>
                                                                                    </p>
                                                                                </span>
                                                                                <span class="def_bak"></span>
                                                                            </span>

                                                                            <? }
                                                                        }
                                                                        break;
                                                                endswitch;
                                                            }
                                                        ?>
                                                    </div>

                                                    <div class="save-options">
                                                        <input type="button" class="btn lightblue small save-page save-page-home" value="Save" />
                                                    </div>
                                                    <div class="save_message save_message_home">
                                                        <p>Sending . . .</p>
                                                    </div>
                                                </div>

                                                <input type="hidden" class="cus_version_home" value="<?php echo $cus_version; ?>" />
                                                <input type="hidden" class="form_key_home" value="<?php echo $form_page_key; ?>" />
                                                
                                            </li>
                                            <script>
                                                jQuery('.pageclear-home').click(function(){

                                                    if(confirm('Do you want to delete your settings in this page?')){
                                                        jQuery('.home-page').removeAttr('checked');
                                                        jQuery('.label-home').removeClass('ui-state-active');

                                                        jQuery('.template-home').removeClass('default');

                                                        jQuery.deletePageSettings('home');

                                                    }

                                                });
                                                jQuery('.home-page').click(function(){
                                                    jQuery('.form_template').fadeOut();
                                                    jQuery('.form-templates-home').slideDown();

                                                    jQuery('.cus_version_home').val( jQuery(this).val() );

                                                });
                                                jQuery('.template-home').click(function(){
                                                    jQuery('.form_key_home').val( jQuery(this).attr('rel') );
                                                    jQuery('.slider-home .item').removeClass('default');
                                                    jQuery(this).addClass('default');
                                                });
                                                jQuery('.save-page-home').click(function(){ 
                                                    var cus_version_home = jQuery('.cus_version_home').val();
                                                    var form_key_home = jQuery('.form_key_home').val();

                                                    var changePage = jQuery.changePageSettings('home', cus_version_home, form_key_home); 

                                                });
                                            </script>
                                                <?php foreach( $mypages as $page ) : 
                                                
                                                    $pageSettings = get_post_meta( $page->ID, 'cUsCF_FormByPage_settings', false );

                                                    if(is_array($pageSettings) && !empty($pageSettings)): //NEW VERSION 3.0

                                                        $cus_version    = $pageSettings[0]['cus_version'];
                                                        $form_page_key  = $pageSettings[0]['form_key'];

                                                    endif;
                                                
                                                ?>
                                            
                                                    <li class="ui-widget-content">
                                                        
                                                        <div class="page_title">
                                                            <span class="title"><?php echo $page->post_title; ?></span>
                                                            <span class="bullet ui-icon ui-icon-circle-zoomin">
                                                                <a target="_blank" href="<?php echo get_permalink( $page->ID ) ;?>" title="Preview <?php echo $page->post_title; ?> page">&nbsp;</a>
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="options">
                                                            <input type="radio" name="pages[<?php echo $page->ID ; ?>]" value="tab" id="pageradio-<?php echo $page->ID ; ?>-1" class="<?php echo $page->ID ; ?>-page" <?php echo (is_array($getTabPages) && in_array($page->ID, $getTabPages) || $cus_version == 'tab')?'checked':'' ?> />
                                                            <label class="label-<?php echo $page->ID ; ?>" for="pageradio-<?php echo $page->ID ; ?>-1">Tab</label>
                                                            <input type="radio" name="pages[<?php echo $page->ID ; ?>]" value="inline" id="pageradio-<?php echo $page->ID ; ?>-2" class="<?php echo $page->ID ; ?>-page" <?php echo (is_array($getInlinePages) && in_array($page->ID, $getInlinePages) || $cus_version == 'inline')?'checked':'' ?> />
                                                            <label class="label-<?php echo $page->ID ; ?>" for="pageradio-<?php echo $page->ID ; ?>-2">Inline</label>
                                                            <a class="ui-state-default ui-corner-all pageclear-<?php echo $page->ID ; ?>" href="javascript:;" title="Clear <?php echo $page->post_title; ?> page settings"><label class="ui-icon ui-icon-circle-close">&nbsp;</label></a>
                                                        </div>
                                                        
                                                        <div class="form_template form-templates-<?php echo $page->ID ; ?>">
                                                            <h4>Pick which form you would like on <?php echo $page->post_title; ?> page</h4>
                                                            <div class="template_slider slider-<?php echo $page->ID ; ?>">
                                                                <?php 
                                                                if($cUsCF_API_getFormKeys){
                                                                        
                                                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);

                                                                        switch ( $cUs_json->status  ) :
                                                                            case 'success':
                                                                                foreach ($cUs_json as $oForms => $oForm) {
                                                                                    if ($oForms !='status' && $oForm->form_type == 0){//GET DEFAULT CONTACT FORM KEY ?>
                                                                                    <span class="<?php echo (strlen($form_page_key) && $form_page_key == $oForm->form_key)?'default':'tpl'?> item template-<?php echo $page->ID ; ?>" rel="<?php echo $oForm->form_key ?>">
                                                                                        <img src="https://admin.contactus.com/popup/tpl/<?php echo $oForm->template_desktop_form ?>/scr.png" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                        <span class="captions">
                                                                                            <p>
                                                                                                Form Name:<?php echo $oForm->form_name ?><br>
                                                                                                Form Key: <?php echo $oForm->form_key ?>
                                                                                            </p>
                                                                                        </span>
                                                                                        <span class="def_bak"></span>
                                                                                    </span>

                                                                                    <? }
                                                                                }
                                                                                break;
                                                                        endswitch;
                                                                    }
                                                                ?>
                                                            </div>
                                                            
                                                            <div class="save-options">
                                                                <input type="button" class="btn lightblue small save-page save-page-<?php echo $page->ID ; ?>" value="Save" />
                                                            </div>
                                                            <div class="save_message save_message_<?php echo $page->ID ; ?>">
                                                                <p>Sending . . .</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <input type="hidden" class="cus_version_<?php echo $page->ID ; ?>" value="<?php echo $cus_version; ?>" />
                                                        <input type="hidden" class="form_key_<?php echo $page->ID ; ?>" value="<?php echo $form_page_key; ?>" />
                                                        
                                                    </li>
                                                    <script>
                                                        jQuery('.pageclear-<?php echo $page->ID ; ?>').click(function(){
                                                            
                                                            if(confirm('Do you want to delete your settings in this page?')){
                                                                jQuery('.<?php echo $page->ID ; ?>-page').removeAttr('checked');
                                                                jQuery('.label-<?php echo $page->ID ; ?>').removeClass('ui-state-active');
                                                                
                                                                jQuery('.template-<?php echo $page->ID ; ?>').removeClass('default');
                                                                
                                                                jQuery.deletePageSettings(<?php echo $page->ID ; ?>);
                                                                
                                                            }
                                                            
                                                        });
                                                        jQuery('.<?php echo $page->ID ; ?>-page').click(function(){
                                                            jQuery('.form_template').fadeOut();
                                                            jQuery('.form-templates-<?php echo $page->ID ; ?>').slideDown();
                                                            
                                                            jQuery('.cus_version_<?php echo $page->ID ; ?>').val( jQuery(this).val() );
                                                            
                                                        });
                                                        jQuery('.template-<?php echo $page->ID ; ?>').click(function(){
                                                            jQuery('.form_key_<?php echo $page->ID ; ?>').val( jQuery(this).attr('rel') );
                                                            jQuery('.slider-<?php echo $page->ID ; ?> .item').removeClass('default');
                                                            jQuery(this).addClass('default');
                                                        });
                                                        jQuery('.save-page-<?php echo $page->ID ; ?>').click(function(){ 
                                                            var cus_version_<?php echo $page->ID ; ?> = jQuery('.cus_version_<?php echo $page->ID ; ?>').val();
                                                            var form_key_<?php echo $page->ID ; ?> = jQuery('.form_key_<?php echo $page->ID ; ?>').val();
                                                            var changePage = jQuery.changePageSettings(<?php echo $page->ID ; ?>, cus_version_<?php echo $page->ID ; ?>, form_key_<?php echo $page->ID ; ?>);
                                                            
                                                        });
                                                    </script>
                                            <?php 
                                                $cus_version = '';
                                                $form_page_key = '';
                                                endforeach; 
                                            ?>
                                        </ul>
                                      
                                        <?php endif; ?>
                                    </div>
                                    <input type="hidden" name="cus_version" value="selectable" />
                                    <input type="hidden" value="settings" name="option" />
                                </form>
                                
                            </div><!-- // TAB LEFT -->
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom?rate=5#postform" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                    <h3>Share the plugin with your friends over <a href="mailto:yourfriend@mail.com?subject=Great new WordPress plugin for contact forms" class="email">email</a></h3>
                                    <h3>Share the plugin on:</h3>
                                    <div class="social_shares">
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://wordpress.org/plugins/contactuscom/'), 
                                                      'facebook-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Facebook<img src="<?php echo plugins_url('style/images/facebook_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://twitter.com/intent/tweet?url=http://bit.ly/1688yva&amp;text=Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form.', 
                                                      'twitter-tweet-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Twitter<img src="<?php echo plugins_url('style/images/twitter_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'http://www.linkedin.com/cws/share?url=http://wordpress.org/plugins/contactuscom/&original_referer=http://wordpress.org/plugins/contactuscom/&token=&isFramed=false&lang=en_US', 
                                                      'linkedin-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >LinkedIn<img src="<?php echo plugins_url('style/images/linkedin_link.png', __FILE__) ;  ?> " /></a>
                                    </div><br/>
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://www.contactus.com/pricing-plans/" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                                
                                 
                            </div><!-- // TAB RIGHT -->
                            
                        </div>
                        
                        <div id="tabs-2">
                            
                                <div class="left-content">
                                    <h2>Change Form/Tab Design</h2>
                                    <div class="versions_options">
                                        
                                        <p>Change your form template, tab template within plugin.</p>

                                        <div class="advice_notice">Advices....</div>

                                        <div class="user_templates">

                                            <?php 
                                            if($cUsCF_API_getFormKeys){
                                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);

                                                    switch ( $cUs_json->status  ) :
                                                        case 'success':
                                                            foreach ($cUs_json as $oForms => $oForm) {
                                                                if ($oForms !='status' && $oForm->form_type == 0){//GET CONTACT FORMS KEY ?>
                                                                  
                                                                <h3>Form Name: <?php echo $oForm->form_name ?> <?php echo ($oForm->default == 1)?' - [ Default ]':''?></h3>
                                                                <?php $formID = $oForms; ?>
                                                                <div>
                                                                    <div class="terminology_c">
                                                                        
                                                                        <div class="template_info">
                                                                            <div class="template-thumb">
                                                                                <p><b>Template Form:</b> <?php echo $oForm->template_desktop_form ?></p>
                                                                                <span class="thumb"><img src="https://admin.contactus.com/popup/tpl/<?php echo $oForm->template_desktop_form ?>/scr.png" class="form_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" /></span>

                                                                                <p><b>Template Tab:</b> <?php echo $oForm->template_desktop_tab ?></p>
                                                                                <img src="https://admin.contactus.com/popup/tpl/<?php echo $oForm->template_desktop_tab ?>/scr.png" class="tab_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                            </div>
                                                                            <div class="template-desc">
                                                                                <p><b>Form Key:</b> <?php echo $oForm->form_key ?></p>
                                                                                <p><b>Website URL:</b> <?php echo $oForm->website_url ?></p>
                                                                                <p><b>Template Mobile Form:</b> <?php echo $oForm->template_mobile_form ?></p>
                                                                                <!-- p><b>Inline Shortcode :</b> <br /> <code>[show-contactus.com-form formkey="<?php echo $oForm->form_key ?>" version="inline"]</code></p>
                                                                                <p><b>Tab Shortcode :</b> <br /><code>[show-contactus.com-form formkey="<?php echo $oForm->form_key ?>" version="tab"] </code><br /> <br />(You can add inline or tab version form in posts, pages editor)</p -->
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="template-selection">
                                                                            
                                                                                                            
                                                                                <div class="form_templates_aCc">

                                                                                    <h4>Change Your Form Template here</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form">
                                                                                            
                                                                                            <?php

                                                                                            $contacFormTemplates = $cUsCF_api->getTemplatesAndTabsAllowed('0', 'Template_Desktop_Form', $cUs_API_Account, $cUs_API_Key);
                                                                                            $contacFormTemplates = json_decode($contacFormTemplates);
                                                                                            $contacFormTemplates = $contacFormTemplates->data;

                                                                                            if(!empty($contacFormTemplates)) : 
                                                                                            ?>
                                                                                            
                                                                                                <div class="template_slider slider-<?php echo $formID; ?>">
                                                                                                    
                                                                                                    <?php foreach ($contacFormTemplates as $formTpl) : ?> 
                                                                                                        
                                                                                                        <span class="<?php echo (strlen($oForm->template_desktop_form) && $oForm->template_desktop_form == $formTpl->id)?'default':'tpl'?> item template-<?php echo $formTpl->id ; ?>" rel="<?php echo $formTpl->id ?>">
                                                                                                            <img src="<?php echo $formTpl->thumbnail ?>"  alt="<?php echo $formTpl->name ?>" title="Form Name:<?php echo $formTpl->name ?>" />
                                                                                                            <span class="captions">
                                                                                                                <p>
                                                                                                                    Form Name:<?php echo $formTpl->name ?><br>
                                                                                                                </p>
                                                                                                            </span>
                                                                                                            <span class="def_bak"></span>
                                                                                                        </span>
                                                                                                    
                                                                                                    <?php
                                                                                                    
                                                                                                    endforeach; ?>
                                                                                                    
                                                                                                </div>
                                                                                            
                                                                                            <?php endif; ?>
                                                                                            
                                                                                            <div class="save_message save_message_<?php echo $formID ; ?>">
                                                                                                <p>Sending . . .</p>
                                                                                            </div>
                                                                                            
                                                                                        </div>
                                                                                        
                                                                                        <input class="btn lightblue sendtemplate save-formtemplate-<?php echo $formID;?>" value="Save Form Template" type="button" />
                                                                                        
                                                                                        <input type="hidden" value="<?php echo $oForm->template_desktop_form ?>" name="id-formtemplate-<?php echo $formID;?>" id="id_formtemplate_<?php echo $formID;?>" />
                                                                                        <input type="hidden" value="<?php echo $oForm->form_key ?>" name="id-formkey-<?php echo $formID;?>" id="id_formkey_<?php echo $formID;?>" />
                                                                                        
                                                                                        <script>
                                                                                            
                                                                                            jQuery('.slider-<?php echo $formID; ?> > .item').click(function(){
                                                                                                
                                                                                                jQuery('#id_formtemplate_<?php echo $formID;?>').val( jQuery(this).attr('rel') );
                                                                                                jQuery('.slider-<?php echo $formID;?> > .item').removeClass('default');
                                                                                                
                                                                                                jQuery(this).addClass('default');
                                                                                                
                                                                                            });
                                                                                            
                                                                                            
                                                                                            jQuery('.save-formtemplate-<?php echo $formID;?>').click(function(){ 
                                                                                                
                                                                                                var id_formtemplate_<?php echo $formID;?> = jQuery('#id_formtemplate_<?php echo $formID;?>').val();
                                                                                                var id_formkey_<?php echo $formID;?> = jQuery('#id_formkey_<?php echo $formID;?>').val();

                                                                                                var changeTemplate = jQuery.changeFormTemplate(<?php echo $formID;?>, id_formkey_<?php echo $formID;?>, id_formtemplate_<?php echo $formID;?>);

                                                                                            });
                                                                                            
                                                                                        </script>
                                                                                        
                                                                                    </div>
                                                                                    
                                                                                    
                                                                                    <h4>Change your Tab Template here</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form">
                                                                                            
                                                                                            <?php

                                                                                            $contacFormTabTemplates = $cUsCF_api->getTemplatesAndTabsAllowed('0', 'Template_Desktop_Tab', $cUs_API_Account, $cUs_API_Key);
                                                                                            $contacFormTabTemplates = json_decode($contacFormTabTemplates);
                                                                                            $contacFormTabTemplates = $contacFormTabTemplates->data;

                                                                                            if(!empty($contacFormTabTemplates)): ?>
                                                                                                
                                                                                                <div class="template_slider tabslider-<?php echo $formID; ?>">
                                                                                                    <?php foreach ($contacFormTabTemplates as $formTpl) : ?> 
                                                                                                        
                                                                                                        <span class="<?php echo (strlen($oForm->template_desktop_tab) && $oForm->template_desktop_tab == $formTpl->id)?'default':'tpl'?> item template-<?php echo $formTpl->id ; ?>" rel="<?php echo $formTpl->id ?>">
                                                                                                            <img src="<?php echo $formTpl->thumbnail ?>"  alt="<?php echo $formTpl->name ?>" title="Tab Name:<?php echo $formTpl->name ?>" />
                                                                                                            <span class="captions">
                                                                                                                <p>
                                                                                                                    Tab Name:<?php echo $formTpl->name ?><br>
                                                                                                                </p>
                                                                                                            </span>
                                                                                                            <span class="def_bak"></span>
                                                                                                        </span>
                                                                                                    
                                                                                                    <?php 
                                                                                                    endforeach; ?>
                                                                                                </div>
                                                                                            
                                                                                            <?php endif; ?>
                                                                                            
                                                                                            
                                                                                            <div class="save_message save_tab_message_<?php echo $formID ; ?>">
                                                                                                <p>Sending . . .</p>
                                                                                            </div>
                                                                                            
                                                                                        </div>
                                                                                        
                                                                                        <input class="btn lightblue sendtemplate save-tabtemplate-<?php echo $formID;?>" value="Save Tab" type="button" />
                                                                                        
                                                                                        <input type="hidden" value="<?php echo $oForm->template_desktop_tab ?>" name="id-tabtemplate-<?php echo $formID;?>" id="id_tabtemplate_<?php echo $formID;?>" />
                                                                                        <input type="hidden" value="<?php echo $oForm->form_key ?>" name="id-tabkey-<?php echo $formID;?>" id="id_tabkey_<?php echo $formID;?>" />
                                                                                        
                                                                                        <script>
                                                                                            
                                                                                            jQuery('.tabslider-<?php echo $formID; ?> > .item').click(function(){
                                                                                                
                                                                                                jQuery('#id_tabtemplate_<?php echo $formID;?>').val( jQuery(this).attr('rel') );
                                                                                                jQuery('.tabslider-<?php echo $formID;?> > .item').removeClass('default');
                                                                                                
                                                                                                jQuery(this).addClass('default');
                                                                                            });
                                                                                            
                                                                                            
                                                                                            jQuery('.save-tabtemplate-<?php echo $formID;?>').click(function(){
                                                                                                
                                                                                                var id_tabtemplate_<?php echo $formID;?> = jQuery('#id_tabtemplate_<?php echo $formID;?>').val();
                                                                                                var id_tabkey_<?php echo $formID;?> = jQuery('#id_tabkey_<?php echo $formID;?>').val();

                                                                                               jQuery.changeTabTemplate(<?php echo $formID;?>, id_tabkey_<?php echo $formID;?>, id_tabtemplate_<?php echo $formID;?>);

                                                                                            });
                                                                                            
                                                                                        </script>
                                                                                        
                                                                                    </div>

                                                                                </div> 
                                                                                    
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>

                                                                <? }
                                                            }
                                                            break;
                                                    endswitch;
                                                }
                                            ?>


                                        </div>

                                    
                                        <div class="advice_notice">Advices....</div>
                                    </div>
                                </div>
                            
                                <div class="right-content">
                                    <div class="upgrade_features">

                                        <h3 class="review">Give a 5 stars review on </h3>
                                        <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom?rate=5#postform" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                        <h3>Share the plugin with your friends over <a href="mailto:yourfriend@mail.com?subject=Great new WordPress plugin for contact forms" class="email">email</a></h3>
                                        <h3>Share the plugin on:</h3>
                                        <div class="social_shares">
                                            <a href="javascript:;"
                                               onclick="
                                                        window.open(
                                                          'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://wordpress.org/plugins/contactuscom/'), 
                                                          'facebook-share-dialog', 
                                                          'width=626,height=436'); 
                                                        return false;"
                                               >Facebook<img src="<?php echo plugins_url('style/images/facebook_link.png', __FILE__) ;  ?> " /></a>
                                            <a href="javascript:;"
                                               onclick="
                                                        window.open(
                                                          'https://twitter.com/intent/tweet?url=http://bit.ly/1688yva&amp;text=Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form.', 
                                                          'twitter-tweet-dialog', 
                                                          'width=626,height=436'); 
                                                        return false;"
                                               >Twitter<img src="<?php echo plugins_url('style/images/twitter_link.png', __FILE__) ;  ?> " /></a>
                                            <a href="javascript:;"
                                               onclick="
                                                        window.open(
                                                          'http://www.linkedin.com/cws/share?url=http://wordpress.org/plugins/contactuscom/&original_referer=http://wordpress.org/plugins/contactuscom/&token=&isFramed=false&lang=en_US', 
                                                          'linkedin-share-dialog', 
                                                          'width=626,height=436'); 
                                                        return false;"
                                               >LinkedIn<img src="<?php echo plugins_url('style/images/linkedin_link.png', __FILE__) ;  ?> " /></a>
                                        </div><br/>
                                        <h3>Discover our great features</h3>
                                        <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>

                                        <a href="http://www.contactus.com/pricing-plans/" target="_blank" class="btn large orange">Upgrade Your Account</a>

                                    </div>
                                </div><!-- // TAB RIGHT -->
                            
                        
                        </div>
                        
                        <div id="tabs-3">
                            <div class="left-content">
                                
                                <h2>ADVANCED ONLY!</h2>
                                <div>
                                    <div class="terminology_c">
                                        <h4>Copy this code into your template, post, page to place the form wherever you want it.  If you use this advanced method, do not select any pages from the page section on the form settings or you may end up with the form displayed on your page twice.</h4>
                                        <hr/>
                                        <ul class="hints">
                                            <li><b>Inline</b>
                                                <br/>WP Shortcode: <code> [show-contactus.com-form formkey="FORM KEY HERE" version="inline"] </code>
                                                <br/>Php Snippet:<code>&#60;&#63;php echo do_shortcode("[show-contactus.com-form formkey="FORM KEY HERE" version="inline"]"); &#63;&#62;</code>
                                            </li>
                                            <li><b>Tab</b>
                                                <br/>WP Shortcode:<code> [show-contactus.com-form formkey="FORM KEY HERE" version="tab"] </code>
                                                <br/>Php Snippet:<code>&#60;&#63;php echo do_shortcode("[show-contactus.com-form formkey="FORM KEY HERE" version="tab"]"); &#63;&#62;</code>
                                            </li>
                                            <li><b>Widget Tool</b><br/><p>Go to <a href="widgets.php"><b>Widgets here </b></a> and drag the ContactUs.com widget into one of your widget areas</p></li>
                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom?rate=5#postform" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                    <h3>Share the plugin with your friends over <a href="mailto:yourfriend@mail.com?subject=Great new WordPress plugin for contact forms" class="email">email</a></h3>
                                    <h3>Share the plugin on:</h3>
                                    <div class="social_shares">
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://wordpress.org/plugins/contactuscom/'), 
                                                      'facebook-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Facebook<img src="<?php echo plugins_url('style/images/facebook_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://twitter.com/intent/tweet?url=http://bit.ly/1688yva&amp;text=Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form.', 
                                                      'twitter-tweet-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Twitter<img src="<?php echo plugins_url('style/images/twitter_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'http://www.linkedin.com/cws/share?url=http://wordpress.org/plugins/contactuscom/&original_referer=http://wordpress.org/plugins/contactuscom/&token=&isFramed=false&lang=en_US', 
                                                      'linkedin-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >LinkedIn<img src="<?php echo plugins_url('style/images/linkedin_link.png', __FILE__) ;  ?> " /></a>
                                    </div><br/>
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://www.contactus.com/pricing-plans/" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->
                            
                        </div>
                        <div id="tabs-4">
                            <div class="left-content">
                                <h2>Documentation</h2>
                                
                                <div class="iRecomend">
                                    
                                    
                                    <h3>Helpful Hints</h3>
                                    
                                    <ul class="hints">
                                        <li>Take a moment to log into ContactUs.com (with the user name/password you registered with) to see the full set of solutions offered.</li>
                                        <li>You can also generate leads and newsletter signups from your Facebook page by enabling the ContactUs.com Facebook App.  It only takes two clicks!</li>
                                    </ul>
                                    <hr />
                                    <h3>Important recommendation:</h3>
                                    <p> Your default theme must have the <b>"wp_footer()"</b> function added.</p>
                                    
                                </div>
                                
                               
                            </div>
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom?rate=5#postform" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                    <h3>Share the plugin with your friends over <a href="mailto:yourfriend@mail.com?subject=Great new WordPress plugin for contact forms" class="email">email</a></h3>
                                    <h3>Share the plugin on:</h3>
                                    <div class="social_shares">
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://wordpress.org/plugins/contactuscom/'), 
                                                      'facebook-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Facebook<img src="<?php echo plugins_url('style/images/facebook_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://twitter.com/intent/tweet?url=http://bit.ly/1688yva&amp;text=Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form.', 
                                                      'twitter-tweet-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Twitter<img src="<?php echo plugins_url('style/images/twitter_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'http://www.linkedin.com/cws/share?url=http://wordpress.org/plugins/contactuscom/&original_referer=http://wordpress.org/plugins/contactuscom/&token=&isFramed=false&lang=en_US', 
                                                      'linkedin-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >LinkedIn<img src="<?php echo plugins_url('style/images/linkedin_link.png', __FILE__) ;  ?> " /></a>
                                    </div><br/>
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://www.contactus.com/pricing-plans/" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->
                            
                        </div>
                        
                        
                        <div id="tabs-5">
                            
                            <div class="left-content">
                                <h2>Your ContactUs.com Account</h2>
                                
                                <div class="iRecomend">
                                    <form method="post" action="admin.php?page=cUs_malchimp_plugin" id="cUsMC_data" name="cUsMC_sendkey" class="steps" onsubmit="return false;">
                                        
                                        <table class="form-table">
                                            <tr>
                                                <th><label class="labelform">Names</label><br>
                                                <td><span class="cus_names"><?php echo $current_user->first_name;?> <?php echo $current_user->last_name;?></span></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform">Email</label><br>
                                                <td><span class="cus_email"><?php echo $options['email'];?></span></td>
                                            </tr>

                                            <tr><th></th>
                                                <td>
                                                    <hr/>
                                                    <input id="logoutbtn" class="btn orange cUsCF_LogoutUser" value="Unlink Account" type="button">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                
                            </div>
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom?rate=5#postform" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                    <h3>Share the plugin with your friends over <a href="mailto:yourfriend@mail.com?subject=Great new WordPress plugin for contact forms" class="email">email</a></h3>
                                    <h3>Share the plugin on:</h3>
                                    <div class="social_shares">
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://wordpress.org/plugins/contactuscom/'), 
                                                      'facebook-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Facebook<img src="<?php echo plugins_url('style/images/facebook_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://twitter.com/intent/tweet?url=http://bit.ly/1688yva&amp;text=Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form.', 
                                                      'twitter-tweet-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Twitter<img src="<?php echo plugins_url('style/images/twitter_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'http://www.linkedin.com/cws/share?url=http://wordpress.org/plugins/contactuscom/&original_referer=http://wordpress.org/plugins/contactuscom/&token=&isFramed=false&lang=en_US', 
                                                      'linkedin-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >LinkedIn<img src="<?php echo plugins_url('style/images/linkedin_link.png', __FILE__) ;  ?> " /></a>
                                    </div><br/>
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://www.contactus.com/pricing-plans/" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->
                            
                        </div>
                        <?php }else{ ?>
                        <div id="tabs-1">
                            
                            <div class="left-content">
                                
                                <h3>Note:</h3>
                                <p>Hi ContactUs users, welcome to your V3.0 Contact Form Plugin!, in order for the our new cool upgrades to work, we need to sign in to your ContactUs account here. This is a one time thing, after up-grade set up, we wont ask this again.</p>
                               

                                <div id="cUsCF_settingss">

                                    <div class="loadingMessage"></div>
                                    <div class="advice_notice">Advices....</div>
                                    <div class="notice">Ok....</div>

                                    <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_loginform" name="cUsCF_loginform" class="steps login_form" onsubmit="return false;">
                                        <h3>ContactUs.com Login</h3>

                                        <table class="form-table">

                                            <tr>
                                                <th><label class="labelform" for="login_email">Email</label><br>
                                                <td><input class="inputform" name="cUsCF_settings[login_email]" id="login_email" type="text"></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="user_pass">Password</label></th>
                                                <td><input class="inputform" name="cUsCF_settings[user_pass]" id="user_pass" type="password"></td>
                                            </tr>
                                            <tr><th></th>
                                                <td>
                                                    <input id="loginbtn" class="btn lightblue cUsCF_LoginUser" value="Login" type="submit">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <td>
                                                    <a href="https://www.contactus.com/client-login.php" target="_blank">I forgot my password</a>
                                                </td>
                                            </tr>

                                        </table>
                                    </form>
                                </div>
                                
                          </div>
                            
                          <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://wordpress.org/support/view/plugin-reviews/contactuscom?rate=5#postform" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                    <h3>Share the plugin with your friends over <a href="mailto:yourfriend@mail.com?subject=Great new WordPress plugin for contact forms" class="email">email</a></h3>
                                    <h3>Share the plugin on:</h3>
                                    <div class="social_shares">
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://wordpress.org/plugins/contactuscom/'), 
                                                      'facebook-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Facebook<img src="<?php echo plugins_url('style/images/facebook_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'https://twitter.com/intent/tweet?url=http://bit.ly/1688yva&amp;text=Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form.', 
                                                      'twitter-tweet-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >Twitter<img src="<?php echo plugins_url('style/images/twitter_link.png', __FILE__) ;  ?> " /></a>
                                        <a href="javascript:;"
                                           onclick="
                                                    window.open(
                                                      'http://www.linkedin.com/cws/share?url=http://wordpress.org/plugins/contactuscom/&original_referer=http://wordpress.org/plugins/contactuscom/&token=&isFramed=false&lang=en_US', 
                                                      'linkedin-share-dialog', 
                                                      'width=626,height=436'); 
                                                    return false;"
                                           >LinkedIn<img src="<?php echo plugins_url('style/images/linkedin_link.png', __FILE__) ;  ?> " /></a>
                                    </div><br/>
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://www.contactus.com/pricing-plans/" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->  
                            
                        </div>
                        <?php } //USERS CREDENTIALS UPDATE ?>
                        
                    <?php endif; ?>

            </div>
        </div>

        <?php
    }

}

?>