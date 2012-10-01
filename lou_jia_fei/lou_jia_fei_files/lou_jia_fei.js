// Created by iWeb 3.0.4 local-build-20120910

function createMediaStream_id1()
{return IWCreateMediaCollection("file://localhost/Users/andychen/Documents/Sites/SiteLou/SiteLou/lou_jia_fei/lou_jia_fei_files/rss.xml",true,255,["No photos yet","%d photo","%d photos"],["","%d clip","%d clips"]);}
function initializeMediaStream_id1()
{createMediaStream_id1().load('file://localhost/Users/andychen/Documents/Sites/SiteLou/SiteLou/lou_jia_fei',function(imageStream)
{var entryCount=imageStream.length;var headerView=widgets['widget4'];headerView.setPreferenceForKey(imageStream.length,'entryCount');NotificationCenter.postNotification(new IWNotification('SetPage','id1',{pageIndex:0}));});}
function layoutMediaGrid_id1(range)
{createMediaStream_id1().load('file://localhost/Users/andychen/Documents/Sites/SiteLou/SiteLou/lou_jia_fei',function(imageStream)
{if(range==null)
{range=new IWRange(0,imageStream.length);}
IWLayoutPhotoGrid('id1',new IWPhotoGridLayout(1,new IWSize(325,244),new IWSize(325,34),new IWSize(488,293),27,27,0,new IWSize(78,68)),new IWPhotoFrame([IWCreateImage('lou_jia_fei_files/kids-pink_ul.png'),IWCreateImage('lou_jia_fei_files/kids-pink_top.png'),IWCreateImage('lou_jia_fei_files/kids-pink_ur.png'),IWCreateImage('lou_jia_fei_files/kids-pink_right.png'),IWCreateImage('lou_jia_fei_files/kids-pink_lr.png'),IWCreateImage('lou_jia_fei_files/kids-pink_bottom.png'),IWCreateImage('lou_jia_fei_files/kids-pink_ll.png'),IWCreateImage('lou_jia_fei_files/kids-pink_left.png')],null,0,0.662281,0.000000,0.000000,0.000000,0.000000,71.000000,43.000000,44.000000,58.000000,100.000000,105.000000,100.000000,105.000000,null,null,null,0.300000),imageStream,range,(null),null,1.000000,null,'../Media/slideshow.html','widget4',null,'widget5',{showTitle:false,showMetric:false})});}
function relayoutMediaGrid_id1(notification)
{var userInfo=notification.userInfo();var range=userInfo['range'];layoutMediaGrid_id1(range);}
function onStubPage()
{var args=window.location.href.toQueryParams();parent.IWMediaStreamPhotoPageSetMediaStream(createMediaStream_id1(),args.id);}
if(window.stubPage)
{onStubPage();}
setTransparentGifURL('../Media/transparent.gif');function hostedOnDM()
{return false;}
function onPageLoad()
{IWRegisterNamedImage('comment overlay','../Media/Photo-Overlay-Comment.png')
IWRegisterNamedImage('movie overlay','../Media/Photo-Overlay-Movie.png')
loadMozillaCSS('lou_jia_fei_files/lou_jia_feiMoz.css')
NotificationCenter.addObserver(null,relayoutMediaGrid_id1,'RangeChanged','id1')
adjustLineHeightIfTooBig('id2');adjustFontSizeIfTooBig('id2');Widget.onload();fixupAllIEPNGBGs();fixAllIEPNGs('../Media/transparent.gif');initializeMediaStream_id1()
performPostEffectsFixups()}
function onPageUnload()
{Widget.onunload();}
