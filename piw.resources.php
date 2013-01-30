<?

function ExtendColorHex($color){
	$pattern = "/([0-9A-Fa-f])([0-9A-Fa-f])([0-9A-Fa-f])\b/";
	if (preg_match($pattern,$color))
		return preg_replace($pattern,"$1$1$2$2$3$3",$color);
	else 
		return $color;
}

class PIWFilter{

	public $imageres = false;
	public $colorslayer = array(0,0,0);
	public $alpha = 100;
	public $blendmode = "normal";
	
	public function __construct($img,$colorslayer,$alpha,$blendmode){
		$this->imageres = $img;
		$this->colorslayer = $colorslayer;
		$this->alpha = $alpha;
		$this->blendmode = $blendmode;
	}

	
	// COMPLIMENTARY FILTER FUNCTIONS
		
	private function multiply($imgcolors,$lcolors){
		$returnval = array();
		$counter = 0;
		foreach ($imgcolors as $x=>&$c){
			if ($x!="alpha"){
				$offset = ($this->alpha*.01*255)-($this->alpha*.01*$lcolors[$counter]);
				$newcolor = $c-$offset;
				$returnval[] = ($newcolor < 0)? 0 : $newcolor;
				$counter++;
			}
		}
		
		return $returnval;
	}
	
	private function screen($imgcolors,$lcolors){
		$returnval = array();
		$counter = 0;
		foreach ($imgcolors as $x=>&$c){
			if ($x!="alpha"){
				$newcolor = $c + ($this->alpha*.01*$lcolors[$counter]);
				$returnval[] = ($newcolor > 255)? 255 : $newcolor;
				$counter++;
			}
		}
		
		return $returnval;
	}
	
	private function iflighter($imgcolors,$lcolors){
		$returnval = array();
		$counter = 0;
		foreach ($imgcolors as $x=>&$c){
			if ($x!="alpha"){
				$returnval[] = ($lcolors[$counter] > $c)? $lcolors[$counter] : $c;
				$counter++;
			}
		}
		
		return $returnval;
	}
	
	private function ifdarker($imgcolors,$lcolors){
		$returnval = array();
		$counter = 0;
		foreach ($imgcolors as $x=>&$c){
			if ($x!="alpha"){
				$returnval[] = ($lcolors[$counter] < $c)? $lcolors[$counter] : $c;
				$counter++;
			}
		}
		
		return $returnval;
	}
	
	private function softoverlay($imgcolors,$lcolors){
		$returnval = array();
		$counter = 0;
		foreach ($imgcolors as $x=>&$c){
			if ($x!="alpha"){
				$newcolor = (($lcolors[$counter]*$this->alpha*.01)+$c)/(1+($this->alpha*.01));
				$returnval[] = (int)$newcolor;
				$counter++;
			}
		}
		
		return $returnval;
	}
	
	private function hardlight($imgcolors,$lcolors){
		
		$returnval = array();
		$counter = 0;
		foreach ($imgcolors as $x=>&$c){
			if ($x!="alpha"){
				if ($lcolors[$counter]<127){
					$offset = ($this->alpha*.01*255)-($this->alpha*.01*$lcolors[$counter]);
					$newcolor = $c - $offset;
					if ($newcolor < 0 ) $newcolor = 0;
				} elseif ($lcolors[$counter]>127){
					$newcolor = $c + ($this->alpha*.01*$lcolors[$counter]);
					if ($newcolor > 255) $newcolor = 255;
				} else
					$newcolor = $c;
					
				$returnval[] = ($newcolor < 0)? 0 : $newcolor;
				$counter++;
			}
		}
		
		return $returnval;
	}
	
	private function normal($imgcolors,$lcolors){
		$returnval = array();
		$counter = 0;
		foreach ($imgcolors as $x=>&$c){
			if ($x!="alpha"){
				$newcolor = ($lcolors[$counter]*$this->alpha*.01) + ($c*(1-($this->alpha*.01)));
				$returnval[] = (int)$newcolor;
				$counter++;
			}
		}
		
		return $returnval;
	}

	// MAIN FUNCTION
	
	public function ProcessColorLayer(){
	
		$src = $this->imageres;
		$colorslayer = $this->colorslayer;
		$alpha = $this->alpha;
	   
		$sx = imagesx($src);
		$sy = imagesy($src);
		$srcback = imageCreateTrueColor($sx, $sy);
		imageCopy($srcback, $src, 0, 0, 0, 0, $sx, $sy);
		   
		for ($y=0; $y<$sy; ++$y){
			for($x=0; $x<$sx; ++$x){
				$new_r = $new_g = $new_b = 0;

				$rgb = imagecolorat($srcback, $x, $y);
				$imgcolors = imagecolorsforindex($srcback, $rgb);

				$colorsfiltered = $this->{$this->blendmode}($imgcolors,$colorslayer);

				$new_pxl = ImageColorAllocate($src, (int)$colorsfiltered[0], (int)$colorsfiltered[1], (int)$colorsfiltered[2]);
				if ($new_pxl == -1) {
					$new_pxl = ImageColorClosest($src, (int)$colorsfiltered[0], (int)$colorsfiltered[1], (int)$colorsfiltered[2]);
				}
				if (($y >= 0) && ($y < $sy)) {
					imagesetpixel($src, $x, $y, $new_pxl);
				}
			}
		}
		imagedestroy($srcback);
		return $src;
	}
	
	
	
}
?>