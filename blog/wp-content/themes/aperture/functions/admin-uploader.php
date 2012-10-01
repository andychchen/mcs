<?php
// WooThemes Uploader

function woothemes_uploader_function($id,$std){
    

$uploader .= '<input type="file" name="attachement_'.$id.'" class="upload_input"></input>';
$uploader .= '<span class="submit"><input name="save" type="submit" value="Upload" class="button upload_save" /></span>';
$uploader .= '<input type="hidden" name="attachement_loos_'.$id.'" value="' . $globals['attachement_'.$id] .'"></input>';


$upload = get_option($id);


    $uploader .= '<div class="clear"></div>';
    $uploader .= '<input class="upload-input-text" name="'.$id.'" value="'.$upload.'"/>';
    $uploader .= '<div class="clear"></div>';
    $uploader .= '<a href="'. $upload .'">';
    
    if (empty($upload))
    {
    $uploader .= '<img src="'.$std.'" alt="" width="290" />';
    }
    else
    {        
    $uploader .= '<img src="'.get_bloginfo('template_url').'/thumb.php?src='.$upload.'&w=290&h=200&zc=1" alt="" />';
    }
    $uploader .= '</a>';

return $uploader;
}

function woothemes_uploaded_page(){
    
    $uploads = get_option('woo_uploads');
    $output .= '<div class="title"><h3>Uploaded Archive</h3></div>';
    
    if (is_array($uploads)){
    foreach ($uploads as $upload){
        
        $output .= '<div class="uploader_page"><div class="uploader_page_image">';
        $output .= '<a href="'. $upload .'"><img src="'.get_bloginfo('template_url').'/thumb.php?src='. $upload .'&w=250&h=150&zc=1" alt="" /></a></div>';
        $output .= '<div class="uploader_page_input"><span>Image Location</span><input type="text" value="'. $upload .'" /></div>';
        $output .= '<div class="clear"></div></div>';
    }
    }
    
    else {
        $output .= '<div class="uploader_page"><div class="uploader_page_image">';
        $output .= '<a href="'. $uploads .'"><img src="'.get_bloginfo('template_url').'/thumb.php?src='. $uploads .'&w=250&h=150&zc=1" alt="" /></a></div>';
        $output .= '<div class="uploader_page_input"><span>Image Location</span><input type="text" value="'. $uploads .'" /></div>';
        $output .= '<div class="clear"></div></div>';
     }
    echo $output;
}


?>