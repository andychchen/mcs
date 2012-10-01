<?php 

// Custom fields for WP write panel
// This code is protected under Creative Commons License: http://creativecommons.org/licenses/by-nc-nd/3.0/


function woothemes_meta_box_content() {
    global $post, $woo_metaboxes;
    $output = '';
    $output .= '<table class="woo_metaboxes_table">'."\n";
    foreach ($woo_metaboxes as $woo_metabox) {
    if($woo_metabox['type'] == 'text' OR $woo_metabox['type'] == 'select' OR $woo_metabox['type'] == 'checkbox')
            $woo_metaboxvalue = get_post_meta($post->ID,$woo_metabox["name"],true);
            if ($woo_metaboxvalue == "" || !isset($woo_metaboxvalue)) {
                $woo_metaboxvalue = $woo_metabox['default'];
            }
            if($woo_metabox['type'] == 'text'){
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_metabox.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><input class="woo_input_text" type="'.$woo_metabox['type'].'" value="'.$woo_metaboxvalue.'" name="woothemes_'.$woo_metabox["name"].'" id="'.$woo_metabox.'"/>';
                $output .= '<span class="woo_metabox_desc">'.$woo_metabox['desc'].'</span></td>'."\n";
                $output .= "\t".'<td></td></tr>'."\n";  
                              
            }

            elseif ($woo_metabox['type'] == 'select'){
                            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_metabox.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><select class="woo_input_select" name="woothemes_'. $woo_metabox["name"] .'">';
                $output .= '<option>Select a Upload</option>';
                
                $array = $woo_metabox['options'];
                
                if($array){
                    foreach ( $array as $id => $option ) {
                        $selected = '';
                        if($woo_metabox['default'] == $option){$selected = 'selected="selected"';} 
                        if($woo_metaboxvalue == $option){$selected = 'selected="selected"';}
                        $output .= '<option value="'. $option .'" '. $selected .'>' . $option .'</option>';
                    }
                }
                
                $output .= '</select><span class="woo_metabox_desc">'.$woo_metabox['desc'].'</span></td></td><td></td>'."\n";
                $output .= "\t".'</tr>'."\n";
            }
            
            elseif ($woo_metabox['type'] == 'checkbox'){
                if($woo_metaboxvalue == 'on') { $checked = 'checked="checked"';} else {$checked='';}
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_metabox.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><input type="checkbox" '.$checked.' class="woo_input_checkbox" name="woothemes_'. $woo_metabox["name"] .'" />';
                $output .= '<span class="woo_metabox_desc" style="display:inline">'.$woo_metabox['desc'].'</span></td></td><td></td>'."\n";
                $output .= "\t".'</tr>'."\n";
            }
        
        
            elseif($woo_metabox['type'] == 'upload')
            {
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_metabox.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td class="woo_metabox_fields">'. woothemes_uploader_custom_fields($post->ID,$woo_metabox["name"],$woo_metabox["default"],$woo_metabox["desc"]);
                $output .= '</td>'."\n";
                $output .= "\t".'</tr>'."\n";
                
            }
        }
    
    $output .= '</table>'."\n\n";
    echo $output;
}

function woothemes_uploader_custom_fields($pID,$id,$std,$desc){

    // Start Uploader
    $upload = get_post_meta( $pID, $id, true);
    $uploader .= '<input class="woo_input_text" name="'.$id.'" type="text" value="'.$upload.'" />';
    $uploader .= '<div class="clear"></div>'."\n";
    $uploader .= '<input type="file" name="attachement_'.$id.'" />';
    $uploader .= '<input type="submit" class="button button-highlighted" value="Save" name="save"/>';
    $uploader .= '<span class="woo_metabox_desc">'.$desc.'</span></td>'."\n".'<td class="woo_metabox_image"><a href="'. $upload .'"><img src="'.get_bloginfo('template_url').'/thumb.php?src='.$upload.'&w=150&h=80&zc=1" alt="" /></a>';

return $uploader;
}

function woothemes_metabox_insert() {
    global $woo_metaboxes;
    global $globals;
    $pID = $_POST['post_ID'];
    $counter = 0;

    
    foreach ($woo_metaboxes as $woo_metabox) { // On Save.. this gets looped in the header response and saves the values submitted
    if($woo_metabox['type'] == 'text' OR $woo_metabox['type'] == 'select' OR $woo_metabox['type'] == 'checkbox') // Normal Type Things...
        {
            $var = "woothemes_".$woo_metabox["name"];
            if (isset($_POST[$var])) {            
                if( get_post_meta( $pID, $woo_metabox["name"] ) == "" )
                    add_post_meta($pID, $woo_metabox["name"], $_POST[$var], true );
                elseif($_POST[$var] != get_post_meta($pID, $woo_metabox["name"], true))
                    update_post_meta($pID, $woo_metabox["name"], $_POST[$var]);
                elseif($_POST[$var] == "")
                    delete_post_meta($pID, $woo_metabox["name"], get_post_meta($pID, $woo_metabox["name"], true));
            }
            else {
                   delete_post_meta($pID, $woo_metabox["name"], get_post_meta($pID, $woo_metabox["name"], true)); // Deletes check boxes OR no $_POST
            }    
        }
  
    elseif($woo_metabox['type'] == 'upload') // So, the upload inputs will do this rather
        {    
                $counter++;
                $uploaddir = ABSPATH . "wp-content/woo_custom/" ;
                if(!is_dir($uploaddir)){
                    mkdir($uploaddir);
                }
                $dir = opendir($uploaddir);
                $files = array();
                    
                $id = $woo_metabox['name'];

                  if(isset($_FILES['attachement_'.$id]) && !empty($_FILES['attachement_'.$id]['name'])) 
                  {
                      if(!eregi('image/', $_FILES['attachement_'.$id]['type']))
                      {
                          echo 'The uploaded file is not an image please upload a valide file. Please go <a href="javascript:history.go(-1)">go back</a> and try again.';
                      } 
                      else 
                      { 
                        while($file = readdir($dir)) { array_push($files,$file);} closedir($dir);
                        $loc = get_bloginfo('url').'/wp-content/woo_custom/';
                        $name = $_FILES['attachement_'.$id]['name'];
                        $file_name = substr($name,0,strpos($name,'.'));
                        $file_name = str_replace(' ','_',$file_name);
                                       
                        $_FILES['attachement_'.$id]['name'] = $loc . ceil(count($files) + 1).'-'. $file_name .''.strrchr($name, '.');
                        $uploadfile = $uploaddir . basename($_FILES['attachement_'.$id]['name']);
                    
                         if(move_uploaded_file($_FILES['attachement_'.$id]['tmp_name'], $uploadfile)) {
                         
                         $uploaded_file = $_FILES['attachement_'.$id]['name'];
                                  
                          if (isset($uploaded_file)) {    
                              if( get_post_meta( $pID, $id ) == "" )
                              {
                                  add_post_meta($pID, $id, $uploaded_file, true );
                              }
                              elseif($uploaded_file != get_post_meta($pID, $id, true))
                              {
                                  update_post_meta($pID, $id, $uploaded_file);
                              }
                              elseif($uploaded_file == "")
                              {
                                delete_post_meta($pID, $id, get_post_meta($pID, $id, true));
                              }    
                             }  
                          } 
                    }
               }   
        }
    }
}

function woothemes_meta_box() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box('woothemes-settings',$GLOBALS['themename'].' Custom Settings','woothemes_meta_box_content','post','normal');
        add_meta_box('woothemes-settings',$GLOBALS['themename'].' Custom Settings','woothemes_meta_box_content','page','normal');
    }
}

function woothemes_header_inserts(){
?>
<script type="text/javascript">
/* <![CDATA[ */
    jQuery(document).ready(function(){
        jQuery('form#post').attr('enctype','multipart/form-data');
        jQuery('.woo_metaboxes_table th:last, .woo_metaboxes_table td:last').css('border','0');
        var val = jQuery('input#title').attr('value');
        if(val == ''){ 
        jQuery('.woo_metabox_fields .button-highlighted').after("<em class='woo_red_note'>Please add a Title before uploading a file</em>");
        };
    });
/* ]]> */
</script>
<style type="text/css">
.woo_input_text { margin:0 0 10px 0; background:#f0f0f0; color:#444; width:80%; font-size:11px; padding: 5px;}
.woo_input_select { margin:0 0 10px 0; background:#f0f0f0; color:#444; width:60%; font-size:11px; padding: 5px;}
.woo_input_checkbox { margin:0 10px 0 0; }
.woo_metabox_desc { font-size:10px; color:#aaa; display:block}
.woo_metaboxes_table{ border-collapse:collapse; width:100%}
.woo_metaboxes_table tr:hover th,
.woo_metaboxes_table tr:hover td { background:#f4f4f4}
.woo_metaboxes_table th,
.woo_metaboxes_table td{ border-bottom:1px solid #ddd; padding:10px 10px;text-align: left; vertical-align:top}
.woo_metabox_names { width:20%}
.woo_metabox_fields { width:70%}
.woo_metabox_image { text-align: right;}
.woo_red_note { margin-left: 5px; color: #c77; font-size: 10px;}
</style>
<?php
}
add_action('admin_menu', 'woothemes_meta_box');
add_action('admin_head', 'woothemes_header_inserts');
add_action('save_post', 'woothemes_metabox_insert');
?>