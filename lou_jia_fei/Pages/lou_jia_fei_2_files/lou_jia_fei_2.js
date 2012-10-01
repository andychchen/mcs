// Created by iWeb 3.0.4 local-build-20120910

function createMediaStream_id1()
{return IWCreatePhotocast("file://localhost/Users/andychen/Documents/Sites/SiteLou/SiteLou/lou_jia_fei/Pages/lou_jia_fei_2_files/rss.xml",false);}
function initializeMediaStream_id1()
{createMediaStream_id1().load('file://localhost/Users/andychen/Documents/Sites/SiteLou/SiteLou/lou_jia_fei/Pages',function(imageStream)
{var entryCount=imageStream.length;var headerView=widgets['widget1'];headerView.setPreferenceForKey(imageStream.length,'entryCount');NotificationCenter.postNotification(new IWNotification('SetPage','id1',{pageIndex:0}));});}
function layoutMediaGrid_id1(range)
{createMediaStream_id1().load('file://localhost/Users/andychen/Documents/Sites/SiteLou/SiteLou/lou_jia_fei/Pages',function(imageStream)
{if(range==null)
{range=new IWRange(0,imageStream.length);}
IWLayoutPhotoGrid('id1',new IWPhotoGridLayout(4,new IWSize(166,166),new IWSize(166,0),new IWSize(172,181),27,27,0,new IWSize(16,16)),new IWPhotoFrame([IWCreateImage('lou_jia_fei_2_files/Formal_inset_01.png'),IWCreateImage('lou_jia_fei_2_files/Formal_inset_02.png'),IWCreateImage('lou_jia_fei_2_files/Formal_inset_03.png'),IWCreateImage('lou_jia_fei_2_files/Formal_inset_06.png'),IWCreateImage('lou_jia_fei_2_files/Formal_inset_09.png'),IWCreateImage('lou_jia_fei_2_files/Formal_inset_08.png'),IWCreateImage('lou_jia_fei_2_files/Formal_inset_07.png'),IWCreateImage('lou_jia_fei_2_files/Formal_inset_04.png')],null,0,0.600789,1.000000,1.000000,1.000000,1.000000,14.000000,14.000000,14.000000,14.000000,191.000000,262.000000,191.000000,262.000000,null,null,null,0.100000),imageStream,range,null,null,1.000000,{backgroundColor:'rgb(0, 0, 0)',reflectionHeight:100,reflectionOffset:2,captionHeight:0,fullScreen:1,transitionIndex:2},'../../Media/slideshow.html','widget1','widget2','widget3')});}
function relayoutMediaGrid_id1(notification)
{var userInfo=notification.userInfo();var range=userInfo['range'];layoutMediaGrid_id1(range);}
function onStubPage()
{var args=window.location.href.toQueryParams();parent.IWMediaStreamPhotoPageSetMediaStream(createMediaStream_id1(),args.id);}
if(window.stubPage)
{onStubPage();}
setTransparentGifURL('../../Media/transparent.gif');function hostedOnDM()
{return false;}
function onPageLoad()
{IWRegisterNamedImage('comment overlay','../../Media/Photo-Overlay-Comment.png')
IWRegisterNamedImage('movie overlay','../../Media/Photo-Overlay-Movie.png')
loadMozillaCSS('lou_jia_fei_2_files/lou_jia_fei_2Moz.css')
NotificationCenter.addObserver(null,relayoutMediaGrid_id1,'RangeChanged','id1')
adjustLineHeightIfTooBig('id2');adjustFontSizeIfTooBig('id2');Widget.onload();fixAllIEPNGs('../../Media/transparent.gif');initializeMediaStream_id1()
performPostEffectsFixups()}
function onPageUnload()
{Widget.onunload();}
