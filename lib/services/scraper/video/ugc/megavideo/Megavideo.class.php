<?php

/*
* MegaVideo Video Scraper
*   Premium Account Required
* @author Aziz S. Hussain - www.AzizSaleh.com
* @license http://www.gnu.org/licenses/lgpl.html
*/

class MegaVideo
{
	private $megaVideoURL; //MegaVideo link
	private $finalLink; //Final link
	
	/* 
   * Constructor
   *   Just stores the video link
   */
	function __construct($videoURL)	{
		$this->finalLink = $videoURL;
	}
	
  function oldMegavideo($url) {
  preg_match('#\?v=(.+?)$#', $url, $id);
  $this->id = ($id[1]) ? $id[1] : $url;
    $this->getxml();    
    $parse = array	(	'runtimehms'	=>	'duration',
              'size'			=>	'size',
              's'				=>	'server',
              'title'			=>	'title',
              'description'	=>	'description',
              'added'			=>	'added',
              'username'		=>	'username',
              'category'		=>	'category',
              'views'			=>	'views',
              'comments'		=>	'comments',
              'favorited'		=>	'favorited',
              'rating'		=>	'rating',
              'k1'			=>	'key1',
              'k2'			=>	'key2',
              'un'			=>	'str');
                
    foreach($parse as $key=>$val)
    {
      $this->parsexml($key, $val);
    }

    $this->size = round($this->size/(1024*1024));    
  }
  
  function get($what=false)	{
    $all = array(	"URL"			=>	"http://www".$this->server.".megavideo.com/files/".$this->decrypt($this->str, $this->key1, $this->key2)."/",
            "SIZE"			=>	$this->size,
            "TITLE"			=>	$this->title,
            "DURATION"		=>	$this->duration,
            "SERVER"		=>	$this->server,
            "DESCRIPTION"	=>  $this->description,
            "ADDED"			=>  $this->added,
            "USERNAME"		=>  $this->username,
            "CATEGORY"		=>	$this->category,
            "VIEWS"			=>	$this->views,
            "COMMENTS"		=>	$this->comments,
            "FAVORITED"		=>	$this->favorited,
            "RATING"		=>  $this->rating
          );
          
    return $what&&array_key_exists(strtoupper($what),$all)?$all[strtoupper($what)]:$all;
  }
  
  function getxml() {
      $this->xml = file_get_contents("http://www.megavideo.com/xml/videolink.php?v=".$this->id."&id=".time()) or die("Error!\n");
  }
        
  function parsexml($attribute, $name) {
      preg_match("#\s$attribute=\"(.+?)\"#", $this->xml, $tmp);
      list(,$this->$name) = $tmp;
  }
			  

 /**
	* getMegavideoVars
	*   Netscape HTTP Cookie File: http://www.netscape.com/newsref/std/cookie_spec.html
	*   This file was generated by libcurl! Edit at your own risk.  
	*   This function will return the megaVideo vars
	*   Note that it uses CURL and the COOKIE megavideoCookie.txt
	*   Cookie text file must be in this format:   
	*     .megavideo.com	TRUE	/	FALSE	1263332544	user 	4BDHJJNEJOKDF4KJHKJFJIUGHUYG3.JKLHDU4  
 	* You need to change the user number 1263332544 and session  4BDHJJNEJOKDF4KJHKJFJIUGHUYG3.JKLHDU4
	* You can get that information by viewing the cookie information (using firefox)
	* URL passed must be in this format:  http://www.megavideo.com/?v=6PTHEVUY
	*/
	function getMegavideoVars()	{
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL,$this->finalLink);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'MegavideoCookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'MegavideoCookie.txt');
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	
		$buffer = curl_exec($ch);
		curl_close($ch);
		preg_match('/flashvars\.un = "(.*)";.*flashvars\.k1 = "(.*)";.*flashvars\.k2 = "(.*)";.*flashvars\.s = "(.*)";/Ums', $buffer,$results);		
		$finalResults = array($results[4],$results[1],$results[2],$results[3]); //Return required vars
		return $finalResults;
	}


	/**
   * decrypt
   *   This function will decrypt the required keys from megavideo
   */
	function decrypt($str, $key1, $key2) {
		$reg1 = array();
		for($reg3=0; $reg3<strlen($str); $reg3++)	{
			$reg0 = $str[$reg3];
	   
			switch($reg0)	{
			  case '0': $reg1[] = '0000'; break;
			  case '1': $reg1[] = '0001'; break;
			  case '2': $reg1[] = '0010'; break;
			  case '3': $reg1[] = '0011'; break;
			  case '4': $reg1[] = '0100'; break;
			  case '5': $reg1[] = '0101'; break;
			  case '6': $reg1[] = '0110'; break;
			  case '7': $reg1[] = '0111'; break;
			  case '8': $reg1[] = '1000'; break;
			  case '9': $reg1[] = '1001'; break;
			  case 'a': $reg1[] = '1010'; break;
			  case 'b': $reg1[] = '1011'; break;
			  case 'c': $reg1[] = '1100'; break;
			  case 'd': $reg1[] = '1101'; break;
			  case 'e': $reg1[] = '1110'; break;
			  case 'f': $reg1[] = '1111'; break;
		   }
		}		  
		$reg1 = join($reg1);
		$reg6 = array();
		  
		for($reg3=0; $reg3<384; $reg3++) {
			$key1 = ($key1 * 11 + 77213) % 81371;
			$key2 = ($key2 * 17 + 92717) % 192811;
			$reg6[] = ($key1 + $key2) % 128;
		}
		  
		for($reg3=256; $reg3>=0; $reg3--) {
			$reg5 = $reg6[$reg3];
			$reg4 = $reg3 % 128;
			$reg8 = $reg1[$reg5];
			$reg1[$reg5] = $reg1[$reg4];
			$reg1[$reg4] = $reg8;
		}
		  
		for($reg3=0; $reg3<128; $reg3++) {
			$reg1[$reg3] = $reg1[$reg3] ^ ($reg6[$reg3+256] & 1);   
		}
		  
		$reg12 = $reg1;
		$reg7 = array();
		  
		for($reg3=0; $reg3<strlen($reg12); $reg3+=4) {
			$reg9 = substr($reg12, $reg3, 4);
			$reg7[] = $reg9;
		}
		  
		$reg2 = array();
		  
		for($reg3=0; $reg3<count($reg7); $reg3++)	{
			$reg0 = $reg7[$reg3];
			switch($reg0) {
			  case '0000': $reg2[] = '0'; break;
			  case '0001': $reg2[] = '1'; break;
			  case '0010': $reg2[] = '2'; break;
			  case '0011': $reg2[] = '3'; break;
			  case '0100': $reg2[] = '4'; break;
			  case '0101': $reg2[] = '5'; break;
			  case '0110': $reg2[] = '6'; break;
			  case '0111': $reg2[] = '7'; break;
			  case '1000': $reg2[] = '8'; break;
			  case '1001': $reg2[] = '9'; break;
			  case '1010': $reg2[] = 'a'; break;
			  case '1011': $reg2[] = 'b'; break;
			  case '1100': $reg2[] = 'c'; break;
			  case '1101': $reg2[] = 'd'; break;
			  case '1110': $reg2[] = 'e'; break;
			  case '1111': $reg2[] = 'f'; break;
			}
		}
		return join($reg2);
	}
	
	/**
   * doScrape
   *   Actually return the URL that the video can be viewed without time restrictions
   */
	function doScrape() {		
		list($serverID,$un,$k1,$k2) = $this->getMegavideoVars(); //Retrieve info		
		$decKey = $this->decrypt($un,$k1,$k2); //Get the key	
		$this->finalLink = "http://www$serverID.megavideo.com/files/$decKey/randomName$un.flv";
	}
	
	/**
   * getLink
   *   Return the link begotten after scraping
   */
	function getLink() {
		return $this->finalLink;
	}
  
}

?>