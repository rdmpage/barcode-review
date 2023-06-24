<?php

ini_set('memory_limit', '-1');

error_reporting(E_ALL);

$basedir = dirname(__FILE__) . '/.';

$files = scandir($basedir);


$ids = array();

echo "id\taccession\torganism\tspecimen_voucher\tflag\tcreatedate\tupdatedate\n";

foreach ($files as $filename)
{
	if (preg_match('/\.json$/', $filename, $m))
	{	
		$json = file_get_contents($basedir . '/' . $filename);

		$json = file_get_contents($filename);
		$obj = json_decode($json);
		
		if ($obj)
		{
			/*
			if (!isset($obj->result))
			{
				echo $filename . "\n";
				exit();
			}
			*/

			foreach ($obj->result as $k => $v)
			{
				if (is_numeric($k))
				{
					$ids[] = $v->uid;
			
					$row = array();
					$row[] = $v->uid;
					$row[] = $v->caption;
					$row[] = $v->organism;
					
					// voucher
					$voucher = '';
					
					if (isset($v->subtype))
					{
						$keys = explode("\", $v->subtype);						
						$values = explode("\", $v->subname);
						
						$index = array_search('specimen_voucher', $keys);
						if ($index)
						{
							$vocuher = $values[$index];
						}
					}
					$row[] = $voucher;
				
					// detect stuff
					if (!preg_match('/^[A-Z]\w+\s+[a-z\-]+(\s+\w+)?$/',  $v->organism))
					{
						$row[] = 1;
					}
					else
					{
						$row[] = '';
					}
				
				
					$row[] = str_replace('/', '-', $v->createdate);
					$row[] = str_replace('/', '-', $v->updatedate);
				
			
					echo join("\t", $row) . "\n";
				}
			}
		}
	}
}

// echo json_encode($ids) . "\n";

?>
