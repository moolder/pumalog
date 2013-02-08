<?
require_once("include_login.php");

# set to true for local test, false for production
$testmode = true; # true / false

# Page title
$pagetitle = "Leas Tagebuch";

# path to mail files
if ($testmode)
	$mailpath="new/";
else
	$mailpath="/var/qmail/mailnames/schuh-wie-der-schuh.de/fotos/Maildir/new/";

# Contentpath on linux
$contentpath="content/";

### Windows solution FOR NOW - does not work as web path!
#$contentpath="/xampp/htdocs/puma/content/";

$tmppath="imgtemp/";
$cachepath=$tmppath; # TODO warum zwei Namen?
$deletedpath="deleted/";
$uploadpath="upload/";
$contentprefix="content_";
$ignorelist="content/ignorelist.txt";
$viewmode="monthly"; # "all", "monthly"

#image config
$useshowimg=true; #true / false
#$contrastmode = "none"; # "middle" / "maxcontrast" / "none"
$usefilecache = true; #true / false

# for contrastmode maxcontrast:
$blackpercent = 0.01; // x % of pixels will be mapped to black
$whitepercent = 0.01; // x % of pixels will be mapped to white

# for contrastmode middle:
$targetmiddle = 128;

# debugging contrast
$debuglightcount = false;
$debuglightmap = false;


?>