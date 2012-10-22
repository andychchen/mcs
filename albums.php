
<LINK href="https://lh4.googleusercontent.com/s/v/lighthousefe_99.10/styles/lh.css" rel="stylesheet" type="text/css">
<?php

function cloneNode($node,$doc){
    $nd=$doc->createElement($node->nodeName);
            
    foreach($node->attributes as $value)
        $nd->setAttribute($value->nodeName,$value->value);
            
    //if(!$node->childNodes) 
        return $nd;
                
    /*foreach($node->childNodes as $child) {
        if($child->nodeName=="#text")
            $nd->appendChild($doc->createTextNode($child->nodeValue));
        else
            $nd->appendChild(cloneNode($child,$doc));
    }
            
    return $nd;*/
}

function createElement($domObj, $tag_name, $value = NULL, $attributes = NULL)
{
    $element = ($value != NULL ) ? $domObj->createElement($tag_name, $value) : $domObj->createElement($tag_name);

    if( $attributes != NULL )
    {
        foreach ($attributes as $attr=>$val)
        {
            $element->setAttribute($attr, $val);
        }
    }

    return $element;
}

function echoOneAlbum($entry, $dom, $root)
{

        
  //$dom = new DOMDocument('1.0', 'utf-8');

  //$div = $dom->createElement("div");
  $div = createElement($dom, 'div', '', array("class"=>"gphoto-album-cover goog-inline-block"));
  //$div->setAttribute("class","gphoto-album-cover goog-inline-block");
  $root->appendChild($div);
  $div1 = createElement($dom, 'div', '', array("class"=>"SPRITE_shadow"));
  $div->appendChild($div1);
  
  $div2 = createElement($dom, 'div', '', array("class"=>"gphoto-album-cover-frame"));
  $div1->appendChild($div2);
  
  foreach( $entry->getElementsByTagName('a') as $aTag ) {
        $newA = cloneNode($aTag,$dom);
        $div2->appendChild($newA);
        //echo $aTag->c14n();
  }
  foreach( $entry->getElementsByTagName('img') as $imgTag ) {
        $newImg = cloneNode($imgTag,$dom);
        $newImg->setAttribute("class","gphoto-album-cover-img");
        $newImg->setAttribute("width","144");
        $newImg->setAttribute("height","144");
        $newA->appendChild($newImg);
        //echo $aTag->c14n();
  }
  $index=0;
  foreach( $entry->getElementsByTagName('p') as $pTag ) {
        if( $index==0)
        {
                $newP = createElement($dom, 'p', '', array("class"=>"gphoto-album-cover-title"));
                $newA = cloneNode($aTag,$dom);
                $newA->setAttribute("class","gphoto-album-cover-link");
                $newA->nodeValue=$pTag->nodeValue;
                
                $newP->appendChild($newA);
                
                $div->appendChild($newP);
        }elseif( $index==2)
        {
                $newP = createElement($dom, 'p', '', array("class"=>"gphoto-album-cover-date"));
                $newA = cloneNode($aTag,$dom);
                $newA->setAttribute("class","gphoto-album-cover-link");
                $newA->nodeValue=$pTag->nodeValue;
                
                $newP->appendChild($newA);
                
                $div->appendChild($newP);
                
        }elseif( $index==3)
        {
                $newP = createElement($dom, 'p', '', array("class"=>"gphoto-album-cover-photocount"));
                $newA = cloneNode($aTag,$dom);
                $newA->setAttribute("class","gphoto-album-cover-link");
                $newA->nodeValue=$pTag->nodeValue;
                
                $newP->appendChild($newA);
                
                $div->appendChild($newP);
                
        }        
        
        
        $index++;
  }
  
  
  
 
    //foreach( $entry->getElementsByTagName('img') as $l ) {
      // $l->setAttribute('class','shadow');
       //$img = $dom->createElement("img");
       //$img->setAttribute("class","shadow");
        //$element->appendChild($l);
        //$div->appendChild($img);
    ///}
        //$xpath->query("//img",$entry)->setAttribute('class','shadow');
      //echo $dom->saveXML();  
    //echo $div->c14n();    
}
function get_data($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function getElementsByClassName(DOMDocument $DOMDocument, $ClassName)
{
    $Elements = $DOMDocument -> getElementsByTagName("*");
    $Matched = array();
 
    foreach($Elements as $node)
    {
        if( ! $node -> hasAttributes())
            continue;
 
        $classAttribute = $node -> attributes -> getNamedItem('class');
 
        if( ! $classAttribute)
            continue;
 
        $classes = explode(' ', $classAttribute -> nodeValue);
 
        if(in_array($ClassName, $classes))
            $Matched[] = $node;
    }
 
    return $Matched;
}

function getEntriesByUrl($url)
{
        $html = get_data($url);        
        
$doc = new DOMDocument;

// We don't want to bother with white spaces
$doc->preserveWhiteSpace = false;

$doc->LoadHTML($html);

$xpath = new DOMXPath($doc);

//$tbody = $doc->getElementsByTagName('body')->item(0);

// our query is relative to the tbody node
$query = "count(//div[@id='lhid_albums']//noscript/div)";

//$query = "count(//div[@class='SPRITE_shadow'])";


$entries = $xpath->evaluate($query);
//echo "There are $entries english books\n";


$entries = $xpath->query("//div[@id='lhid_albums']//noscript/div");

return $entries;
}


$dom = new DOMDocument('1.0', 'utf-8');

$divBody1 = createElement($dom, 'div', '', array("class"=>"lhcl_body"));
$divBody1->setAttribute('id','albums');
$dom->appendChild($divBody1);

$divTable1 = createElement($dom, 'table', '', array("id"=>"lhid_shell"));
$divTable1->setAttribute('cellpadding','0');
$divTable1->setAttribute('cellspacing','0');
$divTable1->setAttribute('style','width:100%');

$divBody1->appendChild($divTable1);

$tr1 = createElement($dom, 'tr', '', NULL);
$divTable1->appendChild($tr1);
$td1 = createElement($dom, 'td', '', array("class"=>"lhcl_content"));
$td1->setAttribute('id','lhid_leftbox');
$tr1->appendChild($td1);



$divBody = createElement($dom, 'div', '', array("class"=>"lhcl_body"));
$td1->appendChild($divBody);

$divTable = createElement($dom, 'table', '', array("style"=>"width:100%"));
$divTable->setAttribute('cellpadding','0');
$divTable->setAttribute('cellspacing','0');
$divBody->appendChild($divTable);

$tr = createElement($dom, 'tr', '', NULL);
$divTable->appendChild($tr);
$td = createElement($dom, 'td', '', NULL);
$tr->appendChild($td);


$div = createElement($dom, 'div', '', array("class"=>"lhcl_albums"));
$div->setAttribute('id','lhid_albums');



$td->appendChild($div);
$div1 = createElement($dom, 'div', '', NULL);

$div->appendChild($div1);

$urls = array("https://picasaweb.google.com/104083450451262181332",
              "https://picasaweb.google.com/MichiganChineseSchool");

foreach($urls as $url)
{
  $entries = getEntriesByUrl($url);
  foreach ($entries as $entry) {
   echoOneAlbum($entry,$dom, $div1);
  }
}

echo $divBody1->c14n();

?>
