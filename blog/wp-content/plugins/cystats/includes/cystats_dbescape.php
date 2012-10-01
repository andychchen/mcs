<?php

   /**
    * filter output for html,xss ...
    */
    function cystats_htmlfilter($s, $html = TRUE) {
        // replace whitespace characters, leave \n \r \t
        $s=preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $s); 
        if ($html == TRUE) {
            $search[] = "=\%=ui"; $repl[] = "&#37;";
            $search[] = "=\<=ui"; $repl[] = "&lt;";
            $search[] = "=\>=ui"; $repl[] = "&gt;";
            return(preg_replace($search, $repl, $s));
        }else{
            return($s);
        }
    }
    
       function addslash($v){
            return(mysql_real_escape_string($v));
        } 
        function stripslash($v){
            return(($v));
        } 
        /**
        * dbEscape()
        * escape special chars in query vars
        * @param string $v->query string
        */
        function dbEscape($v) {
            if (get_magic_quotes_gpc()) {
                $v = stripslashes($v);
            }
            $v=addslash($v);
            $v=preg_replace(
                array(
                    "=\-=u",
                    "=\#=u",
                    "=\;=u"
               ),array( 
                    "\-",
                    "\#",
                    "\;"
                ), $v);
        return(trim($v));
        }
         
         
        /**
        * dbStrip()
        * filter previously escaped database results for output
        * @param string $v->string to strip
        * @param bool $html->filter html TRUE/FALSE
        */
        function dbStrip($v, $html = FALSE) {
            $v=preg_replace(
                array(
                    "=\\\-=u",
                    "=\\\#=u",
                    "=\\\;=u"
                ),array( 
                    "-",
                    "#",
                    ";"
                ), $v);
        $v =($html == FALSE)?cystats_htmlfilter($v):cystats_htmlfilter($v,FALSE);
        return($v);
        }
        
        function dbStripArray($d){
                $newarray=array();
                foreach($d AS $k=>$v){
                    if(is_array($v)){
                        $newsub=array();
                        foreach($v AS $k2=>$v2){
                                    $newsub[$k2]=dbStrip($v2);
                            }                                
                        }else{
                        $newsub=dbStrip($v);    
                    }
                    $newarray[$k]=$newsub;
            }
            return($newarray);
        }
        
 ?>
