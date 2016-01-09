<?php
class MyImage_Core {
	
    public function __construct()
    {  
    }
    
    public static function thumbnail($maxw='',$maxh='',$src='')
    {
		$size = @getimagesize ($src);
		if($maxw<1){
			if($maxh<$size[1]){
				$newx = intval ($size[0] * ($maxh / $size[1]));
				$newy = intval ($maxh);
			} else {
				$newx = intval ($size[0]);
				$newy = intval ($size[1]);
			}
		} else if($maxh<1) {
			$newx = intval ($maxw);
			$newy = intval ($size[1] * ($maxw / $size[0]));
		} else {
			if(($size[0] >= $size[1]) && $size[0]>$maxw)
			{
				$newx = intval ($maxw);
				$newy = intval ($size[1] * ($maxw / $size[0]));
			}
			elseif(($size[0] < $size[1]) && $size[1]>$maxh)
			{
				$newx = intval ($size[0] * ($maxh / $size[1]));
				$newy = intval ($maxh);
			}
			elseif(($size[0] >= $size[1]) && $size[1]> $maxh)
			{
				$newx = intval (($maxh*$size[0])/$size[1]);
				$newy = intval ($maxh);
			}
			else
			{
				$newx = intval ($size[0]);
				$newy = intval ($size[1]);
			}
		}
		$arrDimension = array('width'=>$newx,'height'=>$newy);
		return $arrDimension;
    }
}
?>