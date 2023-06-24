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


$headings = array();

$row_count = 0;

$filename = "gbif/media.txt";

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
	
		// print_r($obj);	
		
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
					
					// clean (should do this in client)
					//$img->url = str_replace('#', '%23', $img->url);
					break;

				case 'title':
					$img->name = $v;
					break;
					
				case 'license':
					$img->license = clean_license($v);
					break;
					
				default:
					break;
			}
		}
		
		if (0)
		{
			if (isset($obj->license))
			{
				$license = $obj->license;
			
				// fix any encoding errors				
				$license = preg_replace('/\x{FFFD}/u', '-', $license);
				
				$license = preg_replace('/\(by-sa\) \d+/', '(by-sa)', $license);
		
				if (!isset($values[$license]))
				{
					$values[$license] = 0;
				}
				$values[$license]++;
			}
		}
		
		if (0)
		{
			if (!isset($obj->format))
			{
				$values[$obj->format] = 0;
			}
			$values[$obj->format]++;
		}
		
		if (1)
		{
			//print_r($img);
			
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
		
		
		if (0)
		{
			echo $img->url . "\n";
		}
		
		
	}	
	$row_count++;	
	
	if ($row_count > 10)
	{
		//break;
	}
	
}	

// print_r($values);



?>
