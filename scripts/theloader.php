<?php
include('../../scriptconfig.php');
include($GLOBALS['FATEPATH'] . '/fate.php');
//ini_set('memory_limit', '2GB');

define('MIN_LINE_LEN', 40);
define('MIN_TOK_LEN', 5);

$starttime = time();
$datapath = '../data';
//$files = scandir($datapath);

$filepath = $datapath . '/thebible/conbible.txt';

$text = file_get_contents($filepath);

echo $filepath . ' len: ' . strlen($text);
echo "\n";

$lines = util_split("\n", $text);
$strips = array();

$i = 0;
$prev = '';
$trip = array('', '', '');
foreach ($lines as $line) {
  $line = $prev . $line;
  $prev = '';
  $linelen = strlen($line);
  if (strlen($linelen) < 1) {
  	util_except("found empty line at i = $i");
  }
  if ($linelen < MIN_LINE_LEN) {
  	$prev = $line;
  	continue;
  }
  if ($trip[2] == '') {
  	if ($i > 0) {
  	  util_except("empty trip2 with i > 0");
  	}
  	$trip[2] = $line;
  	$i++;
  	continue;
  }
  if ($trip[1] == '') {
  	if ($trip[2] == '') {
  	  util_except("empty trip2 without empty trip1");
  	}
  	$trip[1] = $trip[2];
  	$trip[2] = $line;
  	if ($trip[0] == '') {
      if ($i > 1) {
      	util_except("empty trip0 with i > 1");
      }
      $i++;
  	  continue;
  	}
  }
  $trip[2] = $line;

  $trip1len = strlen($trip[1]);
  if (!isset($strips[$trip1len])) {
  	$strips[$trip1len] = array();
    //if ($trip1len > 100) {
    //  $stoks[$trip1len] []= $trip;    	
    //}
  }
  //makes a deep copy of trip by default
  $strips[$trip1len] []= $trip;

  $trip[0] = $trip[1];
  $trip[1] = $trip[2];
  $i++;
  //echo "inserted $i tokens into the db\n";
}

krsort($strips);
$toksarr = array();

$sql = 'TRUNCATE TABLE trips';
queryf($sql);

$sql = 'INSERT INTO trips (gem1, gem2, gem3)';
$sql .= ' VALUES (%s, %s, %s)';
$i = 0;
foreach ($strips as $triparr) {
  foreach ($triparr as $trip) {
    queryf($sql, $trip[0], $trip[1], $trip[2]);
    $lid = last_insert_id();
    $i++;
    echo "inserted $i trips into the db\n";

  	$toks = explode(" ", $trip[1]);
  	foreach ($toks as $tok) {
      $toklen = strlen($tok);
      $trimtok = '';
      $started = false;
      for ($j = 0; $j < $toklen; $j++) {
        if (ctype_alpha($tok[$j])) {
          $trimtok .= $tok[$j];
          $started = true;
        } else {
          if ($started) {
            break;
          }
        }
      }
      $trimtoklen = strlen($trimtok);
      if ($trimtoklen >= MIN_TOK_LEN) {
        if (!isset($toksarr[$trimtok])) {
          $toksarr[$trimtok] = array();
        }
        $toksarr[$trimtok][$lid] = true;
      }
  	}
  }
}

$temparr = array();

$sql = 'TRUNCATE TABLE toks';
queryf($sql);

$sql = 'INSERT INTO toks (tokstr, tripidstr)';
$sql .= ' VALUES (%s, %s)';
$i = 0;
foreach ($toksarr as $tok => $lids) {
  $tripidstr = '';
  $first = true;
  foreach ($lids as $lid => $flag) {
  	if ($first) {
  	  $first = false;
  	} else {
  	  $tripidstr .= ' ';
  	}
  	$tripidstr .= $lid;
  }

  queryf($sql, $tok, $tripidstr);
  $i++;
  echo "inserted $i toks into the db\n";

  $temparr[$tok] = $tripidstr;
}

$elapsed = time() - $starttime;
echo "DONE in $elapsed seconds\n\n";
