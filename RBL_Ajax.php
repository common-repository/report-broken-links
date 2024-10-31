<?php
function RBL_UI_JS(){?>
	<script type="text/javascript">
    jQuery(document).ready(function($) {
<?php
    if(get_option('RBL_SDB') == 'true') {
?>
        var dialogbox = '<div id="dialog" style="display:none;">'+
                            '<span><?=get_option('RBL_Mail');?> :</span><br /><input type="text" name="RBL_Mail" size="24"/><br />'+
                            '<span><?=get_option('RBL_Desc');?> :</span><br /><textarea name="RBL_Desc" cols="26" rows="10"></textarea><br />'+
                            '<button id="RBL_Send"><?=get_option('RBL_Submit');?></button>'+
                        '</div>';
        $(dialogbox).insertAfter('#RBL_Element');

        $('#RBL_Element').click(function(){
            $('#dialog').dialog({dialogClass: 'no-close', closeOnEscape: 'true', title: '<?=get_option('RBL_DBT');?>'});
        });

        $('#RBL_Send').click(function(){
            var mail = $('input[name="RBL_Mail"]').val();
            var desc = $('textarea[name="RBL_Desc"]').val();
            var link = '<?=get_bloginfo('url').$_SERVER['REQUEST_URI'];?>';
			$.post('<?=admin_url('admin-ajax.php', __FILE__); ?>', {action: "RBL_Add", RBL_URL: link, RBL_Mail : mail, RBL_Desc: desc}, function(){$('#dialog').dialog('close');alert('<?php _e(get_option('RBL_msg'), 'RBL'); ?>');});
		});
<?php
    } else {
?>
        $('#RBL_Element').click(function(){
            var link = '<?=get_bloginfo('url').$_SERVER['REQUEST_URI'];?>';
			$.post('<?=admin_url('admin-ajax.php', __FILE__); ?>', {action: "RBL_Add", RBL_URL: link}, function(){alert('<?php _e(get_option('RBL_msg'), 'RBL'); ?>');});
		});
<?php
    }
?>
    });
    </script>
<?php
}
add_action('wp_head', 'RBL_UI_JS');
add_action('wp_ajax_RBL_Add', 'RBL_add');
add_action('wp_ajax_nopriv_RBL_Add', 'RBL_add');
function RBL_add(){RBL_Add_Data($_POST['RBL_URL'], $_POST['RBL_Mail'], $_POST['RBL_Desc']);}
########################
function RBL_Admin_JS(){?>
	<script type="text/javascript">
	function RBL_Del(id){
       jQuery.post(ajaxurl, {action: 'RBL_Del', RBL_ID: id}, function(){
            if(/,/.test(id)) {
                jQuery.each(id.split(','), function(key, id1){jQuery('tr#'+id1).detach();});
            } else {
                jQuery('tr#'+id).detach();
            }
            jQuery('.reports > tbody').append('<tr><td colspan="5"><?php _e('Selected Report(s) Deleted Successfully', 'RBL'); ?></td></tr>');
        });
    }
	</script>
<?php }
add_action('admin_enqueue_scripts', 'RBL_Admin_JS');
add_action('wp_ajax_RBL_Del', 'RBL_Del');
function RBL_Del(){RBL_Del_Data($_POST['RBL_ID']);}
?>