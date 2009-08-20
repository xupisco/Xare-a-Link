<?php
    // Gonvert a string to a 32-bit integer
    function StrToNum($Str, $Check, $Magic) {
        $Int32Unit = 4294967296;  // 2^32
    
        $length = strlen($Str);
        for($i = 0; $i < $length; $i++) {
            $Check *= $Magic;   
            // if the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
            // the result of converting to integer is undefined
            // refer to http://www.php.net/manual/en/language.types.integer.php
            if($Check >= $Int32Unit) {
               $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
               // if the check less than -2^31
               $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
            }
            $Check += ord($Str{$i}); 
        }
        return $Check;
    }
    
    // Genearate a hash for a url
    function HashURL($String) {
        $Check1 = StrToNum($String, 0x1505, 0x21);
        $Check2 = StrToNum($String, 0, 0x1003F);
    
        $Check1 >>= 2;  
        $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
        $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
        $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF); 
        
        $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
        $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
        
        return ($T1 | $T2);
    }
    
    // Genearate a checksum for the hash string
    function CheckHash($Hashnum) {
        $CheckByte = 0;
        $Flag = 0;
    
        $HashStr = sprintf('%u', $Hashnum) ;
        $length = strlen($HashStr);
        
        for ($i = $length - 1;  $i >= 0;  $i --) {
            $Re = $HashStr{$i};
            if (1 === ($Flag % 2)) {              
                $Re += $Re;     
                $Re = (int)($Re / 10) + ($Re % 10);
            }
            $CheckByte += $Re;
            $Flag ++;   
        }
    
        $CheckByte %= 10;
        if (0 !== $CheckByte) {
            $CheckByte = 10 - $CheckByte;
            if (1 === ($Flag % 2) ) {
                if (1 === ($CheckByte % 2)) {
                    $CheckByte += 9;
                }
                $CheckByte >>= 1;
            }
        }
        return '7'.$CheckByte.$HashStr;
    }
    
    // Return the pagerank checksum hash
    function getch($url) {
       return CheckHash(HashURL($url));
    }
    
    // Return the pagerank figure
    function getPR($url) {
	    $googlehost = 'toolbarqueries.google.com';
	    $googleua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US;
	                     rv:1.8.0.6) Gecko/20060728 Firefox/1.5';
    
        $ch = getch($url);
        $fp = fsockopen($googlehost, 80, $errno, $errstr, 30);
        if ($fp) {
           $out = "GET /search?client=navclient-auto&ch=$ch&features=Rank&q=info:$url HTTP/1.1\r\n";
           // echo "<pre>$out</pre>\n"; //debug only
           $out .= "User-Agent: $googleua\r\n";
           $out .= "Host: $googlehost\r\n";
           $out .= "Connection: Close\r\n\r\n";
        
           fwrite($fp, $out);
           
           // $pagerank = substr(fgets($fp, 128), 4); //debug only
           // echo $pagerank; //debug only
           while (!feof($fp)) {
                $data = fgets($fp, 128);
                //echo $data;
                $pos = strpos($data, "Rank_");
                if($pos === false){} else{
                    $pr = substr($data, $pos + 9);
                    $pr = trim($pr);
                    $pr = str_replace("\n",'',$pr);
                    return $pr;
                }
           }
           // else { echo "$errstr ($errno)<br />\n"; } //debug only
           fclose($fp);
           return "0";
        }
    }
    
    // Generate the graphical pagerank
    function pageRank($url, $width=40, $method='style', $txt = 0) {
        if (!preg_match('/^(http:\/\/)?([^\/]+)/i', $url)) { $url='http://'.$url; }
        $pr = (!is_numeric($url)) ? getPR($url) : $url;
        $pagerank = ($pr > 0) ? "PageRank: $pr/10" : "Sem classificação";
    
        //The (old) image method
        if ($method == 'image') {
        $prpos = $width * $pr / 10;
        $prneg = $width - $prpos;
        $html = '<img src="http://www.google.com/images/pos.gif" width='.$prpos.' height=4 border=0 alt="'.$pagerank.'" title="'.$pagerank.'"><img src="http://www.google.com/images/neg.gif" width='.$prneg.' height=4 border=0>';
        }
        //The pre-styled method
        if ($method == 'style') {
        $prpercent = 100 * $pr / 10;
        $html='<div style="position: relative; text-align: left; width: '.$width.'px; padding: 0; background: #D9D9D9;" alt="'.$pagerank.'" title="'.$pagerank.'"><strong style="width: '.$prpercent.'%; display: block; position: relative; background: #5EAA5E; text-align: center; color: #333; height: 4px; line-height: 4px;"><span></span></strong></div>';
        }

        $out = $html;
        if($txt) {
            return $out." ".$url." = ".$pr." /10<br>";
        } else {
        	return $out;
        }
    }
?>