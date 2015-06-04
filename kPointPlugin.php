<?php
/*
  Plugin Name: kPoint Video Embedder
  Plugin URI: http://www.kpoint.com
  Description: Helps you to embed kPoint videos on your hosted WordPress, using shortcode.
  Author: Team kPoint
  Version: 7.8
  Author URI: http://www.kpoint.com
  License: All right reserved, 2009-2014 kPoint Technologies.
*/
 
if(is_admin()) {
    //We'll key on the slug for the settings page so set it here so it can be used in various places
    define( 'KPOINT_PLUGIN_SLUG', 'kpoint_kapsules_helper' );
    require_once('kPointSettings.php');
    //Register a callback for our specific plugin's actions
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'kPoint_plugin_action_links' );
    function kPoint_plugin_action_links( $links )
    {
        $links[] = '<a href="'. menu_page_url( KPOINT_PLUGIN_SLUG, false ) .'">Settings</a>';
        return $links;
    }
    add_action( 'admin_menu', 'wpkapsulerhelper_add_admin_menu' );
    add_action( 'admin_init', 'wpkapsulerhelper_settings_init' );
}

class kpoint_shortcode
{
    function shortcode($attrs, $content = null)
    {
        $options = get_option( 'wpkapsulerhelper_settings' );
        
        // BEGIN CONFIGURATION 
        $KPOINT_HOST = $options['wpkapsulerhelper_kpoint_domain'];
        $CLIENT_ID = $options['wpkapsulerhelper_client_id'];
        $SECRET_KEY = $options['wpkapsulerhelper_secret_key'];
        
        // END OF CONFIGURATION
        
        if(empty($KPOINT_HOST)) {
            return "<!-- kpoint_kapsule: invalid kpoint domain: $KPOINT_HOST -->";
        }
        extract(shortcode_atts(array(
            'id' => '',
            'width' => '400',
            'height' => '390',
            'version' => 2,
            'size' => 'L',
            'skipauth' => 'false'
        ), $attrs));

        // check gconfid
        if (empty($id) || strpos($id, "gcc-") !== 0) {
            return "<!-- kpoint_kapsule: invalid gconfid: $gconfid -->";
        }

        //To show public kapsule no authorization required.
        if((strcasecmp($skipauth,"true") == 0)) {
            return
            "<iframe src='http://$KPOINT_HOST/kapsule/$id/v$version/embedded?size=$size' 
                               allowFullScreen webkitallowFullScreen mozallowFullScreen
                               width='$width' height='$height'
                               rel='nofollow'>
             </iframe>
            ";
        }
        // check user
        $current_user = wp_get_current_user();
        if (!$current_user) 
            return "<!-- kpoint_kapsule: unable to get user details -->";
        $email = $current_user->user_email;
        $displayname = $current_user->display_name;
        $challenge = time();

        // compute hmac token
        $data = "$CLIENT_ID:$email:$displayname:$challenge";
        $token = hash_hmac("md5", $data, $SECRET_KEY, true);
        $b64token = base64_encode($token);
        $b64token = str_replace("=","", $b64token);
        $b64token = str_replace("+","-", $b64token);
        $b64token = str_replace("/","_", $b64token);
        
        
        $xtToken = "client_id=$CLIENT_ID&user_email=$email&user_name=$displayname&challenge=$challenge&xauth_token=$b64token";
        $xtToken = base64_encode($xtToken);
        $xtToken = str_replace("=","", $xtToken);
        $xtToken = str_replace("+","-", $xtToken);
        $xtToken = str_replace("/","_", $xtToken);
        
        // generate iframe
        return
            "<iframe src='http://$KPOINT_HOST/kapsule/$id/v$version/embedded?size=$size&xt=$xtToken' 
                               allowFullScreen webkitallowFullScreen mozallowFullScreen
                               width='$width' height='$height'
                               rel='nofollow'>
             </iframe>
            ";
    }
}

add_shortcode('kapsule', array('kpoint_shortcode', 'shortcode'));
