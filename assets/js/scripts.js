
//PLUGIN $ ENVIROMENT ($)
var cUsCF_myjq = jQuery.noConflict();

cUsCF_myjq(window).error(function(e){
    e.preventDefault();
});

//ON READY DOM LOADED
cUsCF_myjq(document).ready(function($) {
    
    try{
        
        //LOADING UI BOX
        $( ".cUsCF_preloadbox" ).delay(1500).fadeOut()
        $('.tooltips').tooltip();
        
        //UI TABS
        //$( "#cUsCF_tabs" ).tabs({active: false});
        //$( "#menuWrapper" ).tabs({active: false});
        //$('#tabs').tabs();
        //console.log('tabs loaded')

        //UNBIND UI TABS LINK ON CLICK
        $("li.gotohelp a").unbind('click');

        //colorbox window
        $(".tooltip_formsett").colorbox({iframe:true, innerWidth:'75%', innerHeight:'80%'});

        cUsCF_myjq('.sign-in').click(function() {
            cUsCF_myjq('.signup-form').slideToggle('slow');
            cUsCF_myjq('.login-form').slideToggle('slow');
        });

        $(".custom-checkbox").bootstrapSwitch({
            onColor: 'success',
            offColor: 'danger',
            size: 'small'
        });

       
    }catch(err){
        console.log(err);
        $('.advice_notice').html('Error - please update your WordPress  to the latest version. If the problem continues, contact us at support@contactus.com.: ' + err ).slideToggle().delay(2000).fadeOut(2000);
    }
    
    //TOOLTIPS
    try{
        //JQ UI TOOLTIPS
        $(".setLabels").tooltip();
    }catch(err){
        $('.advice_notice').html('Error - please update your WordPress version to the latest version. If the problem continues, contact us at support@contactus.com. ' + err ).slideToggle().delay(2000).fadeOut(2000);
    }

    //UNLINK ACCOUNT AND DELETE PLUGIN OPTIONS AND SETTINGS
    cUsCF_myjq('.LogoutUser').click(function(){

        bootbox.confirm("Do you want to unlink your account? <span class='loading'></span>", function(result) {
            if(result){
                cUsCF_myjq('.loading').fadeIn();
                cUsCF_myjq.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_logoutUser'},
                    success: function(data) {
                        cUsCF_myjq('.loading').fadeOut();
                        location.reload();
                    }
                });
            }
        });

    });


    
});//ON LOAD