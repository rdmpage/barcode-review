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

$filename = 'barcode.xml';

$xml = file_get_contents($filename);

$dom = new DOMDocument;
$dom->loadXML($xml, LIBXML_NOCDATA); // So we get text wrapped in <![CDATA[ ... ]]>
$xpath = new DOMXPath($dom);


$seqids = array();

foreach($xpath->query('//IdList/Id') as $node)
{
   $seqids[] = $node->firstChild->nodeValue;
}

echo count($seqids) . "\n";

// 
$json = file_get_contents('ids.json');
$have_ids = json_decode($json);

$seqids = array_diff($seqids, $have_ids);

echo count($seqids) . "\n";


$chunk_size = 100;
$chunks = array_chunk($seqids,$chunk_size);

$start = 0;
$end = count($chunks);

for ($i = $start; $i < $end; $i++)
{
	$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=nucleotide&retmode=json&id=' . join(",", $chunks[$i]);
	
	$seqs = get($url);    
	
	$seq_filename = 'chunk-extra-'. $i . '.json';
		
	file_put_contents($seq_filename, $seqs);
}

?>


