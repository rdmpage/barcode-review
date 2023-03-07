<?php

// Gicven a list copy an accessions we have already got across from previous attempt



// read sequences
$filename = "accessions.txt";

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$accession = trim(fgets($file_handle));
	
	$accession = str_replace('-SUPPRESSED', '', $accession );

	if (preg_match('/(?<prefix>[A-Z]+)(?<suffix>\d+)(\.\d+)?$/', $accession, $m))
	{
		$source_filename = '/Users/rpage/Development/bold-stuff-o/versions/' . $m['prefix'] . '/' . $m['suffix'] . '.html';

		if (file_exists($source_filename))
		{

			$dir = dirname(__FILE__)  . "/html/" . $m['prefix'];
			if (!file_exists($dir))
			{
				$oldumask = umask(0); 
				mkdir($dir, 0777);
				umask($oldumask);
			}
		
			$output_filename = $dir . '/' . $m['suffix'] . '.html';
			
			// copy
			copy($source_filename, $output_filename);
		}
	}
}
	
?>
