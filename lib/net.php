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
  $tempstr = isset($GLOBALS['FATE_BROWSER_ID']) ? $GLOBALS['FATE_BROWSER_ID'] : 'n/a';
  util_log('session::browserid', $tempstr);
  $tempstr = (net_is_found_existing_session() ? 'true' : 'false');
  util_log('session::found_existing', $tempstr);
}

function net_init_session() {
  if (isset($_SESSION)) {
    util_except('$_SESSION should not be set before session_start()');
  }

  if ($GLOBALS['ISPROD']) {
    error_reporting(E_ALL); //0);
    register_shutdown_function("net_check_for_fatal");
    //TODO uncomment these when production is stable
    //set_error_handler('show_fail_page', E_ALL);
    //set_exception_handler('show_fail_page');
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
  $GLOBALS['FATE_BROWSER_ID'] = net_get_or_create_browser_id(DEFAULT_DURATION);
  if (isset($_SESSION['USER_ID'])) {
    $GLOBALS['USER_ID'] = $_SESSION['USER_ID'];
  }
}

function net_end_session() {
  if (!isset($_SESSION['FATE_BROWSER_ID'])) {
    util_except('tried to endsession without a session');
  } else if (isset($GLOBALS['USER_ID'])) {
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
      util_log('session', 'current elapsed: ' . $elapsed);
    }
    if (isset($_SESSION['FATE_LAST_ACTIVITY']) && ($elapsed > $duration)) {
      session_unset(); 
      if ($GLOBALS['DBVERBOSE']) {
        util_log('session', '[3] id and old activity -> unset/destroy, found = false');
      }
    } else {
      $g_found_existing_session = true;
      $_SESSION['FATE_LAST_ACTIVITY'] = time();
      if ($GLOBALS['DBVERBOSE']) {
        util_log('session', '[2] id and recent activity -> do nothing, found = true');
      }
      return $_SESSION['FATE_BROWSER_ID'];
    }
  } else {
    if ($GLOBALS['DBVERBOSE']) {
      util_log('session', '[1] no id -> set id (or last activity), found = false');
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

function net_check_password($passtxt) {
  if (strlen($passtxt) < MIN_PASSWORD_LENGTH) {
    return 'Please enter a longer password.';
  }
  return '';
}

function net_check_handle($handle) {
  if(preg_match("/^([a-zA-Z])+([a-zA-Z0-9\._-])*$/", $handle)) {
    return true;
  }
  return false;
}

function net_check_email($email) {
  if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
                $email)){
    list($username, $domain) = explode('@', $email);
    //if(!checkdnsrr($domain, 'MX')) {
    //  return false;
    //}
    return true;
  }
  return false;
}

function net_curl($url, $post_fields = NULL) {
  $options = array(
                   CURLOPT_RETURNTRANSFER => true,     // return web page
                   CURLOPT_HEADER         => false,    // don't return headers
                   CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                   CURLOPT_ENCODING       => "",       // handle all encodings
                   CURLOPT_USERAGENT      => "spider", // who am i
                   CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                   CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                   CURLOPT_TIMEOUT        => 120,      // timeout on response
                   CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                   );
  if ($post_fields !== NULL) {
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_POSTFIELDS] = $post_fields;
  }

  $ch = curl_init($url);
  curl_setopt_array($ch, $options);
  $content = curl_exec($ch);
  $err = curl_errno($ch);
  $errmsg = curl_error($ch);
  $header = curl_getinfo($ch);
  curl_close($ch);

  $header['errno'] = $err;
  $header['errmsg'] = $errmsg;
  $header['content'] = $content;
  return $header;
}
