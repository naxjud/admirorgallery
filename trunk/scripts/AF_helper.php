<?php

// Igor Kekeljevic, 2010.






class AF_helper {

    
    var $params =  array();
    var $staticParams = array();   

    function AF_createImg($ID) {
         return JURI::root()."plugins/content/admirorframes/scripts/thumbnailer.php?src_file=".$this->params['templates_BASE'].$this->params['template'].DS.$ID.".png&bgcolor=".$this->params['bgcolor']."&colorize=".$this->params['colorize']."&ratio=".$this->params['ratio'];
    }
        
    //Gets the atributes value by name, else returns false
    protected function AF_getAttribute($attrib, $tag, $default) {
        //get attribute from html tag
        $re = '/' . preg_quote($attrib) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
        if (preg_match($re, $tag, $match)) {
            return urldecode($match[2]);
        }
        return $default;
    }

    function AF_createFrame($source_html, $matchValue) { 
    
        // ---------------------------------------------------------- GET PARAMS
		$af_width_inline = $this->AF_getAttribute("width",$matchValue,$this->staticParams['width']);
		if ($af_width_inline)
			$this->params['width'] = $af_width_inline;
		
		$af_bgcolor_inline = $this->AF_getAttribute("bgcolor",$matchValue,$this->staticParams['bgcolor']);
		if ($af_bgcolor_inline)
			$this->params['bgcolor'] = $af_bgcolor_inline;
		
		$af_colorize_inline = $this->AF_getAttribute("colorize",$matchValue,$this->staticParams['colorize']);
		if ($af_colorize_inline)
			$this->params['colorize'] = $af_colorize_inline;
		
		$af_height_inline = $this->AF_getAttribute("height",$matchValue,$this->staticParams['height']);
		if ($af_height_inline)
			$this->params['height'] = $af_height_inline;

		$af_ratio_inline = $this->AF_getAttribute("ratio",$matchValue,$this->staticParams['ratio']);
		if ($af_ratio_inline)
			$this->params['ratio'] = $af_ratio_inline;
		
		$af_horiAlign_inline = $this->AF_getAttribute("horiAlign",$matchValue,$this->staticParams['horiAlign']);
		if ($af_horiAlign_inline)
			$this->params['horiAlign'] = $af_horiAlign_inline;
		
		$af_vertAlign_inline = $this->AF_getAttribute("vertAlign",$matchValue,$this->staticParams['vertAlign']);
		if ($af_vertAlign_inline)
			$this->params['vertAlign'] = $af_vertAlign_inline;
		
		$af_template_inline = $this->AF_getAttribute("template",$matchValue,$this->staticParams['template']);
		if ($af_template_inline)
			$this->params['template'] = $af_template_inline;
		
		$af_float_inline = $this->AF_getAttribute("float",$matchValue,$this->staticParams['float']);
		if ($af_float_inline)
			$this->params['float'] = $af_float_inline;
		
		$af_margin_inline = $this->AF_getAttribute("margin",$matchValue,$this->staticParams['margin']);
		if ($af_margin_inline)
			$this->params['margin'] = $af_margin_inline;
			
		$af_padding_inline = $this->AF_getAttribute("padding",$matchValue,$this->staticParams['padding']);
		if ($af_padding_inline)
			$this->params['padding'] = $af_padding_inline;
			
			
		// -------------------------------------------------------- CONVERT RATIO
		$this->params['ratio'] = $this->params['ratio']/100;
			
			
		// -------------------------------------------------------- CREATE TABLE
		$content="<!-- ADMIROR FRAMES -->";         
        $content.='<table border="0" cellspacing="0" cellpadding="0" class="AF_'.$this->params['template'].'" >'."\n";
        $content.='<tbody>'."\n";
        $content.='<tr><td class="TL">&nbsp;</td><td class="T">&nbsp;</td><td class="TR">&nbsp;</td></tr>'."\n";      
        $content.='<tr><td class="L">&nbsp;</td><td class="C">'.$source_html.'</td><td class="R">&nbsp;</td></tr>'."\n";
        $content.='<tr><td class="BL">&nbsp;</td><td class="B">&nbsp;</td><td class="BR">&nbsp;</td></tr></tbody></table>'."\n";
        
        
        
        // ----------------------------------------------------------------- CSS
        list($TL_width, $TL_height, $TL_type, $TL_attr) = getimagesize($this->params['templates_BASE'].$this->params['template'].DS.'TL.png');
        list($BR_width, $BR_height, $BR_type, $BR_attr) = getimagesize($this->params['templates_BASE'].$this->params['template'].DS.'BR.png');
        $TL_width = round($TL_width * $this->params['ratio']);
        $TL_height = round($TL_height * $this->params['ratio']);
        if($TL_width<4)$TL_width=4;
        if($TL_height<4)$dTL_height=4;
        $BR_width = round($BR_width * $this->params['ratio']);
        $BR_height = round($BR_height * $this->params['ratio']);
        if($BR_width<4)$BR_width=4;
        if($BR_height<4)$BR_height=4;
        
        $content.='
        <style type="text/css">
        ';

        $content.= 'table.AF_'.$this->params['template'].'{'."\n";
            if(!empty($this->params['float']))$content.='float:'.$this->params['float'].';'."\n";
            if(!empty($this->params['margin']))$content.='margin:'.$this->params['margin'].';'."\n";
            if(!empty($this->params['width']))$content.='width:'.$this->params['width'].';'."\n";
            if(!empty($this->params['height']))$content.='height:'.$this->params['height'].';'."\n";
        $content.='}'."\n";

        $content.=' 
        table.AF_'.$this->params['template'].', table.AF_'.$this->params['template'].' tr, table.AF_'.$this->params['template'].' td{border:none;margin:0;padding:0;}
        table.AF_'.$this->params['template'].' .TL{
            background-image:url('.$this->AF_createImg("TL").');
            width:'.$TL_width.'px;
            height:'.$TL_height.'px;
            font-size:1px;
            }
        table.AF_'.$this->params['template'].' .T{
            background-image:url('.$this->AF_createImg("T").');
            font-size:1px;
            }
        table.AF_'.$this->params['template'].' .TR{
            background-image:url('.$this->AF_createImg("TR").');
            background-position:right top;
            font-size:1px;
            }
        table.AF_'.$this->params['template'].' .L{
            background-image:url('.$this->AF_createImg("L").');
            font-size:1px;
            }
        ';

        $content.='table.AF_'.$this->params['template'].' .C{
            background-image:url('.$this->AF_createImg("C").');
            '."\n";
            if(!empty($this->params['padding']))$content.='padding:'.$this->params['padding'].';'."\n";
            if(!empty($this->params['horiAlign']))$content.='text-align:'.$this->params['horiAlign'].';'."\n";
            if(!empty($this->params['vertAlign']))$content.='vertical-align:'.$this->params['vertAlign'].';'."\n";
        $content.='}'."\n";

        $content.='
        table.AF_'.$this->params['template'].' .R{
            background-image:url('.$this->AF_createImg("R").');
            background-position:right top;
            font-size:1px;
            }
        table.AF_'.$this->params['template'].' .BL{
            background-image:url('.$this->AF_createImg("BL").');
            font-size:1px;
            }
        table.AF_'.$this->params['template'].' .B{
            background-image:url('.$this->AF_createImg("B").');
            font-size:1px;
            }
        table.AF_'.$this->params['template'].' .BR{
            background-image:url('.$this->AF_createImg("BR").');
            background-position:right top;
            width:'.$BR_width.'px;
            height:'.$BR_height.'px;
            font-size:1px;
            }
        </style>
        ';    

        return $content;

    }
    
}





  
  
  

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

?>
