<?php

// fetch genbank versions


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

$count = 1;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$accession = trim(fgets($file_handle));
	
	$accession = str_replace('-SUPPRESSED', '', $accession );
	
	echo $accession . "\n";

	if (preg_match('/(?<prefix>[A-Z]+)(?<suffix>\d+)(\.\d+)?$/', $accession, $m))
	{

		$dir = dirname(__FILE__)  . "/html/" . $m['prefix'];
		if (!file_exists($dir))
		{
			$oldumask = umask(0); 
			mkdir($dir, 0777);
			umask($oldumask);
		}
		
		$output_filename = $dir . '/' . $m['suffix'] . '.html';
		
		if (!file_exists($output_filename) || $force)
		{
	
			$url = 'https://www.ncbi.nlm.nih.gov/nuccore/' . $accession . '?report=girevhist';
	
			$html = get($url);
	
			if ($html != '')
			{	
				echo $accession . "\n";
				file_put_contents($output_filename, $html);
				
				// Give server a break every 10 items
				if (($count++ % 10) == 0)
				{
					$rand = rand(1000000, 3000000);
					echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
					usleep($rand);
				}
			}					
		}
	}
}
	
?>

