<div class="wrap cystats">
<?php
    echo '<h2>'.htmlspecialchars(__('CyStats Options','cystats')).'</h2>';
?>
<?php
global $wpdb;
if(isset($_POST['action'])){
if ('insert' == $_POST['action']){

    update_option('cystats_rawtable_max',$_POST['cystats_rawtable_max']);
    update_option('cystats_cleanup_interval',$_POST['cystats_cleanup_interval']);
    update_option('cystats_visit_deltatime',$_POST['cystats_visit_deltatime']);
    update_option('cystats_visits_displayrows',$_POST['cystats_visits_displayrows']);
    
    update_option('cystats_userlevel_tracking',$_POST['cystats_userlevel_tracking']);
    update_option('cystats_adminpage_tracking',$_POST['cystats_adminpage_tracking']);
    update_option('cystats_javascript_tracking',$_POST['cystats_javascript_tracking']);
    update_option('cystats_noip',$_POST['cystats_noip']);

    update_option('cystats_shorten_page',$_POST['cystats_shorten_page']);
    update_option('cystats_shorten_referer',$_POST['cystats_shorten_referer']);
    update_option('cystats_shorten_user_agent',$_POST['cystats_shorten_user_agent']);
    update_option('cystats_localreferer_tracking',$_POST['cystats_localreferer_tracking']);
    update_option('cystats_hits_delta',intval($_POST['cystats_hits_delta']));
    update_option('cystats_visits_delta',intval($_POST['cystats_visits_delta']));
    update_option('cystats_time_offset',intval($_POST['cystats_time_offset']));
    update_option('cystats_hide_cookie',intval($_POST['cystats_hide_cookie']));

	// sanitize ignore lists
	$_POST['cystats_ignorelist_ip']=(explode("\n",$_POST['cystats_ignorelist_ip']));
    $_POST['cystats_ignorelist_ua']=(explode("\n",$_POST['cystats_ignorelist_ua']));
    $_POST['cystats_ignorelist_pages']=(explode("\n",$_POST['cystats_ignorelist_pages']));
	
	$ignore_uas=$ignore_ips=$ignore_pages=array();
	foreach($_POST['cystats_ignorelist_ip'] AS $v){
		$v=trim($v);
		if($v!=''){
			$ignore_ips[]=$v;
		}
	}

	foreach($_POST['cystats_ignorelist_ua'] AS $v){
		$v=trim($v);
			if($v!=''){
			$ignore_uas[]=$v;
		}
	}

	foreach($_POST['cystats_ignorelist_pages'] AS $v){
		$v=trim($v);
			if($v!=''){
			$ignore_pages[]=$v;
		}
	}

    update_option('cystats_ignorelist_ip',serialize($ignore_ips));
    update_option('cystats_ignorelist_ua',serialize($ignore_uas));
    update_option('cystats_ignorelist_pgs',serialize($ignore_pages));

	

    echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Options successfully saved','cystats')).'.</p></div>';
}elseif ('delete' == $_POST['action']){

    $delete_all=intval($_POST['cystats_delete_dball']);
    $delete_live=intval($_POST['cystats_delete_dblive']);
    $delete_static=intval($_POST['cystats_delete_dbstatic']);
    $delete_sure=intval($_POST['cystats_delete_dbsure']);
    
    if($delete_sure!=1){
        echo '<div id="message" class="error fade"><p>'.htmlspecialchars(__('Please check \'To be sure\' checkbox for security reasons.','cystats')).'.</p></div>';        
        echo '</div>';
        die();
    }
    
    if($delete_live!=1 && $delete_static!=1 && $delete_all!=1){
        echo '<div id="message" class="error fade"><p>'.htmlspecialchars(__('Please choose static, live or all database table(s) to empty','cystats')).'.</p></div>';        
        echo '</div>';
        die();
    }

    if($delete_all==1 && $delete_sure==1){
        $r=$wpdb->query('DELETE FROM '.$wpdb->prefix.'options WHERE option_name LIKE \'cystats_%\'');
        if($r!==0)echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Options table cleaned up','cystats')).'.</p></div>';        
        else echo '<div id="message" class="error fade"><p>'.htmlspecialchars(__('Cleaning options not possible','cystats')).'.</p></div>';

        $r=$wpdb->query('DROP TABLE '.$wpdb->prefix.TABLE_STATISTICS_RAW);
        if(is_int($r) && $r==0)echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Raw data table removed','cystats')).'.</p></div>';        
        else echo '<div id="message" class="error fade"><p>'.htmlspecialchars(__('Removing raw data table not possible','cystats')).'.</p></div>';
        $r=$wpdb->query('DROP TABLE '.$wpdb->prefix.TABLE_STATISTICS);
        if(is_int($r) && $r==0)echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Static data table removed','cystats')).'.</p></div>';        
        else echo '<div id="message" class="error fade"><p>'.htmlspecialchars(__('Removing static data table not possible','cystats')).'.</p></div>';
        
        
        die();
    }

    if($delete_live==1 && $delete_sure==1){
        $r=$wpdb->query('DELETE FROM '.$wpdb->prefix.TABLE_STATISTICS_RAW);
        if($r)echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Table now empty','cystats')).'.</p></div>';        
        else echo '<div id="message" class="error fade"><p>'.htmlspecialchars(__('Cleaning table not possible','cystats')).'.</p></div>';
    }
    if($delete_static==1 && $delete_sure==1){
        $r2=$wpdb->query('DELETE FROM '.$wpdb->prefix.TABLE_STATISTICS);
        if($r2)echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Table now empty','cystats')).'.</p></div>';        
        else echo '<div id="message" class="error fade"><p>'.htmlspecialchars(__('Cleaning table not possible','cystats')).'.</p></div>';
    }
}
}
?>
    <form id="cystats_options_form" name="form1" method="post" action="<?php echo $location; ?>">
    <p>
    <fieldset><legend><?php echo htmlspecialchars(__('General information','cystats'));?></legend>
        <?php echo htmlspecialchars(__('CyStats version','cystats')).': '.htmlspecialchars(get_option('cystats_version'));?>
    </p><p>
        <?php echo htmlspecialchars(__('CyStats visits table entries','cystats')).': '.htmlspecialchars($wpdb->get_var('SELECT COUNT(remote_addr) FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW));?>
    </p><p>
        <?php echo htmlspecialchars(__('CyStats first database entry','cystats')).': '.gmdate('d.m.Y, H:i',$wpdb->get_var('SELECT stamp FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW.' ORDER BY stamp ASC LIMIT 1'));?>
    </p><p>
        <?php echo htmlspecialchars(__('CyStats visits table last cleanup','cystats')).': '.date(str_replace(' ', '&#160;', get_option('date_format').'&#160;'.get_option('time_format')), intval(get_option('cystats_last_cleanup')));
        ?>
    </fieldset>
    </p>
    
    <p>
    <fieldset><legend><?php echo htmlspecialchars(__('Database','cystats'));?></legend>
        <label><?php htmlspecialchars(_e('Visits table timeframe','cystats'));?>: </label><input name="cystats_rawtable_max" value="<?php echo get_option('cystats_rawtable_max');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Timeframe for saving visits data in seconds','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Visit table cleanup interval','cystats'));?>: </label><input name="cystats_cleanup_interval" value="<?php echo get_option('cystats_cleanup_interval');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Visits table check and cleanup interval in seconds','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Max. display hit rows','cystats'));?>: </label><input name="cystats_visits_displayrows" value="<?php echo get_option('cystats_visits_displayrows');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Number of hit rows to display on index page','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Visit deltatime','cystats'));?>: </label><input name="cystats_visit_deltatime" value="<?php echo get_option('cystats_visit_deltatime');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Number of seconds for unique visitor interval','cystats'));?></span>
    </fieldset>
    </p>
  
    <p>
    <fieldset>
    <legend><?php echo htmlspecialchars(__('Statistics','cystats'));?></legend>
    
    
    
        <label><?php htmlspecialchars(_e('Disable userlevels:','cystats'));?>: </label><select name="cystats_userlevel_tracking">
            <option value="0" <?php if(get_option('cystats_userlevel_tracking')==0)echo 'selected';?>>0</option>
            <option value="1" <?php if(get_option('cystats_userlevel_tracking')==1)echo 'selected';?>>1</option>
            <option value="2" <?php if(get_option('cystats_userlevel_tracking')==2)echo 'selected';?>>2</option>
            <option value="3" <?php if(get_option('cystats_userlevel_tracking')==3)echo 'selected';?>>3</option>
            <option value="4" <?php if(get_option('cystats_userlevel_tracking')==4)echo 'selected';?>>4</option>
            <option value="5" <?php if(get_option('cystats_userlevel_tracking')==5)echo 'selected';?>>5</option>
            <option value="6" <?php if(get_option('cystats_userlevel_tracking')==6)echo 'selected';?>>6</option>
            <option value="7" <?php if(get_option('cystats_userlevel_tracking')==7)echo 'selected';?>>7</option>
            <option value="8" <?php if(get_option('cystats_userlevel_tracking')==8)echo 'selected';?>>8</option>
            <option value="9" <?php if(get_option('cystats_userlevel_tracking')==9)echo 'selected';?>>9</option>
            <option value="10" <?php if(get_option('cystats_userlevel_tracking')==10)echo 'selected';?>>10</option>
        </select>
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Do not track if userlevel is greater than this ','cystats'));?></span>
    
     
    
    
        <label><?php htmlspecialchars(_e('Admin page statistics','cystats'));?>: </label><input name="cystats_adminpage_tracking" value="1" type="checkbox" <?php if(get_option('cystats_adminpage_tracking')==1)echo 'checked';?> />
        <span class="check" style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Logging of admin page visits','cystats'));?></span>



        <label><?php htmlspecialchars(_e('Anonymize IP','cystats'));?>: </label><input name="cystats_noip" value="1" type="checkbox" <?php if(get_option('cystats_noip')==1)echo 'checked';?> />
        <span class="check" style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Save IP anonymized','cystats'));?></span>
		
    </fieldset>
    </p>

    <p>
    <fieldset><legend><?php echo htmlspecialchars(__('Filtering','cystats'));?></legend>
    <?php 
        global $statistics;
        $ignore=$statistics->get_ignorelists();
    ?>
        <label><?php htmlspecialchars(_e('Set blocker cookie','cystats'));?>: </label><input name="cystats_hide_cookie" value="1" type="checkbox" <?php if(get_option('cystats_hide_cookie')==1)echo 'checked';?> />
        <span class="check" style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Prevent clients with this cookie from beeing tracked by CyStats','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Ignore IP (xxx.xxx.xxx.xxx)','cystats'));?>: </label><textarea name="cystats_ignorelist_ip" ><?php echo ($ignore['IP']==FALSE)?'':implode("\n",$ignore['IP']);?></textarea>
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Ignore those IPs, one IP per line, wildcard (*) allowed (e.g. 192.168.1.*)','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Ignore User Agent (RegExp)','cystats'));?>: </label><textarea name="cystats_ignorelist_ua" ><?php echo ($ignore['UA']==FALSE)?'':implode("\n",$ignore['UA']);?></textarea>
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Ignore user agents matching regular expressions, one per line, delimiters needed. Example: ','cystats'));?><strong><code>=msie=i</code></strong> (<a href="http://de.php.net/preg_match" title="PHP RegExp">RegExp</a>)</span>
        <label><?php htmlspecialchars(_e('Ignore requests by page/post id','cystats'));?>: </label><textarea name="cystats_ignorelist_pages" ><?php echo ($ignore['ID']==FALSE)?'':implode("\n",$ignore['ID']);?></textarea>
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Ignore requests of page/post ids in this list, one id per line','cystats'));?></span>

    </fieldset>
    </p>


    <p>
    <fieldset>
<legend><?php echo htmlspecialchars(__('Display','cystats'));?></legend>

        <label><?php htmlspecialchars(_e('Shorten page name','cystats'));?>: </label><input name="cystats_shorten_page" value="<?php echo get_option('cystats_shorten_page');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Shorten display of page name to this number of chars','cystats'));?></span>

        <label><?php htmlspecialchars(_e('Shorten referer','cystats'));?>: </label><input name="cystats_shorten_referer" value="<?php echo get_option('cystats_shorten_referer');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Shorten display of referer to this number of chars','cystats'));?></span>

        <label><?php htmlspecialchars(_e('Shorten user_agent','cystats'));?>: </label><input name="cystats_shorten_user_agent" value="<?php echo get_option('cystats_shorten_user_agent');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Shorten display of user_agent to this number of chars','cystats'));?></span>

        <label><?php htmlspecialchars(_e('Add this to hits','cystats'));?>: </label><input name="cystats_hits_delta" value="<?php echo get_option('cystats_hits_delta');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Hits correction value','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Add this to visits','cystats'));?>: </label><input name="cystats_visits_delta" value="<?php echo get_option('cystats_visits_delta');?>" type="text" />
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Visits correction value','cystats'));?></span>

    </fieldset>
    </p>
    <p>
        <input class="sbutton" type="submit" value="<?php echo htmlspecialchars(__('Save','cystats'));?>" />
        <input name="action" value="insert" type="hidden" />
    </p>
    </form>
    
    <form id="cystats_delete_table_form" name="form2" method="post" action="<?php echo $location; ?>">
    <p>
    <fieldset>
        <label><?php htmlspecialchars(_e('Delete all database data','cystats'));?>: </label><input name="cystats_delete_dball" value="1" type="checkbox"/>
        <span class="check" style="display:block;color:red;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Delete all data stored in database including options','cystats'));?></span>

        <label><?php htmlspecialchars(_e('Delete live database table','cystats'));?>: </label><input name="cystats_delete_dblive" value="1" type="checkbox"/>
        <span class="check" style="display:block;color:red;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Delete all visits data stored in database','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Delete static database table','cystats'));?>: </label><input name="cystats_delete_dbstatic" value="1" type="checkbox"/>
        <span class="check" style="display:block;color:red;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Delete all static long term data stored in database','cystats'));?></span>
        <label><?php htmlspecialchars(_e('Do you really want this?','cystats'));?>: </label><input name="cystats_delete_dbsure" value="1" type="checkbox"/>
        <span class="check" style="display:block;color:red;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('To be sure','cystats'));?>&hellip;</span>
    </fieldset>
    </p>
    <p>
        <input class="sbutton" type="submit" value="<?php echo htmlspecialchars(__('Delete','cystats'));?>" />
        <input name="action" value="delete" type="hidden" />
    </p>
    </form>
</div>

