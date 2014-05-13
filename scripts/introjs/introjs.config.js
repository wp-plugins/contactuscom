//ON READY DOM LOADED
cUsCF_myjq(document).ready(function($) {

    var selectedTab = 0;

    $('.ug').click(function(){
        $( "#cUsCF_tabs" ).tabs({ active: 0 });
        startIntroB();
    });

    //SHOW INTROJS HINTS
    $('.introjs-skipbutton').on('click',function(){

        //selectedTab = $("#cUsCF_tabs").tabs('option','active');
        console.log(selectedTab);
        //return;

        $( "#dialog-message" ).html('User guide will appear again using the help button above.');
        $( "#dialog-message" ).dialog({
            resizable: false,
            width:430,
            title: 'Disable Helpful Hints?',
            height:180,
            modal: true,
            buttons: {
                "Ok": function() {

                    $.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_disable_introjs'},
                        success: function(data) {
                            $(  "#dialog-message" ).dialog( "close" );
                        },
                        async: true
                    });

                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });

    });

    
});


var intro = introJs();

function startIntro(){
    //var intro = introJs();
    intro.setOptions({
        steps: [
            {
                //1
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb'></span></div><div class='iR'><h1>Your Contact Form is live!</h1><p>Skip if you’re ready to get going with this free plugin. But we recommend spending 2 minutes on this quick orientation to get familiar.</p></div></div>"
            },
            {
                //2
                element: '#cUsCF_tab_fpt',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb fpt_000'></span></div><div class='iR'><h1>This is Your Form Placement Panel.</h1><p>Choose \"Place Across Your Site\" to add the form in all pages or \"Choose Pages\" to select the pages you want the form to be shown in.</p></div></div>"
            },
            {
                //3
                element: '#cUsCF_tab_button',
                intro: "<div class='intro_box_ai'><h1>Choose this option to place the Callout Tab across your entire site.</h1><span class='intro_003_area'></span><ul class='purple'><li>Callout tabs are used to open up your contact form when clicked. </li><li>They’re placed on a layer above your website.</li><li>You choose the callout tab design (or design your own in our Admin Panel). </li></ul></div>",
                position:'right'
            },
            {
                //4
                element: '.cUsCF_sel_def',
                intro: "<div class='intro_box_ai'><h1>Choose Your Form (for existing ContactUs users) </h1><p>If this is your first form, this screen isn’t going to matter much.  However, advanced ContactUs users might have multiple forms in their account. If that’s the case, you’ll need to set which form will be featured in the plugin. </p></div>",
                position: 'top'
            },
            {
                //5
                element: '#cUsCF_custom',
                intro: "<div class='intro_box_ai'><h2>“Choose Pages” if you don’t want the form on your entire page, but on specific pages.</h2><span class='intro_form_area'></span><ul class='purple'><li>If you use this setting, you’ll have the option of using a callout tab or placing the form inline (i.e., directly on the page). </li></ul></div>",
                position: 'right'
            },
            {
                //1
                element: '#cUsCF_tab_fs',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb set_000'></span></div><div class='iR'><h1>Manage settings on your ContactUs.com form(s). </h1><p>You’ll find the deeplinks to your ContactUs.com admin panel to edit your form: </p> <ul class='purple'><li>Select or change your form and tab design templates</li><li>Customize form fields</li><li>Manage 3rd party software integrations</li> <li>Access premium features (for upgraded accounts) like Exit Intent, and A/B Testing </li></ul> </div></div>",
                position: "bottom"
            },
            {
                //1
                element: '#cUsCF_tab_sc',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb sc_000'></span></div><div class='iR'><h1>The ShortCodes Panel</h1><p>Use ContactUs.com forms without the need for programming skills.</p></div></div>",
                position: "bottom"
            },
            {
                //1
                element: '#cUsCF_tab_ac',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb ac_000'></span></div><div class='iR'><h1>ContactUs.com Account Settings</h1><p>This section is primarily used to manage your ContactUs.com login credentials within this plugin. We also provide some links to your ContactUs.com account to manage contacts, update your account info, or upgrade to premium plans.</p></div></div>",
                position: "left"
            },
            {
                //4
                element: '#cUsCF_tab_cp',
                intro: "You’ll have a ton more customization options within your ContactUs.com admin panel.  Click here for one-click navigation to your ContactUs.com account to access everything else your free account gives you.",
                position: "bottom"
            }

        ]
    });

    intro.start();
}

function startIntroB(){
    //var intro = introJs();
    intro.setOptions({
        steps: [

            {
                //2
                element: '#cUsCF_tab_fpt',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb fpt_000'></span></div><div class='iR'><h1>This is Your Form Placement Panel.</h1><p>Choose \"Place Across Your Site\" to add the form in all pages or \"Choose Pages\" to select the pages you want the form to be shown in.</p></div></div>"
            },
            {
                //3
                element: '#cUsCF_tab_button',
                intro: "<div class='intro_box_ai'><h1>Choose this option to place the Callout Tab across your entire site.</h1><span class='intro_003_area'></span><ul class='purple'><li>Callout tabs are used to open up your contact form when clicked. </li><li>They’re placed on a layer above your website.</li><li>You choose the callout tab design (or design your own in our Admin Panel). </li></ul></div>",
                position:'right'
            },
            {
                //4
                element: '.cUsCF_sel_def',
                intro: "<div class='intro_box_ai'><h1>Choose Your Form (for existing ContactUs users) </h1><p>If this is your first form, this screen isn’t going to matter much.  However, advanced ContactUs users might have multiple forms in their account. If that’s the case, you’ll need to set which form will be featured in the plugin. </p></div>",
                position: 'top'
            },
            {
                //5
                element: '#cUsCF_custom',
                intro: "<div class='intro_box_ai'><h2>“Choose Pages” if you don’t want the form on your entire page, but on specific pages.</h2><span class='intro_form_area'></span><ul class='purple'><li>If you use this setting, you’ll have the option of using a callout tab or placing the form inline (i.e., directly on the page). </li></ul></div>",
                position: 'right'
            },
            {
                //1
                element: '#cUsCF_tab_fs',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb set_000'></span></div><div class='iR'><h1>Manage settings on your ContactUs.com form(s). </h1><p>You’ll find the deeplinks to your ContactUs.com admin panel to edit your form: </p> <ul class='purple'><li>Select or change your form and tab design templates</li><li>Customize form fields</li><li>Manage 3rd party software integrations</li> <li>Access premium features (for upgraded accounts) like Exit Intent, and A/B Testing </li></ul> </div></div>",
                position: "bottom"
            },
            {
                //1
                element: '#cUsCF_tab_sc',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb sc_000'></span></div><div class='iR'><h1>The ShortCodes Panel</h1><p>Use ContactUs.com forms without the need for programming skills.</p></div></div>",
                position: "bottom"
            },
            {
                //1
                element: '#cUsCF_tab_ac',
                intro: "<div class='intro_box_ai intro'><div class='iL'><span class='thumb ac_000'></span></div><div class='iR'><h1>ContactUs.com Account Settings</h1><p>This section is primarily used to manage your ContactUs.com login credentials within this plugin. We also provide some links to your ContactUs.com account to manage contacts, update your account info, or upgrade to premium plans.</p></div></div>",
                position: "left"
            },
            {
                //4
                element: '#cUsCF_tab_cp',
                intro: "You’ll have a ton more customization options within your ContactUs.com admin panel.  Click here for one-click navigation to your ContactUs.com account to access everything else your free account gives you.",
                position: "bottom"
            }
        ]
    });

    intro.start();
}