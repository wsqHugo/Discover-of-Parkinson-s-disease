<?php
// $insertstring - the string you want to insert
// $intostring - the string you want to insert it into
// $offset - the offset
function str_insert($insertstring, $intostring, $offset) {
	$part1 = substr($intostring, 0, $offset);
	$part2 = substr($intostring, $offset);
 
	$part1 = $part1 . $insertstring;
	$whole = $part1 . $part2;
	return $whole;
}

//make an abstract for an article

function text_Abstract($text,$length)
{ 
	$size = mb_strlen($text,"utf8");
	$output ="";
	$j = 0;
	for($i=0;$i<$size;++$i)
	{
		$chr = mb_substr($text, $i, 1, 'utf-8');
		if($chr=="<")
		{
			if($i>=$size-1) continue;
			while(mb_substr($text, ++$i, 1, 'utf-8')!=">")
				continue;
		}
		else 
		{
			$output = $output.mb_substr($text, $i, 1, 'utf-8');
			++$j;
			if($j>=$length)
				break;
		}
	}
	
	if($size>$length)
		$output .="...";
		
	return $output;
};
?>