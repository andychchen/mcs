<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<div>
	<input type="text" class="txt" name="s" id="s" value="Type and hit enter to search" onfocus="if (this.value == 'Type and hit enter to search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Type and hit enter to search';}" />
	<input type="image" src="<?php bloginfo('template_directory');?>/images/search_button.gif" class="btn" alt="" value="" />
	</div>
	</form>
