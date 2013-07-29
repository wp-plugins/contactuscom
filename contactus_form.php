<?php
/*
  Plugin Name: Contact Form by ContactUs.com
  Version: 2.5.3
  Plugin URI:  http://help.contactus.com/entries/23229688-Adding-the-ContactUs-com-Plugin-for-WordPress
  Description: Contact Form by ContactUs.com Plugin for Wordpress.
  Author: contactus.com
  Author URI: http://contactus.com/
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

if (!function_exists('cUs_admin_header')) {

    function cUs_admin_header() {
        global $current_screen;
        if ($current_screen->id == 'toplevel_page_cUs_form_plugin') {

            wp_register_script('fancybox', plugins_url('scripts/fancybox/jquery.fancybox.pack.js', __FILE__), array(), '2.0.0', true);
            wp_register_script('cUs_Scripts', plugins_url('scripts/cUs_scripts.js?pluginurl=' . dirname(__FILE__), __FILE__), array(), '2.0.0', true);
            wp_register_script('sharethis', 'http://w.sharethis.com/button/buttons.js', array(), '1.0', true);
            wp_enqueue_style('cUs_Styles', plugins_url('style/cUs_style.css', __FILE__), false, '1');
            wp_enqueue_style('fancybox', plugins_url('scripts/fancybox/jquery.fancybox.css', __FILE__), false, '1');

            wp_enqueue_script('jquery'); //JQUERY WP CORE
            
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-button');
            wp_enqueue_script('jquery-ui-selectable');
            wp_enqueue_script('fancybox');
            wp_enqueue_script('cUs_Scripts');
            wp_enqueue_script('sharethis');
        }
    }

}
add_action('admin_enqueue_scripts', 'cUs_admin_header'); // cUs_admin_header hook
// Add option page in admin menu
if (!function_exists('cUs_admin_menu')) {

    function cUs_admin_menu() {
        add_menu_page('Contact Form by ContactUs.com ', 'Contact Form', 'edit_themes', 'cUs_form_plugin', 'cUs_menu_render', plugins_url("style/images/Icon-Small_16.png", __FILE__));
    }

}
add_action('admin_menu', 'cUs_admin_menu'); // cUs_admin_menu hook

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
add_action('admin_init', 'contactus_register_settings');

function contactus_register_settings() {
    return false;
}

function contactus_settings_validate($args) {

    //make sure you return the args
    return $args;
}

//Display the validation errors and update messages

/*
 * Admin notices
 */

function contactus_admin_notices() {
    settings_errors();
}

add_action('admin_notices', 'contactus_admin_notices');

function contactUs_JS_into_head() {
    if (!is_admin()) {
        $options = get_option('contactus_settings');
        $getTabPages = get_option('contactus_settings_tabpages');
        $userCode = stripslashes($options['javascript_usercode']);

        $boolTab = $options['tab_user'];
        $cus_version = $options['cus_version'];
        $form_key = $options['form_key'];
        $pageID = get_the_ID();
        $userJScode = '<script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/contactus.js"></script>';

        //the theme must have the wp_head() function included
        //include the contactUs.com JS file into the <head> section
        switch ($cus_version) {
            case 'tab':
                if (strlen($form_key) && $boolTab):
                    echo $userJScode;
                endif;
                break;
            case 'selectable':
                if (strlen($form_key) && is_array($getTabPages) && in_array($pageID, $getTabPages)):
                    echo $userJScode;
                elseif (is_home() && in_array('home', $getTabPages)):
                    echo $userJScode;
                endif;
                break;
            default :
                if (strlen($userCode) && $boolTab):
                    echo $userCode;
                endif;
                break;
        }
    }
}

add_action('wp_footer', 'contactUs_JS_into_head');

function cus_shortcode_cleaner() {
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

add_shortcode("show-contactus.com-form", "cus_shortcode_handler"); //[show-contactus.com-form]

function cus_shortcode_handler() {

    $options = get_option('contactus_settings');

    $cus_version = $options['cus_version'];
    $form_key = $options['form_key'];
    if ($cus_version == 'inline' || $cus_version == 'selectable') :
        $inlineJS_output = '<div style="min-height: 500px; width: 100%;clear:both;"><script type="text/javascript" src="//cdn.contactus.com/cdn/forms/' . $form_key . '/inline.js"></script></div>';
    else:
        $inlineJS_output = '';
    endif;

    return $inlineJS_output;
}

function cus_shortcode_add($inline_req_page_id) {
    $oPage = get_page($inline_req_page_id);
    $pageContent = $oPage->post_content;
    $pageContent = $pageContent . "\n[show-contactus.com-form]";
    $aryPage = array();
    $aryPage['ID'] = $inline_req_page_id;
    $aryPage['post_content'] = $pageContent;
    return wp_update_post($aryPage);
}

//CONTACTUS.COM ADD FORM TO PLUGIN PAGE
if (!function_exists('cUs_menu_render')) {

    function cUs_menu_render() {

        //delete_option( 'contactus_settings' );

        $options = get_option('contactus_settings'); //get the values, wont work the first time

        $plugins_url = plugins_url();

        if (!is_array($options)) {
            settings_fields('contactus_settings');
            do_settings_sections(__FILE__);
        }

        if (isset($_REQUEST['option'])):
            switch ($_REQUEST['option']):
                case 'login'://LOGIN
                    $cUs_email = $_REQUEST['contactus_settings']['login_email'];
                    $cUs_pass = $_REQUEST['contactus_settings']['user_pass'];

                    $cusAPIresult = getFormKeyAPI($cUs_email, $cUs_pass); //api hook

                    if ($cusAPIresult === FALSE) :
                        $loginMessage = '<div class="error"><p>You haven\'t logged in to your ContactUs.com account, please login below, if you don\'t have an account <a href="#" id="create-user">Get one free!</a></p></div>';
                        $userStatus = 'inactive';
                    else :

                        $cUs_json = json_decode($cusAPIresult);
                        switch ($cUs_json->status) :

                            case 'success':
                                $loginMessage = '<div id="message" class="updated fade"><p>You have successfully logged in.</p></div>';
                                $userStatus = 'active';
                                $_REQUEST['contactus_settings']['tab_user'] = 1;
                                $_REQUEST['contactus_settings']['cus_version'] = 'tab';
                                $_REQUEST['contactus_settings']['user_status'] = $userStatus;
                                $_REQUEST['contactus_settings']['form_key'] = $cUs_json->form_key;
                                update_option('contactus_settings', $_REQUEST['contactus_settings']);
                                ?>

                                <script>jQuery(document).ready(function($) { setTimeout(function(){ try{  jQuery( "#cUs_tabs" ).tabs({ active: 1 })  }catch(err){console.log(err);}  } ,1500)   });</script><?php
                                $options = get_option('contactus_settings'); //GET THE NEW OPTIONS
                                $userCode = $options['javascript_usercode'];
                                $boolTab = $options['tab_user'];
                                $cUs_email = $options['login_email'];
                                $cus_version = $options['cus_version'];
                                $cUs_pass = $options['user_pass'];
                                $inline_page_id = $options['inline_page_id'];

                                break;

                            case 'error':
                                $loginMessage = '<div class="error">
                                                    <p>Ouch! unfortunately there has being an error during the application: <b>"' . $cUs_json->error . '"</b>.</p>
                                                    <p>If you have just signed up, please make sure to <i>check your email and activate your account</i> before trying this step again.</p>
                                                    <p>If you have already done that and you are still getting this error, you can also  
                                                       <a href="https://www.contactus.com/client-login.php" target="_blank">click here</a> and reset your password.
                                                    </p>
                                                 </div>';
                                $userStatus = 'inactive';
                                delete_option('contactus_settings');
                                break;

                        endswitch;

                    endif;
                    break;

                case 'signup': //SIGNUP 
                    ?>
                    <script>jQuery(document).ready(function($) { try{  jQuery( "#cUs_tabs" ).tabs({ active: 0 })  }catch(err){console.log(err);} });</script><?php
                    $cusAPIresult = createCustomer($_POST);
                    $userStatus = 'inactive';

                    $cUs_email = $_POST['remail'];
                    if ($cusAPIresult) :
                        $cUs_json = json_decode($cusAPIresult);

                        switch ($cUs_json->status) :

                            case 'success':
                                $signupMessage = '<div id="message" class="updated fade">
                                                        <p>Welcome to ContactUs.com, and thank you for your registration.</p>
                                                        <p>We have sent your temporary password to your email account <b>"' . $cUs_email . '"</b>. Please find the email, and feel free to login into your Contactus.com account.</p>
                                                  </div>';

                                $_REQUEST['contactus_settings']['tab_user'] = 1;
                                $_REQUEST['contactus_settings']['cus_version'] = 'tab';
                                $_REQUEST['contactus_settings']['user_autoactive'] = 1;
                                $_REQUEST['contactus_settings']['user_status'] = $userStatus;
                                $_REQUEST['contactus_settings']['form_key'] = $cUs_json->form_key;
                                $_REQUEST['contactus_settings']['login_email'] = $cUs_email;
                                update_option('contactus_settings', $_REQUEST['contactus_settings']);
                                ?>
                                <script>jQuery(document).ready(function($) { 
                                    jQuery( "#cUs_registform" ).hide() });
                                                                                                                                        
                                setTimeout(function(){
                                    location.href = 'admin.php?page=cUs_form_plugin';
                                },4000);
                                                                                                                                        
                                </script><?php
                                break;

                            case 'error':
                                $signupMessage = '<div class="error"><p>Ouch! unfortunately there has being an error during the application: <b>"' . $cUs_json->error[0] . '"</b>. Please try again!</a></p></div>';
                                break;

                        endswitch;
                    else:
                        $signupMessage = '<div class="error"><p>Ouch! unfortunately there has being an error during the application: <b>"Connection Refused"</b>. Please try again!</a></p></div>';
                    endif;
                    break;

                case 'settings': //SAVING FORM SETTINGS TAB - INLINE - SELECTION 
                    ?>
                    <script>jQuery(document).ready(function($) { try{  jQuery( "#cUs_tabs" ).tabs({ active: 1 })  }catch(err){console.log(err);} });</script><?php
                    if (is_array($options)): //ALREADY LOGGED
                        $loginMessage = '<div id="message" class="updated fade"><p>You are already connected with your contactUs.com Account.</p></div>';
                        $settingsMessage = '<div id="message" class="updated fade"><p>Done! Your configuration has been saved correctly.</p></div>';
                        $userStatus = 'active';
                        $inline_req_page_id = $_REQUEST['inline_page_id'];

                        $cUs_email = $options['login_email'];
                        $cUs_pass = $options['user_pass'];
                        $form_key = $options['form_key'];
                        $boolTab = $_REQUEST['tab_user'];
                        $cus_version = $options['cus_version'];

                        $aryOptions = array(
                            'form_key' => $form_key,
                            'tab_user' => $boolTab,
                            'cus_version' => $_REQUEST['cus_version'],
                            'inline_page_id' => $inline_req_page_id,
                            'login_email' => $cUs_email,
                            'user_pass' => $cUs_pass
                        );

                        delete_option('contactus_settings');
                        delete_option('contactus_settings_inlinepages');
                        delete_option('contactus_settings_tabpages');

                        update_option('contactus_settings', $aryOptions); //UPDATE OPTIONS
                        cus_shortcode_cleaner();
                        $options = get_option('contactus_settings'); //GET THE NEW OPTIONS
                        $inline_page_id = $options['inline_page_id'];
                        $cus_version = $options['cus_version'];

                        switch ($_REQUEST['cus_version']):
                            case 'inline':
                                cus_shortcode_add($inline_req_page_id);
                                break;
                            case 'selectable':
                                if (isset($_REQUEST['pages'])):
                                    $aryPages = $_REQUEST['pages'];
                                    $aryInlinePages = array();
                                    $aryTabPages = array();
                                    foreach ($aryPages as $pageID => $version) {
                                        if ($version == 'inline') {
                                            $aryInlinePages[] = $pageID;
                                            cus_shortcode_add($pageID);
                                        } elseif ($version == 'tab') {
                                            $aryTabPages[] = $pageID;
                                        }
                                    }
                                    update_option('contactus_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
                                    update_option('contactus_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
                                endif;
                                break;
                        endswitch;

                    endif;
                    break;

                case 'logout': //LOGOUT
                    $userStatus = 'inactive';
                    delete_option('contactus_settings');
                    delete_option('contactus_settings_welcome');
                    cus_shortcode_cleaner();
                    $loginMessage = '<div class="error"><p>You haven\'t logged in to your ContactUs.com account, please login below, if you don\'t have an account <a href="#" id="create-user">get one free!</a></p></div>';
                    break;

            endswitch; elseif (is_array($options)): //ALREADY LOGGED 
            ?>
            <script>jQuery(document).ready(function($) { try{  jQuery( "#cUs_tabs" ).tabs({ active: 1 })  }catch(err){console.log(err);} });</script><?php
            $userCode = $options['javascript_usercode'];
            $userAutoactive = $options['user_autoactive'];
            $boolTab = $options['tab_user'];
            $cUs_email = $options['login_email'];
            $cus_version = $options['cus_version'];
            $cUs_pass = $options['user_pass'];
            $inline_page_id = $options['inline_page_id'];

            if ($userAutoactive == 1) :
                $userStatus = 'active';
                $loginMessage = '<div id="message" class="updated fade"><p>You are already connected with your contactUs.com Account.</p></div>';
            else:
                $cusAPIresult = getFormKeyAPI($cUs_email, $cUs_pass);

                $cUs_json = json_decode($cusAPIresult);
                switch ($cUs_json->status) :

                    case 'success':
                        $loginMessage = '<div id="message" class="updated fade"><p>You are already connected with your contactUs.com Account.</p></div>';
                        $userStatus = 'active';
                        break;

                    case 'error': //USER NOT ACTIVE OR USER ERROR
                        $loginMessage = '<div class="error"><p>Ouch! unfortunately there has being an error during the application: ' . $cUs_json->error . '.<br/>If you have just signed up, please make sure to check your email and activate your account before trying this step again.<br/>If you have already done that and you are still getting this error, you can also  <a href="https://www.contactus.com/client-login.php" target="_blank">click here</a> and reset your password.!</p></div>';
                        $userStatus = 'inactive';
                        //delete_option( 'contactus_settings' );
                        break;

                endswitch;

            endif;



            if (strlen($userCode)):
                $settingsMessage = '<div class="error" ><p>Please, <a href="javascript:;" class="tologin">update</a> your user account to save your settings.</p></div>';
            endif;

        elseif (!is_array($options))://NOT LOGGED
            $userStatus = 'inactive';
            $cus_version = 'tab';
            $boolTab = 1;
            delete_option('contactus_settings');
            $loginMessage = '<div class="error"><p>You haven\'t logged in to your ContactUs.com account, please login below.<br/><br/>If you don\'t have an account <a href="#" id="create-user">get one free!</a></p></div>';
            $settingsMessage = '<div class="error" ><p>Please, <a href="javascript:;" class="tologin">update</a> your user account to be able to save your settings.</p></div>';
        endif;
        ?>
        <div class="plugin_wrap">
            <div class="cUsMC_header">
                <h2>Contact Form <a href="http://www.contactus.com" target="_blank">by ContactUs.com</a> </h2>
            </div> 

            <div class="cUsMC_formset">
                <div id="cUs_tabs">
                    <ul>
                        <?php if ($userStatus == 'inactive'): ?><li><a href="#tabs-1">Configure</a></li><?php endif; ?>
                        <li><a href="#tabs-2"><?php echo ($userStatus == 'active') ? 'Your ContactUs.com Account' : 'Login'; ?></a></li>
                        <li><a href="#tabs-3">Form Settings</a></li>
                        <li><a href="#tabs-4">More About ContactUs.com</a></li>
                    </ul>

                    <?php
                    global $current_user;
                    get_currentuserinfo();
                    if ($userStatus == 'inactive'):
                        ?>
                        <div id="tabs-1">

                            <div id="cUsMC_mcsettings">
                                <?php if ($userStatus == 'inactive'): ?><p class="sub-title">Configure your plugin below and we will send you a confirmation email to verify that our emails get to you.  This is the email your submissions will be sent to once you’re live.</p><?php endif; ?> 

                                <h2>Configure your plugin below.</h2>
                                <?php echo $signupMessage; ?>
                                <form method="post" action="admin.php?page=cUs_form_plugin" id="cUs_registform" name="cUs_registform">
                                    <table class="form-table">
                                        <tr>
                                            <th></th><td><p class="validateTips">Form fields required [ * ]</p></td>
                                        </tr>
                                        <tr>
                                            <th><label class="labelform" for="fname">* First Name</label></th>
                                            <td><input type="text" class="inputform text" placeholder="First Name" name="fname" id="fname" value="<?php echo (isset($_POST['fname']) && strlen($_POST['fname'])) ? $_POST['fname'] : $current_user->user_firstname; ?>" /></td>
                                        </tr>
                                        <tr>
                                            <th><label class="labelform" for="lname">* Last Name</label></th>
                                            <td><input type="text" class="inputform text" placeholder="Last Name" name="lname" id="lname" value="<?php echo (isset($_POST['lname']) && strlen($_POST['lname'])) ? $_POST['lname'] : $current_user->user_lastname; ?>"/></td>
                                        </tr>
                                        <tr>
                                            <th><label class="labelform" for="remail">* Email</label></th>
                                            <td><input type="text" class="inputform text" placeholder="Email" name="remail" id="remail" value="<?php echo (isset($_POST['remail']) && strlen($_POST['remail'])) ? $_POST['remail'] : $current_user->user_email; ?>"/></td>
                                        </tr>
                                        <tr>
                                            <th></th><td><input id="craccbtn" class="btn orange" value="Create my account" type="submit" /></td>
                                        </tr>
                                        <tr>
                                            <th></th><td>By clicking <a href="https://www.contactus.com/terms-of-service.php" target="_blank">Create my account</a>, you agree to the ContactUs.com Terms of Service.</td>
                                        </tr>
                                    </table>
                                    <input type="hidden" value="signup" name="option" />
                                </form>
                            </div>

                        </div>
                    <?php endif; ?>

                    <div id="tabs-2">
                        <div id="cUsMC_mcsettings">
                            <h2>Login to your ContactUs.com Account</h2>
                            <?php echo $loginMessage; ?>
                            <form method="post" action="admin.php?page=cUs_form_plugin" id="cUs_login" name="cUs_login">
                                <table class="form-table">
                                    <tr>
                                        <th></th><td><p class="validateTips"><?php echo ($userStatus == 'active') ? '' : 'All form fields are required.'; ?></p></td>
                                    <tr>
                                    <tr>
                                        <th><label class="labelform" for="login_email">Email</label><br>
                                        <td><input class="inputform" name="contactus_settings[login_email]" id="login_email" type="text" value="<?php echo (strlen($cUs_email)) ? $cUs_email : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <th><label class="labelform" for="user_pass">Password</label></th>
                                        <td><input class="inputform" name="contactus_settings[user_pass]" id="user_pass" type="password" value="<?php echo (strlen($cUs_pass) || $userAutoactive == 1) ? 'XxxXxxXxxX' : ''; ?>"></td>
                                    </tr>
                                    <tr><th></th>
                                        <td>
                                            <input id="loginbtn" class="btn orange" value="<?php echo ($userStatus == 'active') ? 'Unlink' : 'Login'; ?>" type="submit">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <td>
                                            <a href="https://www.contactus.com/client-login.php" target="_blank">I forgot my password</a>
                                        </td>
                                    </tr>

                                </table>
                                <input type="hidden" value="<?php echo ($userStatus == 'active') ? 'logout' : 'login'; ?>" name="option" />
                            </form>
                        </div>
                    </div>

                    <div id="tabs-3">
                        <div id="cUsMC_mcsettings">
                            <h2>Form Settings</h2>
                            <?php echo $settingsMessage; ?>

                            <div class="versions_options">
                                <table class="form-table">
                                    <tr>
                                        <th>Choose Your Implementation</th>
                                        <td>
                                            <select name="form_version" class="form_version" <?php echo ($userStatus == 'inactive') ? 'disabled' : ''; ?>>
                                                <option value="tab_version" <?php echo ( $cus_version == 'tab' ) ? 'selected="selected"' : ''; ?>>Tab</option>
                                                <option value="inline_version" <?php echo ( $cus_version == 'inline' ) ? 'selected="selected"' : ''; ?>>Inline</option>
                                                <option value="select_version" <?php echo ( $cus_version == 'selectable' ) ? 'selected="selected"' : ''; ?>>Custom</option>
                                            </select>
                                            <br/><span class="message"><?php _e("Select your form version, you can choose our Tab Button or Inline Page Form.", 'cus_plugin'); ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <hr/>
                            </div>


                            <form method="post" action="admin.php?page=cUs_form_plugin" id="cUs_button" class="cus_versionform tab_version <?php echo ( strlen($cus_version) && $cus_version != 'tab') ? 'hidden' : ''; ?>" name="cUs_button">
                                <table class="form-table">
                                    <tr>
                                        <th><?php _e("Contact Us Tab Button Enabled? :", 'cus_plugin'); ?> </th>
                                        <td>
                                            <select id="tab_user" name="tab_user" <?php echo ($userStatus == 'inactive') ? 'disabled' : ''; ?> >
                                                <option <?php echo ($boolTab == 1) ? 'selected="selected"' : ''; ?>value="1">Yes</option>
                                                <option <?php echo (strlen($boolTab) && $boolTab == 0) ? 'selected="selected"' : ''; ?> value="0">No</option>
                                            </select>
                                            <br/><span><?php _e("You can manage the visibility of the ContactUs Button Tab", 'cus_plugin'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <td>
                                            <?php if ($userStatus == 'active'): ?>
                                                <input type="submit" class="btn orange" value="<?php _e('Save Changes') ?>" />
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>

                                <input type="hidden" name="cus_version" value="tab" />
                                <input type="hidden" value="settings" name="option" />
                                <h3>Notice:</h3>
                                <p> Your default theme must have into the head section the <b>"wp_head()"</b> function added.</p>
                            </form>
                            <?php if ($cus_version == 'inline'): ?>
                                <script>
                                jQuery(document).ready(function($) { jQuery( "#cUs_button" ).hide() });
                                </script>
                            <?php endif; ?>
                            <form method="post" action="admin.php?page=cUs_form_plugin" id="cUs_inline" class="cus_versionform inline_version <?php echo ( strlen($cus_version) && $cus_version != 'inline') ? 'hidden' : ''; ?>" name="cUs_inline">
                                <table class="form-table">
                                    <tr>
                                        <th><?php _e("Select your Contact Us page:", 'cus_plugin'); ?> </th>
                                        <td>
                                            <?php
                                            $args = array('id' => 'contactus_settings_page',
                                                'depth' => 0,
                                                'echo' => 1,
                                                'name' => 'inline_page_id');
                                            ?>
                                            <?php wp_dropdown_pages($args); ?>
                                            <?php if (strlen($inline_page_id)): ?>
                                                <a class="button-primary show_preview" target="_blank" href="<?php echo get_permalink($inline_page_id); ?>">Preview >> </a>
                                            <?php endif; ?>
                                            <br/><span><?php _e("Would you like to embed the ContactUs form into one of your web pages?", 'cus_plugin'); ?></span>
                                            <br/><?php _e("Do you need to create a new page on your site?. Click on ", 'cus_plugin'); ?><a href="post-new.php?post_type=page">"Create a new >>"</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <td>
                                            <?php if ($userStatus == 'active'): ?>
                                                <input type="submit" class="btn orange save_page" value="<?php _e('Save Changes') ?>" />
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php if (strlen($inline_page_id)): ?>
                                        <script>
                                        var pageID = <?php echo $inline_page_id; ?>;
                                        jQuery(document).ready(function($) { jQuery( "#contactus_settings_page" ).val(pageID) });
                                        </script>
                                    <?php endif; ?>
                                    <?php if ($userStatus == 'inactive'): ?>
                                        <script>
                                        jQuery(document).ready(function($) { jQuery( "#contactus_settings_page" ).attr({disabled:'disabled'}) });
                                        </script>
                                    <?php endif; ?>
                                </table>

                                <input type="hidden" name="cus_version" value="inline" />
                                <input type="hidden" value="settings" name="option" />
                            </form>

                            <form method="post" action="admin.php?page=cUs_form_plugin" id="cUs_selectable" class="cus_versionform select_version <?php echo ( strlen($cus_version) && $cus_version != 'selectable') ? 'hidden' : ''; ?>" name="cUs_selectable">
                                <h3>Page Selection</h3>
                                <div class="pageselect_cont">
                                    <?php
                                    $mypages = get_pages(array('parent' => 0, 'sort_column' => 'post_date', 'sort_order' => 'desc'));
                                    if (is_array($mypages)) :
                                        $getTabPages = get_option('contactus_settings_tabpages');
                                        $getInlinePages = get_option('contactus_settings_inlinepages');
                                        ?>
                                        <ul class="selectable_pages">
                                            <li class="pages-header">Wordpress pages</li>
                                            <li class="ui-widget-content">
                                                <div class="options home">
                                                    <input type="radio" name="pages[home]" class="home-page" id="pageradio-home" value="tab" <?php echo (is_array($getTabPages) && in_array('home', $getTabPages)) ? 'checked' : '' ?> />
                                                    <label class="label-home" for="pageradio-home">TAB</label>
                                                    <a class="ui-state-default ui-corner-all pageclear-home" href="javascript:;" title="Clear Home page settings"><label class="ui-icon ui-icon-circle-close">&nbsp;</label></a>
                                                </div>
                                                <div class="page_title">
                                                    <span class="bullet ui-icon ui-icon-circle-zoomin">
                                                        <a target="_blank" href="<?php echo get_option('home'); ?>" title="Home Preview">&nbsp;</a>
                                                    </span>
                                                    <span class="title">Home Page</span>
                                                </div>
                                            </li>
                                            <script>
                                            jQuery('.pageclear-home').click(function(){
                                                jQuery('.home-page').removeAttr('checked');
                                                jQuery('.label-home').removeClass('ui-state-active');
                                            });
                                            </script>
                                            <?php foreach ($mypages as $page) : ?>
                                                <li class="ui-widget-content">
                                                    <div class="options">
                                                        <input type="radio" name="pages[<?php echo $page->ID; ?>]" value="tab" id="pageradio-<?php echo $page->ID; ?>-1" class="<?php echo $page->ID; ?>-page" <?php echo (is_array($getTabPages) && in_array($page->ID, $getTabPages)) ? 'checked' : '' ?> />
                                                        <label class="label-<?php echo $page->ID; ?>" for="pageradio-<?php echo $page->ID; ?>-1">TAB</label>
                                                        <input type="radio" name="pages[<?php echo $page->ID; ?>]" value="inline" id="pageradio-<?php echo $page->ID; ?>-2" class="<?php echo $page->ID; ?>-page" <?php echo (is_array($getInlinePages) && in_array($page->ID, $getInlinePages)) ? 'checked' : '' ?> />
                                                        <label class="label-<?php echo $page->ID; ?>" for="pageradio-<?php echo $page->ID; ?>-2">INLINE</label>
                                                        <a class="ui-state-default ui-corner-all pageclear-<?php echo $page->ID; ?>" href="javascript:;" title="Clear <?php echo $page->post_title; ?> page settings"><label class="ui-icon ui-icon-circle-close">&nbsp;</label></a>
                                                    </div>
                                                    <div class="page_title">
                                                        <span class="bullet ui-icon ui-icon-circle-zoomin">
                                                            <a target="_blank" href="<?php echo get_permalink($page->ID); ?>" title="Preview <?php echo $page->post_title; ?> page">&nbsp;</a>
                                                        </span>
                                                        <span class="title"><?php echo $page->post_title; ?></span>
                                                    </div>
                                                </li>
                                                <script>
                                                jQuery('.pageclear-<?php echo $page->ID; ?>').click(function(){
                                                    jQuery('.<?php echo $page->ID; ?>-page').removeAttr('checked');
                                                    jQuery('.label-<?php echo $page->ID; ?>').removeClass('ui-state-active');
                                                });
                                                </script>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="submit_data">
                                            <input type="submit" class="btn orange save_page" value="<?php _e('Save Changes') ?>" />
                                            <hr />
                                            <br/><?php _e("Do you need to create a new page on your site?. Click on ", 'cus_plugin'); ?><a href="post-new.php?post_type=page">"Create a new >>"</a>
                                        </div>
                                            <?php endif; ?>
                                </div>
                                <input type="hidden" name="cus_version" value="selectable" />
                                <input type="hidden" value="settings" name="option" />
                            </form>
                                
                            <div id="terminology">
                                    <h3>Terminology</h3>
                                    <div>
                                        <div class="terminology_c">
                                            <table class="widefat" cellspacing="0">
                                                <tr>
                                                    <td><h4>Tab</h4></td>
                                                    <td>Uses tab callouts with “Contact Us” messaging on the page margins across your website. When pressed, contact form appears as a lightbox above the underlying page. </td>
                                                    <!-- td><h4><a href="#">Preview</a></h4></td-->
                                                </tr>
                                                <tr>
                                                    <td><h4>Inline</h4></td>
                                                    <td>Places your ContactUs.com contact form directly onto a specified page on your website.  (When using this option, please consider the dimensions of your form relative to the space you’re providing it)</td>
                                                    <!-- td><h4><a href="#">Preview</a></h4></td-->
                                                </tr>
                                                <tr>
                                                    <td><h4>Custom</h4></td>
                                                    <td>You can also choose a “Custom” implementation in order to a) use a combination of Tab and Inline, and b) choose specific pages on your site to place Tab or Inline forms.</td>
                                                    <!-- td><h4><a href="#">Preview</a></h4></td-->
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <h3>Helpful Hints</h3>
                                    <div>
                                        <div class="terminology_c">
                                            <ul class="hints">
                                                <li>Take a moment to log into ContactUs.com (with the user name/password you registered with) to see the full set of solutions offered.</li>
                                                <li>You can choose different form design templates from the ContactUs.com library by logging into your account at <a href="http://www.contactus.com" target="_blank">www.ContactUs.com</a></li>
                                                <li>You can also generate leads and newsletter signups from your Facebook page by enabling the ContactUs.com Facebook App.  It only takes two clicks!</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>

                    <div id="tabs-4">
                        <div id="cUsMC_mcsettings">
                            <h2>More About ContactUs.com</h2>

                            <div id="cUs_exampletabs">
                                <ul>
                                    <li><a href="#extabs-1">Form Examples</a></li>
                                    <li><a href="#extabs-2">Mobile Form</a></li>
                                    <li><a href="#extabs-3">Tab Examples</a></li>
                                    <li><a href="#extabs-4">Account Screenshots</a></li>
                                    <li><a href="#extabs-5">Facebook preview</a></li>
                                </ul>
                                <div id="extabs-1">
                                    <h4>Form Examples</h4>
                                    <div class="previews_cont">
                                        <ul id="sortable">
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f1.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f1.png', __FILE__) ?>" alt="ContactUs.com Form Template" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f2.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f2.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f3.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f3.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f4.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f4.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f5.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f5.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f6.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f6.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f7.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f7.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Template" data-fancybox-group="forms_gallery" href="<?php echo plugins_url('style/images/form_preview/large/f8.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/form_preview/thumb/f8.png', __FILE__) ?>" /></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="extabs-2">
                                    <h4>Mobile Form</h4>
                                    <div class="previews_cont">
                                        <ul id="sortable">
                                            <li class="ui-state-default"><a class="examples_gallery" title="Mobile Form Template" data-fancybox-group="mobile_gallery" href="<?php echo plugins_url('style/images/mobile_preview/large/f1.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/mobile_preview/thumb/f1.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="Mobile Form Template" data-fancybox-group="mobile_gallery" href="<?php echo plugins_url('style/images/mobile_preview/large/f2.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/mobile_preview/thumb/f2.png', __FILE__) ?>" /></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="extabs-3">
                                    <h4>Tab Examples</h4> <h5>(shown if “Tab” implementation is chosen)</h5>

                                    <div class="previews_cont">
                                        <ul id="sortable">
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t1.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t1.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t4.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t4.png', __FILE__) ?>" /></a></li>                                            
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t5.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t5.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t6.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t6.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t7.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t7.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t7a.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t7.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t8.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t8.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t8a.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t8.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t9.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t9.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t10.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t10.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t10a.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t10.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t11.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t11.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t11a.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t11.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Tab Button" data-fancybox-group="tabs_gallery" href="<?php echo plugins_url('style/images/tabs/large/t11b.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/tabs/thumb/t11.png', __FILE__) ?>" /></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="extabs-4">
                                    <h4>Account Screenshots</h4>
                                    <div class="previews_cont">
                                        <ul id="sortable">
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Dashboard Preview" data-fancybox-group="admin_gallery" href="<?php echo plugins_url('style/images/admin/large/d1.jpg', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/admin/thumb/d1.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Getting Started Preview" data-fancybox-group="admin_gallery" href="<?php echo plugins_url('style/images/admin/large/d2.jpg', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/admin/thumb/d2.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Detailed Reports" data-fancybox-group="admin_gallery" href="<?php echo plugins_url('style/images/admin/large/d3.jpg', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/admin/thumb/d3.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Settings" data-fancybox-group="admin_gallery" href="<?php echo plugins_url('style/images/admin/large/d4.jpg', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/admin/thumb/d4.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Form Templates" data-fancybox-group="admin_gallery" href="<?php echo plugins_url('style/images/admin/large/d5.jpg', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/admin/thumb/d5.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Calendar/Appointments Preview" data-fancybox-group="admin_gallery" href="<?php echo plugins_url('style/images/admin/large/d6.jpg', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/admin/thumb/d6.png', __FILE__) ?>" /></a></li>
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Website Code Box" data-fancybox-group="admin_gallery" href="<?php echo plugins_url('style/images/admin/large/d7.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/admin/thumb/d7.png', __FILE__) ?>" /></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="extabs-5">
                                    <h4>Facebook preview</h4>
                                    <div class="previews_cont">
                                        <ul id="sortable">
                                            <li class="ui-state-default"><a class="examples_gallery" title="ContactUs.com Facebook App" data-fancybox-group="facebook_gallery" href="<?php echo plugins_url('style/images/facebook/large/f1.png', __FILE__) ?>"><img src="<?php echo plugins_url('style/images/facebook/thumb/f1.png', __FILE__) ?>" /></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <div class="social_share">
                <span class='st_facebook_hcount' displayText='Facebook' st_url="http://wordpress.org/plugins/contactuscom/" st_title="ContactUs.com Contact Form Plugin" st_summary="Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form, powered by ContactUs.com onto your WordPress website"  st_image="https://www.contactus.com/img/ContactUs-Logo.png"></span>
                <span class='st_fblike_hcount' displayText='Facebook Like' st_url="https://www.facebook.com/ContactUscom" st_title="ContactUs.com Contact Form Plugin" st_summary="Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form, powered by ContactUs.com onto your WordPress website"  st_image="https://www.contactus.com/img/ContactUs-Logo.png"></span>
                <span class='st_twitter_hcount' displayText='Tweet' st_url="http://wordpress.org/plugins/contactuscom/" st_title="ContactUs.com offers contact form systems for websites to capture and manage inquiries." st_summary="Add beautiful, customizable, contact forms, used to generate new web customers by adding an advanced Contact Form, powered by ContactUs.com onto your WordPress website"></span>
            </div>
            <a href="http://www.contactus.com" target="_blank" class="powered">Powered By ContactUs.com</a>
        </div>

        <?php
    }

}

/*
 * GET CONTACTUS API RESPONSE
 */

function getFormKeyAPI($cUs_email, $cUs_pass) {

    $cUs_email = preg_replace('/\s+/', '%20', $cUs_email);

    $ch = curl_init();

    $strCURLOPT = 'https://api.contactus.com/api2.php';
    $strCURLOPT .= '?API_Account=AC00000bb19ec0c1dd1fe715ef23afa9cf';
    $strCURLOPT .= '&API_Key=00000b77edc87072ce89f0982b3d9687';
    $strCURLOPT .= '&API_Action=getFormKey';
    $strCURLOPT .= '&Email=' . sanitize_email(trim($cUs_email));
    $strCURLOPT .= '&Password=' . trim($cUs_pass);

    curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);

    return $content;
}

function createCustomer($postData) {

    $postData = preg_replace('/\s+/', '%20', $postData);

    $ch = curl_init();

    $strCURLOPT = 'https://api.contactus.com/api2.php';
    $strCURLOPT .= '?API_Account=AC11111f363ae737fb7c60b75dfdcbb306';
    $strCURLOPT .= '&API_Key=1111165fc715b9857909c062fd5ad7e3';
    $strCURLOPT .= '&API_Action=createSignupCustomer';
    $strCURLOPT .= '&First_Name=' . trim( sanitize_file_name($postData['fname']) );
    $strCURLOPT .= '&Last_Name=' . trim( sanitize_file_name( $postData['lname']) );
    $strCURLOPT .= '&Email=' . sanitize_email(trim($postData['remail']));
    $strCURLOPT .= '&Website=' . esc_url(trim($_SERVER['HTTP_HOST']));
    $strCURLOPT .= '&IP_Address='.getIP();
    $strCURLOPT .= '&Auto_Activate=1';
    $strCURLOPT .= '&Promotion_Code=WP';
    $strCURLOPT .= '&Version=wp|2.5.3';

    curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);

    return $content;
}

function getIP() {

    // Get some headers that may contain the IP address
    $SimpleIP = (isset($REMOTE_ADDR) ? $REMOTE_ADDR : getenv("REMOTE_ADDR"));

    $TrueIP = (isset($HTTP_CUSTOM_FORWARDED_FOR) ? $HTTP_CUSTOM_FORWARDED_FOR : getenv("HTTP_CUSTOM_FORWARDED_FOR"));
    if ($TrueIP == "")
        $TrueIP = (isset($HTTP_X_FORWARDED_FOR) ? $HTTP_X_FORWARDED_FOR : getenv("HTTP_X_FORWARDED_FOR"));
    if ($TrueIP == "")
        $TrueIP = (isset($HTTP_X_FORWARDED) ? $HTTP_X_FORWARDED : getenv("HTTP_X_FORWARDED"));
    if ($TrueIP == "")
        $TrueIP = (isset($HTTP_FORWARDED_FOR) ? $HTTP_FORWARDED_FOR : getenv("HTTP_FORWARDED_FOR"));
    if ($TrueIP == "")
        $TrueIP = (isset($HTTP_FORWARDED) ? $HTTP_FORWARDED : getenv("HTTP_FORWARDED"));

    $GetProxy = ($TrueIP == "" ? "0" : "1");

    if ($GetProxy == "0") {
        $TrueIP = (isset($HTTP_VIA) ? $HTTP_VIA : getenv("HTTP_VIA"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_X_COMING_FROM) ? $HTTP_X_COMING_FROM : getenv("HTTP_X_COMING_FROM"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_COMING_FROM) ? $HTTP_COMING_FROM : getenv("HTTP_COMING_FROM"));
        if ($TrueIP != "")
            $GetProxy = "2";
    };

    if ($TrueIP == $SimpleIP)
        $GetProxy = "0";

    // Return the true IP if found, else the proxy IP with a 'p' at the begining
    switch ($GetProxy) {
        case '0':
            // True IP without proxy
            $IP = $SimpleIP;
            break;
        case '1':
            $b = preg_match("%^([0-9]{1,3}\.){3,3}[0-9]{1,3}%", $TrueIP, $IP_array);
            if ($b && (count($IP_array) > 0)) {
                // True IP behind a proxy
                $IP = $IP_array[0];
            } else {
                // Proxy IP
                $IP = $SimpleIP;
            };
            break;
        case '2':
            // Proxy IP
            $IP = $SimpleIP;
    };

    return $IP;
}



?>