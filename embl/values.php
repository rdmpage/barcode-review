<?php

// Get metadata details from CouchDB


//----------------------------------------------------------------------------------------
function get($url, $content_type = '')
{	
	$data = null;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  
	  CURLOPT_HEADER 		=> FALSE,
	  
	  CURLOPT_SSL_VERIFYHOST=> FALSE,
	  CURLOPT_SSL_VERIFYPEER=> FALSE,
	  
	  CURLOPT_COOKIEJAR=> sys_get_temp_dir() . '/cookies.txt',
	  CURLOPT_COOKIEFILE=> sys_get_temp_dir() . '/cookies.txt',
	  
	);

	if ($content_type != '')
	{
		$opts[CURLOPT_HTTPHEADER] = array(
			"Accept: " . $content_type, 
			"Accept-Language: en-gb",
			"User-agent: Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405" 
		);
	}
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	// echo $data;
	
	return $data;
}

//----------------------------------------------------------------------------------------


// read sequences
$filename = "accessions.txt";

$force = false;

$count = 0;

$hits = array();

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$accession = trim(fgets($file_handle));
	
	$accession = str_replace('-SUPPRESSED', '', $accession );
	
	// do we have this sequence?
	$url = 'http://admin:peacrab@127.0.0.1:5984/embl/' . $accession;
	
	$sequence = get($url);
	
	if ($sequence != '')
	{
		$count++;
		
		$value = 'doi';
		//$value = 'pmid';
		//$value = 'lat_lon';
		//$value = 'specimen_voucher';
		//$value = 'sp';
	
		$url = 'http://admin:peacrab@127.0.0.1:5984/embl/_design/values/_view/' . $value . '?key=' . urlencode('"' . $accession . '"');
	
		$json = get($url);
	
		$obj = json_decode($json);
	
		if (isset($obj->rows[0]))
		{
			$hits[] = $obj->rows[0]->value;
		}
	}
	
}

print_r($hits);

echo "Sequences: $count\n";

?>
