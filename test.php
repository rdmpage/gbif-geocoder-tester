<?php


// https://www.geodatasource.com/developers/php
// See also https://stackoverflow.com/a/52211669/9684

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}


//----------------------------------------------------------------------------------------
function get($url, $user_agent='', $content_type = '')
{	
	$data = null;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	);

	if ($content_type != '')
	{
		$opts[CURLOPT_HTTPHEADER] = array("Accept: " . $content_type);
	}
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}

//----------------------------------------------------------------------------------------
/**
 * @brief Convert degrees, minutes, seconds to a decimal value
 *
 * @param degrees Degrees
 * @param minutes Minutes
 * @param seconds Seconds
 * @param hemisphere Hemisphere (optional)
 *
 * @result Decimal coordinates
 */
function degrees2decimal($degrees, $minutes=0, $seconds=0, $hemisphere='N')
{
	$result = $degrees;
	$result += $minutes/60.0;
	$result += $seconds/3600.0;
	
	if ($hemisphere == 'S')
	{
		$result *= -1.0;
	}
	if ($hemisphere == 'W')
	{
		$result *= -1.0;
	}
	// Spanish
	if ($hemisphere == 'O')
	{
		$result *= -1.0;
	}
	// Spainish OCR error
	if ($hemisphere == '0')
	{
		$result *= -1.0;
	}
	
	return $result;
}



//----------------------------------------------------------------------------------------
function parse_geocordinates($text)
{
	$result = -1.0;
	
	//echo "$text\n";
	
	// 129°33'E
	// 44°35'N
	if (preg_match('/
		(?<degrees>[0-9]{1,3})
		[°]
		(?<minutes>\d+)[\']
		((?<seconds>\d+)("))?
		(?<hemisphere>[W|E|N|S])
		/ux', $text, $m))
	{
		//print_r($m);
	
		$degrees = $minutes = $seconds = 0;
		
		$degrees = $m['degrees'];
		$minutes = $m['minutes'];
		
		if($m['seconds'] != '')
		{
			$seconds = $m['seconds'];
		}
		
		$hemisphere = $m['hemisphere'];
		
		$result = $degrees + ($minutes/60.0) + ($seconds/3600);
		if ($hemisphere == 'S') { $result *= -1.0; };
		if ($hemisphere == 'W') { $result *= -1.0; };		
	}
	
	return $result;

}



// 1. read lines with locality and lat, lon
// 2. geocode
// 3. compute how well we did


//----------------------------------------------------------------------------------------

$row_count = 0;

$header = array();
$header_lookup = array();

$filename = 'test.tsv';
$filename = 'bat.tsv';
$delimiter = "\t";


//$filename = 'DQ523091/table-2.csv';
//$delimiter = ",";

$file = @fopen($filename, "r") or die("couldn't open $filename");		
$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$row = fgetcsv(
		$file_handle, 
		0, 
		$delimiter
		);

	

	$go = is_array($row);
	
	// handle empty rows at end of file
	if ($go && count($row) == 1)
	{
		$go = false;
	}
				
	if ($go && ($row_count == 0))
	{
		$header = $row;
		
		$n = count($header);
		for ($i = 0; $i < $n; $i++)
		{
			$header_lookup[$header[$i]] = $i;
		}
				
		$go = false;
	}
	if ($go)
	{
		
		$obj = new stdclass;
		
		foreach ($row as $k => $v)
		{
			if ($v != '')
			{
				$obj->{$header[$k]} = $v;
			}
		}
		
		
		// process table
		
		// assume we have standard headers
		
		if (is_numeric($obj->Latitude))
		{
			$obj->decimalLatitude = $obj->Latitude;
		}
		else
		{
			$obj->decimalLatitude = parse_geocordinates($obj->Latitude);
		}

		if (is_numeric($obj->Longitude))
		{
			$obj->decimalLongitude = $obj->Longitude;
		}
		else
		{
			$obj->decimalLongitude = parse_geocordinates($obj->Longitude);
		}

		
		
		$data[] = $obj;
		
		
	}

	$row_count++;
}

print_r($data);


foreach ($data as $item)
{
	print_r($item);
	
	// make URL
	
	$url = 'https://lyrical-money.glitch.me/search?q=' . urlencode($item->Locality);
	
	// geocode
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		
		//print_r($obj);
		
		// OK, how shall we compute the score...?
		
		// 1. Compute distance
		
		$hits = array();
		
		// for current result structure
		foreach ($obj->features as $feature)
		{
			if ($feature->geometry->type == 'Point')
			{
				$hit = new stdclass;
				$hit->pt = $feature->geometry->coordinates;
				$hit->score = $feature->properties->d;
				$hit->hit_string = $feature->properties->hit_string;
				$hit->query_string = $feature->properties->query_string;
			
				$hits[]  = $hit;
			}
		
		}
		
		//print_r($hits);
		
		// 2. Sort in reverse order by best score
		usort($hits, function($a, $b) {
    		return $b->score - $a->score;
		});
		
		//print_r($hits);
		
		// 3. Compute distance from query
		$delta = array();
		foreach ($hits as $hit)
		{
			echo $hit->query_string . "\n";
			echo $hit->hit_string . "\n";
			print_r($hit->pt);
			print_r(array($item->decimalLongitude, $item->decimalLatitude));
		
		
			$delta[] = distance(
				$hit->pt[1], 
				$hit->pt[0], 
			
				$item->decimalLatitude, 
				$item->decimalLongitude,
				
				'K'
				
				);
				
				
		
		
		}
		
		print_r($delta);
		
		
		
		
	}
	
	
	
}

?>


