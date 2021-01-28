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

$starttime = time();
$datapath = '../data';
//$files = scandir($datapath);

$filepath = $datapath . '/thebible/pgbible.txt';
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
