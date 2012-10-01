   
    <?php
if ( function_exists('register_sidebar') )
    register_sidebars(2, array(
        'before_widget' => '<ul class="category-bg">',
        'after_widget' => '</ul>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
?>

