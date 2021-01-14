<?php
/* © 2021 TSUZY LLC ALL RIGHTS RESERVED */

define('CHAT_OPEN_FLAG', 'chatopen');
define('TEXT_AREA_FLAG', 'textarea');
define('FATE_SPLASH_FLAG', 'fatesplash');

define('USER_SEARCH_ROWS', 'searchrows');
define('USER_SEARCH_COLS', 'searchcols');

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
