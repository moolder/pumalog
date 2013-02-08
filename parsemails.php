<?
/*
Mails scannen und fotos und texte extrahieren.
*/
?>
<!-- <html>
<head>
<title>Mail parser</title>
</head>
<body>
<h1>Parse Mails</h1>
-->
<?
require("config.php");

#load ignore list
$FILE = fopen($ignorelist, "r") or die("could not open ignorelist $ignorelist for reading");
while(!feof($FILE)){
	$ignoremails[chop(fgets($FILE, 4096))]++;
}
fclose($FILE);

#read mail dir, save filenames to $mailfiles array if new
$DIR = opendir($mailpath) or die("could not open dir $mailpath");
$mailfiles = array();
while($mailfile = readdir($DIR)){
	if(!preg_match('/^\\./',$mailfile)){
		if(!isset($ignoremails[$mailfile])){
			#print $mailfile."<br>";
			$mailfiles[] = $mailpath.$mailfile;
			$stats["new mail"]++;
		} else {
			$stats["old mail"]++;
		}
	}
}
closedir($DIR);

#parse mail files
foreach($mailfiles as $id => $mailfile){
	$content = "";
	unset($imgbinaries);
	$imgcount = 100;
	#print "<br>".$mailfile."<br>";
	$F = fopen($mailfile, "r") or die("could not open $mailfile for reading");
	$timestamp="NOTIME";
	$lastline="";
	while (!feof($F)){
		$line = fgets($F, 1024);
		$line = chop($line);
		
		if(preg_match('/^Subject: (.*)$/', $line, $OUT)){
			$subject = mb_decode_mimeheader($OUT[1]);
			$subject = preg_replace('/_/', " ", $subject);
			$lastline= "subject";
		} else if(preg_match('/^From: (.*)$/', $line, $OUT)){
			$from = $OUT[1];
			$lastline= "from";
		} else if(preg_match('/^Date: ..., (\d?\d) (...) (\d\d\d\d) (\d\d:\d\d:\d\d) /', $line, $OUT)){
			$day = $OUT[1];
			if(strlen($day) == 1){
				$day = "0".$day;
			}

			$month = $OUT[2];
			if($month == "Jan"){
				$month = "01";
			} else if($month == "Feb"){
				$month = "02";
			} else if($month == "Mar"){
				$month = "03";
			} else if($month == "Apr"){
				$month = "04";
			} else if($month == "May"){
				$month = "05";
			} else if($month == "Jun"){
				$month = "06";
			} else if($month == "Jul"){
				$month = "07";
			} else if($month == "Aug"){
				$month = "08";
			} else if($month == "Sep"){
				$month = "09";
			} else if($month == "Oct"){
				$month = "10";
			} else if($month == "Nov"){
				$month = "11";
			} else if($month == "Dec"){
				$month = "12";
			} else {
				die("could not parse month $month");
			}
			
			$year = $OUT[3];
			
			$time = $OUT[4];
			$time = str_replace(":", "-", $time);

			$timestamp=$year."-".$month."-".$day."_".$time;
			$lastline="date";
		} else if(preg_match('/^Content-Type: text\\/plain/', $line)){
		# get text
			#print "x1";
			#$content=$subject."\n";
			$encoding = "";
			while (!feof($F) and !preg_match('/^--/', $line)){
				#print "x2";
				$line = fgets($F, 64000);
				if (!preg_match('/(^Content-Transfer-Encoding: |--|charset=|datum *=)/i', $line)){
					#print "x3";
					$content=$content.$line;
					$content = preg_replace('/=\r?\n/', '', $content);
					#print $line."<br>";
				}
				
				if (preg_match('/^Content-Transfer-Encoding: *quoted-printable/i', $line)){
					$encoding = "quoted-printable";
				}
				
				#set timestamp from mail text
				if (preg_match('/datum *=(.+)\\r?\\n?$/i', $line, $OUT)){
					$datum = $OUT[1];
					#print "datum: $datum<br>";
					if(preg_match('/(\\d{1,2})[^\\d]+(\\d{1,2})[^\\d]+(\\d{2,4})([^\\d]+(\\d{1,2})[^\\d](\\d{1,2})([^\\d](\\d{1,2}))?)?/', $datum, $OUT)){
						for($x=1;$x<20;$x++){
							#print "$x: ".$OUT[$x]."<br>";
						}
						$tstag     = $OUT[1];
						$tsmonat   = $OUT[2];
						$tsjahr    = $OUT[3];
						$tsstunde  = $OUT[5];
						$tsminute  = $OUT[6];
						$tssekunde = $OUT[8];
						
						if (strlen($tstag) == 1) $tstag = "0".$tstag;
						if (strlen($tsmonat) == 1) $tsmonat = "0".$tsmonat;
						if (strlen($tsjahr) == 1) $tsjahr = "200".$tsjahr;
						if (strlen($tsjahr) == 2) $tsjahr = "20".$tsjahr;
						if (strlen($tsjahr) == 3) $tsjahr = "2".$tsjahr;
						if (!isset($tsstunde)){
								$tsstunde="00";
								$tsminute="00";
						} else {
							if (strlen($tsstunde) == 1) $tsstunde = "0".$tsstunde;
							if (strlen($tsminute) == 1) $tsminute = "0".$tsminute;
							if (!isset($tssekunde)){
								$tssekunde="00";
							} else {
								if (strlen($tssekunde) == 1) $tssekunde = "0".$tssekunde;
							}
						}
						
						$timestamp = $tsjahr."-".$tsmonat."-".$tstag."_".$tsstunde."-".$tsminute."-".$tssekunde;
					}
				}
			}

			# if any text content
			if (preg_match('/[A-Za-z0-9]/', $content)){

				#decode
				if ($encoding == "quoted-printable")
					$content = quoted_printable_decode($content);
				
				# add html markup
				$content = preg_replace('/\\r?\\n *\\r?\\n/','<p>', $content);
			}
				
			$lastline="content";
		} else if(preg_match('/^Content-Type: image\\/jpeg/', $line)){
		# get images
			$imgfilename = $imgcount;
			if(preg_match('/name="([^"]+)"/', $line, $OUT)){
				$imgfilename = $OUT[1];
			}
			#print $line."<br>";
			while (!feof($F) and $line != ""){
				$line = fgets($F, 1024);
				$line = chop($line);
			}
			$base64data="";
			while (!feof($F) and !preg_match('/^--/', $line)){
				$line = fgets($F, 1024);
				if (!preg_match('/^--/', $line)){
					$base64data=$base64data.$line;
					#print $line."<br>";
				}
			}
			$imgbinaries[$imgfilename]=base64_decode($base64data);
			$lastline="content";
			$imgcount++;
		} else if (preg_match('/^ +(.+)/', $line, $OUT) and $lastline == "subject"){
			$subject2 = mb_decode_mimeheader($OUT[1]);
			$subject2 = preg_replace('/_/', " ", $subject2);
			$subject = $subject." ".$subject2;
			$lastline= "subject";
		} else {
			$lastline= "";
		}
	}

	### begin function saveentry
	
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
	### end function saveentry
	
	$mailfile_nopath = $mailfile;
	if (preg_match('/([^\\/]+)$/', $mailfile, $OUT))
		$mailfile_nopath = $OUT[1];
	$ignoremails[$mailfile_nopath]++;

	#save ignore list (after every mail - for restart)
	$FILE = fopen($ignorelist, "w") or die("could not open ignore list $ignorelist for writing");
	foreach($ignoremails as $ignorefile => $dummy){
		fputs($FILE, $ignorefile."\n");
	}
	fclose($FILE);

}


if($stats["new mail"] > 0){
	print $stats["new mail"]." neue Mails<br><br>";
}
?>

<!-- </body>
</html> -->