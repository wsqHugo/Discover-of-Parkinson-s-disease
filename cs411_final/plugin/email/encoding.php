<?php

function encode_string($string)
{
	$key = 3;
	$length = strlen($string);
	
	$encoded_string = "";
	$output_string = "";
	for($i=0;$i<$length; $i++)
	{
		$encoded_string = $encoded_string.dechex(ord($string[$i]));
		$output_string = $output_string."  ";
	}
	
	$length = 2*$length;
	$pos = -1;
	for($i = 0; $i<$length; $i++)
	{
		for($j = 0; $j<$key; $j++)
		{
			$pos++;
			if($pos>=$length) $pos -= $length;
			while($output_string[$pos]!=" ")
			{
				$pos++;
				if($pos>=$length) $pos -= $length;
			}
		}
		$output_string[$pos] = $encoded_string[$i];
	}
	
	return $output_string;
}

?>