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
    $rs = array(array('artid' => '0', 'datestr' => '01_01_21',
                      'arturl' => 'http://thesuzy.com',
                      'sumstr' => 'TheSuzy Trilogy by Todd Perry'));
  }
  return $rs;
}

function mod_log_search($logtxt) {
  $logtxt = '[' . fd(time()) . ']' . $logtxt . "\n";
  file_put_contents($GLOBALS['SLOGFILE'], $logtxt, FILE_APPEND);
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
    util_except('get_user_flag(' . $intname
                . ') query result missing '
                . $intname);
  }
  return $rs[$intname];
}

function mod_flag_from_toggle($intoggle) {
  switch ($intoggle) {
   case TOGGLE_SPLASH_CMD: return FATE_SPLASH_FLAG;
   case TOGGLE_CHAT_CMD: return CHAT_OPEN_FLAG;
   case TOGGLE_TEXT_CMD: return TEXT_AREA_FLAG;
  }
  util_except('attempted to toggle an unknown flag: '
              . $intoggle);
}
