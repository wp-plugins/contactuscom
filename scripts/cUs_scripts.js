
var myjq = jQuery.noConflict();

myjq(window).error(function(e){
    e.preventDefault();
});

jQuery(document).ready(function($) {
    
    try{stLight.options({publisher: "17dedc98-fb27-4eff-b226-33e5efed8d6f", doNotHash: false, doNotCopy: false, hashAddressBar: false});}catch(err){}
    
    var myjq = jQuery.noConflict();
    
    var fname = $( "#fname" ),
    lname = $( "#lname" ),
    remail = $( "#remail" ),
    pass1 = $( "#pass1" ),
    pass2 = $( "#pass2" ),
    email = $( "#login_email" ),
    password = $( "#user_pass" ),
    allFields = $( [] ).add(email).add(password),
    allFields_reg = $( [] ).add(fname).add(lname).add(remail).add(pass1).add(pass2),
    tips = $( ".validateTips" );
    
    function updateTips( t ) {
        tips
        .text( t )
        .addClass( "ui-state-highlight" );
        setTimeout(function() {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    }

    function checkLength( o, n, min, max ) {
        if ( o.val().length > max || o.val().length < min ) {
            o.addClass( "ui-state-error" );
            updateTips( "Length of " + n + " must be between " +
                min + " and " + max + "." );
            return false;
        } else {
            return true;
        }
    }

    function comparePass( o, n ) {
        if ( o.val() != n.val() ) {
            o.addClass( "ui-state-error" );
            updateTips( "Password don't match." );
            return false;
        } else {
            return true;
        }
    }

    function checkRegexp( o, regexp, n ) {
        if ( !( regexp.test( o.val() ) ) ) {
            o.addClass( "ui-state-error" );
            updateTips( n );
            return false;
        } else {
            return true;
        }
    }
    
    
    $('#cUs_login').submit(function(){
        var bValid = true;
        allFields.removeClass( "ui-state-error" );
        bValid = bValid && checkLength( email, "email", 6, 80 );
        bValid = bValid && checkLength( password, "password", 8, 16 );
        // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
        bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "Please enter a valid email eg. sergio@contactus.com" );
        //bValid = bValid && checkRegexp( password, /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,16}$/, "Password field only allow : A-z 0-9" );
        if ( bValid ) {
            return true;
            $( this ).dialog( "close" );
        }else{
            return false;
        }
    });
    $('#cUs_registform').submit(function(){
        var bValid = true;
        allFields_reg.removeClass( "ui-state-error" );
        bValid = bValid && checkLength( fname, "first name", 2, 16 );
        bValid = bValid && checkLength( lname, "last name", 2, 16 );
        bValid = bValid && checkLength( remail, "email", 6, 80 );
        bValid = bValid && checkLength( pass1, "password", 8, 16 );
        bValid = bValid && comparePass( pass1, pass2 );
        bValid = bValid && checkRegexp( remail, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
        //bValid = bValid && checkRegexp( pass1, /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,16}$/, "Password field only allow : A-z 0-9" );
        if ( bValid ) {
            return true;
            $( this ).dialog( "close" );
        }else{
            return false;
        }
    });
    
    try{
        $( "#cUs_tabs" ).tabs({active: false});
        $( "#cUs_exampletabs" ).tabs({active: false});


        $(".sign_up_button").click(function() {
            $("#loginform").dialog("open");
        });
        $("#cUslogin-user").click(function() {
            $("#loginform").dialog("open");
        });

        $("#create-user").click(function(event) {
            $( "#cUs_tabs" ).tabs({ active: 0 });
            event.preventDefault();
        });
        $(".gotologin").click(function(event) {
            $( "#cUs_tabs" ).tabs({ active: 0 });
            event.preventDefault();
        });
        $(".tologin").click(function(event) {
            $( "#cUs_tabs" ).tabs({ active: 1 });
            event.preventDefault();
        });
        $('.gotoreg, .gotosettingsa').click(function(event) {
            $( "#cUs_tabs" ).tabs({ active: 1 });
            event.preventDefault();
        });
        $('.gotosettings').click(function(event) {
            $( "#cUs_tabs" ).tabs({ active: 2 });
            event.preventDefault();
        });


        $( '.options' ).buttonset();
        $( '#inlineradio' ).buttonset();

        $( "#terminology" ).accordion({
            collapsible: true,
            heightStyle: "content",
            active: false,
            icons: { "header": "ui-icon-info", "activeHeader": "ui-icon-arrowreturnthick-1-n" }
        });

        $('.examples_gallery').fancybox({
              helpers: {
                  title : {
                      type : 'float'
                  }
              }
        });
       
    }catch(err){
        console.log('Please upadate you WP version.')
    }
    
    
//    try{
//        $( '.examples_gallery, .ui-state-default, .page_title' ).tooltip({
//           track: true
//        }); 
//    }catch(err){
//        console.log('Please upadate you WP version.')
//    }
    
    
    $('.form_version').change(function(){
        var val = $(this).val();
        $('.cus_versionform').fadeOut();
        $('.' + val).slideToggle();
    });
    
    $('#contactus_settings_page').change(function(){
        $('.show_preview').fadeOut();
        $('.save_page').fadeOut( "highlight" ).fadeIn().val('>> Save your settings');
    });
    
    $('.callout-button').click(function() {
        $('.getting_wpr').slideToggle('slow');
    });
    
    $('.insertShortcode').click(function() {
        contactUs_mediainsert();
    });
    
    
});

function contactUs_mediainsert() {
    send_to_editor('[show-contactus.com-form]');
}
