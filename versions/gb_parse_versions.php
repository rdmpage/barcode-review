<?php

// process a GenBank version file

require_once (dirname(__FILE__) . '/HtmlDomParser.php');

use Sunra\PhpSimple\HtmlDomParser;




$filename = 'versions/HQ/918302.html';
//$filename = 'versions/MN/184166.html';

$filename = 'versions/HQ/918405.html'; // suppressed then unsuppressed (twice)

$html = file_get_contents($filename);

$dom = HtmlDomParser::str_get_html($html);

if ($dom)
{

	$obj = new stdclass;
	
	$obj->revisions = array();
	
	// name of sequence
	foreach ($dom->find('div[class=rprt] p[class=title]') as $p)
	{
		$obj->title = $p->plaintext;
	}
	
	// accession
	foreach ($dom->find('dl[class=rprtid] dd') as $dd)
	{
		if (preg_match('/^[A-Z]/', $dd->plaintext))
		{
			$obj->accession = $dd->plaintext;
			$obj->accession = preg_replace('/\.\d+$/', '', $obj->accession);			
		}
	}

	// revisions
	foreach ($dom->find('div[class=girevhist]') as $div)
	{
	
		// current status
		foreach ($div->find('div[class=status]') as $status)
		{
			$obj->status = $status->plaintext;
			$obj->status = preg_replace('/Current status:\s+/', '', $obj->status);
		}
		
		// table with multiple <tbody>, may have more than one row
		// to document suppression history

		// revisions (need to handle this better)
		/*
		foreach ($div->find('table tr td[xrowspan=1] input[type=radio]') as $input)
		{
			$obj->revisions[] = $input->value;
		}
		*/
		
		foreach ($div->find('table tbody') as $tbody)
		{
			foreach ($tbody->find('tr') as $tr)
			{
				//echo $tr->plaintext . "\n";
				//echo "\n";
				
				/*
				       <tr>
          <td align="center" valign="bottom" xrowspan="1"><input type="radio" name="a" value="323100267_14_8827326_Feb 15, 2011 12:06 AM_1_1" onclick="setRevhist(this);"></td>
          <td align="center" valign="bottom" xrowspan="1" class="g2"><input type="radio" name="b" value="323100267_14_8827326_Feb 15, 2011 12:06 AM_1_1" onclick="setRevhist(this);"></td>
          <td valign="bottom" align="center" xrowspan="1">1</td>
          <td valign="bottom" xrowspan="1" align="center">323100267</td>
          <td valign="bottom" xrowspan="1" align="center">HQ918405.1</td>
          <td valign="bottom" align="right">
            <a href="/nuccore/323100267?sat=14&amp;satkey=8827326">Feb 15, 2011 12:06 AM</a>
          </td>
          <td valign="top"></td>
        </tr>
				*/
				
				foreach ($tr->find('td[valign=top]') as $td)
				{
					echo "----\n";
					echo $td->plaintext . "\n";
					echo "\n";
					
					$next = $td->next_sibling();
					
					if ($next)
					{
						echo $next->plaintext . "\n";
					}
					echo "----\n";
				}
			
				foreach ($tr->find('td[valign=bottom] input[type=radio]') as $input)
				{
					echo $input->value . "\n";
				}

				
		
			}
			
		
		}
		
		
	}
	
	//$obj->revisions = array_unique($obj->revisions);

	//print_r($obj);
}

?>

