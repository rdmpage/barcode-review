<?php

//----------------------------------------------------------------------------------------
function do_query($pdo, $sql)
{
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


$d = new stdclass;

$rows = array();


//  Recent snapshot of Barcode 500K
$d->name = 'Recent view of BARCODE 500K data release';
$d->database = 'sqlite:bold500K.db';
$d->table = 'ibol';
$d->url = 'http://bins.boldsystems.org/index.php/datarelease';
//$d->doi = 

$d->queries = array(
	'total' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P";',
	'bioug' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND  museumid LIKE "%BIOUG%";',
	'blank' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND  museumid =""',
	'snrp' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND  museumid LIKE "%SRNP%"',	
	'hyphen' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND  museumid LIKE "%-%"',	
	'colon' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND  museumid LIKE "%:%"',	
	'period' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND  museumid LIKE "%.%"',	
);

$d->results = array();

$rows[] = $d;

$d = new stdclass;

// Recent snapshot of all Barcodes
$d->name = '16 June 2023 BOLD';
$d->database = 'sqlite:jun2023.db';
$d->table = 'barcode';
$d->url = 'https://www.boldsystems.org/index.php/datapackage?id=BOLD_Public.16-Jun-2023';
$d->doi = '10.5883/DP-BOLD_Public.16-Jun-2023';

$d->queries = array(
	'total' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P";',
	'bioug' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND museumid LIKE "%BIOUG%";',
	'blank' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND museumid IS NULL',
	'snrp' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND museumid LIKE "%SRNP%"',	
	'hyphen' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND museumid LIKE "%-%"',	
	'colon' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND museumid LIKE "%:%"',	
	'period' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND museumid LIKE "%.%"',	
);

$d->results = array();

$rows[] = $d;



$d = new stdclass;

// BARCODE keyword in GenBank
$d->name = 'GenBank BARCODE 21 June 2023';
$d->database = 'sqlite:gb-barcode.db';
$d->table = 'seq';
$d->url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=nucleotide&retmax=2000000&term=BARCODE%5BKeyword%5D';

$d->queries = array(
	'total' => 'SELECT COUNT(id) AS c FROM <TABLE>;',
	'bioug' => 'SELECT COUNT(id) AS c FROM <TABLE> WHERE specimen_voucher LIKE "%BIOUG%";',
	'blank' => 'SELECT COUNT(id) AS c FROM <TABLE> WHERE specimen_voucher =""',
	'snrp' => 'SELECT COUNT(id) AS c FROM <TABLE> WHERE specimen_voucher LIKE "%SRNP%"',	
	'hyphen' => 'SELECT COUNT(id) AS c FROM <TABLE> WHERE specimen_voucher LIKE "%-%"',	
	'colon' => 'SELECT COUNT(id) AS c FROM <TABLE> WHERE specimen_voucher LIKE "%:%"',	
	'period' => 'SELECT COUNT(id) AS c FROM <TABLE> WHERE specimen_voucher LIKE "%.%"',	
);


$d->results = array();

$rows[] = $d;




foreach ($rows as &$row)
{
	echo $row->name . "\n";
	echo $row->url . "\n";
	
	if (isset($row->doi))
	{
		echo $row->doi . "\n";
	}
	
	$pdo = new PDO($row->database);
	
	foreach ($row->queries as $name => $sql)
	{
	
		$sql = str_replace('<TABLE>', $row->table, $sql);
		
		echo $sql . "\n";
		
		$result = do_query($pdo, $sql);
		
		if (isset($result[0]->c))
		{
			$row->results[$name] = $result[0]->c;
		}
	
	}
	

}

print_r($rows);

?>
