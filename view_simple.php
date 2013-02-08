<?
require_once("include_login.php");
?>
<html>
<head>
<title><? print $pagetitle; ?></title>
</head>
<body>

<?

foreach($items as $nr => $timestamp){
	print "<h1>".$contentmetadata[$timestamp]["Subject"]."</h1>\n";
	print $timestamp."<br>\n";
	print $contentmetadata[$timestamp]["From"]."<br>\n";

	if(isset($contentimg[$timestamp])){
		foreach($contentimg[$timestamp] as $nr2 => $imgfile){
			$imgorientation = "";
			if(isset($contentmetadata[$timestamp][$imgfile."_orientation"]))
				$imgorientation = "&orientation=".$contentmetadata[$timestamp][$imgfile."_orientation"];
			
			print '<a href="'.$imgfile.'"><img border="0" src="showimg.php?file='.$imgfile.'&longside=320'.$imgorientation.'"></a> '."\n";
		}
	}

	if(isset($contenttxt[$timestamp])){
		print "<p>".$contenttxt[$timestamp]."\n";
	}
	print "<hr>\n";
}

?></body></html>