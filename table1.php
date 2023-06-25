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

// Original Barcode 500K
$d->name = 'Original 2016 BARCODE 500K data release';
$d->database = 'sqlite:bold500K.db';
$d->table = 'ibolog';
$d->url = 'http://bins.boldsystems.org/index.php/datarelease';
//$d->doi = 

$d->queries = array(
	'total' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P";',
	'species' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND id_precision = "species"',
	'informal' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND id_precision = "species" AND (taxon LIKE "%sp.%"  OR taxon LIKE "%cf.%"  OR taxon LIKE "%aff.%" OR taxon LIKE "%0%"  OR taxon LIKE "%1%"  OR taxon LIKE "%2%" OR taxon LIKE "%3%"  OR taxon LIKE "%4%" OR taxon LIKE "%5%" OR taxon LIKE "%6%" OR taxon LIKE "%7%"  OR taxon LIKE "%8%"  OR taxon LIKE "%9%" OR taxon LIKE "% group%")',
);

$d->results = array();

$rows[] = $d;

$d = new stdclass;

// Recent snapshot of Barcode 500K
$d->name = 'Recent view of BARCODE 500K data release';
$d->database = 'sqlite:bold500K.db';
$d->table = 'ibol';
$d->url = 'http://www.boldsystems.org/index.php/datapackage?id=iBOLD.31-Dec-2016';
$d->doi = '10.5883/DP-iBOLD.31-Dec-2016';

$d->queries = array(
	'total' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P";',
	'species' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND id_precision = "species"',
	'informal' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND id_precision = "species" AND (taxon LIKE "%sp.%"  OR taxon LIKE "%cf.%"  OR taxon LIKE "%aff.%" OR taxon LIKE "%0%"  OR taxon LIKE "%1%"  OR taxon LIKE "%2%" OR taxon LIKE "%3%"  OR taxon LIKE "%4%" OR taxon LIKE "%5%" OR taxon LIKE "%6%" OR taxon LIKE "%7%"  OR taxon LIKE "%8%"  OR taxon LIKE "%9%" OR taxon LIKE "% group%")',
);

$d->results = array();

$rows[] = $d;

$d = new stdclass;

// Recent snapshot of Barcode
$d->name = '16 June 2023 BOLD';
$d->database = 'sqlite:jun2023.db';
$d->table = 'barcode';
$d->url = 'https://www.boldsystems.org/index.php/datapackage?id=BOLD_Public.16-Jun-2023';
$d->doi = '10.5883/DP-BOLD_Public.16-Jun-2023';

$d->queries = array(
	'total' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P";',
	'species' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND id_precision = "species"',
	'informal' => 'SELECT COUNT(processid) AS c FROM <TABLE> WHERE marker_code="COI-5P" AND id_precision = "species" AND (taxon LIKE "%sp.%"  OR taxon LIKE "%cf.%"  OR taxon LIKE "%aff.%" OR taxon LIKE "%0%"  OR taxon LIKE "%1%"  OR taxon LIKE "%2%" OR taxon LIKE "%3%"  OR taxon LIKE "%4%" OR taxon LIKE "%5%" OR taxon LIKE "%6%" OR taxon LIKE "%7%"  OR taxon LIKE "%8%"  OR taxon LIKE "%9%" OR taxon LIKE "% group%")',
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
	'total' => 'SELECT COUNT(id) AS c FROM <TABLE>',
	'species' => 'SELECT COUNT(id) AS c FROM <TABLE>',
	'informal' => 'SELECT COUNT(id) AS c FROM <TABLE> WHERE  (organism LIKE "%sp.%"  OR organism LIKE "%cf.%"  OR organism LIKE "%aff.%" OR  organism LIKE "%0%"  OR organism LIKE "%1%"  OR organism LIKE "%2%" OR organism LIKE "%3%"  OR organism LIKE "%4%" OR  organism LIKE "%5%" OR organism LIKE "%6%" OR organism LIKE "%7%"  OR organism LIKE "%8%"  OR organism LIKE "%9%" OR organism LIKE "% group%")',
);

$d->results = array();

$rows[] = $d;


/*

Data Source	Total # records	Records with formal binomials	% with formal binomials	Records with qualified or provisional binomials binomials 	% of records with qualified or provisional binomials 	Total records with binomials	% with bionomials
A. Original 2016 BARCODE 500K data release 	2,789,906	375,588	14%	40,163	1.4%	415,751	15%
B. 2022 BARCODE 500K data release 	2,784,901	1,018,565	37%	178,805	6%	1,197,370	43%
C. BOLD data package released June 2023 	9,085,652	4,013,955	44%	652,267	7%	4,666,222	51%
D. BARCODE records in GenBank with BARCODE keyword as of June 2023 	1,175,549	673,993	57%	503,556	43%	1,177,549	100%

*/

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
	
	$row->results['formal'] =  $row->results['species'] - $row->results['informal'];
	
	

}

print_r($rows);

?>
