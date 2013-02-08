<?
require_once("include_login.php");
require_once("function_permissions.php");

$DIR = opendir($contentpath) or die("could not open dir $contentpath");
#$contentfiles = array();
$contentitems = array();
$contentimg = array();
$contentflv = array();
$contentmp4 = array();
$contenttxt = array();
$contentmetadata = array();
while($file = readdir($DIR)){
	if(preg_match('/^'.$contentprefix.'(\d\d\d\d-\d\d-\d\d_\d\d-\d\d-\d\d)/',$file, $OUT)){
		$timestamp = $OUT[1];
		$contentitems[$timestamp][]=$contentpath.$file;
		if(preg_match('/metadata/', $file)){
			$FILE = fopen($contentpath.$file, "r") or die("could not open $file for reading");
			while(!feof($FILE)){
				$line = fgets($FILE, 4096);
				$line = chop($line);
				if(preg_match("/^([^:]+): ?(.*)$/", $line, $OUT)){
					$contentmetadata[$timestamp][$OUT[1]] = $OUT[2];
				}
			}
			fclose($FILE);

		} else if(preg_match('/\.jpg$/', $file)){
			$contentimg[$timestamp][]=$contentpath.$file;
			sort($contentimg[$timestamp]);
	
		} else if(preg_match('/\.flv$/', $file)){
			$contentflv[$timestamp][]=$contentpath.$file;
			sort($contentflv[$timestamp]);

		} else if(preg_match('/\.mp4$/', $file)){
			$contentmp4[$timestamp][]=$contentpath.$file;
			sort($contentmp4[$timestamp]);

		} else if(preg_match('/.txt$/', $file)){
			$FILE = fopen($contentpath.$file, "r") or die("could not open $contentpath$file for reading");
			while(!feof($FILE)){
				$contenttxt[$timestamp]=$contenttxt[$timestamp].fgets($FILE, 4096);
			}
			fclose($FILE);
			
		}
	}
}
closedir($DIR);

$hidecontent = array();
foreach($contentmetadata as $timestamp => $metadata)
	if (!cansee($timestamp, $metadata))
		$hidecontent[] = $timestamp;

foreach($hidecontent as $nr => $timestamp) {
	unset($contentitems[$timestamp]);
	unset($contentimg[$timestamp]);
	unset($contentflv[$timestamp]);
	unset($contentmp4[$timestamp]);
	unset($contenttxt[$timestamp]);
	unset($contentmetadata[$timestamp]);
}	

$items = array_keys($contentitems);
rsort($items);
?>