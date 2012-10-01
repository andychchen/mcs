<?php

$ad_template = "advertising";
$nav_template = "nav";
$image_template = "image";

// THIS IS THE DIFFERENT FIELDS

// ALBUM CATEGORIES BOXES (homepage)
function category_boxes($options) {        
    $cats = get_categories('hide_empty=0');
    foreach ($cats as $cat) {
        $options[] = array(    "name" =>  $cat->cat_name,
                    "desc" => "Check this box if you wish to exclude this category in the photo album categories",
                    "id" => "woo_cat_box_".$cat->cat_ID,
                    "std" => "",
                    "type" => "checkbox");                    
    }
    return $options;
}

// THIS IS THE DIFFERENT FIELDS
$options[] = array(    "name" => "General Settings",
                    "type" => "heading");
                        
$options[] = array(    "name" => "Theme Stylesheet",
                    "desc" => "Please select your colour scheme here.",
                    "id" => $shortname."_alt_stylesheet",
                    "std" => "",
                    "type" => "select",
                    "options" => $alt_stylesheets);

$options[] = array(    "name" => "Custom Logo",
                    "desc" => "Paste the full URL of your custom logo image, should you wish to replace our default logo e.g. 'http://www.yoursite.com/logo-trans.png'.",
                    "id" => $shortname."_logo",
                    "std" => "",
                    "type" => "text");                                                 

$options[] = array(    "name" => "Google Analytics",
                    "desc" => "Please paste your Google Analytics (or other) tracking code here.",
                    "id" => $shortname."_google_analytics",
                    "std" => "",
                    "type" => "textarea");        

$options[] = array(    "name" => "Feedburner RSS URL",
                    "desc" => "Enter your Feedburner URL here.",
                    "id" => $shortname."_feedburner_url",
                    "std" => "",
                    "type" => "text");    

$options[] = array(    "name" => "Feedburner RSS ID",
                    "desc" => "Enter your Feedburner ID here.",
                    "id" => $shortname."_feedburner_id",
                    "std" => "",
                    "type" => "text");
                    
$options[] = array(    "name" => "Layout Settings",
                    "type" => "heading");
                    
$options[] = array( "name" => "Exclude pages from top nav",
                    "desc" => "Enter a comma-separated list of the <a href='http://support.wordpress.com/pages/8/'>ID's</a> that you'd like to exclude from the top navigation. (e.g. 1,2,3,4)",
                    "id" => $shortname."_nav_exclude",
                    "std" => "",
                    "type" => "text");  
                    
$options[] = array(    "name" => "Use Dynamic Image Resizer",
                    "desc" => "This will enable thumb.php dynamic image resizer. ",
                    "id" => $shortname."_resize",
                    "std" => "false",
                    "type" => "checkbox");
                                        
$options[] = array(    "name" => "Home Page Options",
                        "type" => "heading");
                        
$options[] = array(    "name" => "Home Page Slider Posts",
                    "desc" => "Select the number of posts to display in your home page slider.",
                    "id" => $shortname."_scroller_posts",
                    "std" => "Select a number:",
                    "type" => "select",
                    "options" => $other_entries);
                        
    
$options[] = array(    "name" => "About Title",
                    "desc" => "Include a short title for your about module on the home page.",
                    "id" => $shortname."_about_header",
                    "std" => "",
                    "type" => "text");
                    
$options[] = array(    "name" => "About Text",
                    "desc" => "Include a short paragraph of text describing your product/service/company.",
                    "id" => $shortname."_about_text",
                    "std" => "",
                    "type" => "textarea");    
                    
$options[] = array(    "name" => "Read More Button Text",
                    "desc" => "Please enter the text you want to appear on the first button. Leave empty to remove.",
                    "id" => $shortname."_about_button",
                    "std" => "",
                    "type" => "text");
                    
$options[] = array(    "name" => "Read More Button URL",
                    "desc" => "Please enter the URL of the page you want linked to button 1",
                    "id" => $shortname."_button_link",
                    "std" => "",
                    "type" => "text");
                    
$options[] = array(    "name" => "About Photo",
                    "desc" => "Please enter the url of the photo you would like to appear in the about module. Leave empty to remove.",
                    "id" => $shortname."_about_photo",
                    "std" => "",
                    "type" => "text");    
                                        
$options[] = array(    "name" =>  "Home Page Category Boxes To Exclude",
                    "type" => "heading");
                    

$options = category_boxes($options);    

$options[] = array(    "name" => "Blog Setup",
                    "type" => "heading");        

$options[] = array(    "name" => "Add Blog Link to Main Navigation?",
                    "desc" => "If checked, this option will add a blog link to your main navigation next to the Home link.",
                    "id" => $shortname."_blog_navigation",
                    "std" => "false",
                    "type" => "checkbox");                                                                                                                                            
                    
$options[] = array(    "name" => "Add blog categories as a drop-down to top navigation link?",
                    "desc" => "If checked, this option will add a drop-down menu - with all your blog categories - to the blog link in the top navigation.",
                    "id" => $shortname."_blog_subnavigation",
                    "std" => "false",
                    "type" => "checkbox");    
                    
$options[] = array( "name" => "Blog Permalink",
                    "desc" => "Please enter the permalink to your blog parent category (i.e. /category/blog/). If you are not using <a href='http://codex.wordpress.org/Using_Permalinks'>Pretty Permalinks</a> then you can use (/?cat=X) where X is your blog category ID.",
                    "id" => $shortname."_blog_permalink",
                    "std" => "",
                    "type" => "text");                                                                                                                        
                        
$options[] = array( "name" => "Blog Category ID",
                    "desc" => "Please enter the ID of your main blog category. Only the sub-categories of this category will be displayed in the category drop-down.",
                    "id" => $shortname."_blog_cat",
                    "std" => "",
                    "type" => "text"); 
                    
$options[] = array(    "name" => "Blog posts on the home page",
                    "desc" => "Select the number of blog posts you'd like to display on the home page. <br />NOTE: Set total number of posts to show on home page in WordPress admin under Settings -> Reading -> Blog posts to show at most.",
                    "id" => $shortname."_featured_posts",
                    "std" => "Select a number:",
                    "type" => "select",
                    "options" => $other_entries);    
                    
update_option('woo_template',$options); 

$woo_metaboxes = array(


        "image" => array (
            "name"        => "image",
            "default"     => "",
            "label"     => "Image",
            "type"         => "upload",
            "desc"      => "Upload a file for image to be used by the Dynamic Image resizer."
        )

        /*
        "post_select" => array (
            "name"        => "post_select",
            "default"     => "two",
            "label"     => "Select",
            "type"         => "select",
            "options"       => $options_select,
            "desc"      => "Schelect"
        ),
        "post_checkbox" => array (
            "name"        => "post_checkbox",
            "default"     => "on",
            "label"     => "Checkbox",
            "type"         => "checkbox",
            "desc"      => "Schelect"
        )
        */
    );
    

 
?>