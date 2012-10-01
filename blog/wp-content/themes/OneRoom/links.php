<h1>Latest Posts</h1>
<ul><?php get_archives('postbypost', 10); ?></ul>

<h1>Pages</h1>
<ul><?php wp_list_pages('title_li='); ?></ul>

<h1>Archives</h1>
<ul><?php wp_get_archives('type=monthly'); ?></ul>

<h1>Categories</h1>
<ul><?php wp_list_categories('show_count=1&title_li='); ?> </ul>
<ul>

