<?php
/* © 2021 TSUZY LLC ALL RIGHTS RESERVED */

define('TEMPLATE_PAGE', 'page');

function web_init_data() {
  if (!isset($GLOBALS['DATA_PAGE'])) {
    util_except('tried to init data without setting DATA_PAGE');
  }
  $rv = array(TEMPLATE_PAGE => $GLOBALS['DATA_PAGE']);
  return $rv;
}

function web_set_page($dp) {
  $GLOBALS['DATA_PAGE'] = strtolower($dp);
}

function web_get_page() {
  if (!isset($GLOBALS['DATA_PAGE'])) {
    util_except('trying to get_page when no page has been set');
  }
  return $GLOBALS['DATA_PAGE'];
}

function web_is_page($inpage) {
  if (!isset($GLOBALS['DATA_PAGE'])) {
    return false;
  }
  $lopage = strtoloser($inpage);
  return (GLOBALS['DATA_PAGE'] == $lopage);
}

function web_logged_in() {
  return isset($GLOBALS['USER_ID']);
}

function web_get_user() {
  util_assert(web_logged_in(), 'tried to get user while not logged in');
  return $GLOBALS['USER_ID'];
}

function web_toggle_user_flag($userid, $flagname) {
  $curval = web_get_user_flag($userid, $flagname);
  $curval = !$curval;
  $sql = 'UPDATE users set ' . $flagname . ' = %d';
  $sql .= ' WHERE userid = %d';
  queryf_one($sql, $curval, $userid);
}

function web_get_user_flag($userid, $flagname) {
  $sql = 'SELECT ' . $flagname . ' FROM users';
  $sql .= ' WHERE userid = %d';
  $rs = queryf_one($sql, $userid);
  if (!isset($rs) || !isset($rs[$flagname])) {
    util_except('get_user_flag(' . $flagname . ') query result missing ' . $flagname);
  }
  return $rs[$flagname];
}

function web_get_user_name($userid) {
  $sql = 'SELECT username FROM users';
  $sql .= ' WHERE userid = %d';
  $rs = queryf_one($sql, $userid);
  if (!isset($rs) || !isset($rs['username'])) {
    util_except('get_user_name query result missing username');
  }
  return $rs['username'];
}

function web_get_user_lastdate($userid) {
  $sql = 'SELECT lastdate FROM users';
  $sql .= ' WHERE userid = %d';
  $rs = queryf_one($sql, $userid);
  if (!isset($rs) || !isset($rs['lastdate'])) {
    util_except('get_user_lastdate missing lastdate');
  }
  return $rs['lastdate'];
}

function web_update_user($userid) {
  $nowtime = time();
  $sql = 'UPDATE users SET lastdate = %d';
  $sql .= ' WHERE userid = %d';
  queryf($sql, $nowtime, $userid);
}

function web_login_user(&$data) {
  $inuser = $data['username'];
  $inpass = $data['password'];

  if (isset($GLOBALS['USER_ID'])) {
    if (web_get_user_name($GLOBALS['USER_ID']) != $inuser) {
      util_except('login called while a different username is set');
    }
    return true;
  }
  
  $sql = 'SELECT userid FROM users WHERE username = %s';
  $rs = queryf_one($sql, $inuser);
  if (isset($rs) && count($rs) > 0) {
    $userid = $rs['userid'];
    $salt = 'asdf';
    $hashpass = sha1($inpass . $salt);
    $sql = 'SELECT hashpass FROM users WHERE userid = %d';
    $rs = queryf_one($sql, $userid);
    if (isset($rs) && $rs['hashpass'] == $hashpass) {
      $_SESSION['USER_ID'] = $userid;
      $GLOBALS['USER_ID'] = $_SESSION['USER_ID'];
      $username = $inuser;
    } else {
      $data[TEMPLATE_PAGE_MSG] = 'BAD PASSWORD';
    }
  } else {
    $data[TEMPLATE_PAGE_MSG] = 'BAD USERNAME';
  }
  return true;
}

function net_logout_user(&$data) {
  if (!isset($_SESSION['FATE_BROWSER_ID'])) {
    util_log('warning', 'tried to logout without a session');
  } else if (!isset($GLOBALS['USER_ID'])) {
    util_log('warning', 'tried to logout without a user_id');
  }
  session_unset();
  unset($GLOBALS['USER_ID']);
  unset($GLOBALS['BROWSER_ID']);
  $username = '';
  $data[TEMPLATE_PAGE_MSG] = 'Logout successful';
  return true;
}
