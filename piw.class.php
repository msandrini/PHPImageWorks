<?
require "piw.resources.php";

class PIW{

	public $swapdir = "swapimg";
	public $output = "png";
	public $jpegquality = "high";
	public $stretch = false;
	public $noswapmode = false;
	public $debug = false;
	
	public $file = false;
	private $finalwidth = 0;
	private $finalheight = 0;
	public $filters = array();
	
	private $arqswap = "";
	private $swapchecked = false;
	
	public function __construct($file){
		include "piw.config.php";
		$this->swapdir = $swapdir;
		$this->output = $output;
		$this->jpegquality = $jpegquality;
		$this->noswapmode = $noswapmode;
		$this->debug = @$_GET["debug"];
		
		$this->file = $file;
		list($this->originalwidth, $this->originalheight) = @getimagesize($this->file);
		
		$this->extension = strtolower(@array_pop(explode(".",$this->file)));
		
		if (!is_file($this->file)){ // file not found
			$this->outputError("Error","File not found","File: $this->file");
			exit();
		}
		
		$this->arqswap = "$swapdir/".basename($file);
		
	}
	
	private function checkSwap(){
		
		/* CASE 1 - SWAP FILE FOUND */
		
		if (is_file($this->arqswap) && !$this->noswapmode){
			$this->writeHeader();
			print file_get_contents($this->arqswap);
			exit();
			
		/* CASE 2 - ORIGINAL IMAGE HAS THE SAME DIMENSIONS AS THE FINAL ONE - NO RESAMPLING */
		
		} else {
		
			$this->imageres = $this->openImage();		
			if (!$this->imageres) $this->outputError("Error","File format not recognized or not supported","Format: $this->extension");			
			
		}
		
		if ($this->filters) $this->doFilters();
		
		$this->swapchecked = true;
		
	}
	
	private function writeHeader(){
		if (!$this->debug) header("Content-type: image/$this->output");
	}
	
	public function registerFilter(){
		$this->filters[] = func_get_args();
		$swapstring = join("",func_get_args());
		$this->arqswap .= $swapstring;
	}
	
	public function doFilters(){
	
		foreach ($this->filters as $opt){
			switch($opt[0]){			
				case "imagelayer": // image=image, alpha=0...100, blendingmode=(add,multiply,screen,hardlight)
				
					// future implementation
				
				
				break; case "colorlayer": // color=#000000, alpha=0...100, blendingmode=(add,multiply,screen,hardlight)
				
					$color = $opt[1];
					if (isset($opt[2])) $blend = $opt[2]; else $blend="multiply";
					if (isset($opt[3])) $alpha = $opt[3]; else $alpha=100;
					
					list($r, $g, $b) = sscanf($color, '#%2x%2x%2x');
					
					$filter = new PIWFilter($this->imageres, array($r, $g, $b), $alpha, $blend);
					$filter->ProcessColorLayer();
					
				break; default:
					$this->outputError("Error","Unrecognized filter","Type: $opt[0]; Options: ".join(",",$opt));
				
			}
		}
		
	}
	
	public function outputResampled($w,$h){
		
		$this->arqswap .= "-resampl".$w."x".$h;
		$this->checkSwap();
	
		// same image size and no filters	
		if (($this->originalwidth == $this->finalwidth) && ($this->originalheight == $this->finalheight) && !$this->filters){
			$this->writeHeader();
			print file_get_contents($this->file);
			exit();
			
		}		
		
		$this->finalwidth = $fw = $w;
		$this->finalheight = $fh = $h;
				
		/* RESAMPLE */
		
		$ratio_img = $this->originalwidth/$this->originalheight;
		$ratio_final = $this->finalwidth/$this->finalheight;
		
		if ($this->stretch){
			if ($ratio_final <= $ratio_img) { 							//tall
				$tempheight = $this->finalheight/$ratio_img;
				$fy = $fx = 0;
			} else { 													//wide
				$tempwidth = $this->finalheight*$ratio_img;
				$fx = $fy = 0;
			}
		} else {
			if ($ratio_final <= $ratio_img) { 							//tall
				$fw = $this->finalheight*$ratio_img;
				$fx = floor(($w - $fw)/2);
				$fy = 0;
			} else { 													//wide
				$fh = $this->finalwidth/$ratio_img;
				$fy = floor(($h - $fh)/2);
				$fx = 0;
			}
		}
		
		$finalimage = imagecreatetruecolor($this->finalwidth, $this->finalheight);

		// generate resampled image
		imagecopyresampled($finalimage, $this->imageres, $fx, $fy, 0, 0, $fw, $fh, $this->originalwidth, $this->originalheight);

		$this->outputFinal($finalimage);

		
	}
	
	public function outputFinal($image=false){
	
		if (!$this->swapchecked) $this->checkSwap();
		
		$this->writeHeader();
		
		if (!$image) $image = $this->imageres;
	
		// transparent png or jpeg quality
		if ($this->output == "png"){
			imagealphablending($image, false);
			imagesavealpha($image, true);
		} else {
			$jpegqnum = array("minimum"=>10,"low"=>45,"med"=>70,"medium"=>70,"high"=>85,"maximum"=>100);
			$jpegq = $jpegqnum[$this->jpegquality];
		}
		
		// output swap
		if (!$this->noswapmode){
			if ($this->output == "png"){
				imagepng($image, $this->arqswap, 9);
			} else {
				imagejpeg($image, $this->arqswap, $jpegq);
			}
			chmod($this->arqswap,0666);
		}
		
		// output immediate
		if ($this->output == "png"){
			imagepng($image, null, 9);
		} else {
			imagejpeg($image, null, $jpegq);
		}
	
	}
	
	private function outputError($title,$text,$details){
		if ($this->finalwidth && $this->finalheight) 
			$im = imagecreate($this->finalwidth, $this->finalheight);
		else 
			$im = imagecreate($this->originalwidth, $this->originalheight);
		
		$bgcolor = imagecolorallocate($im, 255, 255, 255);
		$textcolor1 = imagecolorallocate($im, 128, 128, 128);
		$textcolor2 = imagecolorallocate($im, 40, 40, 40);
		imagestring($im, 5, 0, 0, $title, $textcolor1);
		imagestring($im, 3, 0, 12, $text, $textcolor2);
		imagestring($im, 1, 0, 25, $details, $textcolor1);
		
		$this->noswapmode = 1;
		$this->outputFinal($im);
	}
	
	private function openImage($file=false){

		if (!$file) $file = $this->file;
		
		$size = getimagesize($file);
		switch($size["mime"]){
			case "image/jpeg":
				$im = imagecreatefromjpeg($file); //jpeg file
			break; case "image/gif":
				$im = imagecreatefromgif($file); //gif file
			break; case "image/png":
				$im = imagecreatefrompng($file); //png file
			break; default:
				$im = false;
			break;
		}
		
		return $im;
		
	}
	
}