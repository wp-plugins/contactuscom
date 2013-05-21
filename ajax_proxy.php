<?php

if(!isset($wpdb))
{
    require_once('../../../wp-config.php');
    require_once('../../../wp-includes/wp-db.php');
}

if(isset($_REQUEST['option'])):
    switch ( $_REQUEST['option'] ):
        case 'preview_url' :
            $ch = curl_init();
            $ip = $_SERVER['REMOTE_ADDR'];
            $url= $_REQUEST['line'];
            
            $strCURLOPT  = 'http://example.contactus.com/old/index_proxy.php?api=1';
            $strCURLOPT .= '&line='.esc_url(trim($url));
            $strCURLOPT .= '&ip='.$ip;
            $strCURLOPT .= '&promo=WP';
            $strCURLOPT .= '&agent='.esc_url(trim($_SERVER['HTTP_USER_AGENT']));

            curl_setopt($ch, CURLOPT_URL, $strCURLOPT);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $content = curl_exec($ch);  
            curl_close($ch);

            if($content === FALSE) :
                echo $userStatus = 'error';
            else :
                //echo $cUs_json = json_decode($content);
                echo $content;
            endif;
            
            //print_r($content);
            
        break;    
        case 'preview_url_' : //testing
            
            echo '{"status":"success","url":"http:\/\/www.contactus.com.example.contactus.com"}';
        break;    
    endswitch;
endif;

?>

