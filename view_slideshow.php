<?
require_once("include_login.php");
?>
<html><head>
<meta http-equiv="refresh" content="10; URL=index.php?view=slideshow">
<?
require("function_getimgdata.php");
require("function_viewhelper.php");

iosheader("user-scalable=no", "yes");

?><title><? print $pagetitle; ?> - Slideshow</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor="black" style="padding:0px;margin:0px">

<?
$random = rand(0,1000);
$rndcounter = 0;
while(true){
	foreach($items as $nr => $timestamp){
		if(isset($contentimg[$timestamp])){
			foreach($contentimg[$timestamp] as $nr2 => $imgfile){
				if ($rndcounter++ == $random){
					$imgorientation = "";
					if(isset($contentmetadata[$timestamp][$imgfile."_orientation"])) {
						$imgorientation = $contentmetadata[$timestamp][$imgfile."_orientation"];
					} else {
						$imgorientation = getImgOrientation($imgfile);
					}

					
					# 1 = no change needed
					# 6 = rotate clockwise
					# 8 = rotate counterclockwise
					# 3 = upside down
		#			$imgorientation=6;
					
					$imglongside=710;
					if ($imgorientation == 1 || $imgorientation == 3)
						$imglongside=960;
					
					print '';
					print '<div align="center"><img border="0" src="showimg.php?file='.$imgfile.'&longside='.$imglongside.'&orientation='.$imgorientation.'"></div>'."\n";
					print '</body></html>';
					exit;
				}
			}
		}
	}
}

?>