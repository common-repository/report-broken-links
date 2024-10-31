<?php
function RBL_Pages($page='reports') {
    global $wpdb;
?>
    <div class="wrapper" dir="<?=(is_rtl() ? 'rtl' : 'ltr');?>">
	<div class="header">
		<h2><?php _e('Broken Links Reporter', 'RBL');?></h2>
    </div>
	<div class="main">
<?php
    switch($page) {
        case 'reports':
            $RBL_qry = $wpdb->get_results("SELECT id, links, description, email FROM {$wpdb->prefix}ReportedLinks WHERE status='1' ORDER BY date DESC");
?>
    		<h2><?php _e('Reported Links', 'RBL');?></h2><hr />
            <div>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" class="reports"><tr>
            <td><input id="chkall" type="checkbox" onclick="jQuery(this).parents('table').find(':checkbox').attr('checked', this.checked);"/></td>
            <td class="brds"><?php _e('Reporter Mail', 'RBL');?></td>
            <td class="brds"><?php _e('Description', 'RBL');?></td>
            <td class="brds"><?php _e('Reported Links', 'RBL');?></td>
            <td class="brds"></td></tr>
<?php
        	foreach ($RBL_qry as $result){
?>
        		<tr id="<?=$result->id;?>">
                <td><input type="checkbox" name="ids" id="<?=$result->id;?>"/></td>
                <td class="brd"><a style="direction:ltr;float:left;" href="mailto:<?=$result->email;?>" target="_blank"><?=$result->email;?></a></td>
                <td class="brd"><?=$result->description;?></td>
                <td class="brd"><a style="direction:ltr;float:left;" href="<?=$result->links;?>"><?=str_replace(get_bloginfo('url'), '', $result->links);?></a></td>
                <td class="brd"><a class="RBL_del" onclick="RBL_Del('<?=$result->id;?>')" title="<?php _e('Delete', 'RBL');?>"></a></td>
                </tr>
<?php
        	}
?>
            </table><br />
            <button onclick="var x='';jQuery(':checked').not('#chkall').each(function(){x = this.id + ',' + x;});RBL_Del(x.slice(0, -1))"><?php _e('Delete Selected Reports', 'RBL');?></button>
            </div>
<?php
            break;
        case 'setting':
            $DBC = isset($_POST['DBC']) ? $_POST['DBC'] : get_option('RBL_DBC');
			$BBG = isset($_POST['BBG']) ? $_POST['BBG'] : get_option('RBL_BBG');
			$BTC = isset($_POST['BTC']) ? $_POST['BTC'] : get_option('RBL_BTC');
			$BCS = isset($_POST['BCS']) ? $_POST['BCS'] : get_option('RBL_BCS');
            $DBT = isset($_POST['DBT']) ? $_POST['DBT'] : get_option('RBL_DBT');
            $EFT = isset($_POST['EFT']) ? $_POST['EFT'] : get_option('RBL_Mail');
			$DFT = isset($_POST['DFT']) ? $_POST['DFT'] : get_option('RBL_Desc');
			$SBT = isset($_POST['SBT']) ? $_POST['SBT'] : get_option('RBL_Submit');
            $TMS = isset($_POST['TMS']) ? $_POST['TMS'] : str_replace('\r\n', "\r\n", get_option('RBL_msg'));
            $SDB = isset($_POST['SDB']) ? $_POST['SDB'] : 'false';
            $DCSS = file_get_contents(RBL_UICSS_URL, TRUE);
			$DCS = isset($_POST['DCS']) ? $_POST['DCS'] : $DCSS;

    		if(isset($_POST['post'])){
    			update_option('RBL_DBC', $DBC);
    			update_option('RBL_BBG', $BBG);
    			update_option('RBL_BTC', $BTC);
    			update_option('RBL_BCS', $BCS);
                update_option('RBL_SDB', $SDB);
                update_option('RBL_DBT', $DBT);
                update_option('RBL_Mail', $EFT);
    			update_option('RBL_Desc', $DFT);
    			update_option('RBL_Submit', $SBT);
                update_option('RBL_msg', str_replace("\r\n", '\r\n', $TMS));
                if($DCSS !== $DCS) {
                    unlink(RBL_UICSS_URL);
                    file_put_contents(RBL_UICSS_URL, $DCS, FILE_USE_INCLUDE_PATH);
                }
    		}
            $SDB = get_option('RBL_SDB');
?>
			<form action="" method="POST">
			<h2><?php _e('Button Appearance', 'RBL');?></h2><hr />
			<div>
    			<span><?php _e('Button Caption', 'RBL');?>: </span><input type="text" name="DBC" value="<?=$DBC;?>"/><br />
    			<span><?php _e('Button Color', 'RBL');?>: </span><input type="color" name="BBG" value="<?=$BBG;?>"/><br />
    			<span><?php _e('Text Color', 'RBL');?>: </span><input type="color" name="BTC" value="<?=$BTC;?>"/><br />
    			<span><?php _e('Button Custom CSS', 'RBL');?>: </span><textarea name="BCS" style="direction:ltr;vertical-align:-25px;" cols="50" rows="3"><?=$BCS;?></textarea><br />
            </div>

            <h2><?php _e('Description Box', 'RBL');?></h2><hr />
            <div>
                <span><?php _e('Show Description Box', 'RBL');?>: </span><input type="checkbox" name="SDB" value="true" <?=($SDB=='true'? 'checked' : '');?>/><br />
				<span><?php _e('Email Field Text', 'RBL');?>: </span><input type="text" name="EFT" value="<?=$EFT;?>"/><br />
				<span><?php _e('Description Field Text', 'RBL');?>: </span><input type="text" name="DFT" value="<?=$DFT;?>"/><br />
				<span><?php _e('Submit Button Text', 'RBL');?>: </span><input type="text" name="SBT" value="<?=$SBT;?>"/><br />
                <span><?php _e('Description Box Title', 'RBL');?>: </span><input type="text" name="DBT" value="<?=$DBT;?>" size="50"/><br />
				<span><?php _e('Description Box Custom CSS', 'RBL');?>: </span><textarea name="DCS" style="direction:ltr;vertical-align:-84px;" cols="70" rows="10"><?=$DCS;?></textarea><br /><br />
			</div>

            <h2><?php _e('Other Options', 'RBL');?></h2><hr />
            <div>
                <span><?php _e('Thanks Message', 'RBL');?>: </span><textarea name="TMS" style="vertical-align: -25px;" cols="40" rows="3"><?=$TMS;?></textarea><br />
                <h6><?php _e('Use "\r\n" to break the line in message box', 'RBL');?></h6><br /><br /><br />
            </div>

            <input type="hidden" name="post" value="true"/>
            <input type="submit" value="<?php _e('Save Setting', 'RBL');?>"/>
            </form>
<?php
            break;
        case 'learn':
?>
			<h2><?php _e('How To Use', 'RBL');?></h2><hr />
			<div>
				<?php _e('You have 3 option to use this plugin', 'RBL');?>:<br /><br />
				1. <?php _e('Open Your template single.php file and place the following code everywhere You want to see the button', 'RBL');?><br />
				<php>&lt;?php RBL_UI(); ?&gt;</php><br /><br />
				2. <?php _e('Or anywhere in Your template create a button with "RBL_Element" id', 'RBL');?><br />
                3. <?php _e('As an alternate or generally You can use RBL shortcode.', 'RBL');?><br />
                <?php _e('To do this use [RBL url="Link to be reported"]Caption Of Button[/RBL] "url" attribute is optional and if You don\'t define It, It\'ll use the page url and also caption can be empty in this way the caption will be "Report Link"', 'RBL');?>
				<div class="clear"></div><br /><hr />
				<?php _e('In setting page You can define button caption & color & text color or input Your custom CSS style for the default button.', 'RBL')?><br />
                <?php _e('There are some other setting available for description box such as: title, description, custom style and There is another field called "email" but you can fill It with everything', 'RBL')?>
                <div class="clear"></div><br />
                <?php _e('If You need to use image intead of button and Its text, empty the button caption and in custom css box add the following code:', 'RBL')?><br />
                <php>background:url(path_to_image);</php>
                <div class="clear"></div>
			</div>
<?php
            break;
        case 'about':
?>
			<h2><?php _e('About Plugin', 'RBL');?></h2><hr />
			<div>
				<?php _e('I hope It makes You satisfy.', 'RBL');?>
				<?php _e('Some of the plugin features', 'RBL');?>:<br /><br />
				<li><?php _e('High security', 'RBL');?></li>
				<li><?php _e('Simple to use with shortcode, jQuery selector and default button', 'RBL');?></li>
				<li><?php _e('Ajax-Based', 'RBL');?></li>
				<li><?php _e('Fully customizable', 'RBL');?></li>
                <li><?php _e('Fast and light', 'RBL');?></li>
                <li><?php _e('Description Box to get email/name of clients and their descriptions', 'RBL');?></li><br/>
			</div>
<?php
            break;
        case 'support':
?>
			<h2><?php _e('About Programmer', 'RBL');?></h2><hr />
			<div>
				<?php _e('Name', 'RBL');?> : RaminMT<br />
				<?php _e('Site', 'RBL');?> : <a href="http://www.netrang.net/">NetRang (Under Construction :D)</a><br /><br />
				<?php _e('Email', 'RBL');?> : <a href="mailto:RaminMT007@gmail.com">RaminMT007@gmail.com</a><br /><br />
                <?php _e('Feel free to contact! Any points and suggestions will be appreciated :X', 'RBL');?>
			</div>
<?php
            break;
    }
?>
    </div>
	</div>
<?php
}
#######################
function RBL_Reports(){RBL_Pages();}
function RBL_Setting(){RBL_Pages('setting');}
function RBL_Learn(){RBL_Pages('learn');}
function RBL_About(){RBL_Pages('about');}
function RBL_Support(){RBL_Pages('support');}
?>