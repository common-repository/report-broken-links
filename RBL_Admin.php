<?php
function RBL_Widget_Content(){
	global $wpdb;
	$RBL_qry = $wpdb->get_results("SELECT ID, Links FROM {$wpdb->prefix}ReportedLinks WHERE status='1' ORDER BY date DESC LIMIT 10");
    $RBL_cnt = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ReportedLinks WHERE status='1'");

	$cont = '<div id="links">';
	foreach ($RBL_qry as $result){
		$cont .= "<a style=\"direction:ltr;float:left;\" href=\"{$result->Links}\">".urldecode($result->Links)."</a><br>";
	}
	$cont .= '</div>';

    if($RBL_cnt >= 13) {
        $cont .= '<a href="'.admin_url('admin.php?page=RBL-reports').'">'.__('See All Reports', 'RBL').'</a>';
    }

    echo $cont;
}
######################
function RBL_Widget(){
	wp_add_dashboard_widget('RBL_Widget', __('10 Recent Reports', 'RBL'), 'RBL_Widget_Content');
}
add_action('wp_dashboard_setup', 'RBL_Widget');
####################
function RBL_Menu(){
	add_menu_page(__('Broken Links Reporter', 'RBL'), __('Broken Links Reporter', 'RBL'), 'manage_options','RBL-reports', 'RBL_Reports');
	add_submenu_page('RBL-reports', __('Reports', 'RBL'), __('Reports', 'RBL'), 'manage_options', 'RBL-reports', 'RBL_Reports');
	add_submenu_page('RBL-reports', __('Setting', 'RBL'), __('Setting', 'RBL'), 'manage_options', 'RBL-Setting', 'RBL_Setting');
	add_submenu_page('RBL-reports', __('How To Use', 'RBL'), __('How To Use', 'RBL'), 'read', 'RBL-learn', 'RBL_Learn');
	add_submenu_page('RBL-reports', __('About Plugin', 'RBL'), __('About Plugin', 'RBL'), 'read', 'RBL-about', 'RBL_About');
	add_submenu_page('RBL-reports', __('Support', 'RBL'), __('Support', 'RBL'), 'read', 'RBL-support', 'RBL_Support');
}
add_action('admin_menu','RBL_Menu');
#########################
function RBL_Admin_enq(){
    if(!wp_script_is('jquery', 'queue')){
		wp_enqueue_script('jquery');
	}
    wp_enqueue_style('RBL_admin', plugins_url(RBL_ADM_PATH, __FILE__), false, NULL);
    if(is_rtl()) {
        wp_enqueue_style('RBL_admin_RTL', plugins_url(RBL_ADMRTL_PATH, __FILE__), 'RBL_admin', NULL);
    }
}
add_action('admin_enqueue_scripts', 'RBL_Admin_enq');
?>