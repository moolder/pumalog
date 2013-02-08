<?
require_once("include_login.php");

function getCurrMonth(){
	return date("Y-m");
}

function getNextMonth($showmonth){
	if (!preg_match("/^(\d{4,4})-(\d{2,2})$/", $showmonth, $OUT))
		die("Fehlerhafte Monatsangabe");
	$showmonth_year = $OUT[1];
	$showmonth_month = $OUT[2];
	$nextmonth_month = $showmonth_month+1;
	$nextmonth_year = $showmonth_year;
	if ($nextmonth_month > 12){
		$nextmonth_month = "01";
		$nextmonth_year++;
	}
	if (strlen($nextmonth_month) == 1)
		$nextmonth_month = "0".$nextmonth_month;
	return $nextmonth_year."-".$nextmonth_month;
}

function getPrevMonth($showmonth){
	if (!preg_match("/^(\d{4,4})-(\d{2,2})$/", $showmonth, $OUT))
		die("Fehlerhafte Monatsangabe");
	$showmonth_year = $OUT[1];
	$showmonth_month = $OUT[2];
	$prevmonth_month = $showmonth_month-1;
	$prevmonth_year = $showmonth_year;
	if ($prevmonth_month < 1){
		$prevmonth_month = "12";
		$prevmonth_year--;
	}
	if (strlen($prevmonth_month) == 1)
		$prevmonth_month = "0".$prevmonth_month;
	return $prevmonth_year."-".$prevmonth_month;
}

function checkMonthFormat($showmonth){
	return preg_match("/^(\d{4,4})-(\d{2,2})$/", $showmonth);
}

function getTextualMonth($showmonth, $smallmonth = false){
	if (!preg_match("/^(\d{4,4})-(\d{2,2})$/", $showmonth, $OUT))
		die("Fehlerhafte Monatsangabe");
	$showmonth_month = $OUT[2];
	$showmonth_year = $OUT[1];
	
	if ($smallmonth == true) {
		$monthnames["01"] = "Jan.";
		$monthnames["02"] = "Feb.";
		$monthnames["03"] = "M&auml;rz";
		$monthnames["04"] = "Apr.";
		$monthnames["05"] = "Mai";
		$monthnames["06"] = "Juni";
		$monthnames["07"] = "Juli";
		$monthnames["08"] = "Aug.";
		$monthnames["09"] = "Sep.";
		$monthnames["10"] = "Okt.";
		$monthnames["11"] = "Nov.";
		$monthnames["12"] = "Dez.";
	} else {
		$monthnames["01"] = "Januar";
		$monthnames["02"] = "Februar";
		$monthnames["03"] = "M&auml;rz";
		$monthnames["04"] = "April";
		$monthnames["05"] = "Mai";
		$monthnames["06"] = "Juni";
		$monthnames["07"] = "Juli";
		$monthnames["08"] = "August";
		$monthnames["09"] = "September";
		$monthnames["10"] = "Oktober";
		$monthnames["11"] = "November";
		$monthnames["12"] = "Dezember";
	}
	
	$monthname = $monthnames[$showmonth_month];
	
	if (!isset($monthname))
		die("unbekannter Monatsname!");
	
	return $monthname." ".$showmonth_year;
}

function getMonthFromTimestamp($timestamp){
	$m = substr($timestamp, 0, 7);
	if (!checkMonthFormat($m)) return false;
	return $m;
}
?>