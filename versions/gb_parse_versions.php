<?php

// process a GenBank version file

require_once (dirname(__FILE__) . '/HtmlDomParser.php');

use Sunra\PhpSimple\HtmlDomParser;



//----------------------------------------------------------------------------------------
// Convert NCBI style date to timestamp
function ncbi_date_to_timestamp($date_string)
{
	$timestamp = 0;
	
	$date_string = preg_replace('/\s+\d+:\d+\s+[A|P]M/', '', $date_string);
	
	if (false != strtotime($date_string))
	{
		$timestamp = strtotime($date_string);
	}	
	
	return $timestamp;
}

//----------------------------------------------------------------------------------------


$basedir = "html";


$accessions=array(
'MF132148',
'JN312089',
'JF454444',
'JF453030',
'JF846690',
'MG936888',
'HQ938008',
'HQ600936',
'KX069615',
'HQ023471',
'MW487546',
'MN674269',
'MN668823',
'MN665772',
'HQ024360',
'KX145591',
'KX043603',
'JF870733',
'KR976448',
'KR946139',
'MG475424',
'KR975581',
'MF937163',
'KR433985',
'MG507332',
'KM993996',
'KR440670',
'KR254389',
'MF923950',
'KR990954',
'KR443398',
'KR794258',

);



$accessions=array(
'MF132148',
'JN312089',
'JF454444',
'JF453030',
'JF452933',
'GU685233',
'HM372911',
'GU685679',
'JF456915',
'KU687800',
'JN113192',
'JF456940',
'JF446322',
'KC419776',
'KP150332',
'OK042152',
'MG084678',
'MG085725',
'MG442752',
'KJ492529',
'KX782440',
'KX782438',
'HQ151768',
'LC178015',
'JX531647',
'JN184087',
'HM224222',
'MH429360',
'HQ533344',
'KJ148993',
'MF185535',
'JX192043',
'KX117950',
'KX117994',
'KT355203',
'MK264494',
'MH091971',
'MK039926',
'MK101243',
'MK560545',
'KF403463',
'KF389477',
'MZ050278',
'JF862759',
'JN283544',
'GU710964',
'HM434231',
'EU396132',
'EU398479',
'JQ853153',
'JQ850600',
'KF548671',
'HM434888',
'HM435017',
'HM374013',
'JN964824',
'JN964927',
'HQ927642',
'KR789716',
'MG449535',
'MG060276',
'MG116165',
'JF869046',
'HM412509',
'HM413929',
'HQ552677',
'HM436131',
'MF920810',
'JF842038',
'KJ378445',
'HM415295',
'HM426890',
'GU691253',
'MN993132',
'MG048297',
'MG665813',
'MN674651',
'MK759342',
'KP848979',
'JF843075',
'MN132281',
'MN131599',
'KU692760',
'KR434299',
'JQ579328',
'JQ564897',
'JQ565920',
'JQ566142',
'JQ568225',
'JQ559534',
'JN677601',
'JQ554634',
'JQ549740',
'JQ550678',
'JQ551065',
'GU675169',
'GU696557',
'HM410637',
'HM402280',
'HM403821',
'HQ555609',
'JN295517',
'JQ547401',
'JX516831',
'JN025162',
'JN265258',
'KX283416',
'KU357477',
'KX820376',
'HM905837',
'HQ557800',
'JF846690',
'MG936888',
'HQ938008',
'HQ600936',
'KX069615',
'HQ023471',
'MW487546',
'MN674269',
'MN668823',
'MN665772',
'HQ024360',
'KX145591',
'KX043603',
'JF870733',
'KR976448',
'KR946139',
'MG475424',
'KR975581',
'MF937163',
'KR433985',
'MG507332',
'KM993996',
'KR440670',
'KR254389',
'MF923950',
'KR990954',
'KR443398',
'KR794258',
'KR789582',
'KM867333',
'KR443646',
'KR042617',
'KR439423',
'KR365931',
'KR396204',
'KR383067',
'KR121107',
'KR122565',
'KR346462',
'KT084249',
'KR295609',
'KR370587',
'KR413188',
'MG477648',
'KR948591',
'KR958105',
'MG468017',
'KR272672',
'KR281130',
'KR272180',
'KR278449',
'KR103434',
'KR281099',
'KR284768',
'KM863242',
'KR428550',
'KR438461',
'KR442149',
'KR790535',
'MF732667',
'MF734564',
'MF904290',
'MF749333',
'MF852973',
'MF855769',
'MF634054',
'MG514934',
'MF876990',
'MF864651',
'MF844054',
'KR917248',
'KM866788',
'KM866851',
'KR466410',
'KT095256',
'KR248679',
'KR193750',
'KR377924',
'KR199008',
'KT138986',
'KR472588',
'KR458582',
'KR471505',
'KR502031',
'KR246757',
'KR123272',
'KT085214',
'KT099008',
'KR944424',
'KR407605',
'KT096564',
'KR286568',
'KR411996',
'KR373352',
'KT085353',
'KT087376',
'KR272025',
'KT078552',
'KR451567',
'HM431266',
'KR506256',
'KR509045',
'KT144790',
'KR604169',
'MF878486',
'KR590872',
'KR600006',
'KM619608',
'KR118018',
'KP976506',
'KR170562',
'KR175475',
'KR218858',
'KR341221',
'KT084085',
'KT080125',
'KR226709',
'MG398073',
'KJ092784',
'KJ167375',
'MG411977',
'MG397568',
'KJ164937',
'KJ445288',
'KJ444526',
'KR587410',
'KR592621',
'KR585674',
'MF702631',
'MF702102',
'MF840276',
'KR801001',
'KM542145',
'KM861861',
'KR447088',
'KP645510',
'KR223594',
'KR964192',
'KR959017',
'KR946573',
'KR916585',
'MF911311',
'KM832399',
'KP038853',
'KR620750',
'MF850834',
'MF725682',
'MF843668',
'MF726004',
'MF916683',
'MF721756',
'MG497090',
'KT095328',
'KT095868',
'KT078736',
'MG482801',
'KT086426',
'KR628006',
'KR615617',
'KR613363',
'KR623851',
'KR649011',
'KR642854',
'KM850827',
'MG511495',
'MF747186',
'KR927271',
'MZ659343',
'ON688150',
'HM398295',
'KM611625',
'MG141254',
'HQ943725',
'DQ292313',
'DQ291985',
'MZ402789',
'KY441770',
'GU712107',
'MW498802',
'MN680109',
'GQ501727',
'HQ561097',
'KR486682',
'JF494343',
'KC016970',
'HM900345',
'HM900434',
'JN420261',
'JN420071',
'GU805478',
'MG316412',
'JQ935042',
'MZ482440',
'OK624057',
'MW500248',
'MH840602',
'KM527647',
'HQ562269',
'KY261986',
'KM442770',
'KM448176',
'KM451382',
'JQ841870',
'JQ306261',
'MZ626768',
'MZ479454',
'MZ630650',
'JN640865',
'HQ564039',
'JN312976',
'JN313000',
'EU820584',
'EF607573',
'MF810242',
'JQ353598',
'JN989053',
'KP981751',
'MG432442',
'AB542560',
'KJ745144',
'KR182542',
'KC665378',
'GU125873',
'JQ267495',
'KP232191',
'KP422072',
'MF416867',
'KY698087',
'AY382202',
'KJ398935',
'KF978744',
'MF542507',
'DQ319679',
'LC278598',
'EF446695',
'MG873660',
'GQ426642',
'GQ473612',
'GQ474243',
'KM014170',
'JX392071',
'KC145537',
'JX912900',
'KY472763',
'BA000004',
'KY268526',
'KY269144',
'KY268527',
'KY270161',
'KC583702',
'FJ346976',
'KF654516',
'KF655987',
'HM214693',
'KF145177',
'EF542985',
'KY611217',
'MH825539',
'JQ839165',
'KF982189',
'KF962134',
'AY377004',
'KC563109',
'KF548659',
'KY572998',
'AF445613',
'LM655844',
'MH758742',
'AY601085',
'MK887719',
'KF743530',
'MK735907',
'MH189598',
'MH579552',
'KX910124',
'KY706708',
'FM955306',
'HQ010333',
'AB813671',
'AY803588',
'DQ006351',
'HM462890',
'KR738725',
'KX060259',
'MG792342',
'HM466220',
'DQ147451',
'KY026084',
'KX450508',
'KM594054',
'KM653165',
'KU204305',
'MF077904',
'HE659670',
'KC690961',
'MG673617',
'DQ851709',
'KU216212',
'JQ897993',
'AY944906',
'KT594107',
'LN897593',
'EU493620',
'EU733486',
'KF660134',
'EU811782',
'FJ971391',
'KC005369',
'JX948000',
'JN543444',
'DQ225121',
'AY308095',
'EU715549',
'KU667252',
'MH303220',
'MH303539',
'EU668371',
'KJ784414',
'KP994946',
'AY488822',
'KF797544',
'KM588587',
'KP233796',
'MH851096',
'JX442575',
'KT946079',
'KJ007773',
'MF151841',
'EU093025',
'EU585322',
'KF787232',
'KJ423032',
'JX171120',
'JQ704956',
'JQ704427',
'KF146283',
'KF161568',
'KJ445752',
'KP407041',
'KY670890',
'MG429040',
'MK120780',
'MG590296',
'MH383334',
'MH937995',
'AB303897',
'KU992101',
'JX533971',
'LC228833',
'MH988912',
'MH807256',
'GU723006',
'KF684280',
'KM083858',
'JN978553',
'GQ902548',
'GQ902235',
'KF647342',
'HQ828679',
'HE661677',
'KF261331',
'KJ911293',
'FJ171581',
'MG882205',
'LN995550',
'MF783490',
'AB872100',
'KP692728',
'KT891572',
'MF849209',
'AB758100',
'KP143383',
'GU372402',
'MF716888',
'KP195780',
'LC178501',
'KU166510',
'KU755528',
'KT278553',
'KU312365',
'KY223614',
'KP294218',
'KP668799',
'KP213895',
'LC215505',
'JQ239857',
'JN087436',
'HQ535402',
'KJ192925',
'KJ192954',
'LK026432',
'KP941250',
'KM350596',
'KT194299',
'KX791744',
'MF384067',
'MF577804',
'KY613012',
'KR183703',
'KU340895',
'KF525026',
'KY362780',
'KU557043',
'GQ387458',
'KT710359',
'MK754696',
'MK258777',
'LC191942',
'MG969424',
'MH362362',
'AF231755',
'AY579125',
'EU310055',
'JX303350',
'FJ876842',
'DQ980038',
'FJ160669',
'DQ207346',
'KJ472924',
'LC021505',
'KX857358',
'NC_033945',
'KY773686',
'KT282340',
'MK797956',
'MK416250',
'MG688484',
'MG688833',
'MK160454',
'KF444305',
'HM744896',
'MN685707',
'MN405190',
'MN406356',
'MN408013',
'MN408150',
'MH821192',
'MK266863',
'MN158395',
'LC428781',
'LC455684',
'MK978224',
'MK118722',
'MK917144',
'MK584385',
'MT003904',
'MG826477',
'MK672539',
'MN395346',
'MN436587',
'MF982109',
'MN172605',
'KJ630109',
'MT218574',
'MT172793',
'MT171995',
'MT171266',
'MT171123',
'MT979283',
'MT902586',
'LC490879',
'MT476483',
'MN934117',
'MT259950',
'MN866444',
'MN534875',
'MW049050',
'MT783823',
'KX177961',
'MT739069',
'MW322182',
'MW611720',
'MW606204',
'MW600180',
'MW596787',
'MW609624',
'MW608945',
'MW609076',
'MW614445',
'MW607401',
'LC582429',
'MZ360947',
'MW684715',
'MT165296',
'MW491487',
'MW581568',
'MW575825',
'MT541063',
'MT541557',
'MW139306',
'MW603691',
'MN393379',
'MW917044',
'MW324541',
'MT639341',
'LC605305',
'MZ680506',
'MT954946',
'MT726622',
'LC532683',
'MW479627',
'MW305747',
'MW526512',
'MW560336',
'MW069879',
'MW147426',
'MT523326',
'MN058864',
'MK891599',
'OK391829',
'OK391907',
'KU185046',
'MH992029',
'KP242726',
'MH548294',
'MZ050883',
'AB510022',
'KC921263',
'KJ481069',
'KR907092',
'KF559302',
'FN552832',
'GQ304733',
'FJ969958',
'GQ342413',
'EU751037',
'JQ309162',
'KU917115',
'KU908575',
'KU907864',
'KU915326',
'OM547720',
'OM582060',
'OM577596',
'OM576588',
'OM596060',
'OM597080',
'OM604339',
'OM612020',
'OM546902',
'OM581265',
'OM707067',
'OM710116',
'OM707377',
'OM593871',
'OM607507',
'OM609744',
'OM612311',
'MG033627',
'MG033817',
'MG040832',
'MG166046',
'MG106490',
'MG103200',
'MG082484',
'MG378430',
'MG375342',
'MG154666',
'MG157029',
'MG095543',
'MG097292',
'MH271877',
'HQ600666',
'HQ649251',
'KF807779',
'GU686941',
'HM422630',
'HM904502',
'HM911166',
'HM914131',
'HQ565946',
'KF807543',
'HQ566638',
'KR633582',
'MZ630470',
'MZ631353',
'KR040071',
'MT999923',
'KX104763',
'KR646384',
'KR654002',
'KR657780',
'KR657841',
'KR573749',
'KR791987',
'HM394536',
'JF851964',
'MZ196764',
'MZ626012',
'MZ608162',
'HQ568532',
'MK083794',
'MK080992',
'MK081792',
'MH095029',
'MT518099',
'KX106212',
'AB286901',
'KC825226',
'KP684631',
'KR668292',
'KR664057',
'KU380412',
'KY262889',
'KY262978',
'KT132779',
'KJ377853',
'KT130055',
'KT139060',
'KJ394277',
'HM867557',
'KJ383296',
'MN142170',
'HM872048',
'HM873409',
'KM571952',
'MK186491',
'GU662869',
'HM877156',
'GU089288',
'GU088720',
'MT887657',
'KC616434',
'MF920892',
'JF856659',
'KJ390051',
'KM545559',
'KM552188',
'KJ378486',
'GU801143',
'KJ384652',
'KT130348',
'MK566280',
'KF492119',
'KX292297',
'MN319922',
'KF407730',
'KY837384',
'KY834341',
'KP871367',
'KR662850',
'KR659690',
'KR663481',
'KF808979',
'HM389865',
'JQ548506',
'GU161908',
'GU156688',
'JF752874',
'JF761247',
'JQ540027',
'GU160210',
'GU152787',
'JQ540703',
'JQ542808',
'JQ542968',
'JQ543526',
'GU164802',
'JQ535939',
'JQ537824',
'JF754351',
'GU653336',
'HM391342',
'HM894314',
'HM886765',
'JQ524046',
'JQ524428',
'GU693793',
'HQ575112',
'MN351052',
'MN352936',
'MN349567',
'MN353438',
'JN306598',
'JX838003',
'JF903228',
'JX887537',
'KR696882',
'KR681880',
'JX150216',
'JX150005',
'KR689666',
'KR568758',
'KR687624',
'KR681679',
'KR482510',
'KR481210',
'KR570482',
'KR681044',
'KR578066',
'KT131893',
'KR881171',
'KT118663',
'KT115165',
'KX048028',
'OK073610',
'HQ967327',
'MW490424',
'OP348111',
'HQ708772',
'MN357742',
'MN362567',
'MZ364404',
'KT103912',
'KR873531',
'KR900657',
'KT115191',
'MT535229',
'KT118100',
'KT109966',
'KT108907',
'KR953089',
'MW214224',
'JN269370',
'JN307547',
'MG036368',
'KY831887',
'KY831304',
'MG108690',
'MG305582',
'MG126912',
'MG166922',
'MG307843',
'MG348960',
'HQ970073',
'MF130465',
'KP657453',
'KR740837',
'MF544499',
'KJ383333',
'KJ392738',
'KJ376057',
'KJ383219',
'KJ392189',
'GU668175',
'JN027770',
'HQ579113',
'JN026213',
'MW490141',
'KT707554',
'KT616235',
'KT704007',
'KT608117',
'KT607072',
'KT608423',
'KT618363',
'KT607264',
'MG439097',
'MG177603',
'MG353172',
'MG315797',
'JN264578',
'MT806917',
'JN309153',
'JF498757',
'MN346112',
'KR489015',
'KR570698',
'MG138447',
'KR766980',
'KR762899',
'KR776069',
'KR718212',
'MG038693',
'MG401666',
'MG303723',
'MG105332',
'MG111102',
'MG038111',
'MG151303',
'MG142200',
'MG297058',
'MG399630',
'KC567786',
'JN678086',
'HQ975137',
'KF369036',
'KM615345',
'KM944588',
'KM947126',
'KM940210',
'KM563179',
'KM832663',
'KM560986',
'KM929915',
'KM837903',
'MG122244',
'MG083944',
'MG446399',
'MG178975',
'MG126318',
'MG487267',
'KR731356',
'MG406639',
'MG317107',
'MG413046',
'KM936815',
'KM542704',
'KM951819',
'KM567394',
'KM961943',
'KM835378',
'KM617736',
'MF913550',
'KR714573',
'MG472735',
'KR521713',
'MF884605',
'MF937256',
'KR525871',
'MF633543',
'MF701091',
'MF870736',
'MF850749',
'MF870047',
'MG468303',
'MF899337',
'MF832572',
'KM965742',
'MG486709',
'KM841565',
'KM966161',
'KM910900',
'KM563541',
'KM907199',
'KM899259',
'KM915366',
'KR879186',
'MF715939',
'MF908058',
'MF607619',
'MG359615',
'MG342133',
'MF712568',
'MF744373',
'MF822967',
'MF827644',
'MF828855',
'MF874852',
'MF711980',
'MG504041',
'MF711094',
'MF635195',
'FJ998942',
'MG476366',
'KM901094',
'KM914639',
'KM918217',
'KM899219',
'KM917552',
'KM644198',
'MF833293',
'KR521505',
'KR519010',
'MF544342',
'KR574610',
'KR982380',
'KR981450',
'KM642234',
'KM633310',
'KM638903',
'KM558535',
'KM828982',
'KR564836',
'MG935169',
'MG935323',
'MG413262',
'MG058996',
'MG310357',
'HQ980233',
'MK260313',
'HQ982385',
'KJ375803',
'KF930141',
'HM386158',
'GU694805',
'GU694772',
'HQ583131',
'KM020829',
'MH419397',
'MT260532',
'MN139523',
'HQ584804',
'JN282213',
'KT143861',
'KT137977',
'GU091487',
'KC617646',
'JF862357',
);


// read from file
$filename = "accessions.txt";

$accessions = array();

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$accession = trim(fgets($file_handle));
	$accession = str_replace('-SUPPRESSED', '', $accession );
	
	$accessions[] = $accession;

}
 
$records = array();

foreach ($accessions as $accession)
{
	if (preg_match('/([A-Z]+)(\d+)/', $accession, $m))
	{
		$filename = $basedir . '/' . $m[1] . '/' . $m[2] . '.html';

		// get HTML
		$html = file_get_contents($filename);

		$dom = HtmlDomParser::str_get_html($html);
		if ($dom)
		{
			// Hold information on the version history
			$obj = new stdclass;
			
	
			$obj->revisions = array();
	
			// description of sequence
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
		
				foreach ($div->find('table tbody') as $tbody)
				{				
					$cur_revision_id = '';
								
					foreach ($tbody->find('tr') as $tr)
					{
						foreach ($tr->find('td[valign=bottom] input[type=radio]') as $input)
						{
							$cur_revision_id = $input->value;
							
							if (!isset($obj->revisions[$cur_revision_id]))
							{
								$obj->revisions[$cur_revision_id] = new stdclass;
								$obj->revisions[$cur_revision_id]->id = $cur_revision_id;
							}
														
							// parse into bits we can use
							if (preg_match('/(\d+)_(\d+)_(\d+)_(?<datetime>[A-Z][a-z]+\s+\d+,\s+[0-9]{4}\s+\d+:\d+\s+[A|P]M)/', $input->value, $m))
							{
								//print_r($m);
								$obj->revisions[$cur_revision_id]->datetime = $m['datetime'];
								
								$obj->revisions[$cur_revision_id]->timestamp = ncbi_date_to_timestamp($obj->revisions[$cur_revision_id]->datetime);
								$obj->update_times[] = $obj->revisions[$cur_revision_id]->timestamp;
							}
						}	
						
						foreach ($tr->find('td[valign=top]') as $td)
						{
							$comment_id = $td->plaintext;
						
							if (trim($comment_id) != '')
							{						
								$next = $td->next_sibling();
				
								if ($next)
								{
									$comment = $next->plaintext;									
									$comment = preg_replace('/^suppressed([A-Z])/', 'suppressed $1', $comment);
									$obj->comments[$comment_id] = $comment;
									
									$timestamp = ncbi_date_to_timestamp($comment_id);
									$obj->comment_times[] = $timestamp;
								}
							}
						}
		
					}
			
					
		
				}
		
		
			}
	
			//$obj->revisions = array_unique($obj->revisions);
			
			if (count($obj->revisions) == 0)
			{
				echo "$accession no revisions\n";
				exit();
			}
			
			$obj->update_times = array();
			$obj->comment_times = array();
			
			//foreach ($obj)
			foreach ($obj->revisions as $revisions_id => $revision)
			{
				$timestamp = ncbi_date_to_timestamp($comment_id);
				$obj->update_times[] = $revision->timestamp;
			}
			
			$obj->update_times = array_unique($obj->update_times);
			
			if (isset($obj->comments))
			{
				foreach ($obj->comments as $comment_id => $comment)
				{
					$timestamp = ncbi_date_to_timestamp($comment_id);
					$obj->comment_times[] = $timestamp;
				}
			}
			
			$records[] = $obj;
			
			// updates
			$edits = $obj->update_times;
			sort($edits);			
			$created = $edits[0];			
			$points[$created][0] = $edits;
			
			// comments
			$edits = $obj->comment_times;
			sort($edits);
			$points[$created][1] = $edits;		
			

			//print_r($obj);
		}
		else
		{
			echo "$accession bad\n";
			exit();
		}
	}
}

//print_r($points);

// Output data to plot
echo "edited\tcreated\tcommented\n";
foreach ($points as $created => $edits)
{
	foreach ($edits[0] as $edit)
	{
		echo "$edit\t$created\t\n";
	}
	foreach ($edits[1] as $edit)
	{
		echo "$edit\t\t$created\n";
	}
}

if (1)
{
	$edit_frequency = array();

	foreach ($records as $obj)
	{
		$edits = count($obj->revisions);
		if (isset($obj->comments))
		{
			$edits += count($obj->comments);
		}
	
		if (!isset($edit_frequency[$edits]))
		{
			$edit_frequency[$edits] = 0;
		}
		$edit_frequency[$edits]++;
	}
	
	ksort($edit_frequency);
	
	print_r($edit_frequency);

}



if (0)
{
	$num_suppressed = 0;
	$num_unsuppressed = 0;

	foreach ($records as $obj)
	{
		if (isset($obj->comments))
		{
			print_r($obj->comments);
		
			$suppressed = 0;
			$unsuppressed = 0;
		
			$current_status = '';
		
			$latest = 0;
		
			foreach ($obj->comments as $datetime => $comment)
			{
				if (preg_match('/^suppressed/', $comment))
				{
					$suppressed++;
				}
			
				$timestamp = ncbi_date_to_timestamp($datetime);
			
				if ($timestamp > $latest)
				{
					if (preg_match('/^suppressed/', $comment))
					{
						$current_status = "suppressed";
					}
					if (preg_match('/^unsuppressed/', $comment))
					{
						$current_status = "unsuppressed";
					}
				
					$latest = $timestamp;
				
				}
			
			
			}
		
			if ($suppressed > 0)
			{
				$num_suppressed++;
			}
			if ($current_status ==  "unsuppressed")
			{
				$num_unsuppressed++;
			}
		
		}
	}

	echo "  Num suppressed: $num_suppressed\n";
	echo "Num unsuppressed: $num_unsuppressed\n";
}

?>

