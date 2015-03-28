
<?php
getWoeid($_GET['location'],$_GET['type'],$_GET['tempUnit']);
?>
<?php
function getWoeid($location,$type,$unit){
	/*echo $location."<br />";
	echo $type."<br />";
	echo $temp."<br />";*/
	$appid="orLEgLbV34GEZZiDf2LGGDtGXUkG5WydwgC27XkVkmN6kn9n6JKYAyhRUU1v0abYGjM4nmrIPirf8eLLyFTNu_uQn1vi0ho-";
	if($type=="zip")//ZIP Code
	{
		//$cityname=$location;
		$url="http://where.yahooapis.com/v1/concordance/usps/".$location."?appid=".$appid;
		//echo $url;
		//$buf = file_get_contents($url);
        //$xml = simplexml_load_string($buf);
        $xml=simplexml_load_file($url);
        
        /*if($xml->getName()=="error")
        	echo "Zero results found!";*/
        if(!is_object($xml))
			echo "<h1><center>Zero results found!</center></h1>";
			//echo '<script language="javascript">hwin= window.open("","","height=400,width=800,scrollbars=1,titlebar=1,toolbar=1");hwin.document.write("<html><h1><center>Zero results found!</center></h1></html>");</script>';
        else
        {
        	$id=array();
        	$count=count($xml->woeid);
        	for($i=0;$i<$count;$i++)
        	{
	        	//echo $xml->place[$i]->woeid;
	        	$id[$i]=$xml->woeid;       
	        }
	        	getWeather($id,$count,$unit,$location);
	    }
		
	}
	else//$type=="city"
	{
		$cityname=$location;
		$location=preg_replace('/\s+/','+',$location);
		//echo $location."</br>";
		$url="http://where.yahooapis.com/v1/places\$and(.q('".$location."'),.type(7));start=0;count=5?appid=".$appid;
		/*if(!file_exists($url))
			echo "No result";
		else{*/
		//echo $url."<br />";
		//$buf = file_get_contents($url);
        //$xml = simplexml_load_string($buf);
        $xml=simplexml_load_file($url);
        //echo count($xml->place)."<br />";
        //echo $xml->place[0]->woeid;
        $count=count($xml->place);
        if($count==0)//no woeid found
        	echo "<h1><center>Zero results found!</center></h1>";
        	//echo '<script language="javascript">hwin= window.open("","","height=400,width=800,scrollbars=1,titlebar=1,toolbar=1");hwin.document.write("<html><h1><center>Zero results found!</center></h1></html>");</script>';
        else
        {
        	$id=array();
        	for($i=0;$i<$count;$i++)
        	{
	        	//echo $xml->place[$i]->woeid;
	        	$id[$i]=$xml->place[$i]->woeid;       
	        }
	        getWeather($id,$count,$unit,$cityname);
	     }
	 }
}
?>
<?php
function getWeather($woeid,$count,$unit,$cityname){
			header('Content-Type: text/xml');
	        $url= "http://weather.yahooapis.com/forecastrss?w=".$woeid[0]."&u=".$unit;
	        //echo $url."<br />";
	        $yweather = "http://xml.weather.yahoo.com/ns/rss/1.0";	
	       
	        
	        $res = new DOMDocument();
	        $res->load($url);
	       
	        
	        
	        $node=$res->getElementsByTagName("title")->item(1)->childNodes->item(0)->nodeValue;	        
	        //echo $node."<br />";
	        $err="City not found";
	        
	        if($node==$err);//city not found
	        	//echo "error"."<br />";
	        else
	        {
	        	echo "<weather>";
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'location');
	        	$city = $node->item(0)->attributes->item(0)->nodeValue;
	        	if($city==null)$city="N/A";				
	        	echo "<city>".$city."</city>";
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'location'); 
	        	$region = $node->item(0)->attributes->item(1)->nodeValue;
	        	if($region==null)$region="N/A";	
	        	//echo $region."<br />";
	        	$node = $res->getElementsByTagNameNS($yweather, 'location'); 
	        	$country = $node->item(0)->attributes->item(2)->nodeValue;
	        	if($country==null)$country="N/A";
	        	//echo $country."<br />";
	        	$regioncountry=$region.",".$country;
	        	echo "<regioncountry>".$regioncountry."</regioncountry>";
	        	
	        		        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'units'); 
	        	$temperature = $node->item(0)->attributes->item(0)->nodeValue;
	        	//if($temperature==null)$temperature="N/A";
	        	echo "<tempUnit>".$temperature."</tempUnit>";
	        	
	        	$node = $res->getElementsByTagName("description");
	        	$image = $node->item(1)->childNodes->item(0)->nodeValue;
	        	//preg_match("/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i",$image,$match);
	        	preg_match("/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i",$image,$match);	        	
	        	echo "<imagesrc>".$match[1]."</imagesrc>";
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'condition'); 
	        	$text = $node->item(0)->attributes->item(0)->nodeValue;
	        	//if($text==null)$text="N/A";
	        	echo "<text>".$text."</text>";
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'condition'); 
	        	$temp = $node->item(0)->attributes->item(2)->nodeValue;
	        	//if($temp==null)$temp="N/A";
	        	echo "<temp>".$temp."</temp>";
	        	
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'forecast');
	        	$day0 = $node->item(0)->attributes->item(0)->nodeValue;
	        	echo "<daya>".$day0."</daya>";	
	        	$text0 = $node->item(0)->attributes->item(4)->nodeValue;
	        	echo "<texta>".$text0."</texta>";	        		      	        	
	        	$low0 = $node->item(0)->attributes->item(2)->nodeValue;
	        	echo "<lowa>".$low0."</lowa>";	
	        	$high0 = $node->item(0)->attributes->item(3)->nodeValue;
	        	echo "<higha>".$high0."</higha>";
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'forecast');
	        	$day1 = $node->item(1)->attributes->item(0)->nodeValue;
	        	echo "<dayb>".$day1."</dayb>";	
	        	$text1 = $node->item(1)->attributes->item(4)->nodeValue;
	        	echo "<textb>".$text1."</textb>";	        		      	        	
	        	$low1 = $node->item(1)->attributes->item(2)->nodeValue;
	        	echo "<lowb>".$low1."</lowb>";	
	        	$high1 = $node->item(1)->attributes->item(3)->nodeValue;
	        	echo "<highb>".$high1."</highb>";
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'forecast');
	        	$day2 = $node->item(2)->attributes->item(0)->nodeValue;
	        	echo "<dayc>".$day2."</dayc>";	
	        	$text2 = $node->item(2)->attributes->item(4)->nodeValue;
	        	echo "<textc>".$text2."</textc>";	        		      	        	
	        	$low2 = $node->item(2)->attributes->item(2)->nodeValue;
	        	echo "<lowc>".$low2."</lowc>";	
	        	$high2 = $node->item(2)->attributes->item(3)->nodeValue;
	        	echo "<highc>".$high2."</highc>";
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'forecast');
	        	$day3 = $node->item(3)->attributes->item(0)->nodeValue;
	        	echo "<dayd>".$day3."</dayd>";	
	        	$text3 = $node->item(3)->attributes->item(4)->nodeValue;
	        	echo "<textd>".$text3."</textd>";	        		      	        	
	        	$low3 = $node->item(3)->attributes->item(2)->nodeValue;
	        	echo "<lowd>".$low3."</lowd>";	
	        	$high3 = $node->item(3)->attributes->item(3)->nodeValue;
	        	echo "<highd>".$high3."</highd>";
	        	
	        	
	        	$node = $res->getElementsByTagNameNS($yweather, 'forecast');
	        	$day4 = $node->item(4)->attributes->item(0)->nodeValue;
	        	echo "<daye>".$day4."</daye>";	
	        	$text4 = $node->item(4)->attributes->item(4)->nodeValue;
	        	echo "<texte>".$text4."</texte>";	        		      	        	
	        	$low4 = $node->item(4)->attributes->item(2)->nodeValue;
	        	echo "<lowe>".$low4."</lowe>";	
	        	$high4 = $node->item(4)->attributes->item(3)->nodeValue;
	        	echo "<highe>".$high4."</highe>";
	        	
	        	
	        	$node = $res->getElementsByTagName("link");
	        	$link = $node->item(0)->childNodes->item(0)->nodeValue;
	        	if($link==null)$link="N/A";
	        	echo "<link>".$link."</link>";
	        	
	        	$url = preg_replace('/&/','&amp;',$url);
	        	echo "<feed>".$url."</feed>";
	        	
	        	
	        	echo "</weather>";
	        	
	        	
	        	
	        	
	        	
	        	
	        	//$html_text.="<tr><td align='center'><a href='".$m[1][0]."'>".$match[0]."</a></td>";
	        	//$html_text.="<tr><td align='center'><a href='".$url."' target='_blank'><img src='".$match[1]."' alt='".$text."' title='".$text."'></a></td>";
	        	
	        }//end else 
	         
	        

	
}
?>

