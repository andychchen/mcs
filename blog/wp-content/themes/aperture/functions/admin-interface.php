<?php
// WooThemes Admin Interface: Setup, Pages, Machine
function woothemes_add_admin() {

    global $themename, $query_string;
    $options = get_option('woo_template');
    $page_advertising = false;
    $page_image_resizing = false;
    $page_nav = false;
    
    foreach  ($options as $value){
        if($value['page'] == 'advertising') $page_advertising = true; 
        if($value['page'] == 'image') $page_resizing = true;
        if($value['page'] == 'nav') $page_nav = true;
    }

    if ( $_GET['page'] == 'woothemes_home' ||
         $_GET['page'] == 'woothemes_advertising' ||
         $_GET['page'] == 'woothemes_uploader' ||
         $_GET['page'] == 'woothemes_image') 
    {
           
        if ( 'save' == $_REQUEST['action'] ) {
    
                foreach ($options as $value) {
                    if ( is_array($value['type'])) {
                        foreach($value['type'] as $meta => $type){
                            if($type == 'text'){
                            update_option( $meta, $_REQUEST[ $meta ]);
                            }
                        }                 
                    }
                    elseif($value['type'] != 'multicheck'){
                         if(isset( $_REQUEST[ $value['id'] ])){update_option( $value['id'], $_REQUEST[ $value['id'] ] );} else { delete_option( $value['id'] ); } 
                    }else{
                        foreach($value['options'] as $mc_key => $mc_value){
                            $up_opt = $value['id'].'_'.$mc_key;
                            update_option($up_opt, $_REQUEST[$up_opt] );
                        }
                    }
                }

                /****
                OLD CODE - Depreciated
                *****
                foreach ($options as $value) {
                        if ( is_array($value['type'])) {
                        foreach($value['type'] as $meta => $type){
                            if($type == 'text'){
                                if(isset($_REQUEST[ $meta ])) { update_option($meta,$_REQUEST[ $meta ] );} else { delete_option( $meta ); }
                            }
                        }                 
                    }
                    elseif($value['type'] != 'multicheck'){
                        if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } 
                    }else{
                        foreach($value['options'] as $mc_key => $mc_value){
                            $up_opt = $value['id'].'_'.$mc_key;                        
                            if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); } 
                        }
                    }
                }
                */
                
                 // Start Uploader
                
                foreach($options as $key => $value){

                $uploaddir = ABSPATH . "/wp-content/woo_uploads/" ;
                if(!is_dir($uploaddir)){
                    mkdir($uploaddir,0777);
                }
                $dir = opendir($uploaddir);
                $files = array();
                    
                update_option('functions_post',$_POST);    
                if($value['type'] == 'upload' ){
                 $id = $value['id'];

                  if(isset($_FILES['attachement_'.$id]) && !empty($_FILES['attachement_'.$id]['name'])) 
                  {
                      if(!eregi('image/', $_FILES['attachement_'.$id]['type']))
                      {
                             echo 'The uploaded file is not an image please upload a valide file. Please go <a href="javascript:history.go(-1)">go back</a> and try again.';
                      } 
                      else 
                      {
                        while($file = readdir($dir)) { array_push($files,$file); } closedir($dir);
                        $loc = get_bloginfo('url').'/wp-content/woo_uploads/';
                        $name = $_FILES['attachement_'.$id]['name'];
                        $file_name = substr($name,0,strpos($name,'.'));
                        $file_name = str_replace(' ','_',$file_name);
                         
                        $_FILES['attachement_'.$id]['name'] = $loc . ceil(count($files) + 1).'-'. $file_name .''.strrchr($name, '.');
                        $uploadfile = $uploaddir . basename($_FILES['attachement_'.$id]['name']);
                    
                    
                    
                         if(move_uploaded_file($_FILES['attachement_'.$id]['tmp_name'], $uploadfile)) {
                                  update_option($id,$_FILES['attachement_'.$id]['name']);
                                
                                  $new_file = $_FILES['attachement_'.$id]['name'];
                                  $old_files = get_option('woo_uploads');
                                  if($old_files){
                                    if(!is_array($old_files))
                                      {
                                      $all_files = array(get_option('woo_uploads'));
                                      }
                                      else
                                      {
                                      $all_files = $old_files;
                                      }
                                      array_unshift($all_files,$new_file);
                                  } else {
                                  $all_files = $new_file;
                                  }
                                  update_option('woo_uploads',$all_files);
                          }
                        }
                      }
                    }
                }

                $send = $_GET['page'];
                header("Location: admin.php?page=$send&saved=true");                                
            
            die;

        } else if ( 'reset' == $_REQUEST['action'] ) {
            global $wpdb;
            $query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'woo_%'";
            $wpdb->query($query);
            
            $send = $_GET['page'];
            header("Location: admin.php?page=$send&reset=true");
            die;
        }

    }

// Check all the Options, then if the no options are created for a ralitive sub-page... it's not created.
    

add_menu_page('Page Title', $themename, 8,'woothemes_home', 'woothemes_page_gen', 'http://www.woothemes.com/favicon.ico');
    add_submenu_page('woothemes_home', $themename, 'Theme Options', 8, 'woothemes_home','woothemes_page_gen'); // Default
    if ($page_advertising){ add_submenu_page('woothemes_home', $themename, 'Advertising', 8, 'woothemes_advertising', 'woothemes_advertising'); }
    if ($page_nav){ add_submenu_page('woothemes_home', $themename, 'Navigation', 8, 'woothemes_nav', 'woothemes_nav'); }
    if ($page_resizing){  add_submenu_page('woothemes_home', $themename, 'Image Resizing', 8, 'woothemes_image', 'woothemes_image'); }
    add_submenu_page('woothemes_home', $themename, 'Uploaded Files', 8, 'woothemes_uploaded', 'woothemes_uploaded');
    add_submenu_page('woothemes_home', 'Available WooThemes', 'Buy Themes', 8, 'woothemes_themes', 'woothemes_more_themes_page');
}

function woothemes_advertising(){ woothemes_page_gen('advertising'); }
function woothemes_nav(){ woothemes_page_gen('nav'); }
function woothemes_image(){ woothemes_page_gen('image'); }
function woothemes_uploaded(){ woothemes_page_gen('uploaded'); }

function woothemes_page_gen($page){

global  $themename, $manualurl; 

$options = get_option('woo_template');

?>
<div class="wrap" id="woo_options">
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post"  enctype="multipart/form-data">
    
        <div id="scrollme"><p class="submit"><input name="save" type="submit" value="Save All Changes" /></p></div>
        <div class="icon32" id="woo-icon">&nbsp;</div>
        <h2><?php echo $themename; ?> Options</h2>

        <div class="info"><strong>Stuck on these options?</strong> <a href="<?php echo $manualurl; ?>" target="_blank">Read The Documentation Here</a> or <a href="http://forum.woothemes.com" target="blank">Visit Our Support Forum</a></div>    

        <?php if ( $_REQUEST['saved'] ) { ?><div style="clear:both;height:20px;"></div><div class="happy"><?php echo $themename; ?>'s Options has been updated!</div><?php } ?>
        <?php if ( $_REQUEST['reset'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?>'s Options has been reset!</div><?php } ?>                        

        <div style="clear:both;height:10px;"></div>

        <?php 
        if($page == 'uploaded')
        {
            woothemes_uploaded_page();
        } 
        else 
        {
            echo woothemes_machine($options,$page);  //The real work horse  
        }
        ?>

    </form>
    
    <div style="clear:both;"></div>

    <form action="<?php echo wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <?php  wp_nonce_field('reset_options'); echo "\n"; ?>
        <p class="submit">
        <input name="reset" type="submit" value="Reset Options" onclick="return confirm('Click OK to reset. Any settings will be lost!');" />
        <input type="hidden" name="action" value="reset" />
        </p>
    </form>
<div style="clear:both;"></div>    
</div><!--wrap-->

 <?php
}

function woothemes_machine($options,$page) {
    
    $counter = 0;
    foreach ($options as $value) {
    
    if($page != $value['page']){
    $counter = 0; //Reset the Counter once a new page settings page is selected
    }
    elseif($page == $value['page']){
    $counter++;
    $val = '';
    //Start Heading
     if ( $value['type'] != "heading" )
     {
         $output .= '<div class="option option-'. $value['type'] .'">'."\n".'<div class="option-inner">'."\n";
        $output .= '<label class="titledesc">'. $value['name'] .'</label>'."\n";
        $output .= '<div class="formcontainer">'."\n".'<div class="forminp">'."\n";
     } 
     //End Heading
                                        
    switch ( $value['type'] ) {
    case 'text':
        $val = $value['std'];
        if ( get_settings( $value['id'] ) != "") { $val = get_settings($value['id']); }
        $output .= '<input name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
    break;
    case 'select':

        $output .= '<select name="'. $value['id'] .'" id="'. $value['id'] .'">';
    
        foreach ($value['options'] as $option) {
            $selected = '';
               if ( get_settings($value['id']) == $option) { $selected = ' selected="selected"'; } elseif ($option == $value['std']) { $selected = ' selected="selected"'; }
            $output .= '<option'. $selected .'>';
            $output .= $option;
            $output .= '</option>';
         } 
         $output .= '</select>';
        
    break;
    case 'textarea':
        $ta_options = $value['options'];
        $ta_value = $value['std'];
        if( get_settings($value['id']) != "") { $ta_value = stripslashes(get_settings($value['id'])); }
        $output .= '<textarea name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $ta_options['cols'] .'" rows="8">'.$ta_value.'</textarea>';
        
    break;
    case "radio":
        
         foreach ($value['options'] as $key=>$option) 
         { 
            $radio_setting = get_settings($value['id']);                                        
            if($radio_setting != '') 
            {
                if ($key == get_settings($value['id']) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }                            
            } 
            else 
            {
                if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
            }
            $output .= '<input type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
        
        }
         
    break;
    case "checkbox":
        if(get_settings($value['id'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }
        $output .= '<input type="checkbox" class="checkbox" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';

    break;
    case "multicheck":
        
        foreach ($value['options'] as $key=>$option) {
                                         
        $woo_key = $value['id'] . '_' . $key;
        $checkbox_setting = get_settings($woo_key);
                
        if($checkbox_setting != '') 
        {        
            if (get_settings($woo_key) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }
        } 
        else 
        { 
            if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
                
        } 
        $output .= '<input type="checkbox" class="checkbox" name="'. $woo_key .'" id="'. $woo_key .'" value="true" '. $checked .' /><label for="'. $woo_key .'">'. $option .'</label><br />';
                                    
        }
    break;
    case "upload":
        
        $output .= woothemes_uploader_function($value['id'],$value['std'],'options');
        
    break;
    case "heading":
        
        if($counter >= 2){
           $output .= '</div>'."\n";
        }
 
        $output .= '<div class="title">';
        $output .= '<p class="submit"><input name="save" type="submit" value="Save Changes" /><input type="hidden" name="action" value="save" /></p>';
        $output .= '<h3>'. $value['name'] .'</h3>'."\n";    
        $output .= '</div>'."\n";
        $output .= '<div class="option_content">'."\n";
    break;                                    
    } 
    
    if ( is_array($value['type'])) {
        
        foreach($value['type'] as $meta => $type){
        if($type == 'text'){
            $val = $value['std'];
            if ( get_settings($meta) != "") { $val = get_settings($meta); }
                $output .= '<input class="input-text-small" name="'. $meta .'" id="'. $meta .'" type="'. $type .'" value="'. $val .'" />';
            }
        elseif ($type == 'meta'){
                $output .= '<span class="meta-two">'.$meta.'</span>';
            }
        }
    }
    
    if ( $value['type'] != "heading" ) { 
        if ( $value['type'] != "checkbox" ) 
            { 
            $output .= '<br/>';
            }
            
        $output .= '</div><div class="desc">'. $value['desc'] .'</div></div>'."\n";
        $output .= '</div></div><div class="clear"></div>'."\n";
    
        }
    }
    }
    $output .= '</div>';
    return $output;
    
}

function quickadd_head() { 
/*
<link rel="stylesheet" href="http://colourlovers.com.s3.amazonaws.com/COLOURloversColorPicker/COLOURloversColorPicker.css" type="text/css" media="all" />
<script type="text/JavaScript" src="http://colourlovers.com.s3.amazonaws.com/COLOURloversColorPicker/js/COLOURloversColorPicker.js"></script>
*/ ?>
<script type="text/javascript">
    jQuery(document).ready(function(){

        try {
        
        var timer = null;  
        var offset = jQuery('#scrollme').offset().top;
          
        jQuery(document).scroll(function(e){
            clearTimeout(timer);
            timer = setTimeout(function(){
                jQuery('#scrollme').animate({
                    top: jQuery(document).scrollTop() + offset 
                }, 'fast');
            }, 200);
        });
          
        } catch(exception) {
          // #scrollme is not on page load.
        }
      
    });
</script>

<?php }

function quickadd_foot() { ?>
<div id="CLCP" class="CLCP"></div>
<script type="text/JavaScript">
_whichField = "hexValue_0";
CLCPHandler = function(_hex) {
// This function gets called by the picker when the sliders are being dragged. The variable _hex contains the current hex value from the picker
// This code serves as an example only, here we use it to do three things:
// Here we simply drop the variable _hex into the input field, so we can see what the hex value coming from the picker is:
document.getElementById(_whichField).value = _hex;
}
// Settings:
_CLCPdisplay = "none"; // Values: "none", "block". Default "none"
_CLCPisDraggable = true; // Values: true, false. Default true
_CLCPposition = "absolute"; // Values: "absolute", "relative". Default "absolute"
_CLCPinitHex = "0039B3"; // Values: Any valid hex value. Default "ffffff"
CLCPinitPicker();
</script> 
<?php 

}

add_action('admin_head', 'quickadd_head');    
//add_action('admin_footer', 'quickadd_foot');


?>