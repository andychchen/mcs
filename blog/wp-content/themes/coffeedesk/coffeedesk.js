/* Designed by Templatelite.com*/
var t_height=0,t_gap=0,content_height=0,container_height=0,total_space, margin, padding;
jQuery(document).ready(
function(){
	if(jQuery("#content").height()<350) jQuery("#content").css("padding-bottom","350px");
	setInterval("checkheight()",1000);
});

function checkheight(){
	if(container_height!=jQuery("#container").height()){
		jQuery("#content").css({"padding-bottom":"0px","margin-bottom":"0px"});
		t_height=jQuery("#container").height() + jQuery("#header").height();
		t_gap=Math.ceil(t_height/223)*223-t_height+69;
			
		total_space=jQuery("#container").height()-jQuery("#content").height()+t_gap-28; //content padding

		margin=total_space+jQuery("#content").height()-Math.floor((total_space+jQuery("#content").height())/40)*40;
		padding=total_space-margin;

		jQuery("#content").css({"padding-bottom":padding+"px","margin-bottom":margin+"px"});
		container_height=jQuery("#container").height();
		jQuery("img.rss").css("bottom","20px");//special for Ie6 hack
	}
}
		
jQuery(window).resize(
function(){
	if(jQuery.browser.safari || jQuery.browser.mozilla){
		if(jQuery("body").width()%2 ==1){
			jQuery("body").css("margin-left","1px");
		}else{
			jQuery("body").css("margin-left","0px");
		};
	}
});
