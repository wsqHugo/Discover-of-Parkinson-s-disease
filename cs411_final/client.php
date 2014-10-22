<?php
$src="media/parkinson2_2.mp4";
$waveform_text="media/test.txt";

print exec("/usr/bin/python /Applications/XAMPP/xamppfiles/htdocs/shangquan/cs411_final/eulerian-magnification/client.py ".$src." ".$waveform_text);
?>
