<?php

// Generate test data from GBIF

//----------------------------------------------------------------------------------------
function get($url, $user_agent='', $content_type = '')
{	
	$data = null;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
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
function gbif_fetch($scientificName)
{
	$parameters = array(
		'scientificName' => $scientificName,
		'hasCoordinate' => 'true',
		'hasGeospatialIssue' => 'false', 
		'limit' => 10
	);
	
	$url = 'https://api.gbif.org/v1/occurrence/search?' . http_build_query($parameters);
	
	//echo $url . "\n";
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		
		echo "ID\tLocality\tLongitude\tLatitude\n";
		
		foreach ($obj->results as $record)
		{
		
			$locality = array();
			
			if (isset($record->verbatimLocality))
			{
				$locality[] = $record->verbatimLocality;
			}
			
			if (count($locality) == 0)
			{
				$keys = array('country', 'stateProvince', 'locality');
				foreach ($keys as $k)
				{
					if (isset($record->{$k}))
					{
						$go = true;
						if ($record->{$k} == '')
						{
							$go = false;
						}
					
						if ($go)
						{
							$locality[] = $record->{$k};
						}
					}
				}
			}
			
							
			if (isset($record->decimalLongitude) && isset($record->decimalLatitude))
			{
				$row = array(
					$record->key,
					join(', ', $locality),
					$record->decimalLongitude,
					$record->decimalLatitude
				);
				
				echo join("\t", $row) . "\n";
			}
		
		}
	}
}



$scientificName = 'Noctilionidae';

gbif_fetch($scientificName);

?>