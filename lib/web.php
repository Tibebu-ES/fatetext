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

$g_user_id = null;

function web_init_data($inpage) {
  $rv = array(TEMPLATE_PAGE => $inpage);
  if (isset($_REQUEST[TEMPLATE_PAGE])) {
    check_string_param(TEMPLATE_PAGE, $rv, $_REQUEST);
  }
  
  if (isset($_REQUEST[TEMPLATE_CMD])) {
    check_string_param(TEMPLATE_CMD, $rv, $_REQUEST);
  } else {
    $rv[TEMPLATE_CMD] = '';
  }

  net_init_session(true);
  fl('INIT COMPLETED with session;');
  return $rv;
}

function web_logged_in() {
  global $g_user_id;
  return isset($g_user_id);
}

function web_get_user() {
  global $g_user_id;
  util_assert(web_logged_in(),
              'tried to get user while not logged in');
  return $g_user_id;
}

function web_set_user($inuser) {
  global $g_user_id;
  $g_user_id = $inuser;
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

function web_update_user_lastdate($userid) {
  $nowtime = time();
  $sql = 'UPDATE users SET lastdate = %d';
  $sql .= ' WHERE userid = %d';
  queryf($sql, $nowtime, $userid);
}

function web_login_user(&$data) {
  global $g_user_id;
  $inuser = $data['username'];
  $inpass = $data['password'];

  if (isset($g_user_id)) {
    if (web_get_user_name($g_user_id) != $inuser) {
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
      $g_user_id = $_SESSION['USER_ID'];
      $username = $inuser;
    } else {
      $data[TEMPLATE_MSG] = 'BAD PASSWORD';
    }
  } else {
    $data[TEMPLATE_MSG] = 'BAD USERNAME';
  }
  return true;
}

function net_logout_user(&$data, $show_msg = true) {
  global $g_user_id;

  if (!isset($_SESSION['FATE_BROWSER_ID'])) {
    util_log('warning', 'tried to logout without a session');
    $show_msg = false;
  } else if (!isset($g_user_id)) {
    util_log('warning', 'tried to logout without a user_id');
    $show_msg = false;
  }
  $g_user_id = null;
  net_end_session();
  if ($show_msg) {
    $data[TEMPLATE_MSG] = 'Logout successful';
  }
  return true;
}
