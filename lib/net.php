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

define('DEFAULT_DURATION', 60*60*24*365);
define('NO_ERRORS', '');
define('MIN_PASSWORD_LENGTH', 4);
$g_found_existing_session = false;

function net_log_user_and_session_info() {
  $tempstr = isset($GLOBALS['FATE_BROWSER_ID']) ?
             $GLOBALS['FATE_BROWSER_ID'] : 'n/a';
  util_log('session::browserid', $tempstr);
  $tempstr = (net_is_found_existing_session() ?
             'true' : 'false');
  util_log('session::found_existing', $tempstr, LLWORK);
}

function net_init_session() {
  if (isset($_SESSION)) {
    util_except('$_SESSION should not be set before session_start()');
  }

  if ($GLOBALS['ISPROD']) {
    error_reporting(E_ALL); //0);
    register_shutdown_function("net_check_for_fatal");
    set_error_handler('net_show_fail_page', E_ALL);
    set_exception_handler('net_show_fail_page');
  } else {
    error_reporting(E_ALL);
  }

  if ($GLOBALS['NOCOOKIES']) {
    ini_set('session.name', 'FATESID');
    ini_set('session.use_cookies', 0);
    ini_set('session.use_only_cookies', 0);
    ini_set('session.use_trans_sid', 1);
  }

  session_start();
  $GLOBALS['FATE_BROWSER_ID'] =
           net_get_or_create_browser_id(DEFAULT_DURATION);
  if (isset($_SESSION['USER_ID'])) {
    web_set_user($_SESSION['USER_ID']);
  }
}

function net_end_session() {
  if (!isset($_SESSION['FATE_BROWSER_ID'])) {
    util_except('tried to endsession without a session');
  } else if (web_logged_in()) {
    util_except('tried to endsession while logged in');
  }
  session_unset();
  session_destroy();
  unset($GLOBALS['FATE_BROWSER_ID']);
  return true;
}

function net_is_found_existing_session() {
  global $g_found_existing_session;
  return $g_found_existing_session;
}

function net_get_or_create_browser_id($duration = DEFAULT_DURATION) {
  global $g_found_existing_session;

  /* [1] no id -> set id (or last activity), found = false
  ** [2] id and recent activity -> do nothing, found = true
  ** [3] id and old activity -> unset/destroy, found = false 
  */
  $rv = 0;
  if (isset($_SESSION['FATE_BROWSER_ID'])) {
    $curtime = time();
    $elapsed = $curtime - $_SESSION['FATE_LAST_ACTIVITY'];
    if ($GLOBALS['DBVERBOSE']) {
      util_log('session', 'current elapsed: ' . $elapsed, LLWORK);
    }
    if (isset($_SESSION['FATE_LAST_ACTIVITY']) && ($elapsed > $duration)) {
      session_unset(); 
      if ($GLOBALS['DBVERBOSE']) {
        util_log('session', '[3] id and old activity'
                 . ' -> unset/destroy, found = false', LLWORK);
      }
    } else {
      $g_found_existing_session = true;
      $_SESSION['FATE_LAST_ACTIVITY'] = time();
      if ($GLOBALS['DBVERBOSE']) {
        util_log('session', '[2] id and recent activity'
                 . ' -> do nothing, found = true', LLWORK);
      }
      return $_SESSION['FATE_BROWSER_ID'];
    }
  } else {
    if ($GLOBALS['DBVERBOSE']) {
      util_log('session', '[1] no id -> set id'
               . ' (or last activity), found = false', LLWORK);
    }
  }
  $browserid = time() . ':' . rand(1, 100000);
  $_SESSION['FATE_BROWSER_ID'] = $browserid;
  $_SESSION['FATE_LAST_ACTIVITY'] = time();
  return $browserid;
}

function net_check_for_fatal() {
  $error = error_get_last();
  if ($error["type"] == E_ERROR) {
    echo 'system error';
    net_show_fail_page();
  }
}

function net_show_fail_page() {
  include('error.php');
  exit(0);
}

function net_util_redirect($url) {
  header('Location: ' . $url);
}
