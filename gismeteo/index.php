<?php
$city="weather-novosibirsk-4690";
include 'simple_html_dom.php';
$out="";
list($date,$out)=json_decode(@file_get_contents("cache.txt"));
//echo $date."\n";
if($date>(time()-300))
{
	//echo "from cache";
}
else
{
	  //echo "from server";
		$out="<head>".file_get_contents('css.html')."</head>\n";
		
		$html=file_get_contents('https://www.gismeteo.ru/'.$city.'/now/');
		$html = str_get_html($html);
		
		$ret2=$html->find('.tabs',0);
		
		//замена ссылкок на табуляции
		$ret2->find("div.tooltip",0)->onclick="opentab(event, '_left')";
		$ret2->find("a.tooltip",0)->href=null;
		$ret2->find("a.tooltip",0)->onclick="opentab(event, '_center')";
		$ret2->find("a.tooltip",1)->href=null;
		$ret2->find("a.tooltip",1)->onclick="opentab(event, '_right')";
		
		$ret=$html->find('.forecast_wrap',0);
		$out.="<section class='content'><div class='content_wrap'><div class='flexbox clearfix'><div class='main'><div class='column-wrap'><div class='__frame_sm'>\n";
		$out.="<div class='forecast_frame forecast_now' data-items='9'>\n";
		$out.="<div class='tabs _left' id='tabs'>\n";
		$out.=$ret2->innertext;
		$out.="</div>\n";
		$out.="\n<div id='_left' class='forecast_wrap horizontal tabcontent' style='display:block;'>";
		$out.=$ret->innertext; 
		$out.="</div>\n";
		
		$html=file_get_contents('https://www.gismeteo.ru/'.$city.'/');
		$html = str_get_html($html);
		$out.="<div id='_center' class='forecast_frame tabcontent' data-items='8'>";
		$ret=$html->find('.forecast_frame',0);
		//затираем лишнее
		$ret->find("div._center",0)->outertext="";
		$ret->find("div.widget__type_anchor",0)->outertext="";
		$ret->find("div.widget__row_anchor",0)->outertext="";
		$out.= $ret->innertext; 
		

		$html=file_get_contents('https://www.gismeteo.ru/'.$city.'/tomorrow/');
		$html = str_get_html($html);
		$out.="</div>\n<div id='tomorrow' class='forecast_frame tabcontent' data-items='8'>\n";
		$ret=$html->find('.forecast_frame',0);
		//затираем лишнее
		$ret->find("div._center",0)->outertext=null;
		$ret->find("div.widget__type_anchor",0)->outertext="";
		$ret->find("div.widget__row_anchor",0)->outertext="";
		$out.=$ret->innertext; 
		
		

		$html=file_get_contents('https://www.gismeteo.ru/'.$city.'/10-days/');
		$html = str_get_html($html);
		$out.="</div>\n<div id='_right' class='forecast_frame tabcontent'  data-items>\n";
		$ret=$html->find('.forecast_frame',0);
		//затираем лишнее
		$ret->find("div.widget__type_anchor",0)->outertext="";
		$ret->find("div.widget__row_anchor",0)->outertext="";
		
		$out.=$ret->innertext; 
		$out.="</div>";
		$date=time();
		file_put_contents("cache.txt" , json_encode(array($date,$out)));
}
echo $out;

?>