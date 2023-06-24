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

?>


<html>
<head>
<style>
	/* heavily based on https://css-tricks.com/adaptive-photo-layout-with-flexbox/ */
	.gallery ul {
	  display: flex;
	  flex-wrap: wrap;
	  
	  list-style:none;
	  padding-left:2px;
	}

	.gallery li {
	  height: 100px;
	  flex-grow: 1;
  
	}

	.gallery li:last-child {
	  flex-grow: 10;
	}

	.gallery img {
	  max-height: 90%;
	  min-width: 90%;
	  object-fit: cover;
	  vertical-align: bottom;
	  
	  border:1px solid rgb(192,192,192);
	}	
	
	</style>
</style>
</head>
<body>
<?php

$path = "Animalia%";
$path = "Animalia,Arthropoda,Insecta,Diptera%";
//$path = "Animalia,Arthropoda,Insecta%";
//$path = "Animalia,Mollusca%";
$path = "Animalia%";
$path = "Animalia,Arthropoda,Insecta,Lepidoptera,Erebidae%";
//$path = "Animalia,Annelida,Clitellata%";
//$path = "Animalia,Arthropoda,Arachnida%";


$sql = 'SELECT taxon, lineage, md5 FROM image INNER JOIN barcode USING(processid) WHERE lineage LIKE "' . $path . '" LIMIT 500;';

$sql = 'SELECT taxon, lineage, md5 FROM image INNER JOIN barcode USING(processid) WHERE lineage LIKE "' . $path . '" LIMIT 10';


echo $sql . "\n";

exit();

$query_result = do_query($sql);

echo '<div class="gallery">';
echo '<ul>';

foreach ($query_result as $data)
{
	$hash_path_parts 	= hash_to_path_array($data->md5);
	$hash_path 			= hash_to_path($data->md5);
	
	$filename 			= 'images' . $hash_path . '/' . $data->md5 . '.jpg';

	echo '<li>';
	echo '<img src="' . $filename . '" title="' . $data->taxon . '">';
	echo '</li>';
	
	echo "\n";
}
echo '<li></li>';
echo '</ul>';
echo '</div>';


?>

</body>
</html>
