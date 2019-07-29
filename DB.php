<?php
$host='localhost';
$username='root';
$password='';
$database='periloscope';
date_default_timezone_set('Asia/Kolkata');


$round1_start = strtotime("05 April 2019 23 hours 50 minutes 00 seconds") - strtotime('now');
$round1 = strtotime("06 April 2019 23 hours 50 minutes 00 seconds") - strtotime('now');
$round2_start = strtotime("14 December 2018 1 hours 55 minutes 00 seconds") - strtotime('now');
$round2 = strtotime("14 December 2018 2 hours 00 minutes 00 seconds") - strtotime('now');
$round3_start = strtotime("13 December 2018 2 hours 1 minutes 00 seconds") -strtotime('now');
$round3 = strtotime("14 December 2018 12 hours 07 minutes 00 seconds") - strtotime('now');
$end = strtotime("14 December 2018 12 hours 14 minutes 00 seconds") - strtotime('now');
?>