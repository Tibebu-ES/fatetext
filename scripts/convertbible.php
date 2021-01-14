<?php
include('../../scriptconfig.php');
include($GLOBALS['FATEPATH'] . '/fate.php');
//ini_set('memory_limit', '2GB');

$starttime = time();
$datapath = '../data';
//$files = scandir($datapath);

$filepath = $datapath . '/thebible/kjbible.txt';
$text = file_get_contents($filepath);

$textlen = strlen($text);
$prevch = $text[0];
for ($i = 1; $i < $textlen; $i++) {
  if ($text[$i] == "\r" || $text[$i] == "\t") {
  	echo 'found \r or \t at ' . $i;
  	exit(0);
  }
  if ($text[$i] == "\n") {
    if ($prevch == "\n") {
      //echo '\n' . "\n";
      echo "\n";
    } else {
      //echo ' \n';
      echo ' ';
    }
  } else {
  	echo $text[$i];
  }
  $prevch = $text[$i];
}
