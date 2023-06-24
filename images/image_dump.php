<?php

$pdo = new PDO('sqlite:../bold.db');

//----------------------------------------------------------------------------------------
function do_query($sql)
{
	global $pdo;
	
	$stmt = $pdo->query($sql);

	$data = array();

	while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

		$item = new stdclass;
		
		$keys = array_keys($row);
	
		foreach ($keys as $k)
		{
			if ($row[$k] != '')
			{
				$item->{$k} = $row[$k];
			}
		}
	
		$data[] = $item;
	
	
	}
	
	return $data;	
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



$path = "Animalia%";
$path = "Animalia,Arthropoda,Insecta,Diptera%";
//$path = "Animalia,Arthropoda,Insecta%";
//$path = "Animalia,Mollusca%";
$path = "Animalia%";
$path = "Animalia,Arthropoda,Insecta,Lepidoptera%";
//$path = "Animalia,Annelida,Clitellata%";
//$path = "Animalia,Arthropoda,Arachnida%";


$output_dir = dirname(__FILE__) . '/output';

$sql = 'SELECT taxon, lineage, md5 FROM image INNER JOIN barcode USING(processid) WHERE lineage LIKE "' . $path . '" LIMIT 500;';

echo $sql . "\n";

$query_result = do_query($sql);


foreach ($query_result as $data)
{
	$hash_path_parts 	= hash_to_path_array($data->md5);
	$hash_path 			= hash_to_path($data->md5);
	
	$filename 			= 'images' . $hash_path . '/' . $data->md5 . '.jpg';
	
	$filename = '/Volumes/LaCie/BOLD/' . $filename;
	
	$dest = $output_dir . '/' . $data->md5 . '.jpg';
	
	copy($filename, $dest);
}

?>
