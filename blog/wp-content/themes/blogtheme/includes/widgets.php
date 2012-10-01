<?php

// Lifestream Widget
function LifestreamWidget()
{

?>

<div class="widget">

	<h4><a href="#">LifeStream</a></h4>
	
		<div class="content" id="lifestream">
		
			<?php 
			if (function_exists('lifestream_sidebar_widget')) 
				lifestream_sidebar_widget(); 
			else
				echo 'Install the <a href="http://wordpress.org/extend/plugins/lifestream/">Lifestream plugin</a>'; 
			?>
		
		</div><!--content-->
	
</div>

<?php

}

register_sidebar_widget('Lifestream', 'LifestreamWidget');

?>