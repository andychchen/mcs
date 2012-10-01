	<ul>
	  <li<?php if ( is_home()) { echo ' id="current"'; } ?>><a class="home" href="<?php echo get_option('home'); ?>/">Home</a></li>
	  <li<?php if ( is_page('about') ) { echo ' id="current"'; } ?>><a class="about" href="<?php echo get_option('home'); ?>/?page_id=2">About</a></li>
	  <li<?php if ( is_page('archives') ) { echo ' id="current"'; } ?>><a class="archives" href="<?php echo get_option('home'); ?>/?page_id=18">Archives</a></li>
	  <li<?php if ( is_page('contact') ) { echo ' id="current"'; } ?>><a class="contact" href="<?php echo get_option('home'); ?>/?page_id=17">Contact</a></li>
	</ul>
	