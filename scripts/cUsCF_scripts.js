
//PLUGIN cUsCF_myjq ENVIROMENT (cUsCF_myjq)
var cUsCF_myjq = jQuery.noConflict();

cUsCF_myjq(window).error(function(e){
    e.preventDefault();
});

//ON READY DOM LOADED
cUsCF_myjq(document).ready(function($) {
    
    try{
        
        //LOADING UI BOX
        cUsCF_myjq( ".cUsCF_preloadbox" ).delay(1500).fadeOut();
        
        //UI TABS
        cUsCF_myjq( "#cUsCF_tabs" ).tabs({active: false});
        
        //GO TO SHORTCODES TABS LINK
        cUsCF_myjq( ".goto_shortcodes" ).click(function(){
            cUsCF_myjq( "#cUsCF_tabs" ).tabs({ active: 2 });
        });
        
        //UNBIND UI TABS LINK ON CLICK
        cUsCF_myjq("li.gotohelp a").unbind('click');
        
        //FORMS AND TABS TEMPLATE SELECTION SLIDER
        cUsCF_myjq('.selectable_cf, .selectable_tabs_cf').bxSlider({
            slideWidth: 160,
            minSlides: 4,
            maxSlides: 4,
            moveSlides:1,
            infiniteLoop:false,
            //captions:true,
            pager:true,
            slideMargin: 5
        });
        
        //PAGES FORM SELECTION SLIDER
        cUsCF_myjq('.template_slider').bxSlider({
            slideWidth: 160,
            minSlides: 4,
            maxSlides: 4,
            moveSlides:1,
            infiniteLoop:false,
            preloadImages:'all',    
            //captions:true,
            pager:true,
            slideMargin: 5
        });
        
        //colorbox window
        cUsCF_myjq(".tooltip_formsett").colorbox({iframe:true, innerWidth:'75%', innerHeight:'80%'});   
        
        //TEMPLATE SELECTION
        cUsCF_myjq( '.options' ).buttonset();
        cUsCF_myjq( '.form_types' ).buttonset();
        cUsCF_myjq( '#inlineradio' ).buttonset();
        
        cUsCF_myjq( '.bx-loading' ).hide(); //DOM BUG FIX
        
        //SELECTED CONTACT FORM TEMPLATE
        cUsCF_myjq(".selectable_cf, .selectable_news").selectable({
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#Template_Desktop_Form').val(idEl);           
            }                   
        });
        
        //SELECTED FORM TAB TEMPLATE
        cUsCF_myjq(".selectable_tabs_cf, .selectable_tabs_news").selectable({
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#Template_Desktop_Tab').val(idEl);           
            }                   
        });
        
        //SELECTED CONTACT FORM TEMPLATE
        cUsCF_myjq(".selectable_ucf, .selectable_unews").selectable({
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#uTemplate_Desktop_Form').val(idEl);           
            }                   
        });
        
        //SELECTED FORM TAB TEMPLATE
        cUsCF_myjq(".selectable_tabs_ucf, .selectable_tabs_unews").selectable({
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#uTemplate_Desktop_Tab').val(idEl);           
            }                   
        });

        //UI ACCORDIONS
        cUsCF_myjq( "#terminology" ).accordion({
            collapsible: true,
            heightStyle: "content",
            active: false,
            icons: { "header": "ui-icon-info", "activeHeader": "ui-icon-arrowreturnthick-1-n" }
        });
        
        cUsCF_myjq( "#user_forms" ).accordion({
            collapsible: true,
            heightStyle: "content",
            active: true,
            icons: { "header": "ui-icon-circle-plus", "activeHeader": "ui-icon-circle-minus" }
        });
        
        cUsCF_myjq( ".user_templates" ).accordion({
            collapsible: true,
            active: false,
            heightStyle: "content",
            icons: { "header": "ui-icon-circle-plus", "activeHeader": "ui-icon-circle-minus" }
        });
        
        cUsCF_myjq( "#form_examples, #tab_examples" ).accordion({
            collapsible: true,
            heightStyle: "content",
            icons: { "header": "ui-icon-info", "activeHeader": "ui-icon-arrowreturnthick-1-n" }
        });
        
        cUsCF_myjq( ".form_templates_aCc" ).accordion({
            collapsible: true,
            heightStyle: "content",
            icons: { "header": "ui-icon-circle-plus", "activeHeader": "ui-icon-circle-minus" }
        });
        
        cUsCF_myjq( ".signup_templates" ).accordion({
            collapsible: true,
            heightStyle: "content",
            icons: { "header": "ui-icon-info", "activeHeader": "ui-icon-arrowreturnthick-1-n" }
        });
       
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Error - please update your version of WordPress to the latest version. If the problem continues, contact us at support@contactus.com.: ' + err ).slideToggle().delay(2000).fadeOut(2000);
    }
    
    //TOOLTIPS
    try{
        //JQ UI TOOLTIPS
        cUsCF_myjq(".setLabels").tooltip();
        cUsCF_myjq(".setLabel_All").tooltip({content: function (){ var t = 'Places Default Form on all pages'; return t;}  });
        cUsCF_myjq(".setLabel_Custom").tooltip({content:function (){var t = 'Lets You Choose Different Forms for Each Page'; return t;}});
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Error - please update your version of WordPress to the latest version. If the problem continues, contact us at support@contactus.com. ' + err ).slideToggle().delay(2000).fadeOut(2000);
    }
    
    try{
        cUsCF_myjq('.setDefaulFormKey').change(function(){
            var sRadio = cUsCF_myjq(this);
            var form_key = cUsCF_myjq(this).val();
            cUsCF_myjq('.loadingMessage.def').show();
            cUsCF_myjq('.defaultF li .setLabel').html('<span class="ui-button-text">Set as Default</span>');
            //AJAX POST CALL setDefaulFormKey
            cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_setDefaulFormKey',formKey:form_key},
                success: function(data) {

                    switch(data){
                        //SAVED
                        case '1':
                            
                            message = '<p>Form Key saved succesfuly . . . .</p>';
                            sRadio.next().html('<span class="ui-button-text">Default</span>');
                            break;
                        //API OR CONNECTION ISSUES
                        default:
                            message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
                            cUsCF_myjq('.advice_notice').html(message).show();
                            break;
                    }

                    cUsCF_myjq('.loadingMessage.def').fadeOut();

                },
                fail: function(){ //AJAX FAIL
                   message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
                   cUsCF_myjq('.advice_notice').html(message).show();
                   cUsCF_myjq('.loadingMessage.def').fadeOut();
                },
                async: false
            });
            
        });
    }catch(err){
        console.log(err);
    }
    
    
    //LOGIN ALREADY CUS OR OLD CUS USERS
    try{
        cUsCF_myjq('.cUsCF_LoginUser').click(function(e){
            
            e.preventDefault();
            
            var email = cUsCF_myjq('#login_email').val();
            var pass = cUsCF_myjq('#user_pass').val();
            cUsCF_myjq('.loadingMessage').show();

            //LENGTH VALIDATIONS
            if(!email.length){
                cUsCF_myjq('.advice_notice').html('User Email is a required and valid field').slideToggle().delay(2000).fadeOut(2000);
                cUsCF_myjq('#login_email').focus();
                cUsCF_myjq('.loadingMessage').fadeOut();
            }else if(!pass.length){
                cUsCF_myjq('.advice_notice').html('User password is a required field').slideToggle().delay(2000).fadeOut(2000);
                cUsCF_myjq('#user_pass').focus();
                cUsCF_myjq('.loadingMessage').fadeOut();
            }else{
                var bValid = checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. sergio@cUsCF_myjq.com" );  
                if(!bValid){ //EMAIL VALIDATION
                    cUsCF_myjq('.advice_notice').html('Please enter a valid User Email').slideToggle().delay(2000).fadeOut(2000);
                    cUsCF_myjq('.loadingMessage').fadeOut();
                }else{

                    cUsCF_myjq('.cUsCF_LoginUser').val('Loading . . .').attr('disabled', true);

                    //AJAX POST CALL
                    cUsCF_myjq.ajax({ type: "POST", dataType:'json', url: ajax_object.ajax_url, data: {action:'cUsCF_loginAlreadyUser',email:email,pass:pass},
                        success: function(data) {

                            switch(data.status){

                                //USER CRATED SUCCESS
                                case 1:

                                    cUsCF_myjq('.cUsCF_LoginUser').val('Success . . .');

                                    message = '<p>Welcome to ContactUs.com</p>';

                                    setTimeout(function(){
                                        cUsCF_myjq('#cUsCF_loginform').slideUp().fadeOut();
                                        location.reload();
                                    },2500);

                                    cUsCF_myjq('.notice').html(message).show().delay(3000).fadeOut();
                                    cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false);

                                break;

                                //OLD USER DON'T HAVE DEFAULT CONTACT FORM
                                case 2:

                                    cUsCF_myjq('.cUsCF_LoginUser').val('Error . . .');

                                    message = '<p>To continue, you will need to create a default contact form.</p>';
                                    message += '<p> This takes just a few minutes by logging in to your ContactUs.com admin panel with the credentials you used to setup the plugin. '; 
                                    message += '<a href="https://admin.contactus.com/partners/index.php?loginName='+data.cUs_API_Account;
                                    message += '&userPsswd='+data.cUs_API_Key+'&confirmed=1&redir_url='+data.deep_link_view+'?';
                                    message += encodeURIComponent('pageID=81&id=0&do=addnew&formType=contact_us');
                                    message += ' " target="_blank">Click here to continue</a></p>';
                                    message += '<p>you will be redirected to our admin login page.</p>';

                                    cUsCF_myjq.messageDialogLogin('Default Contact Form Required');

                                    cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false);
                                    
                                    cUsCF_myjq('#dialog-message').html(message);


                                break;

                                //API ERROR OR CONECTION ISSUES
                                case 3:
                                    cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false);
                                    message = '<p>Unfortunately, we weren’t able to log you into your ContactUs.com account.</p>';
                                    message += '<p>Please try again with the email address and password used when you created a ContactUs.com account. If you still aren’t able to log in, please submit a ticket to our support team at <a href="http://help.contactus.com" target="_blank">http://help.contactus.com.</a></p>';
                                    message += '<p>Error:  <b>' + data.message + '</b></p>';
                                    cUsCF_myjq('.advice_notice').html(message).show();
                                break;

                                //API ERROR OR CONECTION ISSUES
                                case '':
                                default:
                                    cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false);
                                    message = '<p>Unfortunately, we weren’t able to log you into your ContactUs.com account.</p>';
                                    message += '<p>Please try again with the email address and password used when you created a ContactUs.com account. If you still aren’t able to log in, please submit a ticket to our support team at <a href="http://help.contactus.com" target="_blank">http://help.contactus.com.</a></p>';
                                    message += '<p>Error:  <b>' + data.message + '</b></p>';
                                    cUsCF_myjq('.advice_notice').html(message).show();
                                    break;
                            }

                            cUsCF_myjq('.loadingMessage').fadeOut();


                        },
                        fail: function(){ //AJAX FAIL
                           message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
                           cUsCF_myjq('.advice_notice').html(message).show();
                           cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false); 
                        },
                        async: false
                    });
                }
            }
        });
    
    }catch(err){
        message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
        cUsCF_myjq('.advice_notice').html(message).show();
        cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false); 
    };
    
    //jQ UI ALERTS & MESSAGE DIALOGS
    cUsCF_myjq.messageDialogLogin = function(title){
        try{
            cUsCF_myjq( "#dialog-message" ).dialog({
                modal: true,
                title: title,
                minWidth: 520,
                buttons: {
                    Ok: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        }catch(err){
            //console.log(err);
        }
    };
    
    //JUI CUSTOM ALERTS AND MESSAGE DIALOGS
    cUsCF_myjq.messageDialog = function(title, msg){
        try{
            cUsCF_myjq( "#dialog-message" ).html(msg);
            cUsCF_myjq( "#dialog-message" ).dialog({
                modal: true,
                title: title,
                minWidth: 520,
                buttons: {
                    Ok: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        }catch(err){
            //console.log(err);
        }
    };
    
    
    //SENT LIST ID AJAX CALL /// STEP 2
    try{
        cUsCF_myjq('#cUsCF_CreateCustomer').click(function(e) {
            
            e.preventDefault();
            
            var postData = {};
            
            //GET ALL FORM FIELDS DATA
            var cUsCF_first_name = cUsCF_myjq('#cUsCF_first_name').val();
            var cUsCF_last_name = cUsCF_myjq('#cUsCF_last_name').val();
            var cUsCF_phone = cUsCF_myjq('#cUsCF_phone').val();
            var cUsCF_email = cUsCF_myjq('#cUsCF_email').val();
            //EMAIL VALIDATION FUNCTION
            var cUsCF_emailValid = checkRegexp( cUsCF_email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. sergio@cUsCF_myjq.com" );
            var cUsCF_pass = cUsCF_myjq('#cUsCF_password').val();
            var cUsCF_pass2 = cUsCF_myjq('#cUsCF_password_r').val();
            var cUsCF_web = cUsCF_myjq('#cUsCF_web').val();
            //URL VALIDATION FUNCTION
            var cUsCF_webValid = checkURL(cUsCF_web);
            
           cUsCF_myjq('.loadingMessage').show();
           
           //lenght validations
           if( !cUsCF_first_name.length){
               cUsCF_myjq('.advice_notice').html('Your First Name is a required field').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_first_name').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if( !cUsCF_last_name.length){
               cUsCF_myjq('.advice_notice').html('Your Last Name is a required field').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_last_name').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(!cUsCF_email.length){
               cUsCF_myjq('.advice_notice').html('Email is a required field').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#apikey').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(!cUsCF_pass.length){
               cUsCF_myjq('.advice_notice').html('Password is a required field').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_password').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(cUsCF_pass.length < 8){ //PASSWORD 8 CHARS VALIDATION
               cUsCF_myjq('.advice_notice').html('Password must be 8 characters or more').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_password').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(cUsCF_pass2 != cUsCF_pass){
               cUsCF_myjq('.advice_notice').html('Confirm Password not match').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_password_r').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(!cUsCF_emailValid){
               cUsCF_myjq('.advice_notice').html('Please, enter a valid Email').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_email').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(!cUsCF_web.length){
               cUsCF_myjq('.advice_notice').html('Your Website is a required field').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_web').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(!cUsCF_webValid){
               cUsCF_myjq('.advice_notice').html('Please, enter one valid website URL').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_web').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else{
                cUsCF_myjq('#cUsCF_CreateCustomer').val('Loading . . .').attr('disabled', true);
                
                postData = {action: 'cUsCF_verifyCustomerEmail', fName:str_clean(cUsCF_first_name),lName:str_clean(cUsCF_last_name),Email:cUsCF_email,Phone:cUsCF_phone,credential:cUsCF_pass,website:cUsCF_web};
                
                //AJAX POST CALL
                cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: postData,
                    success: function(data) {
                        switch(data){
                            
                            //NO USER, CONTINUE WITH NEXT STEP
                            case '1':
                                message = '<h4>Continue with Form Design Selection . . .</h4>';
                                
                                setTimeout(function(){
                                    cUsCF_myjq('.step1').slideDown().fadeOut();
                                    cUsCF_myjq('.step2').slideUp().fadeIn();
                                },1900);
                                
                                cUsCF_myjq('#cUsCF_CreateCustomer').val('Next >>').attr('disabled', false);
                                cUsCF_myjq('.notice').html(message).show().delay(8000).fadeOut(2000);
                                
                            break;
                            
                            //OLD USER, LOGIN
                            case '2':
                                message = 'Seems like you already have one Contactus.com Account, Please Login below';
                                cUsCF_myjq('#cUsCF_CreateCustomer').val('Next >>').attr('disabled', false); 
                                setTimeout(function(){
                                    cUsCF_myjq('#login_email').val(cUsCF_email).focus();
                                    cUsCF_myjq('#cUsCF_userdata').fadeOut();
                                    cUsCF_myjq('#cUsCF_settings').slideDown('slow');
                                    cUsCF_myjq('#cUsCF_loginform').delay(1000).fadeIn();
                                },2000);
                                cUsCF_myjq('.advice_notice').html(message).show().delay(8000).fadeOut(2000);
                            break; 
                        
                            //API OR CONNECTION ISSUES
                            case '':
                            default:
                                message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com. <br/>Error: <b>' + data + '</b>.</a></p>';
                                cUsCF_myjq('.advice_notice').html(message).show();
                                cUsCF_myjq('#cUsCF_CreateCustomer').val('Next >>').attr('disabled', false);
                            break;
                        }
                        
                        cUsCF_myjq('.loadingMessage').fadeOut();
                        

                    },
                    fail: function(){ //AJAX FAIL
                       message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com.</a></p>';
                       cUsCF_myjq('.advice_notice').html(message).show();
                       cUsCF_myjq('#cUsCF_CreateCustomer').val('Next >>').attr('disabled', false); 
                    }
                });
           }
           
            
        });
    }catch(err){ //JS ISSUES
        cUsCF_myjq('.advice_notice').html('Unfortunately there has being an error during the application. ' + err).slideToggle().delay(2000).fadeOut(2000);
        cUsCF_myjq('#cUsCF_CreateCustomer').val('Next >>').attr('disabled', false);
    }
    
    //cUsCF_myjq("#cUsCF_SendTemplates").colorbox({inline:true, maxWidth:'100%', minHeight:'425px', scrolling:false });
    
    cUsCF_myjq("#cUsCF_SendTemplates").on('click', function(e) {
           
        e.preventDefault();
        
        var Template_Desktop_Form = cUsCF_myjq('#Template_Desktop_Form').val();
        var Template_Desktop_Tab = cUsCF_myjq('#Template_Desktop_Tab').val();

        if (!Template_Desktop_Form.length) {
            cUsCF_myjq('.advice_notice').html('Please select a form template before continuing.').slideToggle().delay(2000).fadeOut(2000);
            cUsCF_myjq('.loadingMessage').fadeOut();
            cUsCF_myjq(".signup_templates").accordion({active: 0});
        } else if (!Template_Desktop_Tab.length) {
            cUsCF_myjq('.advice_notice').html('Please select a tab template before continuing.').slideToggle().delay(2000).fadeOut(2000);
            cUsCF_myjq('.loadingMessage').fadeOut();
            cUsCF_myjq(".signup_templates").accordion({active: 1});
        } else {
            cUsCF_myjq("#cUsCF_SendTemplates").colorbox({escKey:false,overlayClose:false,closeButton:false, inline: true, maxWidth: '100%', minHeight: '430px', scrolling: false});
        }

    });
    
    //SIGNUP TEMPLATE SELECTION
    try{ cUsCF_myjq('.btn-skip').click(function(e) {
           
           e.preventDefault();
           var oThis = cUsCF_myjq(this);
           oThis.hide();
           cUsCF_myjq('#open-intestes').hide();
           
           //GET SELECTED TEMPLATES
           var Template_Desktop_Form = cUsCF_myjq('#Template_Desktop_Form').val();
           var Template_Desktop_Tab = cUsCF_myjq('#Template_Desktop_Tab').val();
           // this are optional so do not passcheck
           var CU_category 	= cUsCF_myjq('#CU_category').val();
           var CU_subcategory 	= cUsCF_myjq('#CU_subcategory').val();
           
            var new_goals = '';
            var CU_goals = cUsCF_myjq('input[name="the_goals[]"]').each(function(){
                    new_goals += cUsCF_myjq(this).val()+',';	
            });

            if( cUsCF_myjq('#other_goal').val() )
                    new_goals += cUsCF_myjq('#other_goal').val()+',';
           
           cUsCF_myjq(".img_loader").show();
           cUsCF_myjq('.loadingMessage').show();
           
           //VALIDATION
           if(!Template_Desktop_Form.length){
               cUsCF_myjq('.advice_notice').html('Please select a form template before continuing.').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( ".signup_templates" ).accordion({ active: 0 });
           }else if(!Template_Desktop_Tab.length){
               cUsCF_myjq('.advice_notice').html('Please select a tab template before continuing.').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( ".signup_templates" ).accordion({ active: 1 });
           }else{
                
                cUsCF_myjq('#cUsCF_SendTemplates').val('Loading . . .').attr('disabled', true);
                oThis.attr('disabled', true);
                
                //AJAX POST CALL cUsCF_createCustomer
                cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_createCustomer',Template_Desktop_Form:Template_Desktop_Form,Template_Desktop_Tab:Template_Desktop_Tab,CU_category:CU_category,CU_subcategory:CU_subcategory,CU_goals:new_goals },
                    success: function(data) {

                        switch(data){
                            
                            //USER CREATED
                            case '1':
                                message = '<p>Template saved succesfuly . . . .</p>';
                                message += '<p>Welcome to ContactUs.com, and thank you for your registration.</p>';
                                cUsCF_myjq('.notice').html(message).show().delay(4900).fadeOut(800);
                                //cUsCF_myjq("#cUsFC_SendTemplates").colorbox.close();
                                setTimeout(function(){
                                    cUsCF_myjq('.step3').slideUp().fadeOut();
                                    cUsCF_myjq('.step4').slideDown().delay(800);
                                    cUsCF_myjq('#cUsCF_SendTemplates').val('Create My Account').attr('disabled', false); 
                                    location.reload();
                                },2000);
                                break;
                             //OLD USER - LOGING
                             case '2':
                                message = 'Seems like you already have one Contactus.com Account, Please Login below';
                                cUsCF_myjq('.advice_notice').html(message).show();
                                cUsCF_myjq('#cUsCF_SendTemplates').val('Create My Account').attr('disabled', false);
                                cUsCF_myjq("#cUsCF_SendTemplates").colorbox.close();
                                cUsCF_myjq(".img_loader").hide();
                                setTimeout(function(){
                                    cUsCF_myjq('#login_email').val(cUsCF_email).focus();
                                    cUsCF_myjq('#cUsCF_userdata').fadeOut();
                                    cUsCF_myjq('#cUsCF_settings').slideDown('slow');
                                    cUsCF_myjq('#cUsCF_loginform').delay(1000).fadeIn();
                                },2000);
                                break;
                            //API OR CONNECTION ISSUES
                            case '':
                            default:
                                message = '<p>Unfortunately there has being an error during the application. If the problem continues, contact us at support@contactus.com. <br/>Error: <b>' + data + '</b>.</a></p>';
                                cUsCF_myjq('.advice_notice').html(message).show();
                                cUsCF_myjq('#cUsCF_SendTemplates').val('Create My Account').attr('disabled', false);
                                cUsCF_myjq("#cUsCF_SendTemplates").colorbox.close();
                                break;
                        }
                        
                        cUsCF_myjq('.loadingMessage').fadeOut();

                    },
                    async: false
                });
           }
           
            
        });
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Unfortunately there has being an error during the application. ' + err).slideToggle().delay(9000).fadeOut(2000);
        cUsCF_myjq('#cUsCF_SendTemplates').val('Create My Account').attr('disabled', false); 
    }
    
    //UPDATE TEMPLATES FOR ALREADY USERS
    try{ cUsCF_myjq('#cUsCF_UpdateTemplates').click(function() {
           
           //GET SELECTED TEMPLATES
           var Template_Desktop_Form = cUsCF_myjq('#uTemplate_Desktop_Form').val();
           var Template_Desktop_Tab = cUsCF_myjq('#uTemplate_Desktop_Tab').val();
           cUsCF_myjq('.loadingMessage').show();
           
           //VALIDATION
           if(!Template_Desktop_Form.length){
               cUsCF_myjq('.advice_notice').html('Please select a form template before continuing.').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( "#form_examples" ).accordion({ active: 0 });
           }else if(!Template_Desktop_Tab.length){
               cUsCF_myjq('.advice_notice').html('Please select a tab template before continuing.').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( "#form_examples" ).accordion({ active: 1 });
           }else{
                
                cUsCF_myjq('#cUsCF_UpdateTemplates').val('Loading . . .').attr('disabled', true);
                
                //AJAX POST CALL cUsCF_UpdateTemplates
                cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_UpdateTemplates',Template_Desktop_Form:Template_Desktop_Form,Template_Desktop_Tab:Template_Desktop_Tab},
                    success: function(data) {

                        switch(data){
                            //SAVED
                            case '1':
                                message = '<p>Template saved succesfuly . . . .</p>';
                                cUsCF_myjq('.notice').html(message).show();
                                setTimeout(function(){
                                    cUsCF_myjq('.step3').slideUp().fadeOut();
                                    cUsCF_myjq('.step4').slideDown().delay(800);
                                    location.reload();
                                },2000);
                                break;
                            //API OR CONNECTION ISSUES
                            default:
                                message = '<p>Unfortunately there has being an error during the application: <b>' + data + '</b>. Please try again</a></p>';
                                cUsCF_myjq('.advice_notice').html(message).show();
                                cUsCF_myjq('#cUsCF_UpdateTemplates').val('Update my template').attr('disabled', false); 
                                break;
                        }
                        
                        cUsCF_myjq('.loadingMessage').fadeOut();

                    },
                    async: false
                });
           }
           
            
        });
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Unfortunately there has being an error during the application.  '+ err).slideToggle().delay(2000).fadeOut(2000);
        cUsCF_myjq('#cUsCF_UpdateTemplates').val('Update my template').attr('disabled', false); 
    }
    
    //loading default template
    try{ cUsCF_myjq('.load_def_formkey').click(function() { 
            
        cUsCF_myjq('.loadingMessage').show();
          
        cUsCF_myjq('.load_def_formkey').html('Loading . . .');

        cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_LoadDefaultKey'},
            success: function(data) {

                switch(data){
                    case '1':
                        message = '<p>New form Loaded correctly. . . .</p>';
                        cUsCF_myjq('.load_def_formkey').html('Completed . . .');
                        setTimeout(function(){
                            location.reload();
                        },2000);
                        break;
                }

                cUsCF_myjq('.loadingMessage').fadeOut();
                cUsCF_myjq('.advice_notice').html(message).show();
                 

            },
            async: false
        });
           
            
        });
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Unfortunately there has being an error during the application.  '+ err).slideToggle().delay(2000).fadeOut(2000);
        cUsCF_myjq('.load_def_formkey').html('Update my template'); 
    }
    
    //JQ FUNCTION - CHANGE PAGE SETTINGS IN PAGE SELECTION
    cUsCF_myjq.changePageSettings = function(pageID, cus_version, form_key) { 
        
        if(!cus_version.length){
            cUsCF_myjq('.advice_notice').html('Please select TAB or INLINE').slideToggle().delay(2000).fadeOut(2000);
        }else if(!form_key.length){
            cUsCF_myjq('.advice_notice').html('Please select your Contact Us Form Template').slideToggle().delay(2000).fadeOut(2000);
        }else{
            
            cUsCF_myjq('.save_message_'+pageID).show();
            
            //AJAX POST CALL cUsCF_changePageSettings
            cUsCF_myjq.ajax({type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_changePageSettings',pageID:pageID,cus_version:cus_version, form_key:form_key },
                success: function(data) {

                    switch(data){
                        case '1':
                            message = '<p>Saved Successfully . . . .</p>';
                            cUsCF_myjq('.save_message_'+pageID).html(message);
                            cUsCF_myjq('.save-page-'+pageID).val('Completed . . .');

                            setTimeout(function(){
                                cUsCF_myjq('.save_message_'+pageID).fadeOut();
                                cUsCF_myjq('.save-page-'+pageID).val('Save');
                                cUsCF_myjq('.form-templates-'+pageID).slideUp();
                            },2000);

                            break;
                    }

                },
                async: false
            });
        }  
            
    };
    
    //JQ FUNCTION - REMOVE PAGE SETTINGS IN PAGE SELECTION
    cUsCF_myjq.deletePageSettings = function(pageID) { 

        cUsCF_myjq.ajax({type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_deletePageSettings',pageID:pageID},
            success: function(data) {
                //console.log('Success . . .');
            },
            async: false
        });
            
    };
    
    
    //CHANGE FORM TEMPLATES
    cUsCF_myjq.changeFormTemplate = function(formID, form_key, Template_Desktop_Form) {
        
        if(!Template_Desktop_Form.length || !form_key.length){
            cUsCF_myjq('.advice_notice').html('Please select your Contact Us Form Template').slideToggle().delay(2000).fadeOut(2000);
        }else{
            
            cUsCF_myjq('.save_message_'+formID).show();
            
            cUsCF_myjq.ajax({type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_changeFormTemplate',Template_Desktop_Form:Template_Desktop_Form, form_key:form_key },
                success: function(data) {

                    switch(data){
                        case '1':
                            message = '<p>Saved Successfully . . . .</p>';
                            cUsCF_myjq('.save_message_'+formID).html(message);
                            cUsCF_myjq('.form_thumb_'+formID).attr('src','https://admin.contactus.com/popup/tpl/'+Template_Desktop_Form+'/scr.png');

                            setTimeout(function(){
                                cUsCF_myjq('.save_message_'+formID).fadeOut();
                            },2000);

                            break
                    }

                },
                async: false
            });
        }  
            
    };
    
    //CHANGE FORM TEMPLATES
    cUsCF_myjq.changeTabTemplate = function(formID, form_key, Template_Desktop_Tab) { //loading default template
        
        
        if(!Template_Desktop_Tab.length || !form_key.length){
            cUsCF_myjq('.advice_notice').html('Please select your Contact Us Tab Template').slideToggle().delay(2000).fadeOut(2000);
        }else{
            
            cUsCF_myjq('.save_tab_message_'+formID).show();
            
            cUsCF_myjq.ajax({type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_changeTabTemplate',Template_Desktop_Tab:Template_Desktop_Tab, form_key:form_key },
                success: function(data) {

                    switch(data){
                        case '1':
                            message = '<p>Saved Successfully . . . .</p>';
                            cUsCF_myjq('.save_tab_message_'+formID).html(message);
                            cUsCF_myjq('.tab_thumb_'+formID).attr('src','https://admin.contactus.com/popup/tpl/'+Template_Desktop_Tab+'/scr.png');

                            setTimeout(function(){
                                cUsCF_myjq('.save_tab_message_'+formID).fadeOut();
                            },2000);

                            break
                    }

                },
                async: false
            });
        }  
            
    };
    
    //UNLINK ACCOUNT AND DELETE PLUGIN OPTIONS AND SETTINGS
    cUsCF_myjq('.cUsCF_LogoutUser').click(function(){
        
        cUsCF_myjq( "#dialog-message" ).html('Please confirm you would like to unlink your account.');
        cUsCF_myjq( "#dialog-message" ).dialog({
            resizable: false,
            width:430,
            title: 'Close your account session?',
            height:180,
            modal: true,
            buttons: {
                "Yes": function() {
                    
                    cUsCF_myjq('.loadingMessage').show();
                    cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_logoutUser'},
                        success: function(data) {
                            cUsCF_myjq('.loadingMessage').fadeOut();
                              location.reload();
                        },
                        async: false
                    });
                    
                    cUsCF_myjq( this ).dialog( "close" );
                    
                },
                Cancel: function() {
                    cUsCF_myjq( this ).dialog( "close" );
                }
            }
        });
        
    });
    
    //FORM PLACEMENT SELECITION / DEFAULT FORM OR CUSTOM SETTINGS
    cUsCF_myjq('.form_version').click(function(){
        
        var value = cUsCF_myjq(this).val();
         
        var msg = '';
        switch(value){
            case 'select_version':
                msg = '<p>You are about to change to Custom Form Settings. You need to choose what forms go on each page or home page</p>';
                break;
            case 'tab_version':
                msg = '<p>You are about to change to Default Form Settings, only your Default form will show up in all of your site</p>';
                break;
        }
        
        cUsCF_myjq( "#dialog-message" ).html(msg);
        cUsCF_myjq( "#dialog-message" ).dialog({
            resizable: false,
            width:430,
            title: 'Change your Form Settings?',
            height:180,
            modal: true,
            buttons: {
                "Yes": function() {
                    
                    switch(value){
                        case 'select_version':
                            cUsCF_myjq('.tab_button').addClass('gray').removeClass('green').attr('disabled', false);
                            cUsCF_myjq('.custom').addClass('green').removeClass('disabled').attr('disabled', true);
                            cUsCF_myjq('.ui-buttonset input').removeAttr('checked');
                            cUsCF_myjq('.ui-buttonset label').removeClass('ui-state-active');

                            cUsCF_myjq('.loadingMessage').show();
                            cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_saveCustomSettings',cus_version:'selectable',tab_user:0},
                                success: function(data) {
                                    cUsCF_myjq('.loadingMessage').fadeOut();
                                    cUsCF_myjq('.notice_success').html('<p>Custom settings saved . . .</p>').fadeIn().delay(2000).fadeOut(2000);
                                    //location.reload();
                                },
                                async: false
                            });

                            break;
                        case 'tab_version':
                            cUsCF_myjq('.custom').addClass('gray').removeClass('green').attr('disabled', false);
                            cUsCF_myjq('.tab_button').removeClass('gray').addClass('green').attr('disabled', true);

                            cUsCF_myjq('.loadingMessage').show();
                            cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_saveCustomSettings',cus_version:'tab',tab_user:1},
                                success: function(data) {
                                    cUsCF_myjq('.loadingMessage').fadeOut();
                                    cUsCF_myjq('.notice_success').html('<p>Tab settings saved . . .</p><p>Your default Form will appear in all your website.</p>').fadeIn().delay(5000).fadeOut(2000);
                                    //location.reload();
                                },
                                async: false
                            });

                            break;
                    }

                    cUsCF_myjq('.cus_versionform').fadeOut();
                    cUsCF_myjq('.' + value).fadeToggle();
                    
                    cUsCF_myjq( this ).dialog( "close" );
                    
                },
                Cancel: function() {
                    cUsCF_myjq( this ).dialog( "close" );
                }
            }
        });
        
    });
    
    cUsCF_myjq('.btab_enabled').click(function(){
        var value = cUsCF_myjq(this).val();
        cUsCF_myjq('.tab_user').val(value);
        cUsCF_myjq('.loadingMessage').show();
       
        setTimeout(function(){
            cUsCF_myjq('#cUsCF_button').submit();
        },1500);
        
    });
    
    cUsCF_myjq('#contactus_settings_page').change(function(){
        cUsCF_myjq('.show_preview').fadeOut();
        cUsCF_myjq('.save_page').fadeOut( "highlight" ).fadeIn().val('>> Save your settings');
    });
    
    cUsCF_myjq('.callout-button').click(function() {
        cUsCF_myjq('.getting_wpr').slideToggle('slow');
    });
    
    cUsCF_myjq('#cUsCF_yes').click(function() {
        cUsCF_myjq('#cUsCF_userdata, #cUsCF_templates').fadeOut();
        cUsCF_myjq('#cUsCF_settings').slideDown('slow');
        cUsCF_myjq('#cUsCF_loginform').delay(600).fadeIn();
    });
    cUsCF_myjq('#cUsCF_no').click(function() {
        cUsCF_myjq('#cUsCF_loginform, #cUsCF_templates').fadeOut();
        cUsCF_myjq('#cUsCF_settings').slideDown('slow');
        cUsCF_myjq('#cUsCF_userdata').delay(600).fadeIn();
    });
    
    //DOM ISSUES ON LOAD
    $('.form_template, .step2, #cUsCF_settings').css("display","none");
    
    function checkRegexp( o, regexp, n ) {
        if ( !( regexp.test( o ) ) ) {
            return false;
        } else {
            return true;
        }
    }
    
    function checkURL(url) {
        return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }
    
    function str_clean(str){
           
        str = str.replace("'" , " ");
        str = str.replace("," , "");
        str = str.replace("\"" , "");
        str = str.replace("/" , "");

        return str;
    }
    
});//ON LOAD