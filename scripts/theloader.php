<?php /* MIT License

Copyright (c) 2021 Todd Perry

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. */

include('../../scriptconfig.php');
include($GLOBALS['FATEPATH'] . '/fate.php');
//ini_set('memory_limit', '2GB');

$GLOBALS['DBVERBOSE'] = false;

define('MIN_LINE_LEN', 40);
define('MIN_TOK_LEN', 5);
define('BIBLE_BOOK_ID', 1);
define('REPORT_MOD', 1000);

$starttime = time();
$datapath = '../data';
//$files = scandir($datapath);

$filepath = $datapath . '/library/thebible.txt';

$text = file_get_contents($filepath);

echo $filepath . ' len: ' . strlen($text);
echo "\n";

$lines = util_split("\n", $text);
$chests = array();

$i = 0;
$prevline = '';
$trip = array('', '', '');
foreach ($lines as $line) {
  $linelen = strlen($line);
  if (strlen($linelen) < 1) {
    util_except("found empty line at i = $i");
  }

  if ($line[0] == '_') {
    echo 'Skipping: ' . $line . "\n";
    continue;
  }

  $line = $prevline . $line;
  $prevline = '';
  if ($linelen < MIN_LINE_LEN) {
  	$prevline = $line;
    continue;
  }

  $chests []= $line;
  $i++;
}

util_assert($i == count($chests));
echo 'found ' . $i . ' chests' . "\n";


$sql = 'TRUNCATE TABLE chests';
queryf($sql);

$sql = 'INSERT INTO chests (datastr, bookid)';
$sql .= ' VALUES (%s, %d)';

$toksarr = array();
$i = 0;
foreach ($chests as $datastr) {
  queryf($sql, $datastr, BIBLE_BOOK_ID);
  $lid = last_insert_id();
  $i++;

	$toks = explode(" ", $datastr);
	foreach ($toks as $tok) {
    $toklen = strlen($tok);

    $trimtok = '';
    $started = false;
    for ($j = 0; $j < $toklen; $j++) {
      if (ctype_alpha($tok[$j])) {
        //accumulate characters until a non-alphabet char is seen
        $trimtok .= $tok[$j];
        $started = true;
      } else {
        if ($started) {
          break;
        }
      }
    }

    $trimtok = strtolower($trimtok);
    $trimtoklen = strlen($trimtok);
    if ($trimtoklen >= MIN_TOK_LEN) {
      if (!isset($toksarr[$trimtok])) {
        $toksarr[$trimtok] = array();
      }
      $toksarr[$trimtok][$lid] = true;
    }

	} //end foreach toks

  if ($i % REPORT_MOD == 0) {
    echo "inserted $i chests into the db\n";
  }
} //end foreach chests


$sql = 'TRUNCATE TABLE toks';
queryf($sql);

$sql = 'INSERT INTO toks (tokstr, chestidstr)';
$sql .= ' VALUES (%s, %s)';

$i = 0;
foreach ($toksarr as $tok => $lids) {
  $tripidstr = implode(' ', array_keys($lids));
  queryf($sql, $tok, $tripidstr);
  $i++;

  if ($i % REPORT_MOD == 0) {
    echo "inserted $i toks into the db\n";
  }
}

$elapsed = time() - $starttime;
echo "DONE in $elapsed seconds\n\n";
