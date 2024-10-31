<?php
/*
Plugin Name: Links/Problem Reporter
Plugin URI: http://www.NetRang.net/plugins/Report-Broken-Links
Description: Let Your Site Users Tell You About Your Site Broken Links. It's So Simple! Try It Once!
Version: 2.6.0
Author: RaminMT
Author URI: http://netrang.net/
Licence: GPLv2
*/
/****************** Globals ******************/
define('RBL_DBV', '1.0');
define('RBL_UICSS_PATH', 'scripts/RBL_UI.css');
define('RBL_UICSS_URL', plugin_dir_path(__FILE__).'scripts/RBL_UI.css');
define('RBL_ADM_PATH', 'scripts/RBL_adm.css');
define('RBL_ADMRTL_PATH', 'scripts/RBL_adm_rtl.css');

load_plugin_textdomain('RBL', false, 'report-broken-links/langs');
require_once ('RBL_Ajax.php');

/****************** Admin Section UI ******************/
require_once('ReportBrokenLinks-options.php');
require_once ('RBL_Admin.php');

/****************** User Section UI ******************/
function RBL_UI_enq(){
    if(!wp_script_is('jquery', 'queue')){
		wp_enqueue_script('jquery');
	}
    wp_enqueue_script('jquery-ui-dialog', NULL, array('jquery'));
    wp_enqueue_style('RBL_UI_css', plugins_url(RBL_UICSS_PATH, __FILE__), false);
}
add_action('wp_enqueue_scripts', 'RBL_UI_enq');
##################
function RBL_UI(){
?>
	<input name="RBL_URL" type="hidden" value="<?=get_bloginfo('url').$_SERVER['REQUEST_URI'];?>"/>
	<div id="RBL_Element" style="background:<?=get_option('RBL_BBG').';color:'.get_option('RBL_BTC').';'.get_option('RBL_BCS');?>" role="button"><?=get_option('RBL_DBC');?></div>
<?php
}

/****************** Core Section ******************/
function RBL_Add_Data($dead_url, $email, $description){
	global $wpdb;
    $data = array('links'           =>      $dead_url,
                  'status'          =>      1,
                  'email'           =>      $email,
                  'description'     =>      $description);

	$wpdb->insert($wpdb->prefix.'ReportedLinks', $data);
}
############################
function RBL_Del_Data($ids){
	global $wpdb;
    $wpdb->query("UPDATE {$wpdb->prefix}ReportedLinks SET status='2' WHERE id IN($ids)");
}
##################
function RBL_SC($atts, $content='Report Link') {
	extract(shortcode_atts(array('url' => (get_bloginfo('url').$_SERVER['REQUEST_URI'])), $atts));

	return "<input name=\"RBL_URL\" type=\"hidden\" value=\"{$url}\"/>
		    <div id=\"RBL_Element\" style=\"background:".get_option('RBL_BBG').';color:'.get_option('BTC').';'.get_option('RBL_BCS')."\" role=\"button\">$content</div>";
}
add_shortcode('RBL', 'RBL_SC');
######################
function check_dbv() {
    global $wpdb;

    if(get_option('RBL_dbvcheck') == 'false') {
        return false;
    } else {
        RBL_Install(true);
        $wpdb->query("UPDATE {$wpdb->prefix}ReportedLinks SET status='1' WHERE status='0'");
        update_option('RBL_dbvcheck', 'false');
    }
}
add_action('plugins_loaded', 'check_dbv');

/****************** Installation & UnInstallation ******************/
function RBL_Install($update=false){
	global $wpdb;
    $query = "CREATE TABLE {$wpdb->prefix}ReportedLinks
              (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
               links TEXT CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
               email VARCHAR(30) NULL,
               date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
               description TEXT CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
               status TINYINT UNSIGNED NOT NULL COMMENT '1 For News | 2 For Deleted')";

    if($update) {
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($query);

        delete_option('RBL_jSel');
        delete_option('RBL_txtColor');
        delete_option('RBL_bCSS');
        delete_option('RBL_Btxt');

    } else {
      $wpdb->query($query);
    }

	add_option('RBL_BBG', '#0066CC', '');
    add_option('RBL_BTC', '#ffffff');
    add_option('RBL_BCS', 'cursor:default;padding-right:5px;padding-left:5px;display:inline-block;');
    add_option('RBL_SDB', 'true');
    add_option('RBL_DBVer', RBL_DBV);
    add_option('RBL_dbvcheck', 'true');

    switch(get_locale()) {
        case 'fa_IR':
            add_option('RBL_DBT', 'درباره مشکل صفحه بنویسید');
            add_option('RBL_DBC', 'گزارش خرابی');
            add_option('RBL_msg', 'گزارش با موفقیت ارسال شد.\r\n با تشکر');
            add_option('RBL_Submit', 'ارسال گزارش');
            add_option('RBL_Mail', 'ایمیل');
            add_option('RBL_Desc', 'شرح مشکل');
            break;
        default:
            add_option('RBL_DBT', 'Please tell about this page problem');
            add_option('RBL_DBC', 'Report Problem');
            add_option('RBL_msg', 'Report Sent Successfully.\r\n Thank You!');
            add_option('RBL_Submit', 'Send Report');
            add_option('RBL_Mail', 'Your Email');
            add_option('RBL_Desc', 'Problem Description');
    }
}
register_activation_hook(__FILE__, 'RBL_Install');
#########################
function RBL_UnInstall(){
	global $wpdb;
	$wpdb->query("DROP TABLE {$wpdb->prefix}ReportedLinks");
	delete_option('RBL_BBG');
    delete_option('RBL_BTC');
    delete_option('RBL_BCS');
    delete_option('RBL_DBT');
    delete_option('RBL_DBC');
    delete_option('RBL_SDB');
    delete_option('RBL_msg');
    delete_option('RBL_Submit');
    delete_option('RBL_Mail');
    delete_option('RBL_Desc');
    delete_option('RBL_DBVer');
    delete_option('RBL_dbvcheck');
}
register_deactivation_hook(__FILE__, 'RBL_UnInstall');
?>