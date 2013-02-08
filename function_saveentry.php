<?
require_once("include_login.php");
#speicherfunktion für Einträge

#totaler krampf - so gehts nicht

/*
 * $timestampnew: timestamp, unter dem der eintrag künftig zu finden ist
 * $timestampold: falls nicht null: vorheriger timestamp des eintrags.
 *   Alle Daten (z.B. Bilder) werden vom alten auf den neuen Eintrag
 *   umgezogen.
 * $subject: Subject des Eintrags
 * $content: Falls gesetzt: Content des Eintrags
 * $contentmetadata: Hash der Metadaten. Falls $timestampold gesetzt, werden
 *   die vorherigen Metadaten erhalten, und nur hier
 * $imgbinaries: Hash der Bild-Binaries. Falls nicht gesetzt wird nichts
 *   verändert
 *
 */
function saveentry($timestamp, $timestampold, $subject, $content, 
					$contentmetadata, $imgbinaries, $mailfile){
	
	if(!isset($contentmetadata))
		$contentmetadata = new Array;
					
	#save text
	if (preg_match('/[A-Za-z0-9]/', $content)){
		#print "x4";
		$txtfilename=$contentpath.$contentprefix.$timestamp.".txt";
		$TXT = fopen($txtfilename, "w") or die("could not open txt file \"$txtfilename\" for writing");
		fputs($TXT, $content);
		fclose($TXT);
	}

	#save images
	unset($imgfilenames_meta);
	if(isset($imgbinaries)){
		$count = 100;
		$imgfilenamesorig = array_keys($imgbinaries);
		sort($imgfilenamesorig);
		foreach($imgfilenamesorig as $nr => $imgfilenameorig){
			$imgbinary = $imgbinaries[$imgfilenameorig];
			#if(!empty($imgbinaries[$nr])){
			$imgfilename=$contentpath.$contentprefix.$timestamp."_".$count.".jpg";
			$IMG = fopen($imgfilename, "wb") or die("could not open img file \"$imgfilename\" for writing");
			fputs($IMG, $imgbinary);
			$count++;
			fclose($IMG);
			$imgfilenames_meta[$imgfilename] = $imgfilenameorig;
			#}
		}
		#unset($imgbinaries);
	}

	if (isset($timestampold)){
		$metafilename=$contentpath.$contentprefix.$timestamp."_metadata.txt";
		$TXT = fopen($metafilename, "r") or die("could not open meta file \"$metafilename\" for writing");
		while (!feof($TXT)){
			$line = fgets($TXT, 16000);
			#load old metadata, join with given
			if (preg_match('/^(.+): (.+)$/', $line, $OUT))
				if(!isset($contentmetadata[$OUT[1]]))
					$contentmetadata[$OUT[1]] = $OUT[2];
		}
		fclose($TXT);
	}
	
	# save metadata
	$metafilename=$contentpath.$contentprefix.$timestamp."_metadata.txt";
	$TXT = fopen($metafilename, "w") or die("could not open meta file \"$metafilename\" for writing");
	fputs($TXT, "Timestamp: $timestamp\n");
	fputs($TXT, "Subject: $subject\n");
	fputs($TXT, "From: $from\n");
	fputs($TXT, "Mailfile: $mailfile\n");
	foreach ($imgfilenames_meta as $imgfilename => $imgfilenameorig)
		fputs($TXT, "Imgfilename $imgfilename: $imgfilenameorig\n");
	fclose($TXT);

}