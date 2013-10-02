<?php

// loginAlreadyUser handler function...
add_action('wp_ajax_cUsCF_loginAlreadyUser', 'cUsCF_loginAlreadyUser_callback');
function cUsCF_loginAlreadyUser_callback() {
    $cUsCF_api = new cUsComAPI_CF();
    $cUs_email = $_REQUEST['email'];
    $cUs_pass = $_REQUEST['pass'];
    
    $cUsCF_API_credentials = $cUsCF_api->getAPICredentials($cUs_email, $cUs_pass); //api hook;
    
    if($cUsCF_API_credentials){
        $cUs_json = json_decode($cUsCF_API_credentials);
        
        switch ( $cUs_json->status  ) :
            case 'success':
                
                $cUs_API_Account    = $cUs_json->api_account;
                $cUs_API_Key        = $cUs_json->api_key;
                
                if(strlen(trim($cUs_API_Account)) && strlen(trim($cUs_API_Key))):
                    
                    $aryUserCredentials = array(
                        'API_Account' => $cUs_API_Account,
                        'API_Key'     => $cUs_API_Key
                    );
                    update_option('cUsCF_settings_userCredentials', $aryUserCredentials);
                    
                    $cUsCF_API_getKeysResult = $cUsCF_api->getFormKeysAPI($cUs_API_Account, $cUs_API_Key); //api hook;
                    
                    $old_options = get_option('contactus_settings'); //GET THE OLD OPTIONS
                    
                    $cUs_jsonKeys = json_decode($cUsCF_API_getKeysResult);
                
                    if($cUs_jsonKeys->status == 'success' ):
                        
                        $postData = array( 'email' => $cUs_email, 'credential'    => $cUs_pass);
                        update_option('cUsCF_settings_userData', $postData);
                        
                        foreach ($cUs_jsonKeys as $oForms => $oForm) {
                            if ($oForms !='status' && $oForm->form_type == 0 && $oForm->default == 1){ //GET DEFAULT CONTACT FORM KEY
                               $defaultFormKey = $oForm->form_key;
                            }
                        }  
                            
                        if(empty($old_options)):
                            
                            $aryFormOptions = array('tab_user' => 1,'cus_version' => 'tab'); //DEFAULT SETTINGS / FIRST TIME
                            
                            update_option('cUsCF_FORM_settings', $aryFormOptions );//UPDATE FORM SETTINGS
                            update_option('cUsCF_settings_form_key', $defaultFormKey);//DEFAULT FORM KEYS
                            update_option('cUsCF_settings_form_keys', $cUs_jsonKeys); // ALL FORM KEYS
                            
                            echo 1; 
                            
                        else:
                            
                            if(strlen($old_options['form_key'])){
                               
                                update_option('cUsCF_FORM_settings', $old_options );//UPDATE FORM SETTINGS
                                
                                $aryTabPages = get_option('contactus_settings_tabpages');
                                $aryInlinePages = get_option('contactus_settings_inlinepages');
                                
                                update_option('cUsCF_settings_form_key', $old_options['form_key']);//DEFAULT FORM KEYS
                                
                                if(!empty($aryTabPages) && is_array($aryTabPages)){
                                    
                                    update_option( 'cUsCF_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
                                    
                                    foreach ($aryTabPages as $pageID):
                                        
                                        $aryFormOptions = array( //LOAD OLD PAGE SETTINGS / FIRST TIME
                                            'tab_user'          => 1,
                                            'form_key'          => $old_options['form_key'],   
                                            'cus_version'       => 'tab'
                                        );

                                        if($pageID != 'home'){
                                            update_post_meta($pageID, 'cUsCF_FormByPage_settings', $aryFormOptions);//SAVE DATA ON POST TYPE PAGE METAS
                                        }else{
                                            update_option('cUsCF_HOME_settings', $aryFormOptions );//UPDATE FORM SETTINGS 
                                        }
                                        
                                    endforeach;
                                    
                                }
                                    
                                
                                if(!empty($aryInlinePages) && is_array($aryInlinePages)){
                                    update_option( 'cUsCF_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
                                
                                
                                    foreach ($aryInlinePages as $pageID):
                                        
                                        $aryFormOptions = array( //LOAD OLD PAGE SETTINGS / FIRST TIME
                                            'tab_user'          => 0,
                                            'form_key'          => $old_options['form_key'],   
                                            'cus_version'       => 'inline'
                                        );

                                        if($pageID != 'home'){
                                            update_post_meta($pageID, 'cUsCF_FormByPage_settings', $aryFormOptions);//SAVE DATA ON POST TYPE PAGE METAS
                                        }else{
                                            update_option('cUsCF_HOME_settings', $aryFormOptions );//UPDATE FORM SETTINGS 
                                        }
                                        
                                    endforeach;
                                }
                                    
                                
                                echo 1; 
                                
                            }else if(!strlen($defaultFormKey)){
                                echo 2; //NO ONE CONTACT FORM 
                            }
                            
                            
                        endif;

                            //echo 1;
                        
                    else:
                        echo 'Error. . . ';
                    endif;
                    
                else:
                    echo 'Error. . . ';
                endif;
                
                break;

            case 'error':
                echo $cUs_json->error;
                //$cUsCF_api->resetData(); //RESET DATA
                break;
        endswitch;
    }
    
    die();
}

// loginAlreadyUser handler function...
add_action('wp_ajax_cUsCF_LoadDefaultKey', 'cUsCF_LoadDefaultKey_callback');
function cUsCF_LoadDefaultKey_callback() {
    $cUsCF_api = new cUsComAPI_CF();
    $cUsCF_userData = get_option('cUsCF_settings_userData'); //get the saved user data
    $cUs_email = $cUsCF_userData['email'];
    $cUs_pass = $cUsCF_userData['credential'];
    
    $cUsCF_API_result = $cUsCF_api->getFormKeysAPI($cUs_email, $cUs_pass); //api hook;
    if($cUsCF_API_result){
        $cUs_json = json_decode($cUsCF_API_result);

        switch ( $cUs_json->status  ) :
            case 'success':
                
                foreach ($cUs_json as $oForms => $oForm) {
                    if ($oForms !='status' && $oForm->form_type == 0 && $oForm->default == 1){//GET DEFAULT CONTACT FORM KEY
                       $defaultFormKey = $oForm->form_key;
                    }
                }
                
                update_option('cUsCF_settings_form_key', $defaultFormKey); 
                
                echo 1;
                break;

            case 'error':
                echo $cUs_json->error;
                //$cUsCF_api->resetData(); //RESET DATA
                break;
        endswitch;
    }
    
    die();
}


// cUsCF_verifyCustomerEmail handler function...
add_action('wp_ajax_cUsCF_verifyCustomerEmail', 'cUsCF_verifyCustomerEmail_callback');
function cUsCF_verifyCustomerEmail_callback() {
    
    if      ( !strlen($_REQUEST['fName']) ):      echo 'Missing First Name, is required fieldsss!';      die();
    elseif  ( !strlen($_REQUEST['lName']) ):      echo 'Missing Last Name, is required field!';       die();
    elseif  ( !strlen($_REQUEST['Email']) ):      echo 'Missing/Invalid Email, is required field!';   die();
    elseif  ( !strlen($_REQUEST['website']) ):    echo 'Missing Website, is required field!';         die();
    else:
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        
        $postData = array(
            'fname' => $_REQUEST['fName'],
            'lname' => $_REQUEST['lName'],
            'email' => $_REQUEST['Email'],
            'website' => $_REQUEST['website']
        );

        $cUsCF_API_EmailResult = $cUsCF_api->verifyCustomerEmail($_REQUEST['Email']); //EMAIL VERIFICATION
        if($cUsCF_API_EmailResult) :
            $cUsCF_jsonEmail = json_decode($cUsCF_API_EmailResult);
            
            switch ($cUsCF_jsonEmail->result):
                case 0 :
                    //echo 'No Existe';
                    echo 1;
                    update_option('cUsCF_settings_userData', $postData);
                    break;
                case 1 :
                    //echo 'Existe';
                    echo 2;//ALREDY CUS USER
                    delete_option('cUsCF_settings_userData');
                    break;
            endswitch;
        else:  
            echo 'Ouch! unfortunately there has being an error during the application, please try again';
            exit();
        endif;
         
     endif;
    
    die();
}


// cUsCF_createCustomer handler function...
add_action('wp_ajax_cUsCF_createCustomer', 'cUsCF_createCustomer_callback');
function cUsCF_createCustomer_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userData'); //get the saved user data
    
    if      ( !strlen($cUsCF_userData['fname']) ):      echo 'Missing First Name, is required fieldsss!';      die();
    elseif  ( !strlen($cUsCF_userData['lname']) ):      echo 'Missing Last Name, is required field!';       die();
    elseif  ( !strlen($cUsCF_userData['email']) ):      echo 'Missing/Invalid Email, is required field!';   die();
    elseif  ( !strlen($cUsCF_userData['website']) ):    echo 'Missing Website, is required field!';         die();
    elseif  ( !strlen($_REQUEST['Template_Desktop_Form']) ):    echo 'Missing Form Template!';         die();
    elseif  ( !strlen($_REQUEST['Template_Desktop_Tab']) ):    echo 'Missing Tab Template!';         die();
    else:
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        
        $postData = array(
            'fname' => $cUsCF_userData['fname'],
            'lname' => $cUsCF_userData['lname'],
            'email' => $cUsCF_userData['email'],
            'website' => $cUsCF_userData['website'],
            'Template_Desktop_Form' => $_REQUEST['Template_Desktop_Form'],
            'Template_Desktop_Tab' => $_REQUEST['Template_Desktop_Tab']
        );
        
        $cUsCF_API_result = $cUsCF_api->createCustomer($postData);
        if($cUsCF_API_result) :

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) :

                case 'success':
                    echo 1;//GREAT
                    update_option('cUsCF_settings_form_key', $cUs_json->form_key ); //finally get form key form contactus.com // SESSION IN
                    $aryFormOptions = array( //DEFAULT SETTINGS / FIRST TIME
                        'tab_user'          => 1,
                        'cus_version'       => 'tab'
                    ); 
                    update_option('cUsCF_FORM_settings', $aryFormOptions );//UPDATE FORM SETTINGS
                    update_option('cUsCF_settings_userData', $postData);
                    
                    $cUs_API_Account    = $cUs_json->api_account;
                    $cUs_API_Key        = $cUs_json->api_key;
                    
                    $aryUserCredentials = array(
                        'API_Account' => $cUs_API_Account,
                        'API_Key'     => $cUs_API_Key
                    );
                    update_option('cUsCF_settings_userCredentials', $aryUserCredentials);
                    

                break;

                case 'error':

                    if($cUs_json->error[0] == 'Email exists'):
                        echo 2;//ALREDY CUS USER
                        //$cUsCF_api->resetData(); //RESET DATA
                    else:
                        //ANY ERROR
                        echo $cUs_json->error;
                        //$cUsCF_api->resetData(); //RESET DATA
                    endif;
                break;


            endswitch;
         else:
             //echo 3;//API ERROR
             echo $cUs_json->error;
             // $cUsCF_api->resetData(); //RESET DATA
         endif;
        
         
     endif;
    
    die();
}

// cUsCF_createCustomer handler function...
add_action('wp_ajax_cUsCF_UpdateTemplates', 'cUsCF_UpdateTemplates_callback');
function cUsCF_UpdateTemplates_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userData'); //get the saved user data
    
    if      ( !strlen($cUsCF_userData['email']) ):      echo 'Missing/Invalid Email, is required field!';   die();
    elseif  ( !strlen($_REQUEST['Template_Desktop_Form']) ):    echo 'Missing Form Template!';         die();
    elseif  ( !strlen($_REQUEST['Template_Desktop_Tab']) ):    echo 'Missing Tab Template!';         die();
    else:
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        $form_key       = get_option('cUsCF_settings_form_key');
        $postData = array(
            'email' => $cUsCF_userData['email'],
            'credential' => $cUsCF_userData['credential'],
            'Template_Desktop_Form' => $_REQUEST['Template_Desktop_Form'],
            'Template_Desktop_Tab' => $_REQUEST['Template_Desktop_Tab']
        );
        
        $cUsCF_API_result = $cUsCF_api->updateFormSettings($postData, $form_key);
        if($cUsCF_API_result) :

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) :

                case 'success':
                    echo 1;//GREAT

                break;

                case 'error':
                    //ANY ERROR
                    echo $cUs_json->error;
                    //$cUsCF_api->resetData(); //RESET DATA
                break;


            endswitch;
         else:
             //echo 3;//API ERROR
             echo $cUs_json->error;
             // $cUsCF_api->resetData(); //RESET DATA
         endif;
        
         
     endif;
    
    die();
}

add_action('wp_ajax_cUsCF_changeFormTemplate', 'cUsCF_changeFormTemplate_callback');
function cUsCF_changeFormTemplate_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userCredentials'); //get the saved user data
   
    if      ( !strlen($cUsCF_userData['API_Account']) ):     echo 'Missing API Account!';   die();
    elseif  ( !strlen($cUsCF_userData['API_Key']) ):         echo 'Missing Form Key!';         die();
    elseif  ( !strlen($_REQUEST['Template_Desktop_Form']) ):    echo 'Missing Form Template!';         die();
    elseif  ( !strlen($_REQUEST['form_key']) ):    echo 'Missing Form Key!';         die();
    else:
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        $form_key = $_REQUEST['form_key'];
        
        $postData = array(
            'API_Account'       => $cUsCF_userData['API_Account'],
            'API_Key'           => $cUsCF_userData['API_Key'],
            'Template_Desktop_Form' => $_REQUEST['Template_Desktop_Form']
        );
        
        $cUsCF_API_result = $cUsCF_api->updateFormSettings($postData, $form_key);
        if($cUsCF_API_result) :

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) :

                case 'success':
                    echo 1;//GREAT

                break;

                case 'error':
                    //ANY ERROR
                    echo $cUs_json->error;
                    //$cUsCF_api->resetData(); //RESET DATA
                break;


            endswitch;
         else:
             //echo 3;//API ERROR
             echo $cUs_json->error;
             // $cUsCF_api->resetData(); //RESET DATA
         endif;
        
         
     endif;
    
    die();
}

add_action('wp_ajax_cUsCF_changeTabTemplate', 'cUsCF_changeTabTemplate_callback');
function cUsCF_changeTabTemplate_callback() {
    
    $cUsCF_userData = get_option('cUsCF_settings_userCredentials'); //get the saved user data
   
    if      ( !strlen($cUsCF_userData['API_Account']) ):     echo 'Missing API Account!';   die();
    elseif  ( !strlen($cUsCF_userData['API_Key']) ):         echo 'Missing Form Key!';         die();
    elseif  ( !strlen($_REQUEST['Template_Desktop_Tab']) ):    echo 'Missing Tab Template!';         die();
    elseif  ( !strlen($_REQUEST['form_key']) ):    echo 'Missing Form Key!';         die();
    else:
        
        $cUsCF_api = new cUsComAPI_CF(); //CONTACTUS.COM API
        $form_key = $_REQUEST['form_key'];
        
        $postData = array(
            'API_Account'       => $cUsCF_userData['API_Account'],
            'API_Key'           => $cUsCF_userData['API_Key'],
            'Template_Desktop_Tab' => $_REQUEST['Template_Desktop_Tab']
        );
        
        $cUsCF_API_result = $cUsCF_api->updateFormSettings($postData, $form_key);
        if($cUsCF_API_result) :

            $cUs_json = json_decode($cUsCF_API_result);

            switch ( $cUs_json->status  ) :

                case 'success':
                    echo 1;//GREAT

                break;

                case 'error':
                    //ANY ERROR
                    echo $cUs_json->error;
                    //$cUsCF_api->resetData(); //RESET DATA
                break;


            endswitch;
         else:
             //echo 3;//API ERROR
             echo $cUs_json->error;
             // $cUsCF_api->resetData(); //RESET DATA
         endif;
        
         
     endif;
    
    die();
}



// save custom selected pages handler function...
add_action('wp_ajax_cUsCF_saveCustomSettings', 'cUsCF_saveCustomSettings_callback');
function cUsCF_saveCustomSettings_callback() {
    
    $aryFormOptions = array( //DEFAULT SETTINGS / FIRST TIME
        'tab_user'          => $_REQUEST['tab_user'],
        'cus_version'       => $_REQUEST['cus_version']
    ); 
    update_option('cUsCF_FORM_settings', $aryFormOptions );//UPDATE FORM SETTINGS
    
    cUsCF_page_settings_cleaner();
    
    delete_option( 'cUsCF_settings_inlinepages' );
    delete_option( 'cUsCF_settings_tabpages' );
   
    
    die();
}

// save custom selected pages handler function...
add_action('wp_ajax_cUsCF_deletePageSettings', 'cUsCF_deletePageSettings_callback');
function cUsCF_deletePageSettings_callback() {
    
    $pageID = $_REQUEST['pageID'];
    
    delete_post_meta($pageID, 'cUsCF_FormByPage_settings');//reset values
    cUsCF_inline_shortcode_cleaner_by_ID($pageID); //RESET SC
    
    $aryTabPages = get_option('cUsCF_settings_tabpages');
    $aryTabPages = removePage($pageID,$aryTabPages);
    update_option( 'cUsCF_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
            
    $aryInlinePages = get_option('cUsCF_settings_inlinepages');
    $aryInlinePages = removePage($pageID,$aryInlinePages);
    update_option( 'cUsCF_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
    
    die();
}

// save custom selected pages handler function...
add_action('wp_ajax_cUsCF_changePageSettings', 'cUsCF_changePageSettings_callback');
function cUsCF_changePageSettings_callback() {
    
    $pageID = $_REQUEST['pageID'];
    delete_post_meta($pageID, 'cUsCF_FormByPage_settings');//reset values
    cUsCF_inline_shortcode_cleaner_by_ID($pageID); //RESET SC
    $aryTabPages = get_option('cUsCF_settings_tabpages');
    $aryInlinePages = get_option('cUsCF_settings_inlinepages');
    
    switch ($_REQUEST['cus_version']):
        case 'tab':
            
            $tabUser = 1;
            
            $aryTabPages[] = $pageID;
            $aryTabPages = array_unique($aryTabPages);
            update_option('cUsCF_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
            
            if(!empty($aryInlinePages)){
                $aryInlinePages = removePage($pageID,$aryInlinePages);
                update_option( 'cUsCF_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
            }
            
            echo 1;
            
            break;
        case 'inline':
            
            $tabUser = 0;
            
            $aryInlinePages[] = $pageID;
            $aryInlinePages = array_unique($aryInlinePages);
            update_option( 'cUsCF_settings_inlinepages', $aryInlinePages); //UPDATE OPTIONS
            
            if(!empty($aryTabPages)){
                $aryTabPages = removePage($pageID,$aryTabPages);
                update_option( 'cUsCF_settings_tabpages', $aryTabPages); //UPDATE OPTIONS
            }
            
            cUsCF_inline_shortcode_add($pageID); //ADDING SHORTCODE FOR INLINE PAGES
            
            echo 1;
            
            break;
    endswitch;
    
    $aryFormOptions = array( //DEFAULT SETTINGS / FIRST TIME
        'tab_user'          => $tabUser,
        'form_key'          => $_REQUEST['form_key'],   
        'cus_version'       => $_REQUEST['cus_version']
    );
    
    if($pageID != 'home'){
        update_post_meta($pageID, 'cUsCF_FormByPage_settings', $aryFormOptions);//SAVE DATA ON POST TYPE PAGE METAS
    }else{
       update_option('cUsCF_HOME_settings', $aryFormOptions );//UPDATE FORM SETTINGS 
    }
    
    die();
}

function removePage($valueToSearch, $arrayToSearch){
    $key = array_search($valueToSearch,$arrayToSearch);
    if($key!==false){
        unset($arrayToSearch[$key]);
    }
    return $arrayToSearch;
}

// logoutUser handler function...
add_action('wp_ajax_cUsCF_logoutUser', 'cUsCF_logoutUser_callback');
function cUsCF_logoutUser_callback() {
    
    $cUsCF_api = new cUsComAPI_CF();
    $cUsCF_api->resetData(); //RESET DATA
    
    delete_option( 'cUsCF_settings_api_key' );  
    delete_option( 'cUsCF_settings_form_key' );  
    delete_option( 'cUsCF_settings_list_Name' );  
    delete_option( 'cUsCF_settings_list_ID' );  
    
    echo 'Deleted.... User data'; //none list
    
    die();
}

//GET TEMPLATES LIST

// sendTemplateID handler function...
add_action('wp_ajax_cUsCF_getTemplates', 'cUsCF_getTemplates_callback');
function cUsCF_getTemplates_callback() {
    echo 1; //none list
    
    die();
}

// sendTemplateID handler function...
add_action('wp_ajax_cUsCF_sendTemplateID', 'cUsCF_sendTemplateID_callback');
function cUsCF_sendTemplateID_callback() {
    echo 1; //none list
    
    die();
}


?>