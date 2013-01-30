<?
include "piw.class.php";

// instantiates new PIW
$p = new PIW("image.jpg");

// Puts a filter (optional)
$p->registerFilter("colorlayer","#cc0000","hardlight",100);

// outputs...
// resampled
//$p->outputResampled(120,100);
// or not
$p->outputFinal();

?>