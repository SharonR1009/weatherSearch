<?php
	error_reporting(0);
	$whitespace = '/^\s*$/';
	$url="";
	$myappid="Tf8QqK3V34Gn_YNzpunKvbZ.r2ZNqXAM3M0xDP2wUQj8K8OAuvAzz5iGYzNDdLDYOws-";
	$location = $_GET["location"];
	$location = preg_replace('/\s+/','+',$location);
	if($_GET["type"] == "city"){//option == city
		$url = "http://where.yahooapis.com/v1/places\$and(.q('".$location."'),.type(7));start=0;count=5?appid=".$myappid;
		$xmlDoc=simplexml_load_file($url);
		if(count($xmlDoc->place)==0)
			echo "Zero Results Found!";
		else{
			$woeid = $xmlDoc->place[0]->woeid;
			if($_GET['unit']=='c'){
				$namespace = "http://xml.weather.yahoo.com/ns/rss/1.0";
				$geo = "http://www.w3.org/2003/01/geo/wgs84_pos#";
				$urlw = 'http://weather.yahooapis.com/forecastrss?w='.$woeid.'&u=c';
				$xml = new DOMDocument();
				$xml -> load($urlw);
				if(($find = $xml->getElementsByTagName('title')->item(1)->nodeValue)=="City not found")
						echo "City Not Find";
				else{
						$node = $xml->getElementsByTagNameNS($namespace, 'location');
						$city = $node->item(0)->attributes->item(0)->nodeValue;
						$region = $node->item(0)->attributes->item(1)->nodeValue;
						$country = $node->item(0)->attributes->item(2)->nodeValue;
						$details = $xml->getElementsByTagName('link')->item(0)->nodeValue;
						$description = $xml->getElementsByTagName('description')->item(1)->nodeValue;
						preg_match('/http:.+gif/',$description,$imagelink);
						preg_match('/http:.+html/',$description,$imagedetail);
						$text=$xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(0)->nodeValue;
						$temp= $xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(2)->nodeValue;
						$units= $xml->getElementsByTagNameNS($namespace, 'units')->item(0)->attributes->item(0)->nodeValue;
						$imagedetail[0] = preg_replace('/^\s*$/','N/A',$imagedetail[0]);
						$imagelink[0] = preg_replace('/^\s*$/','N/A',$imagelink[0]);
						$text = preg_replace('/^\s*$/',' ',$text);
						$temp = preg_replace('/^\s*$/',' ',$temp);
						$units = preg_replace('/^\s*$/',' ',$units);
						$city = preg_replace('/^\s*$/','N/A',$city);
						$region = preg_replace('/^\s*$/','N/A',$region);
						$country = preg_replace('/^\s*$/','N/A',$country);
						$day=array();
						$low=array();
						$high=array();
						$ftext=array();
						for($num=0 ; $num < 5; $num++){
							array_push($day,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(0)->nodeValue);
							array_push($low,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(2)->nodeValue);
							array_push($high,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(3)->nodeValue);
							array_push($ftext,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(4)->nodeValue);
						}
						header('Content-Type: text/xml');
						$urlw = preg_replace('/&/','&amp;',$urlw);
						$html_head='<?xml version="1.0" encoding="UTF-8"?>';
						$html_code='<weather><feed>'.$urlw.'</feed>';
						$html_code.='<link>'.$details.'</link>';
						$html_code.='<location city="'.$city.'" region="'.$region.'" country="'.$country.'"/>';
						$html_code.='<units temperature="'.$units.'"/>';
						$html_code.='<condition text="'.$text.'" temp="'.$temp.'"/>';
						$html_code.='<img>'.$imagelink[0].'</img>';
						for($num=0 ; $num < 5; $num++){
							$html_code.='<forecast day="'.$day[$num].'" low="'.$low[$num].'" high="'.$high[$num].'"	text="'.$ftext[$num].'"/>';
						}
						$html_code =$html_head.$html_code."</weather>";
						echo $html_code;
					}
				}
			else{
				$namespace = "http://xml.weather.yahoo.com/ns/rss/1.0";
				$geo = "http://www.w3.org/2003/01/geo/wgs84_pos#";
				$urlw = 'http://weather.yahooapis.com/forecastrss?w='.$woeid.'&u=f';
				$xml = new DOMDocument();
				$xml -> load($urlw);
				if(($find = $xml->getElementsByTagName('title')->item(1)->nodeValue)=="City not found")
					echo "City Not Find";
				else{
						$node = $xml->getElementsByTagNameNS($namespace, 'location');
						$city = $node->item(0)->attributes->item(0)->nodeValue;
						$region = $node->item(0)->attributes->item(1)->nodeValue;
						$country = $node->item(0)->attributes->item(2)->nodeValue;
						$details = $xml->getElementsByTagName('link')->item(0)->nodeValue;
						$description = $xml->getElementsByTagName('description')->item(1)->nodeValue;
						preg_match('/http:.+gif/',$description,$imagelink);
						preg_match('/http:.+html/',$description,$imagedetail);
						$text=$xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(0)->nodeValue;
						$temp= $xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(2)->nodeValue;
						$units= $xml->getElementsByTagNameNS($namespace, 'units')->item(0)->attributes->item(0)->nodeValue;
						$imagedetail[0] = preg_replace('/^\s*$/','N/A',$imagedetail[0]);
						$imagelink[0] = preg_replace('/^\s*$/','N/A',$imagelink[0]);
						$text = preg_replace('/^\s*$/',' ',$text);
						$temp = preg_replace('/^\s*$/',' ',$temp);
						$units = preg_replace('/^\s*$/',' ',$units);
						$city = preg_replace('/^\s*$/','N/A',$city);
						$region = preg_replace('/^\s*$/','N/A',$region);
						$country = preg_replace('/^\s*$/','N/A',$country);
						$day=array();
						$low=array();
						$high=array();
						$ftext=array();
						for($num=0 ; $num < 5; $num++){
							array_push($day,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(0)->nodeValue);
							array_push($low,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(2)->nodeValue);
							array_push($high,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(3)->nodeValue);
							array_push($ftext,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(4)->nodeValue);
						}
						header('Content-Type: text/xml');
						$urlw = preg_replace('/&/','&amp;',$urlw);
						$html_head='<?xml version="1.0" encoding="UTF-8"?>';
						$html_code='<weather><feed>'.$urlw.'</feed>';
						$html_code.='<link>'.$details.'</link>';
						$html_code.='<location city="'.$city.'" region="'.$region.'" country="'.$country.'"/>';
						$html_code.='<units temperature="'.$units.'"/>';
						$html_code.='<condition text="'.$text.'" temp="'.$temp.'"/>';
						$html_code.='<img>'.$imagelink[0].'</img>';
						for($num=0 ; $num < 5; $num++){
							$html_code.='<forecast day="'.$day[$num].'" low="'.$low[$num].'" high="'.$high[$num].'"	text="'.$ftext[$num].'"/>';
						}
						$html_code =$html_head.$html_code."</weather>";
						echo $html_code;
					}
				}
			}
		}
			else if($_GET["type"] == "code"){  // option ==code
				$url = "http://where.yahooapis.com/v1/concordance/usps/".$location."?appid=".$myappid;
				//echo $url;
				if(is_bool($xmlDoc=simplexml_load_file($url)))
					echo "Zero Results Found!";
				else{
					$woeid = $xmlDoc -> woeid;
					if($_GET['unit']=='c'){
						$urlw='http://weather.yahooapis.com/forecastrss?w='.$woeid.'&u=c';
						$namespace = "http://xml.weather.yahoo.com/ns/rss/1.0";  //namespace
						$geo = "http://www.w3.org/2003/01/geo/wgs84_pos#";
						$xml = new DOMDocument();
						$xml -> load($urlw);
						$node = $xml->getElementsByTagNameNS($namespace, 'location');
						$city = $node->item(0)->attributes->item(0)->nodeValue;
						$region = $node->item(0)->attributes->item(1)->nodeValue;
						$country = $node->item(0)->attributes->item(2)->nodeValue;
						$latitude = $xml->getElementsByTagNameNS($geo, 'lat')->item(0)->nodeValue;
						$longitude = $xml->getElementsByTagNameNS($geo,'long')->item(0)->nodeValue;
						$details = $xml->getElementsByTagName('link')->item(0)->nodeValue;
						$description = $xml->getElementsByTagName('description')->item(1)->nodeValue;	
						preg_match('/http:.+gif/',$description,$imagelink);
						preg_match('/http:.+html/',$description,$imagedetail);
						$text=$xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(0)->nodeValue;
						$temp= $xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(2)->nodeValue;
						$units= $xml->getElementsByTagNameNS($namespace, 'units')->item(0)->attributes->item(0)->nodeValue;
						$day=array();
						$low=array();
						$high=array();
						$ftext=array();
						for($num=0 ; $num < 5; $num++){
							array_push($day,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(0)->nodeValue);
							array_push($low,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(2)->nodeValue);
							array_push($high,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(3)->nodeValue);
							array_push($ftext,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(4)->nodeValue);
						}
						header('Content-Type: text/xml');
						$urlw = preg_replace('/&/','&amp;',$urlw);
						$html_head='<?xml version="1.0" encoding="UTF-8"?>';
						$html_code='<weather><feed>'.$urlw.'</feed>';
						$html_code.='<link>'.$details.'</link>';
						$html_code.='<location city="'.$city.'" region="'.$region.'" country="'.$country.'"/>';
						$html_code.='<units temperature="'.$units.'"/>';
						$html_code.='<condition text="'.$text.'" temp="'.$temp.'"/>';
						$html_code.='<img>'.$imagelink[0].'</img>';
						for($num=0 ; $num < 5; $num++){
							$html_code.='<forecast day="'.$day[$num].'" low="'.$low[$num].'" high="'.$high[$num].'"	text="'.$ftext[$num].'"/>';
						}
						$html_code =$html_head.$html_code."</weather>";
						echo $html_code;
					}
					else{
						$urlw='http://weather.yahooapis.com/forecastrss?w='.$woeid.'&u=f';
						$namespace = "http://xml.weather.yahoo.com/ns/rss/1.0";  //namespace
						$geo = "http://www.w3.org/2003/01/geo/wgs84_pos#";
						$xml = new DOMDocument();
						$xml -> load($urlw);
						$node = $xml->getElementsByTagNameNS($namespace, 'location');
						$city = $node->item(0)->attributes->item(0)->nodeValue;     
						$region = $node->item(0)->attributes->item(1)->nodeValue;
						$country = $node->item(0)->attributes->item(2)->nodeValue;
						$latitude = $xml->getElementsByTagNameNS($geo, 'lat')->item(0)->nodeValue;
						$longitude = $xml->getElementsByTagNameNS($geo,'long')->item(0)->nodeValue;
						$details = $xml->getElementsByTagName('link')->item(0)->nodeValue;
						$description = $xml->getElementsByTagName('description')->item(1)->nodeValue;	
						//echo $description;
						preg_match('/http:.+gif/',$description,$imagelink);
						preg_match('/http:.+html/',$description,$imagedetail);
						$text= $xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(0)->nodeValue;
						$temp= $xml->getElementsByTagNameNS($namespace, 'condition')->item(0)->attributes->item(2)->nodeValue;
						$units= $xml->getElementsByTagNameNS($namespace, 'units')->item(0)->attributes->item(0)->nodeValue;
						$day=array();
						$low=array();
						$high=array();
						$ftext=array();
						for($num=0 ; $num < 5; $num++){
							array_push($day,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(0)->nodeValue);
							array_push($low,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(2)->nodeValue);
							array_push($high,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(3)->nodeValue);
							array_push($ftext,$xml->getElementsByTagNameNS($namespace, 'forecast')->item($num)->attributes->item(4)->nodeValue);
						}
						header('Content-Type: text/xml');
						$urlw = preg_replace('/&/','&amp;',$urlw);
						$html_head='<?xml version="1.0" encoding="UTF-8"?>';
						$html_code='<weather><feed>'.$urlw.'</feed>';
						$html_code.='<link>'.$details.'</link>';
						$html_code.='<location city="'.$city.'" region="'.$region.'" country="'.$country.'"/>';
						$html_code.='<units temperature="'.$units.'"/>';
						$html_code.='<condition text="'.$text.'" temp="'.$temp.'"/>';
						$html_code.='<img>'.$imagelink[0].'</img>';
						for($num=0 ; $num < 5; $num++){
							$html_code.='<forecast day="'.$day[$num].'" low="'.$low[$num].'" high="'.$high[$num].'"	text="'.$ftext[$num].'"/>';
						}
						$html_code =$html_head.$html_code."</weather>";
						echo $html_code;
					}
				}
			}
			else
				echo "Error";
			//}
?>