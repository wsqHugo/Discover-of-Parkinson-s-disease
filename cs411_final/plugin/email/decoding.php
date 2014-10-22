<?php

function decode_string($string)
{
	$key = 3;
	$length = strlen($string);
	
	$pos = -1;
	$decoded_string = "";
	$output_string = "";
	for($i=0;$i<$length; $i++)
	{
		for($j = 0; $j<$key; $j++)
		{
			$pos++;
			if($pos>=$length) $pos -= $length;
			while($string[$pos]==" ")
			{
				$pos++;
				if($pos>=$length) $pos -= $length;
			}
		}
		
		$decoded_string = $decoded_string.$string[$pos];
		$string[$pos] = " ";
	}
	
	for($i=0;$i<$length; $i+=2)
	{
		$char = $decoded_string[$i] . $decoded_string[$i+1];
		$output_string = $output_string.chr(hexdec($char));
	}
	
	return $output_string;
}

?>