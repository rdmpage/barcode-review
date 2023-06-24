<?php

// Parse old iBOL dumps and generate a SQL dump of just the key metadata field
// we are interested in. For example, we exclude the sequences.

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


$table = 'ibolog';

$files = scandir(".");

foreach ($files as $filename)
{
	if (preg_match('/\.tsv$/', $filename))
	{
		echo "-- $filename\n";
	
		$row_count = 0;
		
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
					
					//print_r($headings);
				}
				else
				{
					$data = new stdclass;
		
					foreach ($row as $k => $v)
					{
						if ($v != '')
						{
							$data->{$headings[$k]} = $v;
						}
					}
		
					//print_r($data);	
			
			
		/*
		processid
		sampleid
		museumid
		fieldid
		bin_guid
		bin_name
		vouchertype
		inst_reg
		phylum_reg
		class_reg
		order_reg
		family_reg
		subfamily_reg
		genus_reg
		species_reg
		taxonomist_reg
		collectors
		collectiondate
		lifestage
		lat
		lon
		site
		sector
		region
		province_reg
		country_reg
		fundingsrc
		seqentryid
		seqdataid
		marker_code
		nucraw
		aminoraw
		seq_update
		total_trace_count
		high_trace_count
		accession
		*/			
			
					// create a simplified object that we will store for an analysis			
					$obj = new stdclass;
			
					foreach ($data as $k => $v)
					{
						switch($k)
						{
							// specimen ids
							case 'processid':
								$obj->{$k} = str_replace('.COI-5P', '', $v);
								break;					
					
							case 'sampleid':
							case 'museumid':
							case 'fieldid':
								$obj->{$k} = $v;
								break;
						
							// taxonomy (we do the hierarchy separately below)
							case 'bin_guid':
								$obj->bin_uri = $v;
								break;
					
							case 'species_reg':
								$obj->taxon = $v;
								break;
						
							// sequence
							case 'accession':
								$obj->gb_acs = $v;
								break;					
					
							case 'marker_code':
								$obj->{$k} = $v;
								break;
						
							// locality
							case 'country_reg':
								$obj->country = $v;
								break;							
							case 'province_reg':
								$obj->province = $v;
								break;	
							case 'region':
								$obj->{$k} = $v;
								break;
					
							// coordinates
							case 'lat':
								$coords = array($v);
								if (isset($data->lon))
								{
									$coords[] = $data->lon;
									$obj->coord = '(' . join(',', $coords) . ')';
								}
								break;	
						
							// institutions
							case 'inst_reg':
								$obj->inst = $v;
								break;
						
							// people
							case 'taxonomist_reg':
								$obj->taxonomist = $v;
								break;

							case 'collectors':
								$obj->{$k} = preg_split('/,\s*/', $v);
								break;
											
							// dates
							case 'collectiondate':
								$obj->{$k} = parse_ncbi_date($v);
								break;
											
							case 'seq_update':
								$obj->sequence_upload_date = substr($v, 0, 10);
								break;

							default:
								break;
						}
					}
			
					// taxonomic hierarchy
					$obj->lineage = array();
					$ranks = array('phylum_reg','class_reg','order_reg','family_reg','subfamily_reg','genus_reg','species_reg');
			
					$obj->id_precision = null;
			
					foreach ($ranks as $k)
					{
						if (isset($data->{$k}))
						{
							$key = str_replace('_reg', '', $k);
					
							if ($key == 'species')
							{
								// handle "dark taxa"
								if (preg_match('/\s+sp\./', $data->{$k}))
								{
									// ignore
								}
								else
								{
									$obj->id_precision = $key;
								}
							}
							else
							{
								$obj->id_precision = $key;
							}
							$obj->lineage[] = $data->{$k};
						}
					}
			
					if (!$obj->id_precision)
					{
						unset($obj->id_precision);
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
			if ($row_count == 10)
			{
				//exit();
			}
		}
	}
}	

?>
