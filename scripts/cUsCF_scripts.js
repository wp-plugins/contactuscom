
var cUsCF_myjq = jQuery.noConflict();

cUsCF_myjq(window).error(function(e){
    e.preventDefault();
});

cUsCF_myjq(document).ready(function($) {
    
    try{
        cUsCF_myjq( "#cUsCF_tabs" ).tabs({active: false});
        
        cUsCF_myjq( ".goto_shortcodes" ).click(function(){
            cUsCF_myjq( "#cUsCF_tabs" ).tabs({ active: 2 });
        });
        
        cUsCF_myjq("li.gotohelp a").unbind('click');
        
        cUsCF_myjq(".setLabels").tooltip();
        
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
        
        cUsCF_myjq(".tooltip_formsett").colorbox({iframe:true, innerWidth:'75%', innerHeight:'80%'});   
        
        cUsCF_myjq( '.bx-loading' ).hide();
        cUsCF_myjq( '.options' ).buttonset();
        
        cUsCF_myjq( '.form_types' ).buttonset();//TEMPLATE SELECTION
        cUsCF_myjq ('.form_types input[type=radio]').change(function() {
            var form_type = this.value;
            cUsCF_myjq('#Template_Desktop_Form').val('');//RESET ON CHANGE
            cUsCF_myjq('#Template_Desktop_Tab').val('');//RESET ON CHANGE
            switch(form_type){
                case 'contact_form': 
                    cUsCF_myjq( '.Template_Contact_Form' ).fadeIn();
                    cUsCF_myjq( '.Template_Newsletter_Form' ).hide();
                    break;
                case 'newsletter_form':
                    cUsCF_myjq( '.Template_Newsletter_Form' ).fadeIn();
                    cUsCF_myjq( '.Template_Contact_Form' ).hide();
                    break;
            }
        });
        
        cUsCF_myjq(".selectable_cf, .selectable_news").selectable({//SELECTED CONTACT FORM TEMPLATE
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#Template_Desktop_Form').val(idEl);           
            }                   
        });
        
        cUsCF_myjq(".selectable_tabs_cf, .selectable_tabs_news").selectable({//SELECTED FORM TAB TEMPLATE
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#Template_Desktop_Tab').val(idEl);           
            }                   
        });
        
        cUsCF_myjq(".selectable_ucf, .selectable_unews").selectable({//SELECTED CONTACT FORM TEMPLATE
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#uTemplate_Desktop_Form').val(idEl);           
            }                   
        });
        
        cUsCF_myjq(".selectable_tabs_ucf, .selectable_tabs_unews").selectable({//SELECTED FORM TAB TEMPLATE
            selected: function(event, ui) {
                var idEl = cUsCF_myjq(ui.selected).attr('id');
                cUsCF_myjq(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");           
                cUsCF_myjq('#uTemplate_Desktop_Tab').val(idEl);           
            }                   
        });
        
        cUsCF_myjq( '#inlineradio' ).buttonset();

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
        
        var glow = $('.toDash');
        setInterval(function(){
            glow.hasClass('glow') ? glow.removeClass('glow') : glow.addClass('glow');
        }, 9000);
        
       
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Oops, something wrong happened, please try again later!').slideToggle().delay(2000).fadeOut(2000);
    }
    
    cUsCF_myjq('.cUsCF_LoginUser').click(function(){//LOGIN ALREADY USERS
        var email = cUsCF_myjq('#login_email').val();
        var pass = cUsCF_myjq('#user_pass').val();
        cUsCF_myjq('.loadingMessage').show();
        
        if(!email.length){
            cUsCF_myjq('.advice_notice').html('User Email is a required and valid field!').slideToggle().delay(2000).fadeOut(2000);
            cUsCF_myjq('#login_email').focus();
            cUsCF_myjq('.loadingMessage').fadeOut();
        }else if(!pass.length){
            cUsCF_myjq('.advice_notice').html('User password is a required field!').slideToggle().delay(2000).fadeOut(2000);
            cUsCF_myjq('#user_pass').focus();
            cUsCF_myjq('.loadingMessage').fadeOut();
        }else{
            var bValid = checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. sergio@jquery.com" );  
            if(!bValid){
                cUsCF_myjq('.advice_notice').html('Please enter a valid User Email!').slideToggle().delay(2000).fadeOut(2000);
                cUsCF_myjq('.loadingMessage').fadeOut();
            }else{
                
                cUsCF_myjq('.cUsCF_LoginUser').val('Loading . . .').attr('disabled', true);
                
                cUsCF_myjq.ajax({ type: "POST", dataType:'json', url: ajax_object.ajax_url, data: {action:'cUsCF_loginAlreadyUser',email:email,pass:pass},
                    success: function(data) {

                        switch(data.status){
                            case 1:
                                
                                cUsCF_myjq('.cUsCF_LoginUser').val('Success . . .');
                                
                                message = '<p>Welcome to ContactUs.com</p>';
                                
                                setTimeout(function(){
                                    cUsCF_myjq('#cUsCF_loginform').slideUp().fadeOut();
                                    location.reload();
                                },2500)
                                
                                cUsCF_myjq('.notice').html(message).show().delay(3000).fadeOut();
                                cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false);
                                
                            break;
                            case 2:
                                
                                cUsCF_myjq('.cUsCF_LoginUser').val('Error . . .');
                                
                                message = '<p>Seems like you don\'t have one Default Contact Forms added in your ContactUs.com account!.</p>';
                                message += '<p>Please login into your admin panel at ContactUs.com and add at least one to continue...</p>';
                                
                                
                                linkHref = cUsCF_myjq(".toAdmin").attr('href') + '&uE='+data.uE+"&uC="+data.uC;
                                
                                cUsCF_myjq(".toAdmin").attr({'href':linkHref})
                                
                                cUsCF_myjq.messageDialogLogin('Default Contact Form Required!');
                                
                                cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false);
                                
                                cUsCF_myjq('.advice_notice').html(message).show().delay(6000).fadeOut();
                                
                            break;
                            case 3:
                                cUsCF_myjq('.cUsMC_LoginUser').html('Login').removeAttr('disabled');
                                message = '<p>Ouch! unfortunately there has being an error during the application: <br/> <br/> <b>' + data.message + '</b>. <br/> Please try again!</a></p>';
                                cUsCF_myjq('.advice_notice').html(message).show().delay(10000).fadeOut();
                                cUsCF_myjq('.loadingMessage').fadeOut();
                            break;
                            default:
                                cUsCF_myjq('.cUsCF_LoginUser').val('Login').attr('disabled', false);
                                message = '<p>Ouch! unfortunately there has being an error during the application: <b>' + data + '</b>. Please try again!</a></p>';
                                cUsCF_myjq('.advice_notice').html(message).show().delay(6000).fadeOut();
                                break;
                        }
                        
                        cUsCF_myjq('.loadingMessage').fadeOut();
                        

                    },
                    async: false
                });
            }
        }
    });
    
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
        
    }
    
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
        
    }
    
    
    //SENT LIST ID AJAX CALL /// STEP 2
    try{
        cUsCF_myjq('#cUsCF_CreateCustomer').click(function() {
            
            var postData = {};

            var cUsCF_first_name = cUsCF_myjq('#cUsCF_first_name').val();
            var cUsCF_last_name = cUsCF_myjq('#cUsCF_last_name').val();
            var cUsCF_email = cUsCF_myjq('#cUsCF_email').val();
            var cUsCF_emailValid = checkRegexp( cUsCF_email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. sergio@jquery.com" );
            var cUsCF_pass = cUsCF_myjq('#cUsCF_password').val();
            var cUsCF_pass2 = cUsCF_myjq('#cUsCF_password_r').val();
            var cUsCF_web = cUsCF_myjq('#cUsCF_web').val();
            var cUsCF_webValid = checkURL(cUsCF_web);
            
            cUsCF_myjq('.loadingMessage').show();
           
           if( !cUsCF_first_name.length){
               cUsCF_myjq('.advice_notice').html('Your First Name is a required field').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_first_name').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if( !cUsCF_last_name.length){
               cUsCF_myjq('.advice_notice').html('Your Last Name is a required field').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_last_name').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(!cUsCF_email.length){
               cUsCF_myjq('.advice_notice').html('Email is a required field!').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#apikey').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(!cUsCF_pass.length){
               cUsCF_myjq('.advice_notice').html('Password is a required field!').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_password').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(cUsCF_pass.length < 8){
               cUsCF_myjq('.advice_notice').html('Password must be 8 characters or more!').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('#cUsCF_password').focus();
               cUsCF_myjq('.loadingMessage').fadeOut();
           }else if(cUsCF_pass2 != cUsCF_pass){
               cUsCF_myjq('.advice_notice').html('Confirm Password not match!').slideToggle().delay(2000).fadeOut(2000);
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
                
                postData = {action: 'cUsCF_verifyCustomerEmail', fName:str_clean(cUsCF_first_name),lName:str_clean(cUsCF_last_name),Email:cUsCF_email,credential:cUsCF_pass,website:cUsCF_web};
                
                cUsCF_myjq.ajax({ 
                    type: "POST", 
                    url: ajax_object.ajax_url,
                    data: postData,
                    success: function(data) {
                        switch(data){
                            case '1':
                                message = '<h4>Continue with Form Design Selection . . .</h4>';
                                
                                setTimeout(function(){
                                    cUsCF_myjq('.step1').slideDown().fadeOut();
                                    cUsCF_myjq('.step2').slideUp().fadeIn();
                                },3000);
                                
                                cUsCF_myjq('#cUsCF_CreateCustomer').val('Continue to Step 2').attr('disabled', false); 
                                
                            break;
                            case '2':
                                message = 'Seems like you already have one Contactus.com Account, Please Login below!';
                                cUsCF_myjq('#cUsCF_CreateCustomer').val('Continue to Step 2').attr('disabled', false); 
                                setTimeout(function(){
                                    cUsCF_myjq('#login_email').val(cUsCF_email).focus();
                                    cUsCF_myjq('#cUsCF_userdata').fadeOut();
                                    cUsCF_myjq('#cUsCF_settings').slideDown('slow');
                                    cUsCF_myjq('#cUsCF_loginform').delay(1000).fadeIn();
                                },2000)
                            break;  
                            default:
                                message = '<p>Ouch! unfortunately there has being an error during the application: <b>' + data + '</b>. Please try again!</a></p>';
                                cUsCF_myjq('#cUsCF_CreateCustomer').val('Continue to Step 2').attr('disabled', false);
                            break;
                        }
                        
                        cUsCF_myjq('.loadingMessage').fadeOut();
                        cUsCF_myjq('.advice_notice').html(message).show().delay(4000).fadeOut(2000);

                    },
                    fail: function(){
                       message = '<p>Ouch! unfortunately there has being an error during the application. Please try again!</a></p>';
                       cUsCF_myjq('#cUsCF_CreateCustomer').val('Continue to Step 2').attr('disabled', false); 
                    }
                });
           }
           
            
        });
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Oops, something wrong happened, please try again later!').slideToggle().delay(2000).fadeOut(2000);
        cUsCF_myjq('#cUsCF_CreateCustomer').val('Continue to Step 2').attr('disabled', false);
    }
    
    try{ cUsCF_myjq('#cUsCF_SendTemplates').click(function() {
           
           var Template_Desktop_Form = cUsCF_myjq('#Template_Desktop_Form').val();
           var Template_Desktop_Tab = cUsCF_myjq('#Template_Desktop_Tab').val();
           cUsCF_myjq('.loadingMessage').show();
           
           if(!Template_Desktop_Form.length){
               cUsCF_myjq('.advice_notice').html('Please select your Contact Us Template form').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( ".signup_templates" ).accordion({ active: 0 });
           }else if(!Template_Desktop_Tab.length){
               cUsCF_myjq('.advice_notice').html('Please select your Contact Us Button Tab Template').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( ".signup_templates" ).accordion({ active: 1 });
           }else{
                
                cUsCF_myjq('#cUsCF_SendTemplates').val('Loading . . .').attr('disabled', true);
                
                cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_createCustomer',Template_Desktop_Form:Template_Desktop_Form,Template_Desktop_Tab:Template_Desktop_Tab},
                    success: function(data) {

                        switch(data){
                            case '1':
                                message = '<p>Template saved succesfuly . . . .</p>';
                                message += '<p>Welcome to ContactUs.com, and thank you for your registration.</p>';
                                
                                setTimeout(function(){
                                    cUsCF_myjq('.step3').slideUp().fadeOut();
                                    cUsCF_myjq('.step4').slideDown().delay(800);
                                    location.reload();
                                },2000)
                                break;
                             case '2':
                                message = 'Seems like you already have one Contactus.com Account, Please Login below!';
                                cUsCF_myjq('#cUsCF_SendTemplates').val('Build my account').attr('disabled', false); 
                                setTimeout(function(){
                                    cUsCF_myjq('#login_email').val(cUsCF_email).focus();
                                    cUsCF_myjq('#cUsCF_userdata').fadeOut();
                                    cUsCF_myjq('#cUsCF_settings').slideDown('slow');
                                    cUsCF_myjq('#cUsCF_loginform').delay(1000).fadeIn();
                                },2000)
                                break;  
                            default:
                                message = '<p>Ouch! unfortunately there has being an error during the application: <b>' + data + '</b>. Please try again!</a></p>';
                                cUsCF_myjq('#cUsCF_SendTemplates').val('Build my account').attr('disabled', false); 
                                break;
                        }
                        
                        cUsCF_myjq('.loadingMessage').fadeOut();
                        cUsCF_myjq('.advice_notice').html(message).show().delay(1900).fadeOut(800);

                    },
                    async: false
                });
           }
           
            
        });
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Oops, something wrong happened, please try again later!').slideToggle().delay(2000).fadeOut(2000);
        cUsCF_myjq('#cUsCF_SendTemplates').val('Build my account').attr('disabled', false); 
    }
    
    //UPDATE TEMPLATES FOR ALREADY USERS
    try{ cUsCF_myjq('#cUsCF_UpdateTemplates').click(function() {
           
           var Template_Desktop_Form = cUsCF_myjq('#uTemplate_Desktop_Form').val();
           var Template_Desktop_Tab = cUsCF_myjq('#uTemplate_Desktop_Tab').val();
           cUsCF_myjq('.loadingMessage').show();
           
           if(!Template_Desktop_Form.length){
               cUsCF_myjq('.advice_notice').html('Please select your Contact Us Template form').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( "#form_examples" ).accordion({ active: 0 });
           }else if(!Template_Desktop_Tab.length){
               cUsCF_myjq('.advice_notice').html('Please select your Contact Us Button Tab Template').slideToggle().delay(2000).fadeOut(2000);
               cUsCF_myjq('.loadingMessage').fadeOut();
               cUsCF_myjq( "#form_examples" ).accordion({ active: 1 });
           }else{
                
                cUsCF_myjq('#cUsCF_UpdateTemplates').val('Loading . . .').attr('disabled', true);
                
                cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_UpdateTemplates',Template_Desktop_Form:Template_Desktop_Form,Template_Desktop_Tab:Template_Desktop_Tab},
                    success: function(data) {

                        switch(data){
                            case '1':
                                message = '<p>Template saved succesfuly . . . .</p>';
                                
                                setTimeout(function(){
                                    cUsCF_myjq('.step3').slideUp().fadeOut();
                                    cUsCF_myjq('.step4').slideDown().delay(800);
                                    location.reload();
                                },2000)
                                break;
                             
                            default:
                                message = '<p>Ouch! unfortunately there has being an error during the application: <b>' + data + '</b>. Please try again!</a></p>';
                                cUsCF_myjq('#cUsCF_UpdateTemplates').val('Update my template').attr('disabled', false); 
                                break;
                        }
                        
                        cUsCF_myjq('.loadingMessage').fadeOut();
                        cUsCF_myjq('.advice_notice').html(message).show().delay(1900).fadeOut(800);

                    },
                    async: false
                });
           }
           
            
        });
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Oops, something wrong happened, please try again later!').slideToggle().delay(2000).fadeOut(2000);
        cUsCF_myjq('#cUsCF_UpdateTemplates').val('Update my template').attr('disabled', false); 
    }
    
    try{ cUsCF_myjq('.load_def_formkey').click(function() { //loading default template
            
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
                        },2000)
                        break;
                }

                cUsCF_myjq('.loadingMessage').fadeOut();
                cUsCF_myjq('.advice_notice').html(message).show().delay(1900).fadeOut(800);
                 

            },
            async: false
        });
           
            
        });
    }catch(err){
        cUsCF_myjq('.advice_notice').html('Oops, something wrong happened, please try again later!').slideToggle().delay(2000).fadeOut(2000);
        cUsCF_myjq('.load_def_formkey').html('Update my template'); 
    }
    
    
    cUsCF_myjq.changePageSettings = function(pageID, cus_version, form_key) { //loading default template
        
        if(!cus_version.length){
            cUsCF_myjq('.advice_notice').html('Please select TAB or INLINE').slideToggle().delay(2000).fadeOut(2000);
        }else if(!form_key.length){
            cUsCF_myjq('.advice_notice').html('Please select your Contact Us Form Template').slideToggle().delay(2000).fadeOut(2000);
        }else{
            
            cUsCF_myjq('.save_message_'+pageID).show();
            
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

                            break
                    }

                },
                async: false
            });
        }  
            
    }
    
    cUsCF_myjq.deletePageSettings = function(pageID) { //loading default template

        cUsCF_myjq.ajax({type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_deletePageSettings',pageID:pageID},
            success: function(data) {

                //console.log('Success . . .');

            },
            async: false
        });
           
            
    }
    
    
    //CHANGE FORM TEMPLATES
    cUsCF_myjq.changeFormTemplate = function(formID, form_key, Template_Desktop_Form) { //loading default template
        
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
            
    }
    
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
            
    }
    
    
    cUsCF_myjq('.cUsCF_LogoutUser').click(function(){
        
        cUsCF_myjq( "#dialog-message" ).html('Are you sure you want to quit from Contact Form?');
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
                                    cUsCF_myjq('.notice_success').html('<p>Tab settings saved . . .</p><p>Your default Contact Form will appear in all your website.</p>').fadeIn().delay(5000).fadeOut(2000);
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
    
    $('.form_template, .step2, #cUsCF_settings').css("display","none");
    
    function checkRegexp( o, regexp, n ) {
        if ( !( regexp.test( o ) ) ) {
            return false;
        } else {
            return true;
        }
    }
    
    function checkURL(url) {
        return /^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/.test(url);
    }
    
    function str_clean(str){
           
        str = str.replace("'" , " ");
        str = str.replace("," , "");
        str = str.replace("\"" , "");
        str = str.replace("/" , "");

        return str;
    }
    $('.insertShortcode').click(function(){
        console.log('Code')
    });
    
    function contactUs_mediainsert() {
        console.log('sentTo');
        send_to_editor('[show-contactus.com-form]');
    }
    
    
});
