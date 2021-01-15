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

define('CHAT_OPEN_FLAG', 'chatopen');
define('TEXT_AREA_FLAG', 'textarea');
define('FATE_SPLASH_FLAG', 'fatesplash');
define('AGREE_TOS_FLAG', 'agreetos');

define('USER_SEARCH_ROWS', 'searchrows');
define('USER_SEARCH_COLS', 'searchcols');

function mod_get_hall_art() {
  $sql = 'SELECT * FROM hallart ORDER BY artid DESC';
  $rs = queryf_all($sql);
  if (count($rs) == 0) {
    $rs = array(
array('artid' => '1', 'datestr' => '01_05_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/228',
      'sumstr' => 'The Aeneid by Virgil'),
array('artid' => '2', 'datestr' => '01_06_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/8438',
      'sumstr' => 'The Ethics of Aristotle by Aristotle'),
array('artid' => '3', 'datestr' => '01_07_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/14020',
      'sumstr' => 'The Works of Horace by Horace'),
array('artid' => '4', 'datestr' => '01_08_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/6130',
      'sumstr' => 'The Iliad by Homer'),
array('artid' => '5', 'datestr' => '01_09_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/10',
      'sumstr' => 'The King James Version of the Bible'),
array('artid' => '6', 'datestr' => '01_10_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/2680',
      'sumstr' => 'Meditations by Emperor of Rome Marcus Aurelius'),
array('artid' => '7', 'datestr' => '01_11_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/1727',
      'sumstr' => 'The Odyssey by Homer'),
array('artid' => '8', 'datestr' => '01_12_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/6762',
      'sumstr' => 'Politics: A Treatise on Government by Aristotle'),
array('artid' => '9', 'datestr' => '01_13_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/1497',
      'sumstr' => 'The Republic by Plato'),
array('artid' => '10', 'datestr' => '01_14_21',
      'arturl' => 'http://www.gutenberg.org/ebooks/100',
      'sumstr' => 'The Complete Works of William Shakespeare')
    );
    $rs = array_reverse($rs);
  }
  return $rs;
}

function mod_log_search($logtxt) {
  $logtxt = '[' . fd(time()) . ']' . $logtxt . "\n";
  $logfile = $GLOBALS['LOGPATH'] . '/searchlog.txt';
  file_put_contents($logfile, $logtxt, FILE_APPEND);
}

function mod_update_user_rows($userid, $inrows) {
  $sql = 'UPDATE users set searchrows = %d';
  $sql .= ' WHERE userid = %d';
  queryf_one($sql, $inrows, $userid);
}

function mod_update_user_cols($userid, $incols) {
  $sql = 'UPDATE users set searchcols = %d';
  $sql .= ' WHERE userid = %d';
  queryf_one($sql, $incols, $userid);
}

function mod_get_user_int($userid, $intname) {
  $sql = 'SELECT ' . $intname . ' FROM users';
  $sql .= ' WHERE userid = %d';
  $rs = queryf_one($sql, $userid);
  if (!isset($rs) || !isset($rs[$intname])) {
    util_except('get_user_flag(' . $intname . ') query result missing ' . $intname);
  }
  return $rs[$intname];
}
