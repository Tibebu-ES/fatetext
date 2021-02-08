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
$datapath = $GLOBALS['FILESDIR'];
//$files = scandir($datapath);

$file_path_arr = array(1 => '/thesuzy/suzymem.txt',
                       2 => '/thesuzy/theshow.txt',
                       3 => '/thesuzy/themems.txt');

$sql = 'TRUNCATE TABLE chests';
queryf($sql);

$sql = 'TRUNCATE TABLE toks';
queryf($sql);

foreach ($file_path_arr as $book_id => $file_path) {

  $text = file_get_contents($datapath . $file_path);

  echo $file_path . ' len: ' . strlen($text);
  echo "\n";

  $lines = util_split("\n", $text);
  $chests = array();

  $cleanchars = ' ~`#{}\!\"\$\%\&\'\(\)\,\-\.\/\:\;\<\=\>\?\@';
  $cleanchars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ\[\]\_';
  $cleanchars .= 'abcdefghijklmnopqrstuvwxyz0123456789';

  $charcounts = array();
  $cclen = strlen($cleanchars);
  for ($i = 0; $i < $cclen; $i++) {
    $charcounts[$cleanchars[$i]] = true;
  }

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

    $line = $prevline . ' ' . $line;
    $prevline = '';
    if ($linelen < MIN_LINE_LEN) {
    	$prevline = $line;
      continue;
    }

    /*if ($line != utf8_encode($line)) {
      echo $line . "\n";
      echo utf8_encode($line) . "\n\n";
    }*/

    $cleanline = '';
    $linelen = strlen($line);
    for ($j = 0; $j < $linelen; $j++) {
      if (isset($charcounts[$line[$j]])) {
        $cleanline .= $line[$j];
      }
    }

    $chests []= utf8_encode($cleanline);
    $i++;
  }

  util_assert($i == count($chests));
  echo 'found ' . $i . ' chests' . "\n";

  $sql = 'INSERT INTO chests (datastr, bookid)';
  $sql .= ' VALUES (%s, %d)';

  $toksarr = array();
  $i = 0;
  foreach ($chests as $datastr) {
    queryf($sql, $datastr, $book_id);
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

  $sql = 'INSERT INTO toks (tokstr, chestidstr, bookid)';
  $sql .= ' VALUES (%s, %s, %d)';

  $i = 0;
  foreach ($toksarr as $tok => $lids) {
    $tripidstr = implode(' ', array_keys($lids));

if ($tok == 'misunderstanding') {
p($tok);
p($lids);
  continue;
}

    queryf($sql, $tok, $tripidstr, $book_id);
    $i++;

    if ($i % REPORT_MOD == 0) {
      echo "inserted $i toks into the db\n";
    }
  }

}

$elapsed = time() - $starttime;
echo "DONE in $elapsed seconds\n\n";
