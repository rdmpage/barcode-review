<?php

error_reporting(E_ALL);

//----------------------------------------------------------------------------------------
function clean_license($v)
{
	$terms = array();
	
	if (preg_match('/CreativeCommons/', $v))
	{
		$terms[] = 'CC';
	}

	if (preg_match('/Attribution/', $v))
	{
		$terms[] = 'BY';
	}

	if (preg_match('/Non-?Commercial/i', $v))
	{
		$terms[] = 'NC';
	}

	if (preg_match('/Share\s*-?(Alike)?/i', $v))
	{
		$terms[] = 'SA';
	}

	if (preg_match('/No Derivatives/', $v))
	{
		$terms[] = 'ND';
	}

	if (preg_match('/No Rights/', $v))
	{
		$terms[] = 'CC0';
	}

	if (preg_match('/-by-nc-nd/', $v))
	{
		$terms = ['CC', 'BY', 'NC', 'ND'];
	}
	
	return join('-', $terms);

}

//----------------------------------------------------------------------------------------
// http://stackoverflow.com/questions/247678/how-does-mediawiki-compose-the-image-paths
function hash_to_path_array($hash)
{
	preg_match('/^(..)(..)(..)/', $hash, $matches);
	
	$hash_path_parts = array();
	$hash_path_parts[] = $matches[1];
	$hash_path_parts[] = $matches[2];
	$hash_path_parts[] = $matches[3];

	return $hash_path_parts;
}

//----------------------------------------------------------------------------------------
// Return path for a sha1
function hash_to_path($hash)
{
	$hash_path_parts = hash_to_path_array($hash);
	
	$hash_path = '/' . join("/", $hash_path_parts);

	return $hash_path;
}

//----------------------------------------------------------------------------------------
if (0)
{
// images

$row_count = 0;

$filename = "gbif/media.txt";

$terms = array();

$headings = array('processid', 'title', 'identifier', 'references', 'format', 'license');
$values = array();
$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		$obj = new stdclass;
	
		foreach ($row as $k => $v)
		{
			if ($v != '')
			{						
				$obj->{$headings[$k]} = $v;
			}
		}
		
		//print_r($row);
		
		$img = new stdclass;
		foreach ($obj as $k => $v)
		{
			switch ($k)
			{
				case 'processid':
				case 'format':
					$img->$k = $v;
					break;
					
				case 'identifier':
					$img->url = $v;					
					$img->md5 = md5($img->url);					
					$img->filename = hash_to_path($img->md5) . '/' . $img->md5. '.jpg';
					break;
					
				case 'license':
					$img->license = clean_license($v);
					break;
					
				case 'title':
					$img->name = $v;
					$term = trim(str_replace($img->processid, '', $img->name));
					$term = strtolower($term);
				
					if ($term != '')
					{
						$img->comment = $term;
					}
					break;
					
				default:
					break;
			}
		}
		
		
		
		if (1)
		{
			// print_r($img);
			
			$keys = array();
			$values = array();
			
			foreach ($img as $k => $v)
			{
				$keys[] = $k;
				
				if (is_array($v))
				{
					$values[] = '"' . str_replace('"', '""', join(",", $v)) . '"';
				}
				elseif(is_object($v))
				{
					$values[] = '"' . str_replace('"', '""', json_encode($v)) . '"';
				}
				else
				{				
					$values[] = '"' . str_replace('"', '""', $v) . '"';
				}
							
			}
			
			echo 'REPLACE INTO image(' . join(",", $keys) . ') VALUES (' . join(",", $values) . ');' . "\n"; 		
		}
		
		
		// analyse terms
		if (0)
		{
			if (isset($img->name))
			{
				$term = trim(str_replace($img->processid, '', $img->name));
				$term = strtolower($term);
				
				if ($term != '')
				{
					if (!isset($terms[$term]))
					{
						$terms[$term] = 0;
					}
					$terms[$term]++;
				}
			}
		}
		
	}	
	$row_count++;	
	
	if ($row_count > 10)
	{
		//break;
	}
	
}	
}
//----------------------------------------------------------------------------------------

// barcodes
if (1)
{
$row_count = 0;

$filename = "gbif/occurrences.txt";

$headings = array('processid',
'occurrenceID','catalogNumber','fieldNumber','identificationRemarks','basisOfRecord','occurrenceRemarks','phylum','class','order','family','genus','scientificName','identifiedBy','associatedOccurrences','associatedTaxa','collectionCode','eventID','locationRemarks','eventTime','habitat','samplingProtocol','locationID','eventDate','recordedBy','country','stateProvince','locality','decimalLatitude','decimalLongitude','coordinatePrecision','georeferenceSources','maximumDepthInMeters','minimumDepthInMeters','maximumElevationInMeters','minimumElevationInMeters','eventRemarks','lifestage','sex','preparations','rightsHolder','rights','language','taxonConceptID','taxonID','associatedSequences');
$values = array();
$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		$obj = new stdclass;
	
		foreach ($row as $k => $v)
		{
			if ($v != '')
			{						
				$obj->{$headings[$k]} = $v;
			}
		}
	
		// print_r($obj);	
		
		$barcode = new stdclass;
		
		foreach ($obj as $k => $v)
		{
			switch ($k)
			{
				case 'processid':
				case 'catalogNumber':
				case 'fieldNumber':
				case 'identificationRemarks':
				case 'phylum':
				case 'class':
				case 'order':
				case 'family':
				case 'genus':
				case 'scientificName':
				case 'identifiedBy':
				case 'associatedOccurrences':
				case 'country':
				case 'stateProvince':
				case 'locality':
				case 'decimalLatitude':
				case 'decimalLongitude':
				case 'lifestage':
				case 'sex':
				case 'associatedSequences':
					$barcode->$k = $v;
					break;
					
				case 'taxonID':
					$barcode->$k = str_replace('http://www.boldsystems.org/index.php/Public_BarcodeCluster?clusteruri=', '', $v);
					break;
					
				default:
					break;
			}
		}
		
		if (1)
		{
			// print_r($barcode);
			
			$terms = array();
			$values = array();
			
			foreach ($barcode as $k => $v)
			{
				switch ($k)
				{
					case 'processid':
						break;
						
					case 'catalogNumber':
					case 'fieldNumber':
					case 'identificationRemarks':
					case 'phylum':
					case 'class':
					case 'order':
					case 'family':
					case 'genus':
					case 'scientificName':
					case 'identifiedBy':
					case 'associatedOccurrences':
					case 'country':
					case 'stateProvince':
					case 'locality':
					case 'decimalLatitude':
					case 'decimalLongitude':
					case 'lifestage':
					case 'sex':
					case 'taxonID':
						$terms[] = "`" . $k . '`=' . '"' . str_replace('"', '""', $v) . '"';
						break;
						
					case 'associatedSequences':
						if ($v != "\N")					
						{
							$terms[] = "`" . $k . '`=' . '"' . str_replace('"', '""', $v) . '"';						
						}
						break;
					
					default:
						break;
				}
			
							
			}
			
			echo 'UPDATE image SET ' . join(', ', $terms) . ' WHERE processid="' . $barcode->processid . '";' . "\n";
			
		}

	}	

	$row_count++;	
	
	if ($row_count > 100000)
	{
		//break;
	}
	
}

}

?>
