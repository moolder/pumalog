<?
require_once("include_login.php");
#speicherfunktion für Einträge
function savecontent($timestamp, $content){
	require("config.php");
	#save text
	$txtfilename=$contentpath.$contentprefix.$timestamp.".txt";
	$TXT = fopen($txtfilename, "w") or die("could not open txt file \"$txtfilename\" for writing");
	fputs($TXT, $content);
	fclose($TXT);

}

function savemetadata($timestamp, $metadata){
	require("config.php");
	#save metafile
	$metafilename=$contentpath.$contentprefix.$timestamp."_metadata.txt";
	$TXT = fopen($metafilename, "w") or die("could not open meta file \"$metafilename\" for writing");
	foreach ($metadata as $metakey => $metavalue)
		fputs($TXT, "$metakey: $metavalue\n");
	fclose($TXT);

}

function existstimestamp($timestamp){
	require("config.php");
	$DIR = opendir($contentpath);
	while (false !== $file = readdir($DIR)){
		if (preg_match('/^'.$contentprefix.$timestamp.'/', $file)){
			closedir($DIR);
			return true;
		}
	}
	closedir($DIR);
	return false;
}

function changetimestamp($timestamp, $newtimestamp, $metadata){
	require("config.php");
	if (existstimestamp($newtimestamp))
		return false;
	$DIR = opendir($contentpath);
	while (false !== $file = readdir($DIR)) {
		if (preg_match('/^'.$contentprefix.$timestamp.'(.*)$/', $file, $OUT)) {
			$oldfiles[] = $contentpath.$contentprefix.$timestamp.$OUT[1];
			$newfiles[] = $contentpath.$contentprefix.$newtimestamp.$OUT[1];
		}
	}
	closedir($DIR);
	
	#print "<pre>renaming files...\n";
	foreach ($oldfiles as $nr => $oldfile) {
		rename($oldfile, $newfiles[$nr]) or die("could not rename $oldfile to ".$newfiles[$nr]);
		#print "rename $oldfile\n";
	}
	#print "done.</pre>\n";
	
	foreach($metadata as $metakey => $metavalue){
		$newmetakey = preg_replace("/$timestamp/", $newtimestamp, $metakey);
		$newmetavalue = preg_replace("/$timestamp/", $newtimestamp, $metavalue);
		$newmetadata[$newmetakey] = $newmetavalue;
	}
	
	savemetadata($newtimestamp, $newmetadata);
	
	return true;
}

function deleteentry($timestamp){

	require("config.php");
	
	$DIR = opendir($contentpath) or die("could not open dir $contentpath");
	$count = 0;
	while(false !== ($file = readdir($DIR))){
		#if (preg_match('/^'.$contentprefix.$timestamp.'/', $file)){
		$pos = strpos($file, $contentprefix.$timestamp);
		if ($pos !== false and $pos == 0){
			$filestomove[$count++] = $file;
		}
	}
	closedir($DIR);
	
	if (isset($filestomove)){
		foreach ($filestomove as $nr => $file)
			rename($contentpath.$file, $deletedpath.$file) 
				or die("could not move $file from $contentpath to $deletedpath");	
	} else {
		die("Nothing to move!?");
	}
}

function addimage($timestamp){

	require("config.php");

	// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
	// of $_FILES.

	#$filename = basename($_FILES['userfile']['name']);
	for($i = 100; $i < 1000; $i++){
		$uploadfile = $contentpath.$contentprefix.$timestamp."_".$i.".jpg";
		if (!is_file($uploadfile)) {
			break;
		} else {
			unset($uploadfile);
		}
	}
	
	if (!isset($uploadfile))
		die("Kein Dateiname mehr frei");
		
	if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		die("Possible file upload attack!");
	}
	
	return $uploadfile;
}

function deleteimage($timestamp, $imgfile, $metadata){
	
	require("config.php");

	foreach($metadata as $metakey => $metavalue) {
		print "<pre>$imgfile\n</pre>";
		if (preg_match("/".preg_quote($imgfile, "/")."/", $metakey))
			unset($metadata[$metakey]);
	}
	$deletedfile = preg_replace("/".preg_quote($contentpath, "/")."/", $deletedpath, $imgfile);
	
	rename($imgfile, $deletedfile) or die ("could not move $imgfile to $deletedfile");

	savemetadata($timestamp, $metadata);
}

?>