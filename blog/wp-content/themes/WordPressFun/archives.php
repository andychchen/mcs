<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">
			<div class="column two">
				<div class="edge-alt"></div>
				
<div class="entry-extended">
<h2>Archives by Month:</h2>
	<ul>
		<?php wp_get_archives('type=monthly'); ?>
	</ul>

<h2>Archives by Subject:</h2>
	<ul>
		 <?php wp_list_categories(); ?>
	</ul>
</div>
	</div><!-- end column -->
</div><!-- end content -->
<?php get_footer(); ?>