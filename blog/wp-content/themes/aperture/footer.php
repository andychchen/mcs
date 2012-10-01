                <div id="footer">
				
                <?php 
				if ( $GLOBALS['homepage'] ) 
                	include( TEMPLATEPATH . '/footer-home.php');
                else
                	include( TEMPLATEPATH . '/footer-normal.php');
                ?>

                </div><!-- / #footer -->
            </div><!-- / #contentWrap -->
        </div><!-- / #content -->
    </div><!-- / #wrap -->
        
    <div class="container_16 clearfix">
        <div class="grid_16 credits">
            <p>Copyright &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Icons by <strong><a href="http://wefunction.com/2008/07/function-free-icon-set/">Wefunction</a></strong>. Designed by <a href="http://www.woothemes.com"><img src="<?php bloginfo('template_url'); ?>/images/woo-themes.png" alt="Woo Themes" title="" /></a></p>
        </div><!-- / #credits -->
    </div><!-- / #container_16 -->
    
<?php wp_footer(); ?>
<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>

<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>
<!-- Category dropdown --> 
<script type="text/javascript">
<!--
function showlayer(layer){
var myLayer = document.getElementById(layer);
if(myLayer.style.display=="none" || myLayer.style.display==""){
    myLayer.style.display="block";
} else {
    myLayer.style.display="none";
}
}            
-->
</script>
    
</body>
</html>