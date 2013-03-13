<?php
/*
    Plugin Name: ContactUs.Com Form Plugin
    Plugin URI:  http://help.contactus.com/entries/23229688-Adding-the-ContactUs-com-Plugin-for-WordPress
    Description: ContactUs.com Plugin for Wordpress.
    Author: ContactUs.Com
    Version: 0.03b
    Author URI: http://contactus.com/
    License: GPLv2 or later
*/

/*  Copyright 2013  ContactUs.com  ( email: support@contactuscom.zendesk.com )

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

//CONTACTUS.COM PLUGIN STYLES CSS
if ( ! function_exists ( 'cUs_admin_header' ) ) {
    function cUs_admin_header() {
        wp_enqueue_style( 'cUs_Styles', plugins_url( 'style/cUs_style.css', __FILE__ ) );
    }
}
add_action( 'admin_enqueue_scripts', 'cUs_admin_header' ); // cUs_admin_header hook
//END CONTACTUS.COM PLUGIN STYLES CSS

// ADD Add option page in admin menu
if( ! function_exists( 'cUs_admin_menu' ) ) {
    function cUs_admin_menu() {
        add_menu_page( 'ContactUs.com Form Plugin', 'ContactUs.com', 'edit_themes', 'cUs_form_plugin', 'cUs_menu_render', plugins_url( "style/images/Icon-Small_16.png", __FILE__ ), 101);
    }
}
add_action( 'admin_menu', 'cUs_admin_menu' ); // cUs_admin_menu hook

//CONTACTUS.COM ADD FORM TO PLUGIN PAGE
if( ! function_exists( 'cUs_menu_render' ) ) {
	function cUs_menu_render() {

        settings_fields( 'contactus_settings' );
        do_settings_sections( __FILE__ );
        //get the values, wont work the first time
        $options  = get_option( 'contactus_settings' );
        $userCode = stripslashes($options['javascript_usercode']);
        $boolTab  = $options['tab_user'];
        ?>
		<div class="plugin_wrap">

            <h2>ContactUs.com Form Plugin for Wordpress</h2>

            <?php if( isset( $_REQUEST['contactus_settings'] )  ) : ?>

                <div class="conf_message">
                    <p>The changes have been successfully saved.</p>
                </div>

                <script>
                    setTimeout(function(){
                        location.href = 'admin.php?page=cUs_form_plugin';
                    },3000);
                </script>

            <?php endif; ?>

            <form method="post" action="admin.php?page=cUs_form_plugin">
                <table class="form-table">
                    <tr>
                        <th><?php _e( "ContactUs.com JavaScript Code:", 'cus_plugin' ); ?> </th>
                        <td>
                            <textarea type="text" id="cUs_code_user" name="contactus_settings[javascript_usercode]"  /><?php echo (strlen($userCode))?$userCode:'';?></textarea>
                            <br/></a><span><?php _e( "Enter the JavaScript code provided by your ContactUs.com account", 'cus_plugin' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e( "Contact Us Tab Enabled ? :", 'cus_plugin' ); ?> </th>
                        <td>
                            <select id="tab_user" name="contactus_settings[tab_user]">
                                <option <?php echo ($boolTab == 0)?'selected="selected"':'';?> value="0">No</option>
                                <option <?php echo ($boolTab == 1)?'selected="selected"':'';?>value="1">Yes</option>
                            </select>
                            <br/></a><span><?php _e( "You can manage the visibility of the ContactUs Tab", 'cus_plugin' ); ?></span>
                        </td>
                    </tr>
                </table>

                <p>
                    <?php _e( "This plugin allows to add the ContactUs.com Form to your website, we add the next code to your Wordpress Theme:", 'cus_plugin' ); ?>
                    <pre><code>< script type="text/javascript" src="//cdn.contactus.com/cdn/forms/aBcDeFg.../contactus.js" >< /script ></code></pre>
                </p>
                <p>
                    Please visit our website to create or manage your account
                    <a href="http://www.contactus.com" target="_blank">www.contactus.com</a>
                </p>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>
            </form>

            <hr/>
            <h3>Notice:</h3>
            <p>
                Your default theme must have into the head section the <b>"wp_head()"</b> function added.
            </p>

		</div>

        <?php
	}
}
/*
 * Register the settings
 */
add_action('admin_init', 'contactus_register_settings');
function contactus_register_settings(){
    if( isset( $_REQUEST['contactus_settings'] )  ) :
        update_option('contactus_settings', $_REQUEST['contactus_settings']);
    endif;
    register_setting('contactus_settings_group', 'contactus_settings', 'contactus_settings_validate');
}

function contactus_settings_validate($args){

    if(!isset($args['email_user']) || !is_email($args['email_user'])){
        //add a settings error because the email is invalid and make the form field blank, so that the user can enter again
        $args['email_user'] = '';
        //echo 'Please enter a valid email!';
        add_settings_error('contactus_settings', 'email_user', 'Please enter a valid email!', $type = 'error');
    }

    //make sure you return the args
    return $args;
}

//Display the validation errors and update messages
/*
 * Admin notices
 */
function contactus_admin_notices(){
    settings_errors();
}
add_action('admin_notices', 'contactus_admin_notices');

function contactUs_JS_into_head(){
    if (!is_admin()){
        $options = get_option( 'contactus_settings' );
        $userCode =  stripslashes($options['javascript_usercode']);
        $boolTab = $options['tab_user'];

        //the theme must have the wp_head() function included
        //include the contactUs.com JS file into the <head> section
        if(strlen($userCode) && $boolTab) echo $userCode;
        //wp_deregister_script('easing');
    }
}
add_action( 'wp_head', 'contactUs_JS_into_head');

?>