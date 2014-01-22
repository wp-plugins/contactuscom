<?php
/*
  Plugin Name: Contact Form by ContactUs
  Version: 4.1.1
  Plugin URI:  http://help.contactus.com/hc/en-us/sections/200204997-Contact-Form-Plugin-by-ContactUs-com
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

//INCLUDE WP HOOKS ACTIONS & FUNCTIONS
require_once('contactus_form_functions.php');

/*
 * Method in charge to render plugin layout
 * @since 1.0
 * @return string Render HTML layout into WP admin
 */
if (!function_exists('cUsCF_menu_render')) {
    function cUsCF_menu_render() {
        
        $cUsCF_api          = new cUsComAPI_CF(); //CONTACTUS.COM API
        $aryUserCredentials = get_option('cUsCF_settings_userCredentials'); //get the values, wont work the first time
        $options            = get_option('cUsCF_settings_userData'); //get the values, wont work the first time
        $old_options        = get_option('contactus_settings'); //GET THE OLD OPTIONS
        $formOptions        = get_option('cUsCF_FORM_settings');//GET THE NEW FORM OPTIONS
        $form_key           = get_option('cUsCF_settings_form_key');
        $default_deep_link  = get_option('cUsCF_settings_default_deep_link_view');
        $form_id            = get_option('cUsCF_settings_form_id');
        $cus_version        = $formOptions['cus_version'];
        $boolTab            = $formOptions['tab_user'];
        $cUs_API_Account    = $aryUserCredentials['API_Account'];
        $cUs_API_Key        = $aryUserCredentials['API_Key'];
        
        if(!strlen($form_key)) $form_key = $old_options['form_key'];
        if(!strlen($cus_version)) $cus_version = $old_options['cus_version'];
        if(!strlen($boolTab)) $boolTab = $old_options['tab_user'];
        
        
        ?>

        <div id="dialog-message"></div>
        <div class="plugin_wrap">
            
            <div class="cUsCF_header">
                
                <h2>Contact Form <span> by</span><a href="http://bit.ly/1foYG0E" target="_blank"><img src="<?php echo plugins_url('style/images/header-logo.png', __FILE__) ;  ?>"/></a> </h2>
                
                <div class="social_shares">
                    <a class="setLabels" href="http://on.fb.me/HqI1hd" target="_blank" title="Follow Us on Facebook for new product updates"><img src="<?php echo plugins_url('style/images/cu-facebook-m.png', __FILE__) ;  ?> " alt="Follow Us on Facebook for new product updates"/></a>
                    <a class="setLabels" href="http://bit.ly/18DW6O4" target="_blank" title="Follow Us on Google+"><img src="<?php echo plugins_url('style/images/cu-googleplus-m.png', __FILE__) ;  ?> " /></a>
                    <a class="setLabels" href="http://linkd.in/1ivr9kK" target="_blank" title="Follow Us on LinkedIn"><img src="<?php echo plugins_url('style/images/cu-linkedin-m.png', __FILE__) ;  ?> " /></a>
                    <a class="setLabels" href="http://bit.ly/16NxNh5" target="_blank" title="Follow Us on Twitter"><img src="<?php echo plugins_url('style/images/cu-twitter-m.png', __FILE__) ;  ?> " /></a>
                    <a class="setLabels" href="http://bit.ly/1dPwnub" target="_blank" title="Find tutorials on our Youtube channel"><img src="<?php echo plugins_url('style/images/cu-youtube-m.png', __FILE__) ;  ?> " alt="Find tutorials on our Youtube channel" /></a>
                </div>
            </div>
            
            <div class="cUsCF_formset">
                <div class="cUsCF_preloadbox"><div class="cUsCF_loadmessage"><span class="loading"></span></div></div>
                <div id="cUsCF_tabs">
                    <ul>
                        <?php
                        /*
                        * CHECK USER LOGIN STATUS strlen($cUs_API_Account)
                        * @since 1.0
                        */  
                        ?>
                        
                        <?php if ( !strlen($form_key) ){ ?><li><a href="#tabs-1">Contact Form Plugin</a></li><?php } ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ){ ?><li><a href="#tabs-1">Form Placement</a></li><?php } ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ){ ?><li><a href="#tabs-2">Form Settings</a></li><?php } ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ){ ?><li><a href="#tabs-3">Shortcodes</a></li><?php } ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ){ ?><li class="gotohelp"><a href="http://bit.ly/18pJ8dp" target="_blank">Documentation</a></li><?php } ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ){ ?><li><a href="#tabs-4">Account</a></li><?php } ?>
                        <?php if ( strlen($form_key) && strlen($cUs_API_Account) ){ ?><li class="gotohelp"><a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo $cUs_API_Key; ?>&confirmed=1" target="_blank" rel="toDash" class="goToDashTab">Form Control Panel</a></li><?php } ?>
                    </ul>

                    <?php
                    /*
                    * USER LOGIN STATUS : NOT LOGGED
                    * SHOW LOGIN OR SIGNUP BUTTONS 
                    * @since 1.0
                    */
                    if (!strlen($form_key)){
                        
                        global $current_user;
                        get_currentuserinfo();
                        
                        ?>
                        <div id="tabs-1">
                            
                            <div class="left-content">
                                
                                <div class="first_step">
                                    <h2>Are You Already a ContactUs.com User?</h2>
                                    <button id="cUsCF_yes" class="btn" type="button" ><span>Yes</span> Set Up My Form</button>
                                    <button id="cUsCF_no" class="btn mc_lnk"><span>No</span>Signup Free Now</button>
                                    <p>The Contact Form by ContactUs.com is designed for existing ContactUs.com users. If you are not yet a Contact Form user, click on the "No, Signup Free Now" button above.</p>
                                </div>
                                
                                <div id="cUsCF_settings">

                                    <div class="loadingMessage"></div>
                                    <div class="advice_notice">Advices....</div>
                                    <div class="notice">Ok....</div>
                                   
                                    <?php
                                    /*
                                    * LOGIN FORM
                                    * @since 1.0
                                    */
                                    ?>
                                    
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
                                                    <a href="http://bit.ly/Jl1npc" target="_blank">I forgot my password</a>
                                                </td>
                                            </tr>

                                        </table>
                                    </form>
                                    
                                    <?php
                                    /*
                                    * SINGUP FORM - SIGNUP WIZARD - STEP 1
                                    * @since 1.0
                                    */
                                    ?>
                                    
                                    <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_userdata" name="cUsCF_userdata" class="steps step1" onsubmit="return false;">
                                        <h3 class="step_title">Register for your ContactUs.com Account</h3>

                                        <table class="form-table">
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_first_name">First Name</label></th>
                                                <td><input type="text" class="inputform text" placeholder="First Name" name="cUsCF_first_name" id="cUsCF_first_name" value="<?php echo (isset($_POST['cUsCF_first_name']) && strlen($_POST['cUsCF_first_name'])) ? $_POST['cUsCF_first_name'] : $current_user->user_firstname; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_last_name">Last Name</label></th>
                                                <td><input type="text" class="inputform text" placeholder="Last Name" name="cUsCF_last_name" id="cUsCF_last_name" value="<?php echo (isset($_POST['cUsCF_last_name']) && strlen($_POST['cUsCF_last_name'])) ? $_POST['cUsCF_last_name'] : $current_user->user_lastname; ?>"/></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_email">Email</label></th>
                                                <td><input type="text" class="inputform text" placeholder="Email" name="cUsCF_email" id="cUsCF_email" value="<?php echo (isset($_POST['cUsCF_email']) && strlen($_POST['cUsCF_email'])) ? $_POST['cUsCF_email'] : $current_user->user_email; ?>"/></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_password">Password</label></th>
                                                <td><input type="password" class="inputform text" name="cUsCF_password" id="cUsCF_password" value=""/></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_password_r">Confirm Password</label></th>
                                                <td><input type="password" class="inputform text" name="cUsCF_password_r" id="cUsCF_password_r" value=""/></td>
                                            </tr>
                                            <tr>
                                                <th><label class="labelform" for="cUsCF_web">Website</label></th>
                                                <td><input type="text" class="inputform text" placeholder="Website (http://www.example.com)" name="cUsCF_web" id="cUsCF_web" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>"/></td>
                                            </tr>
                                            <tr>
                                                <th></th><td><input id="cUsCF_CreateCustomer" class="btn orange" value="Next >>" type="submit" /></td>
                                            </tr>
                                            <tr>
                                                <th></th><td>By creating a ContactUs.com account, you agree that: <b>a)</b> You have read and accepted our <a href="http://bit.ly/1b5jJA5" class="blue_link" target="_blank">Terms</a> and our <a href="http://bit.ly/17Y9tij" class="blue_link" target="_blank">Privacy Policy</a> and <b>b)</b> You may receive communications from <a href="http://www.contactus.com/" class="blue_link"  target="_blank">ContactUs.com</a></td>
                                            </tr>
                                        </table>
                                    </form>
                                    
                                    
                                    <?php
                                    /*
                                    * SINGUP FORM - SIGNUP WIZARD - STEP 2 TEMPLATES
                                    * @since 1.0
                                    */
                                    ?>
                                    <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_templates" name="cUsCF_templates" class="steps step2" onsubmit="return false;">
                                        <h3 class="step_title">Let's create your first Form</h3>
                                       
                                        <div class="signup_templates">
                                            <h4>Select your Form Template</h4>

                                            <div>
                                                <div class="terminology_c Template_Contact_Form form_templates">
                                                    
                                                    <div class="template_slider slider_forms template_slider_def">
                                                        <?php
                                                        /*
                                                        * GET FREE FORM TEMPLATES
                                                        */
                                                        
                                                        $contacFormTemplates = $cUsCF_api->getTemplatesDataAll('contact_us', 'template_desktop_form');
                                                        $contacFormTemplates = json_decode($contacFormTemplates);
                                                        $contacFormTemplates = $contacFormTemplates->data;
                                                        
                                                        if(is_array($contacFormTemplates)){
                                                                
                                                                foreach ($contacFormTemplates as $formTpl) {
                                                                    if ($formTpl->free){  ?>
                                                                    
                                                                    <span class="tpl item template-form" rel="<?php echo $formTpl->id; ?>">
                                                                        <img src="<?php echo $formTpl->thumbnail; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $formTpl->name; ?>" />
                                                                        <span class="captions">
                                                                            <p>
                                                                                Form Name:<?php echo $formTpl->name; ?>
                                                                            </p>
                                                                        </span>
                                                                        <span class="def_bak"></span>
                                                                    </span>

                                                                    <?php 
                                                                    
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <script>
                                                    jQuery('.template-form').click(function(){
                                                        jQuery('#Template_Desktop_Form').val( jQuery(this).attr('rel') );
                                                        jQuery('.slider_forms .item').removeClass('default');
                                                        jQuery(this).addClass('default');
                                                    });
                                                </script>
                                                
                                            </div>
                                            <h4>Select your Tab Template</h4>
                                            <div>
                                                <div class="terminology_c Template_Contact_Form form_templates">
                                                    
                                                    <div class="template_slider slider_tabs template_slider_def">
                                                        
                                                        <?php
                                                        
                                                        /*
                                                        * GET FREE TAB TEMPLATES
                                                        */
                                                        
                                                        $contacFormTabTemplates = $cUsCF_api->getTemplatesDataAll('contact_us', 'template_desktop_tab');
                                                        $contacFormTabTemplates = json_decode($contacFormTabTemplates);
                                                        $contacFormTabTemplates = $contacFormTabTemplates->data;

                                                        if(is_array($contacFormTabTemplates)){
                                                                
                                                                foreach ($contacFormTabTemplates as $formTpl) {
                                                                    if ($formTpl->free){  ?>
                                                                    
                                                                    <span class="tpl item template-tab" rel="<?php echo $formTpl->id; ?>">
                                                                        <img src="<?php echo $formTpl->thumbnail; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $formTpl->name; ?>" />
                                                                        <span class="captions">
                                                                            <p>
                                                                                Tab Name:<?php echo $formTpl->name; ?>
                                                                            </p>
                                                                        </span>
                                                                        <span class="def_bak"></span>
                                                                    </span>

                                                                    <?php 
                                                                    
                                                                    } //endif
                                                                } //endforeach
                                                        }
                                                        
                                                        ?>
                                                        
                                                    </div>
                                                </div>
                                                
                                                <script>
                                                    jQuery('.template-tab').click(function(){
                                                        jQuery('#Template_Desktop_Tab').val( jQuery(this).attr('rel') );
                                                        jQuery('.slider_tabs .item').removeClass('default');
                                                        jQuery(this).addClass('default');
                                                    });
                                                </script>
                                                
                                            </div>

                                        </div> 
                                        <table class="form-table">
                                            <tr>
                                                <th></th><td><input id="cUsCF_SendTemplates" href="#cats_selection" class="btn orange" value="Create my account" type="submit" /></td>
                                            </tr>
                                            <tr>
                                                <th></th><td>By creating a ContactUs.com account, you agree that: <b>a)</b> You have read and accepted our <a href="http://bit.ly/1b5jJA5" class="blue_link" target="_blank">Terms</a> and our <a href="http://bit.ly/17Y9tij" class="blue_link" target="_blank">Privacy Policy</a> and <b>b)</b> You may receive communications from <a href="http://www.contactus.com/" class="blue_link"  target="_blank">ContactUs.com</a></td>
                                            </tr>
                                            <input type="hidden" value="" name="Template_Desktop_Form" id="Template_Desktop_Form" />
                                            <input type="hidden" value="" name="Template_Desktop_Tab" id="Template_Desktop_Tab" />
                                        </table>
                                    </form>
                                    
                                    <?php
                                    global $current_user;
                                    get_currentuserinfo();
                                    ?>

                                    <!-- CATS SUBCATS AND GOALS -->
                                    <div id="cats_container" style="display:none;">

                                        <div id="cats_selection">

                                            <form action="/" onsubmit="return false;">

                                                <div id="customer-categories-box" class="questions-box">

                                                    <div class="cc-headline">Hi <?php echo $current_user->user_login; ?></div>
                                                    <img src="<?php echo plugins_url('style/images/contactus-users.png', __FILE__); ?>" class="user-graphic">
                                                    <div class="cc-message">We’re working on new ways to personalize your account</div>
                                                    <div class="cc-message-small">Please take 7 seconds to tell us about your website, which helps us identify the best tools for your needs:</div>

                                                    <h4 class="cc-title" id="category-message">Select the Category of Your Website:</h4>
                                                    
                                                    <?php

                                                    /*
                                                    * GET CATEGORIES AND SUBCATEGORIES
                                                    */
                                                    $aryCategoriesAndSub = $cUsCF_api->getCategoriesSubs();

                                                    if (is_array($aryCategoriesAndSub)) {
                                                        ?>
                                                        <ul id="customer-categories">
                                                            <?php foreach ($aryCategoriesAndSub as $category => $arySubs) { ?>

                                                                <li class="parent-category"><span data-maincat="<?php echo $category; ?>" id="<?php echo str_replace(' ', '-', $category); ?>" class="parent-title"><?php echo trim($category); ?></span>
                                                                    <?php if (is_array($arySubs)) { ?>
                                                                        <ul class="sub-category">
                                                                            <?php foreach ($arySubs as $Sub) { ?>
                                                                                <li data-subcat="<?php echo $Sub; ?>"><span><?php echo trim($Sub); ?></span></li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    <?php } ?>
                                                                </li>

                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>

                                                    <div class="int-navigation">
                                                        <button class="btn next btn-skip">Skip</button>
                                                        <img src="<?php echo plugins_url('style/images/ajax-loader.gif', __FILE__); ?>" width="16" height="16" alt="Loading . . ." style="display:none; vertical-align:middle;" class="img_loader" />
                                                        <div class="next btn unactive" id="open-intestes">Next Question</div>
                                                    </div>

                                                </div>

                                                <div id="user-interests-box" class="questions-box">
                                                    <div class="cc-headline">Hi <?php echo $current_user->user_login; ?></div>
                                                    <div class="cc-message">What are your goals for your ContactUs.com form?</div>
                                                    
                                                    <?php

                                                    /*
                                                    * GET GOALS
                                                    */  
                                                    $aryGoals = $cUsCF_api->getGoals();

                                                    if (is_array($aryGoals)) {
                                                        ?>
                                                        <ul id="user-interests">
                                                            <?php foreach ($aryGoals as $Goal) { ?>
                                                                <li data-goals="<?php echo trim($Goal); ?>" <?php if ($Goal === 'Other') { ?>id="other"<?php } ?>><span <?php if (strpos($Goal, 'to my email') !== false) { ?> class="grey" <?php } ?>><?php echo trim($Goal); ?></span></li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>
                                                    
                                                    <div id="other-interest">Please tell us <input type="text" name="other" id="other_goal" value="" /></div>

                                                    <div class="int-navigation">
                                                        <button class="btn next btn-skip">Skip</button>
                                                        <div class="next btn unactive btn-skip" id="save">Save Preferences</div>
                                                        <img src="<?php echo plugins_url('style/images/ajax-loader.gif', __FILE__); ?>" width="16" height="16" alt="Loading . . ." style="display:none; vertical-align:middle;" class="img_loader" />
                                                    </div>

                                                </div>

                                                <!-- input the category and subcategory data -->
                                                <input type="hidden" value="" name="CU_category" id="CU_category" />
                                                <input type="hidden" value="" name="CU_subcategory" id="CU_subcategory" />
                                                <!-- <input type="hidden" value="" name="CU_goals" id="CU_goals" /> -->

                                                <div id="goals_added"></div>
                                                
                                            </form>
                                            <br /><br /><br />
                                        </div>
                                    </div>
                                    <!-- / CATS SUBCATS AND GOALS -->

                                </div>
                                <div class="contaus_features">
                                    <div class="col-md-12 why-contactuscom">
                                        <h3 class="lb_title feat_box">What do you get with a ContactUs.com account?</h3>
                                        <div class="row"><div class="col-md-6 "><div class="panel"><div class="text-center"><img alt="" src="https://www.contactus.com/wp-content/uploads/2013/11/1383815658_app_48.png" /></div><h4 class="heading">Create beautiful, conversion-optimized forms to engage your users and customers.</h4><p>Choose from one of our standard, conversion-optimized design templates for contact forms and signup forms. Premium users of ContactUs.com can unlock customized, premium form designs.</p></div></div><div class="col-md-6"><div class="panel"><div class="text-center"><img alt="" src="https://www.contactus.com/wp-content/uploads/2013/11/1383816749_Setup.png" /></div><h4 class="heading">Easily set-up and customize your forms.</h4><p>All ContactUs.com tabs and forms start with simple and effective designs. You can also customize your call-to-actions, button text, confirmation page messaging, add your business information (for Contact forms), social media links and even business hours!</p></div></div></div><div class="row"><div class="col-md-6"><div class="panel"><div class="text-center"><img alt="" src="https://www.contactus.com/wp-content/uploads/2013/11/1383817424_graph.png" /></div><h4 class="heading">Gain actionable intelligence on your online marketing with integrated web analytics.</h4><p>Track how leads got to your site, and what information they read or viewed before contacting you. Where your leads have been will give you actionable intelligence on where they are going.</p></div></div><div class="col-md-6 "><div class="panel"><div class="text-center"><img alt="" src="https://www.contactus.com/wp-content/uploads/2013/11/1383817662_docs_cloud_connect.png" /></div><h4 class="heading">Seamless integration with 3rd-party software.</h4><p>Use ContactUs.com as your gateway to other great CRM and marketing tools. Automatically deliver your form submissions for MailChimp, Constant Contact, iContact, Zendesk, Zoho CRM, Google Docs and many other web services. Use extensions such as WordPress plugins to easily install on your site!</p></div></div></div>
                                    </div>
                                </div>
                                
                            </div><!-- // TAB LEFT -->
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Plugin Overview</h3>
                                    
                                    <div class="video">
                                        
                                        <iframe src="//player.vimeo.com/video/79343284" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        
                                    </div>
                                    
                                    <p><a href="http://bit.ly/1cwSocX" target="_blank" class="btn large lightblue">Other plugins by ContactUs.com</a></p>
                                    
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <p><a href="http://bit.ly/18pJ8dp" target="_blank" class="btn large lightblue">Support</a></p>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->

                        </div> <!-- // TAB 1 -->
                        
                    <?php }else{
                        
                        global $current_user;
                        get_currentuserinfo();
                        /*
                         * UPDATE OLD USERS
                         * MIGRATION PROCESS
                         * If current logged user don't have api credentials
                         */                              

                        //API CREDENTIALAS STORED
                        if (strlen($cUs_API_Account)) {
                            
                            /*
                            * Get Forms Data // all FORM TYPES
                            */
                            $cUsCF_API_getFormKeys = $cUsCF_api->getFormKeysData($cUs_API_Account, $cUs_API_Key); //api hook;
                            
                            $default_deep_link = $cUsCF_api->parse_deeplink ( $default_deep_link );
                            if( !strlen($default_deep_link) ){
                                $default_deep_link = $cUsCF_api->getDefaultDeepLink( $cUs_API_Account, $cUs_API_Key ); // get a default deeplink
                                update_option('cUsCF_settings_default_deep_link_view', $default_deep_link );
                            }
                            
                    ?>    
                        
                    <div id="tabs-1">
                            
                            <div class="left-content">
                                <h2>Forms Placement & Position</h2>
                                
                                <div class="versions_options">
                                    
                                    <div class="button_set_tabs_fp">
                                        <button class="form_version btn_tab tab_button <?php echo ( $cus_version == 'tab' )?'green':'gray'; ?>" value="tab_version" <?php echo ( $cus_version == 'tab' )?'disabled="disabled"':''; ?>>DEFAULT FORM</button> 
                                        <button class="form_version btn_tab custom <?php echo ( $cus_version == 'selectable' )?'green':'gray'; ?>" value="select_version" <?php echo ( $cus_version == 'selectable' )?'disabled="disabled"':''; ?> >CUSTOM</button>
                                        <span class="sc_message">Do you want use Shortcodes? <br/>Go to <a href="#tabs-3" class="goto_shortcodes">Shortcode Instructions</a></span>
                                    </div>
                                    
                               </div>
                                
                               <div id="message" class="updated fade notice_success"></div>
                               <div class="advice_notice"></div>
                               <div class="loadingMessage"></div>
                                
                               <div>    
                                    <p>If you just want a simple Contact Form on all pages, use your default Contact Form.</p>
                                    <p>When you activate custom form Settings, your default form is deactivated automatically. Select the pages you want the form to be shown in, and customize the form for every page. If you already clicked on custom, click the default form button to reinstate default settings.</p>
                                    <p>View a quick tutorial here <a class="setLabels tooltip_formsett media_link" href="http://player.vimeo.com/video/79342945" title="Click to watch the video"> Link</a></p>
                               </div>
                               
                               <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_selectable" class="cus_versionform tab_version <?php echo ( strlen($cus_version) && $cus_version != 'tab')?'hidden':''; ?>" name="cUsCF_defaultformkey">
                                   <h3 class="form_title">Available Form Types</h3>
                                   <div class="pageselect_cont">
                                       <p>If you want use one of your default forms in all WordPress environment just select as "Default" and will appear in you site.</p>
                                       <p>If you want to change the default form for each form type, go to the form settings page in your ContactUs.com admin panel, by clicking 
                                           <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo $default_deep_link.'?pageID=81';?>" target="_blank" class="blue_link">here</a>
                                           , and follow these instructions. <a href="http://help.contactus.com/hc/en-us/articles/201090883-Setting-Default-Forms" target="_blank" class="blue_link">http://help.contactus.com/hc/en-us/articles/201090883-Setting-Default-Forms</a>
                                       </p>
                                       
                                       <div class="loadingMessage def"></div><div class="advice_notice">Advice ....</div><div class="notice">Messages....</div>
                                       <ul class="selectable_pages defaultF">
                                           
                                           <?php
                                                        
                                            /*
                                             * DEFAULT FORM TYPES
                                             * Render Forms Data
                                             */
                                           
                                            if($cUsCF_API_getFormKeys){
                                                $cUs_json = json_decode($cUsCF_API_getFormKeys);
                                                switch ( $cUs_json->status  ) {
                                                    case 'success':
                                                        foreach ($cUs_json->data as $oForms => $oForm) {
                                                        if(cUsCF_allowedFormType($oForm->form_type) && $oForm->default == 1) {
                                                            
                                                            //RE-ASSING DEFAULT FORM KEY
                                                            //$form_key = updateDefaultFormKey($oForm->form_key);
                                                            
                                                            ?>
                                           
                                                            <li class="ui-widget-content <?php echo $oForm->form_type; ?>">
                                                                <div class="page_title">
                                                                    <span class="name">Name: <strong><?php echo $oForm->form_name ?></strong></span>  | 
                                                                    <span class="key">Key: <?php echo $oForm->form_key; ?></span>
                                                                </div>

                                                                <div class="options">
                                                                    <input type="radio" name="defaultformkey[]" value="<?php echo $oForm->form_key; ?>" id="formkeyradio-<?php echo $oForm->form_id; ?>-1" class="setDefaulFormKey" <?php echo ($oForm->form_key == $form_key) ? 'checked' : '' ?> />
                                                                    <label class="setLabel label-<?php echo $oForm->form_id; ?>" for="formkeyradio-<?php echo $oForm->form_id; ?>-1" title="Set as Default, click to save"><?php echo ($oForm->form_key == $form_key) ? 'Default' : 'Set as Default' ?></label>
                                                                </div>
                                                            </li>
                                            
                                                            <?php
                                                        }
                                                    }
                                                    break;
                                                } //endswitch;
                                            }
                                            ?>
                                       </ul>
                                   </div>
                               </form>
                               
                                <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_button" class="cus_versionform tab_version <?php echo ( strlen($cus_version) && $cus_version != 'tab')?'hidden':''; ?>" name="cUsCF_button">
                                   
                                    <input type="hidden" class="tab_user" name="tab_user" value="1" />
                                    <input type="hidden" name="cus_version" value="tab" />
                                    <input type="hidden" value="settings" name="option" />
                                    
                                </form>


                                <form method="post" action="admin.php?page=cUs_form_plugin" id="cUsCF_selectable" class="cus_versionform select_version <?php echo ( !strlen($cus_version) || $cus_version == 'tab')?'hidden':''; ?>" name="cUsCF_selectable">
                                    <h3 class="form_title">Page Selection  <a href="post-new.php?post_type=page">Create a new page <span>+</span></a></h3> 
                                    <div class="pageselect_cont">
                                    <?php 
                                        
                                        /*
                                         * Get Main WP Pages
                                         */
                                        $mypages = get_pages( array( 'parent' => 0, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) );
                                    
                                        if( is_array($mypages) ) { 
                                            
                                            $getTabPages = get_option('cUsCF_settings_tabpages');
                                            $getInlinePages = get_option('cUsCF_settings_inlinepages');
                                            
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
                                                        <a target="_blank" href="<?php echo get_option('home'); ?>" title="Home Preview" class="setLabels">&nbsp;</a>
                                                    </span>
                                                </div>

                                                <div class="options home">
                                                    <input type="radio" name="pages[home]" class="home-page" id="pageradio-home" value="tab" <?php echo (is_array($getTabPages) && in_array('home', $getTabPages) || $home_cus_version == 'tab') ? 'checked' : '' ?> />
                                                    <label class="label-home setLabels" for="pageradio-home" title="Will show up as a floating tab">Tab</label>
                                                    
                                                    <?php if(is_array($getInlinePages) && in_array('home', $getInlinePages) || $home_cus_version == 'inline') { ?>
                                                    <input type="radio" name="pages[home]" value="inline" id="pageradio-home-2" class="home-page" <?php echo (is_array($getInlinePages) && in_array('home', $getInlinePages) || $home_cus_version == 'inline') ? 'checked' : '' ?> />
                                                    <label class="label-home setLabels" for="pageradio-home-2" title="Inline Form appear in your website layout and posts">Inline</label>
                                                    <?php } ?>
                                                    
                                                    <a class="ui-state-default ui-corner-all pageclear-home setLabels" href="javascript:;" title="Clear Home page settings"><label class="ui-icon ui-icon-circle-close">&nbsp;</label></a>
                                                </div>
                                                
                                                <div class="form_template form-templates-home">
                                                    <h4>Pick which form you would like on this page</h4>
                                                    <div class="template_slider slider-home">
                                                        <?php
                                                        
                                                        /*
                                                         * HOME PAGE
                                                         * Render Forms Data
                                                         */
                                                        
                                                        if($cUsCF_API_getFormKeys){
                                                            $cUs_json = json_decode($cUsCF_API_getFormKeys);
                                                            
                                                            switch ( $cUs_json->status  ) {
                                                                case 'success':
                                                                    foreach ($cUs_json->data as $oForms => $oForm) {
                                                                        
                                                                        if(cUsCF_allowedFormType($oForm->form_type)) {
                                                                            
                                                                            if(strlen($home_form_key) && $home_form_key == $oForm->form_key){
                                                                                $itemClass = 'default';
                                                                            }else if(!strlen($home_form_key) && $form_key == $oForm->form_key){
                                                                                $itemClass = 'default';
                                                                            }else{
                                                                                $itemClass = 'tpl';
                                                                            }
                                                                            
                                                                            ?>
                                                                            <span class="<?php echo $itemClass; ?> item template-home" rel="<?php echo $oForm->form_key ?>">
                                                                                <img class="tab tab-home" src="<?php echo $oForm->template_desktop_tab_thumbnail; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                <img src="<?php echo $oForm->template_desktop_form_thumbnail; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                <span class="captions">
                                                                                    <p>
                                                                                        Form Name:<?php echo $oForm->form_name ?><br>
                                                                                        Form Key: <?php echo $oForm->form_key ?>
                                                                                    </p>
                                                                                </span>
                                                                                <span class="def_bak"></span>
                                                                            </span>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    break;
                                                            } //endswitch;
                                                        }
                                                        
                                                        ?>
                                                    </div>

                                                    <div class="save-options">
                                                        <input type="button" class="btn lightblue save-page save-page-home" value="Save" />
                                                    </div>
                                                    <div class="save_message save_message_home">
                                                        <p>Sending . . .</p>
                                                    </div>
                                                </div>

                                                <input type="hidden" class="cus_version_home" value="<?php echo $cus_version; ?>" />
                                                <input type="hidden" class="form_key_home" value="<?php echo (strlen($home_form_key)) ? $home_form_key : $form_key ; ?>" />
                                                
                                            </li>
                                            <script>
                                                
                                                
                                                
                                               /*
                                                * HOME PAGE JS ACTION
                                                * 
                                                * Clear home page settngs
                                                */
                                                
                                                jQuery('.pageclear-home').click(function(){
                                                    
                                                    jQuery( "#dialog-message" ).html('Do you want to delete your settings in this page?');
                                                    jQuery( "#dialog-message" ).dialog({
                                                        resizable: false,
                                                        width:430,
                                                        title: 'Delete page settings?',
                                                        height:130,
                                                        modal: true,
                                                        buttons: {
                                                            "Yes": function() {

                                                                jQuery('.home-page').removeAttr('checked');
                                                                jQuery('.label-home').removeClass('ui-state-active');

                                                                jQuery('.template-home').removeClass('default');

                                                                jQuery.deletePageSettings('home');
                                                                
                                                                jQuery( this ).dialog( "close" );

                                                            },
                                                            Cancel: function() {
                                                                jQuery( this ).dialog( "close" );
                                                            }
                                                        }
                                                    });

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
                                                
                                               /*
                                                * HOME PAGE JS ACTION
                                                * 
                                                * Save home page settngs
                                                */
                                                jQuery('.save-page-home').click(function(){ 
                                                    var cus_version_home = jQuery('.cus_version_home').val();
                                                    var form_key_home = jQuery('.form_key_home').val();

                                                    var changePage = jQuery.changePageSettings('home', cus_version_home, form_key_home); 

                                                });
                                            </script>
                                                <?php 
                                                
                                               /*
                                                * PAGE SELECTION
                                                * 
                                                * Render all main wp pages
                                                */
                                                
                                                foreach( $mypages as $page ) { 
                                                
                                                        $pageSettings = get_post_meta( $page->ID, 'cUsCF_FormByPage_settings', false );

                                                        if(is_array($pageSettings) && !empty($pageSettings)){ //NEW VERSION 3.0

                                                            $cus_version    = $pageSettings[0]['cus_version'];
                                                            $form_page_key  = $pageSettings[0]['form_key'];

                                                        } //endif;
                                                
                                                ?>
                                            
                                                    <li class="ui-widget-content">
                                                        
                                                        <div class="page_title">
                                                            <span class="title"><?php echo $page->post_title; ?></span>
                                                            <span class="bullet ui-icon ui-icon-circle-zoomin">
                                                                <a target="_blank" href="<?php echo get_permalink( $page->ID ) ;?>" title="Preview <?php echo $page->post_title; ?> page" class="setLabels">&nbsp;</a>
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="options">
                                                            <input type="radio" name="pages[<?php echo $page->ID ; ?>]" value="tab" id="pageradio-<?php echo $page->ID ; ?>-1" class="<?php echo $page->ID ; ?>-page" <?php echo (is_array($getTabPages) && in_array($page->ID, $getTabPages) || $cus_version == 'tab')?'checked':'' ?> />
                                                            <label class="setLabels label-<?php echo $page->ID ; ?>" for="pageradio-<?php echo $page->ID ; ?>-1" title="Will show up as a floating tab">Tab</label>
                                                            <input type="radio" name="pages[<?php echo $page->ID ; ?>]" value="inline" id="pageradio-<?php echo $page->ID ; ?>-2" class="<?php echo $page->ID ; ?>-page" <?php echo (is_array($getInlinePages) && in_array($page->ID, $getInlinePages) || $cus_version == 'inline')?'checked':'' ?> />
                                                            <label class="setLabels label-<?php echo $page->ID ; ?>" for="pageradio-<?php echo $page->ID ; ?>-2" title="The form was added by inserting a short code in your page. You can change its location by moving the short code within the page content">Inline</label>
                                                            <a class="ui-state-default ui-corner-all pageclear-<?php echo $page->ID ; ?> setLabels" href="javascript:;" title="Clear <?php echo $page->post_title; ?> page settings"><label class="ui-icon ui-icon-circle-close">&nbsp;</label></a>
                                                        </div>
                                                        
                                                        <div class="form_template form-templates-<?php echo $page->ID ; ?>">
                                                            <h4>Pick which Form/Tab combination you would like on <?php echo $page->post_title; ?> page</h4>
                                                            <div class="template_slider slider-<?php echo $page->ID ; ?>">
                                                                <?php 
                                                                
                                                                /*
                                                                 * MAIN WP PAGES
                                                                 * Render Forms Data
                                                                 */
                                                                
                                                                if($cUsCF_API_getFormKeys){
                                                                        
                                                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);

                                                                        switch ( $cUs_json->status  ) {
                                                                            case 'success':
                                                                                foreach ($cUs_json->data as $oForms => $oForm) { 
                                                                                    if(cUsCF_allowedFormType($oForm->form_type)) {
                                                                                        
                                                                                        if(strlen($form_page_key) && $form_page_key == $oForm->form_key){
                                                                                            $itemClass = 'default';
                                                                                        }else if(!strlen($form_page_key) && $form_key == $oForm->form_key){
                                                                                            $itemClass = 'default';
                                                                                        }else{
                                                                                            $itemClass = 'tpl';
                                                                                        }  
                                                                                        
                                                                                    ?>
                                                                                        <span class="<?php echo $itemClass; ?> item template-<?php echo $page->ID ; ?>" rel="<?php echo $oForm->form_key ?>">
                                                                                            <img class="tab tab-<?php echo $page->ID ; ?>" src="<?php echo $oForm->template_desktop_tab_thumbnail; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                            <img src="<?php echo $oForm->template_desktop_form_thumbnail; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                            <span class="captions">
                                                                                                <p>
                                                                                                    Form Name:<?php echo $oForm->form_name ?><br>
                                                                                                    Form Key: <?php echo $oForm->form_key ?>
                                                                                                </p>
                                                                                            </span>
                                                                                            <span class="def_bak"></span>
                                                                                        </span>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                break;
                                                                        } //endswitch;
                                                                    }
                                                                ?>
                                                            </div>
                                                            
                                                            <div class="save-options">
                                                                <input type="button" class="btn lightblue save-page save-page-<?php echo $page->ID ; ?>" value="Save" />
                                                            </div>
                                                            <div class="save_message save_message_<?php echo $page->ID ; ?>">
                                                                <p>Sending . . .</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <input type="hidden" class="cus_version_<?php echo $page->ID ; ?>" value="<?php echo $cus_version; ?>" />
                                                        <input type="hidden" class="form_key_<?php echo $page->ID ; ?>" value="<?php echo (strlen($form_page_key)) ? $form_page_key : $form_key ; ?>" />
                                                        
                                                    </li>
                                                    <script>
                                                        
                                                        /*
                                                         * ACTIONS BY PAGE ID
                                                         */
                                                        
                                                        jQuery('.pageclear-<?php echo $page->ID ; ?>').click(function(){
                                                            
                                                            jQuery( "#dialog-message" ).html('Do you want to delete your settings in this page?');
                                                            jQuery( "#dialog-message" ).dialog({
                                                                resizable: false,
                                                                width:430,
                                                                title: 'Delete page settings?',
                                                                height:130,
                                                                modal: true,
                                                                buttons: {
                                                                    "Yes": function() {

                                                                        jQuery('.<?php echo $page->ID ; ?>-page').removeAttr('checked');
                                                                        jQuery('.label-<?php echo $page->ID ; ?>').removeClass('ui-state-active');

                                                                        jQuery('.template-<?php echo $page->ID ; ?>').removeClass('default');

                                                                        jQuery.deletePageSettings(<?php echo $page->ID ; ?>);

                                                                        jQuery( this ).dialog( "close" );

                                                                    },
                                                                    Cancel: function() {
                                                                        jQuery( this ).dialog( "close" );
                                                                    }
                                                                }
                                                            });
                                                            
                                                        });
                                                        jQuery('.<?php echo $page->ID ; ?>-page').click(function(){
                                                            jQuery('.form_template').fadeOut();
                                                            jQuery('.form-templates-<?php echo $page->ID ; ?>').slideDown();
                                                            
                                                            jQuery('.cus_version_<?php echo $page->ID ; ?>').val( jQuery(this).val() );
                                                            
                                                            var version = jQuery(this).val();
                                                            
                                                            if(version == 'tab'){
                                                                jQuery('img.tab-<?php echo $page->ID ; ?>').show();
                                                            }else{
                                                                jQuery('img.tab-<?php echo $page->ID ; ?>').hide();
                                                            }
                                                            
                                                            
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
                                                
                                                } //endforeach; 
                                                
                                            ?>
                                        </ul>
                                      
                                        <?php } //endif; ?>
                                    </div>
                                    <input type="hidden" name="cus_version" value="selectable" />
                                    <input type="hidden" value="settings" name="option" />
                                </form>
                                
                            </div><!-- // TAB LEFT -->
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://bit.ly/1hjjYjv" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                    <h3 class="review">Plugin Overview</h3> 
                                    
                                    <div class="video">
                                        
                                        <iframe src="//player.vimeo.com/video/79343284" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        
                                    </div>
                                    
                                    <p><a href="http://bit.ly/1cwSocX" target="_blank" class="btn large lightblue">Other plugins by ContactUs.com</a></p>
                                    
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://bit.ly/1a6cvf7" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                    <p><a href="http://bit.ly/18pJ8dp" target="_blank" class="btn large lightblue">Support</a></p>
                                    
                                </div>
                                
                                 
                            </div><!-- // TAB RIGHT -->
                            
                        </div>
                        
                        <div id="tabs-2">
                            
                                <div class="left-content">
                                    <h2>Configure your form settings</h2>
                                    
                                    <div class="versions_options versions_options_fs">
                                        
                                        <div class="button_set_tabs">
                                            <?php
                                            
                                           /*
                                            * DEEP LINKS
                                            */

                                            $default_deep_link = $cUsCF_api->parse_deeplink ( $default_deep_link );
                                            
                                            $reports = $default_deep_link.'?pageID=12';
                                            $upgrade = $default_deep_link.'?pageID=82';
                                            $createform = $default_deep_link.'?pageID=81&id=0&do=addnew&formType=';

                                            ?>
                                            <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($reports)); ?>" target="_blank" class="deep_link_action_tab rep">Detailed Reports</a>
                                            <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($upgrade)); ?>" target="_blank" class="deep_link_action_tab ac">Upgrade Account</a>
                                        </div>
                                        
                                        <p>Manage your forms here. You have a default contact form. You can create more forms, including Newsletter Forms, Appointment Forms and Donation Forms.</p>
                                        
                                        <div class="advice_notice">Advices....</div>
                                        
                                        <!-- CONTACT FORMS-->
                                        <div class="gray_box">
                                            <h3 class="form_title">CONTACT FORMS <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>contact_us" target="_blank">Add a New Contact Form <span>+</span></a></h3>
                                            
                                                <?php
                                               /*
                                                * USER FORMS
                                                * Render All user Forms & Data
                                                */

                                                /*
                                                 * Get Forms Data // contact_us FORM TYPES
                                                 */
                                                
                                                if($cUsCF_API_getFormKeys){
                                                    
                                                    $form_Type = 'contact_us';
                                                    
                                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);
                                                        switch ( $cUs_json->status  ) {
                                                            case 'success': ?>
                                                            <div class="user_templates">
                                                            <?php
                                                              $nCF = 1; 
                                                              foreach ($cUs_json->data as $oForms => $oForm) {
                                                                
                                                                if(cUsCF_allowedFormType($oForm->form_type) && $oForm->form_type == $form_Type) {
                                                                    $formID = $oForms;
                                                                    //RE-ASSING DEFAULT FORM KEY
                                                                    //if($oForm->default == 1){ updateDefaultFormKey($oForm->form_key); }
                                                                    ?>
                                                                    <h3>Form Name: <?php echo $oForm->form_name ?> <?php echo ($oForm->default == 1)?' - [ Default ]':''?></h3>
                                                                    <div>
                                                                        <div class="terminology_c">
                                                                            <div class="template_info">
                                                                                <p class="form_key"><b>FORM KEY:</b> <?php echo $oForm->form_key ?></p>
                                                                                <div class="template-thumb">
                                                                                    <p><b>Form Template:</b> <?php echo $oForm->template_desktop_form ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_desktop_form_thumbnail ?>" class="form_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" /></span>

                                                                                    <p><b>Tab Template:</b> <?php echo $oForm->template_desktop_tab ?></p>
                                                                                    <img src="<?php echo $oForm->template_desktop_tab_thumbnail ?>" class="tab_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                </div>
                                                                                <div class="template-desc">
                                                                                    <p><b>Mobile Form Template:</b> <?php echo $oForm->template_mobile_form ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_mobile_form_thumbnail ?>"  /></span>

                                                                                    <p><b>Website URL:</b> <?php echo $oForm->website_url ?></p>
                                                                                </div>
                                                                            </div>

                                                                            <h4>Instructions on how to build short codes and theme snippets <a href="javascript:;" class="blue_link" onclick="jQuery( '#cUsCF_tabs' ).tabs({ active: 2 })"> Here. </a></h4>

                                                                            <div class="template-selection">
                                                                                <div class="form_templates_aCc">

                                                                                    <h4>Form Tools</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form">
                                                                                            <div class="button_set">
                                                                                                <?php 

                                                                                                $default_deep_link = $oForm->deep_link_view;
                                                                                                $ablink = $cUsCF_api->parse_deeplink ( $default_deep_link );
                                                                                                $ablink = $ablink.'?pageID=90&do=view&formID='.$oForm->form_id;

                                                                                                ?>
                                                                                                                                                                                            
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=14%26newTemplate=genericTemplate2" target="_blank" class="btn lightblue abutton cf"  >Custom Fields</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=4" target="_blank" class="btn lightblue abutton confF"  >Configure Form</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=5" target="_blank" class="btn lightblue abutton ct"  >Configure Tab</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($ablink)); ?>" target="_blank" class="btn lightblue abutton AbTest"  >A/B Test</a>                                                                                               
                                                                                                

                                                                                            </div>
                                                                                            
                                                                                            <p><strong>NOTE:</strong> You will be redirected to your ContactUs.com admin panel to edit your form configurations.</p>
                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <h4>Delivery Options / 3rd Party services</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form delivery_options">
                                                                                            <div class="button_set">
                                                                                                <?php $default_deep_link = $oForm->deep_link_view; ?>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo $cUs_API_Key; ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=103" target="_blank" rel="toDash" class="btn lightblue abutton">3rd Party Integrations</a>                                                                                                
                                                                                            </div>
                                                                                            <p>How to manage or configure your software integration services? <a href="http://bit.ly/1gvehyV" class="blue_link" target="_blank">Click here</a></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    $nCF++;
                                                                    //END IF ALLOWED TYPES
                                                                    }
                                                                    
                                                                }
                                                                ?>
                                                                </div>
                                                                <?php    
                                                                break;
                                                        } //endswitch;
                                                        
                                                        if($nCF <= 1){ ?>
                                                            <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>contact_us" target="_blank" class="deep_link_action">Add Contact Form <span>+</span></a>
                                                        <?php }
                                                    }
                                                ?>
                                        </div>
                                        <!-- CONTACT FORMS-->
                                        
                                        <!-- NEWSLETTER FORMS-->
                                        <div class="gray_box">
                                            <h3 class="form_title">NEWSLETTER FORMS <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>newsletter" target="_blank">Add a New Newsletter Form <span>+</span></a></h3>
                                           
                                                <?php
                                               /*
                                                * USER FORMS
                                                * Render All user Forms & Data
                                                */

                                                /*
                                                 * Get Forms Data // newsletter FORM TYPES
                                                 */
                                                $form_Type = 'newsletter';
                                                //$cUsCF_API_getFormKeys = $cUsCF_api->getFormKeysData($cUs_API_Account, $cUs_API_Key , $form_Type); //api hook;
                                                if($cUsCF_API_getFormKeys){
                                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);
                                                        switch ( $cUs_json->status  ) {
                                                            case 'success': ?>
                                                            <div class="user_templates">
                                                            <?php
                                                                $nNL = 1;
                                                                foreach ($cUs_json->data as $oForms => $oForm) {
                                                                    if(cUsCF_allowedFormType($oForm->form_type) && $oForm->form_type == $form_Type) {
                                                                    $formID = $oForms;
                                                                    ?>
                                                                    <h3>Form Name: <?php echo $oForm->form_name ?> <?php echo ($oForm->default == 1)?' - [ Default ]':''?></h3>
                                                                    <div>
                                                                        <div class="terminology_c">
                                                                            <div class="template_info">
                                                                                <p class="form_key"><b>FORM KEY:</b> <?php echo $oForm->form_key ?></p>
                                                                                <div class="template-thumb">
                                                                                    <p><b>Form Template:</b> <?php echo $oForm->template_desktop_form ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_desktop_form_thumbnail; ?>" class="form_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" /></span>

                                                                                    <p><b>Tab Template:</b> <?php echo $oForm->template_desktop_tab ?></p>
                                                                                    <img src="<?php echo $oForm->template_desktop_tab_thumbnail; ?>" class="tab_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                </div>
                                                                                <div class="template-desc">
                                                                                    <p><b>Mobile Form Template:</b> <?php echo $oForm->template_mobile_form ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_mobile_form_thumbnail; ?>"  /></span>

                                                                                    <p><b>Website URL:</b> <?php echo $oForm->website_url ?></p>
                                                                                </div>
                                                                            </div>

                                                                            <h4>Instructions on how tu build short codes and theme snippets <a href="javascript:;" class="blue_link" onclick="jQuery( '#cUsCF_tabs' ).tabs({ active: 2 })"> Here. </a></h4>

                                                                            <div class="template-selection">
                                                                                <div class="form_templates_aCc">
                                                                                    <h4>Form Tools</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form">
                                                                                            <div class="button_set">
                                                                                                <?php 

                                                                                                $default_deep_link = $oForm->deep_link_view;
                                                                                                $ablink = $cUsCF_api->parse_deeplink ( $default_deep_link );
                                                                                                $ablink = $ablink.'?pageID=90&do=view&formID='.$oForm->form_id;

                                                                                                ?>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=4" target="_blank" class="btn lightblue abutton confF"  >Configure Form</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=5" target="_blank" class="btn lightblue abutton ct"  >Configure Tab</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($ablink)); ?>" target="_blank" class="btn lightblue abutton AbTest"  >A/B Test</a>                                                                                               

                                                                                            </div>
                                                                                            
                                                                                            <p><strong>NOTE:</strong> You will be redirected to your ContactUs.com admin panel to edit your form configurations.</p>
                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                    <h4>Delivery Options / 3rd Party services</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form delivery_options">
                                                                                            <div class="button_set">
                                                                                                <?php $default_deep_link = $oForm->deep_link_view; ?>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo $cUs_API_Key; ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=103" target="_blank" rel="toDash" class="btn lightblue abutton">3rd Party Integrations</a>                                                                                                
                                                                                            </div>
                                                                                            <p>How to manage or configure your software integration services? <a href="http://bit.ly/1gvehyV" class="blue_link" target="_blank">Click here</a></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                        $nNL++;
                                                                    }
                                                                } ?>
                                                                </div>
                                                                <?php    
                                                                break;
                                                        } //endswitch;
                                                        if($nNL <= 1){ ?>
                                                            <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>newsletter" target="_blank" class="deep_link_action">Add Newsletter Form <span>+</span></a>
                                                        <?php }
                                                    }
                                                ?>
                                        </div>
                                        <!-- NEWSLETTER FORMS-->
                                        
                                        <!-- APPOINTMENTS FORMS-->
                                        <div class="gray_box">
                                            <h3 class="form_title">APPOINTMENT FORMS <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>appointment" target="_blank">Add a New Appointment Form <span>+</span></a></h3>
                                            
                                                <?php
                                               /*
                                                * USER FORMS
                                                * Render All user Forms & Data
                                                */

                                                /*
                                                 * Get Forms Data // appointment FORM TYPES
                                                 */
                                                $form_Type = 'appointment';
                                                //$cUsCF_API_getFormKeys = $cUsCF_api->getFormKeysData($cUs_API_Account, $cUs_API_Key , $form_Type); //api hook;
                                                if($cUsCF_API_getFormKeys){
                                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);
                                                        switch ( $cUs_json->status  ) {
                                                            case 'success': ?>
                                                            <div class="user_templates">
                                                            <?php
                                                                $nAF = 1; 
                                                                foreach ($cUs_json->data as $oForms => $oForm) {
                                                                    if(cUsCF_allowedFormType($oForm->form_type) && $oForm->form_type == $form_Type) {
                                                                    $formID = $oForms;
                                                                    ?>
                                                                    <h3>Form Name: <?php echo $oForm->form_name ?> <?php echo ($oForm->default == 1)?' - [ Default ]':''?></h3>
                                                                    <div>
                                                                        <div class="terminology_c">
                                                                            <div class="template_info">
                                                                                <p class="form_key"><b>FORM KEY:</b> <?php echo $oForm->form_key; ?></p>
                                                                                <div class="template-thumb">
                                                                                    <p><b>Form Template:</b> <?php echo $oForm->template_desktop_form; ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_desktop_form_thumbnail; ?>" class="form_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" /></span>

                                                                                    <p><b>Tab Template:</b> <?php echo $oForm->template_desktop_tab; ?></p>
                                                                                    <img src="<?php echo $oForm->template_desktop_tab_thumbnail; ?>" class="tab_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                </div>
                                                                                <div class="template-desc">
                                                                                    <p><b>Mobile Form Template:</b> <?php echo $oForm->template_mobile_form; ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_mobile_form_thumbnail; ?>"  /></span>

                                                                                    <p><b>Website URL:</b> <?php echo $oForm->website_url; ?></p>
                                                                                </div>
                                                                            </div>

                                                                            <h4>Instructions on how tu build short codes and theme snippets <a href="javascript:;" class="blue_link" onclick="jQuery( '#cUsCF_tabs' ).tabs({ active: 2 })"> Here. </a></h4>

                                                                            <div class="template-selection">
                                                                                <div class="form_templates_aCc">

                                                                                    <h4>Form Tools</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form">
                                                                                            <div class="button_set">
                                                                                                <?php 

                                                                                                $default_deep_link = $oForm->deep_link_view;
                                                                                                $ablink = $cUsCF_api->parse_deeplink ( $default_deep_link );
                                                                                                $ablink = $ablink.'?pageID=90&do=view&formID='.$oForm->form_id;

                                                                                                ?>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=4" target="_blank" class="btn lightblue abutton confF"  >Configure Form</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=5" target="_blank" class="btn lightblue abutton ct"  >Configure Tab</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($ablink)); ?>" target="_blank" class="btn lightblue abutton AbTest"  >A/B Test</a>                                                                                               

                                                                                            </div>
                                                                                            <p><strong>NOTE:</strong> You will be redirected to your ContactUs.com admin panel to edit your form configurations.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <h4>Delivery Options / 3rd Party services</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form delivery_options">
                                                                                            <div class="button_set">
                                                                                                <?php $default_deep_link = $oForm->deep_link_view; ?>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo $cUs_API_Key; ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=103" target="_blank" rel="toDash" class="btn lightblue abutton">3rd Party Integrations</a>                                                                                                
                                                                                            </div>
                                                                                            <p>How to manage or configure your software integration services? <a href="http://bit.ly/1gvehyV" class="blue_link" target="_blank">Click here</a></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                        $nAF++;
                                                                    }
                                                                } ?>
                                                                </div>
                                                                <?php    
                                                                break;
                                                        } //endswitch;
                                                        
                                                        if($nAF <= 1 ){ ?>
                                                            <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>appointment" target="_blank" class="deep_link_action">Add Appointment Form <span>+</span></a>
                                                        <?php }
                                                    }
                                                ?>
                                        </div>
                                        <!-- APPOINTMENTS FORMS-->
                                        
                                        <!-- DONATION FORMS-->
                                        <div class="gray_box">
                                            <h3 class="form_title">DONATION FORMS <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>donation" target="_blank">Add a New Donation Form <span>+</span></a></h3>
                                            
                                                <?php
                                               /*
                                                * USER FORMS
                                                * Render All user Forms & Data
                                                */

                                                /*
                                                 * Get Forms Data // donation FORM TYPES
                                                 */
                                                $form_Type = 'donation';
                                                //$cUsCF_API_getFormKeys = $cUsCF_api->getFormKeysData($cUs_API_Account, $cUs_API_Key , $form_Type); //api hook;
                                                if($cUsCF_API_getFormKeys){
                                                    $cUs_json = json_decode($cUsCF_API_getFormKeys);
                                                        switch ( $cUs_json->status  ) {
                                                           case 'success': ?>
                                                            <div class="user_templates">
                                                            <?php
                                                                $nDF = 1;
                                                                foreach ($cUs_json->data as $oForms => $oForm) {
                                                                    if(cUsCF_allowedFormType($oForm->form_type) && $oForm->form_type == $form_Type) {
                                                                    $formID = $oForms;
                                                                    ?>
                                                                    <h3>Form Name: <?php echo $oForm->form_name ?> <?php echo ($oForm->default == 1)?' - [ Default ]':''?></h3>
                                                                    <div>
                                                                        <div class="terminology_c">
                                                                            <div class="template_info">
                                                                                <p class="form_key"><b>FORM KEY:</b> <?php echo $oForm->form_key; ?></p>
                                                                                <div class="template-thumb">
                                                                                    <p><b>Form Template:</b> <?php echo $oForm->template_desktop_form; ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_desktop_form_thumbnail; ?>" class="form_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" /></span>

                                                                                    <p><b>Tab Template:</b> <?php echo $oForm->template_desktop_tab; ?></p>
                                                                                    <img src="<?php echo $oForm->template_desktop_tab_thumbnail; ?>" class="tab_thumb_<?php echo $formID; ?>" alt="<?php echo $oForm->form_name ?>" title="Form Name:<?php echo $oForm->form_name ?> - Form Key: <?php echo $oForm->form_key ?>" />
                                                                                </div>
                                                                                <div class="template-desc">
                                                                                    <p><b>Mobile Form Template:</b> <?php echo $oForm->template_mobile_form; ?></p>
                                                                                    <span class="thumb"><img src="<?php echo $oForm->template_mobile_form_thumbnail; ?>"  /></span>

                                                                                    <p><b>Website URL:</b> <?php echo $oForm->website_url ?></p>
                                                                                </div>
                                                                            </div>

                                                                            <h4>Instructions on how tu build short codes and theme snippets <a href="javascript:;" class="blue_link" onclick="jQuery( '#cUsCF_tabs' ).tabs({ active: 2 })"> Here. </a></h4>

                                                                            <div class="template-selection">
                                                                                <div class="form_templates_aCc">
                                                                                    
                                                                                    <h4>Form Tools</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form">
                                                                                            <div class="button_set">
                                                                                                <?php 

                                                                                                $default_deep_link = $oForm->deep_link_view;
                                                                                                $ablink = $cUsCF_api->parse_deeplink ( $default_deep_link );
                                                                                                $ablink = $ablink.'?pageID=90&do=view&formID='.$oForm->form_id;

                                                                                                ?>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=4" target="_blank" class="btn lightblue abutton confF"  >Configure Form</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=5" target="_blank" class="btn lightblue abutton ct"  >Configure Tab</a>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($ablink)); ?>" target="_blank" class="btn lightblue abutton AbTest"  >A/B Test</a>                                                                                               

                                                                                            </div>
                                                                                            <p><strong>NOTE:</strong> You will be redirected to your ContactUs.com admin panel to edit your form configurations.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <h4>Delivery Options / 3rd Party services</h4>
                                                                                    <div>
                                                                                        <div class="form_templates terminology_c Template_Contact_Form delivery_options">
                                                                                            <div class="button_set">
                                                                                                <?php $default_deep_link = $oForm->deep_link_view; ?>
                                                                                                <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo $cUs_API_Key; ?>&confirmed=1&redir_url=<?php echo urlencode(trim($default_deep_link)); ?>%26expand=103" target="_blank" rel="toDash" class="btn lightblue abutton">3rd Party Integrations</a>                                                                                                
                                                                                            </div>
                                                                                            <p>How to manage or configure your software integration services? <a href="http://bit.ly/1gvehyV" class="blue_link" target="_blank">Click here</a></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    $nDF++;
                                                                    }
                                                                } ?>
                                                                </div>
                                                                <?php    
                                                                break;
                                                        } //endswitch;
                                                        
                                                        if($nDF <= 1){ ?>
                                                            <a href="<?php echo cUsCF_PARTNER_URL; ?>/index.php?loginName=<?php echo $cUs_API_Account; ?>&userPsswd=<?php echo urlencode($cUs_API_Key); ?>&confirmed=1&redir_url=<?php echo urlencode(trim($createform)); ?>donation" target="_blank" class="deep_link_action">Add Donation Form <span>+</span></a>
                                                        <?php }
                                                    }
                                                ?>
                                        </div>
                                        <!-- DONATION FORMS-->  

                                    </div>
                                </div>
                            
                                <div class="right-content">
                                    <div class="upgrade_features">

                                        <h3 class="review">Give a 5 stars review on </h3>
                                        <a href="http://bit.ly/1hjjYjv" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
                                        <h3 class="review">Plugin Overview</h3> 
                                    
                                        <div class="video">

                                            <iframe src="//player.vimeo.com/video/79343284" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

                                        </div>

                                        <p><a href="http://bit.ly/1cwSocX" target="_blank" class="btn large lightblue">Other plugins by ContactUs.com</a></p>

                                        <h3>Discover our great features</h3>
                                        <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>

                                        <a href="http://bit.ly/1a6cvf7" target="_blank" class="btn large orange">Upgrade Your Account</a>

                                        <p><a href="http://bit.ly/18pJ8dp" target="_blank" class="btn large lightblue">Support</a></p>

                                    </div>
                                </div><!-- // TAB RIGHT -->
                            
                        
                        </div>
                        
                        <div id="tabs-3">
                            <div class="left-content">
                                
                                <h2>WordPress Shortcodes and Snippets</h2>
                                <div>
                                    <div class="terminology_c">
                                        <h4>Copy this code into your template, post, page to place the form wherever you want it.  If you use this advanced method, do not select any pages from the page section on the form settings or you may end up with the form displayed on your page twice.</h4>
                                        <h4>Note: You can find the Form Key alongside form thumbnails in the form settings tab.</h4>
                                        <hr/>
                                        <ul class="hints">
                                            <li><b>Inline</b>
                                                <br/>WP Shortcode:<br/> <code> [show-contactus.com-form formkey="FORM KEY HERE" version="inline"] </code>
                                                <br/>Php Snippet:<br/> <code>&#60;&#63;php echo do_shortcode('[show-contactus.com-form formkey="FORM KEY HERE" version="inline"]'); &#63;&#62;</code>
                                            </li>
                                            <li><b>Tab</b>
                                                <br/>WP Shortcode:<br/> <code> [show-contactus.com-form formkey="FORM KEY HERE" version="tab"] </code>
                                                <br/>Php Snippet:<br/> <code>&#60;&#63;php echo do_shortcode('[show-contactus.com-form formkey="FORM KEY HERE" version="tab"]'); &#63;&#62;</code>
                                            </li>
                                            <li><b>Widget Tool</b><br/><p>Go to <a href="widgets.php"><b>Widgets here </b></a> and drag the ContactUs.com widget into one of your widget areas</p></li>
                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="right-content">
                                <div class="upgrade_features">
                                    
                                    <h3 class="review">Give a 5 stars review on </h3>
                                    <a href="http://bit.ly/1hjjYjv" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
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
                                    
                                    <a href="http://bit.ly/1a6cvf7" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->
                            
                        </div>
                        
                        <div id="tabs-4">
                            
                            <div class="left-content">
                                <h2>Your ContactUs.com Account</h2>
                                
                                <div class="iRecomend">
                                    <form method="post" action="admin.php?page=cUs_malchimp_plugin" id="cUsCF_data" name="cUsCF_sendkey" class="steps" onsubmit="return false;">
                                        
                                        <table class="form-table">
                                            
                                            <?php if( strlen($options['fname']) || strlen($options['lname']) || strlen($current_user->first_name) ) { ?>
                                            <tr>
                                                <th><label class="labelform">Name</label><br>
                                                <td>
                                                    <span class="cus_names">
                                                        <?php echo ( strlen($options['fname']) || strlen($options['lname']) ) ? $options['fname'] . " " . $options['lname'] : $current_user->first_name . " " . $current_user->last_name ; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php } ?>
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
                                    <a href="http://bit.ly/1hjjYjv" target="_blank">Wordpress.org <img src="<?php echo plugins_url('style/images/five_stars.png', __FILE__) ;?> " /></a><br/><br/>
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
                                    
                                    <a href="http://bit.ly/1a6cvf7" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->
                            
                        </div>
                        <?php }else{ 
                            
                           /*
                            * MIGRATION FROM PREVIOUS VERSIONS
                            * Login Form
                            */
                            
                            ?>
                        <div id="tabs-1">
                            
                            <div class="left-content">
                                
                                <h3>Note:</h3>
                                <p>Hi ContactUs users, welcome to your V4.0 Contact Form Plugin!, in order for our new cool upgrades to work, you need to sign in to your ContactUs account here. This is a one time thing, after up-grade is done, we wont ask this again.</p>
                               

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
                                    
                                    <h3 class="review">Plugin Overview</h3> 
                                    
                                    <div class="video">
                                        
                                        <iframe src="//player.vimeo.com/video/79343284" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        
                                    </div>
                                    
                                    <p><a href="http://bit.ly/1cwSocX" target="_blank" class="btn large lightblue">Other plugins by ContactUs.com</a></p>
                                    
                                    <h3>Discover our great features</h3>
                                    <p>Enjoying the Free version of ContactUs.com? You will Love ContactUs.com Pro versions.</p>
                                    
                                    <a href="http://bit.ly/1a6cvf7" target="_blank" class="btn large orange">Upgrade Your Account</a>
                                    
                                    <p><a href="http://bit.ly/18pJ8dp" target="_blank" class="btn large lightblue">Support</a></p>
                                    
                                </div>
                            </div><!-- // TAB RIGHT -->  
                            
                        </div>
                        <?php } //USERS CREDENTIALS UPDATE ?>
                        
                    <?php } ?>

            </div>
        </div>

        <?php
    } //END IF

} // END IF FUNCTION RENDER