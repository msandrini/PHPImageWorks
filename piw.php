<?
include "piw.class.php";

if (@$_GET["image"]){
	if (is_file($_GET["image"])){
		$p = new PIW(strip_tags($_GET["image"]));
	} else {
		die("Error: Image file not found");
	}
} else {
	die("Error: no image specified");
}

if (@$_GET["colorlayer"]){
	$cl = ExtendColorHex("#".strip_tags($_GET["colorlayer"]));
	$bm = strip_tags(@$_GET["blendingmode"]);
		if (!$bm) $bm = "normal";
	$op = strip_tags(@$_GET["opacity"]);
		if (!$op || !is_numeric($op)) $op = 100;
	
	$p->registerFilter("colorlayer",$cl,$bm,$op);
}

$p->debug = @$_GET["debug"];

if (@$_GET["w"] && @$_GET["h"] && is_numeric(@$_GET["w"]) && is_numeric(@$_GET["h"])){
	$p->outputResampled(strip_tags($_GET["w"]),strip_tags($_GET["h"]));
} else {
	$p->outputFinal();
}

?>