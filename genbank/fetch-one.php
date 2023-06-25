<?php

ini_set('memory_limit', '-1');

error_reporting(E_ALL);

//----------------------------------------------------------------------------------------
function get($url, $format = '')
{
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	if ($format != '')
	{
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: " . $format));	
	}
	
	$response = curl_exec($ch);
	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];
	
	switch ($info['http_code'])
	{
		case 404:
			echo "$url Not found\n";
			exit();
			break;
			
		case 429:
			echo "Blocked\n";
			exit();
			break;
	
		default:
			break;
	}	
	
	curl_close($ch);
	
	return $response;
}

//----------------------------------------------------------------------------------------


$seqids = array('KF461936');


$chunk_size = 100;
$chunks = array_chunk($seqids,$chunk_size);

$start = 0;
$end = count($chunks);

for ($i = $start; $i < $end; $i++)
{
	$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=nucleotide&retmode=json&id=' . join(",", $chunks[$i]);
	
	$seqs = get($url);  
	
	echo $seqs;  
	
	$seq_filename = 'chunk-extra-'. $i . '.json';
		
	//file_put_contents($seq_filename, $seqs);
}

?>


