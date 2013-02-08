<?
require_once("function_viewhelper.php");
?>
body {
	margin-top:0px;
	margin-left:20px;
	font-family:Verdana, sans-serif;
	font-size:<? if (issmallscreen()){ ?> 24pt <? } else { ?> 12pt <? } ?>;
	background:url(hintergrund-oben.png) repeat-x;
}

.bodyshowimage {
	background: black;
}

.imgshowimage {
	#float:left;
	text-align:center;
	padding-right:10px;
	margin-left:-20px;
	margin-bottom:0px;
	padding-bottom:0px;
}

.navishowimage {
	position:absolute;
	top:0px;
	right:0px;
	padding:10px;
	line-height:140%;
	#background-color:black;
	text-align:right;
	#font-size:2.2em;
}

.navishowimage a {
	text-decoration:none; 
	color:white;
}

.navishowimageios {
	position:absolute;
	top:0px;
	right:0px;
	padding:10px;
	line-height:170%;
	#background-color:red;
	text-align:right;
	font-size:1em;
	opacity:0.7;
}

.navishowimageios a {
	text-decoration:none; 
	color:white;
}

.navispanshowimage {
	padding:2px;
	background:black;
}


h1 {
	font-size:<? if (issmallscreen()){ ?> 28pt <? } else { ?> 16pt <? } ?>;
	color:#7da359;
	margin-top:20pt;
	margin-right:0pt;
	margin-bottom:10pt;
	margin-left:0pt;
}

h2 {
	font-size:<? if (issmallscreen()){ ?> 24pt <? } else { ?> 12pt <? } ?>;
	margin-top:2pt;
	margin-right:0pt;
	margin-bottom:10pt;
	margin-left:0pt;
}

.eintragsdatum {
	<? if (issmallscreen()){ ?>margin-bottom:60px;<? } ?>
	<? if (isipad()){ ?>margin-bottom:40px;<? } ?>
}

.breakimg {
	margin-left:-20px;
	margin-top:20pt;
	margin-bottom:0pt;
}

.eintrag {
	width:980px;
}

.imgcontainer {
	margin-left:-20px;
	padding:5px;
	background-color:#e0e6c1;
	text-align:center;
}

.imgplaceholder{
	#background-color:red;
	width:480px;
	height:480px;
	padding:0px;
	margin:2px;
	text-align:center;
	vertical-align:middle;
	float:left;
}

.imgplaceholderclear{
	clear:left;
	width:0px;
	height:0px;
	padding:0px;
	margin:0px;
}

.flvcontainer {
	margin-left:-20px;
	padding:0px;
	background-color:#e0e6c1;
	text-align:center;
}

.infotext {
	margin-left:-20px;
	width:1000px;
	margin-top:20pt;
	background-color:#e0e6c1;
	color:darkred;
	font-weight:bold;
	text-align:center;
	padding-top:4pt;
	padding-bottom:4pt;
}

.logininfo {
	position:absolute;
	right:10pt;
	top:10pt;
}

.monthselect {
	width:960px;
	padding-top:20px;
	#background-color:#e0e6c1;
	text-align:center;
}

.main {
	float:left;
	#background-color:red;
}

.rightnav {
	width:250px;
	margin-top:200px;
	margin-left:20px;
	padding:4px;
	float:left;
	background-color:#e5e6e9;
	text-align:left;
}

.filename {
	font:monospace;
}