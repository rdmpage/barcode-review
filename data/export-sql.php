<?php

// Parse BOLD file and generate a SQL dump of just the key metadata field we are interested in.
// For example, we exclude the sequences.

//----------------------------------------------------------------------------------------
// Convert NCBI style date (e.g., "07-OCT-2015") to Y-m-d
function parse_ncbi_date($date_string)
{
	$date = '';
	
	if (false != strtotime($date_string))
	{
		// format without leading zeros
		$date = date("Y-m-d", strtotime($date_string));
	}	
	
	return $date;
}

//----------------------------------------------------------------------------------------

$headings = array();

$row_count = 0;

if (0)
{
	$table = 'barcode';
	$filename = "../downloads/BOLD_Public.28-Sep-2022/BOLD_Public.28-Sep-2022.tsv";
}

if (0)
{
	$table = 'ibol';
	$filename = "../downloads/iBOLD.31-Dec-2016/iBOLD.31-Dec-2016.tsv";
}


if (1)
{
	$table = 'barcode';
	$filename = "../downloads/BOLD_Public.16-Jun-2023/BOLD_Public.16-Jun-2023.tsv";
}

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		if ($row_count == 0)
		{
			$headings = $row;		
		}
		else
		{
			$data = new stdclass;
		
			foreach ($row as $k => $v)
			{
				if ($v != '' && $v != "None")
				{
					$data->{$headings[$k]} = $v;
				}
			}
		
			// print_r($data);	
			
			// create a simplified object that we will store for an analysis			
			$obj = new stdclass;
			
			foreach ($data as $k => $v)
			{
				switch($k)
				{
					// specimen ids
					case 'processid':
					case 'sampleid':
					case 'specimenid':
					case 'fieldid':
					case 'museumid':
						$obj->{$k} = $v;
						break;
				
					// taxonomy (we do the hierarchy separately below)
					case 'identificationmethod':
					case 'bin_uri':
					case 'taxon':
						$obj->{$k} = $v;
						break;
						
					// reference for name
					case 'species_reference':
						$obj->{$k} = $v;
						break;
						
					// sequence
					case 'gb_acs':
					case 'marker_code':
					//case 'nucraw':
						$obj->{$k} = $v;
						break;
						
					// locality
					case 'country':
					case 'country_iso':
					case 'province':
					case 'region':
					//case 'sector':
					//case 'site':
						$obj->{$k} = $v;
						break;
					
					// coordinates
					case 'coord':
						/*
						if (preg_match('/\((.*),(.*)\)/', $v, $m))
						{					
							$obj->geometry = new stdclass;
							$obj->geometry->type = "Point";
							$obj->geometry->coordinates = array(
								(float) $m[2],
								(float) $m[1]
								);
						}
						*/
						$obj->{$k} = $v;
						break;	
						
					// institutions
					case 'inst':
						$obj->{$k} = $v;
						break;
						
					// people
					case 'taxonomist':
						$obj->{$k} = $v;
						break;

					// to do - remove spaces from collectors, e.g. PHLCA834-11
					case 'collectors':
						$obj->{$k} = explode(",", $v);
						break;
						
						/*
					// projects
					case 'recordsetcodearr':
						$v = preg_replace('/^\[/', '', $v);						
						$v = preg_replace('/\]$/', '', $v);
						$v = preg_replace('/\'/', '', $v);
						
						$obj->{$k} = preg_split('/,\s+/', $v);
						break;
						*/
					
					// dates
					case 'collectiondate':
						$obj->{$k} = $v;
						break;
						
					case 'processid_minted_date':
					case 'sequence_upload_date':
						$obj->{$k} = parse_ncbi_date($v);
						break;

					default:
						break;
				}
			}
			
			// taxonomic hierarchy
			$obj->lineage = array();
			$ranks = array('kingdom','phylum','class','order','family','subfamily','subfamily','genus','species','subspecies');
			
			$obj->id_precision = null;
			
			foreach ($ranks as $k)
			{
				if (isset($data->{$k}))
				{
					$obj->id_precision = $k;
					$obj->lineage[] = $data->{$k};
				}
			}
			
			if (!$obj->id_precision)
			{
				unset($obj->id_precision);
			}
			
			// some version of the data lack the taxon field
			if (!isset($obj->taxon))
			{
				$obj->taxon = $obj->lineage[count($obj->lineage) - 1];
			}
			
			// print_r($obj);
			
			// export			
			$keys = array();
			$values = array();
			
			foreach ($obj as $k => $v)
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
			
			echo 'REPLACE INTO ' . $table . '(' . join(",", $keys) . ') VALUES (' . join(",", $values) . ');' . "\n"; 
		}
	}	
	
	$row_count++;
	
	// If we are just playing
	if ($row_count == 100)
	{
		//exit();
	}
	
}	

?>
