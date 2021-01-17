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

define('BULK_BLOCK_SIZE', 200);

function get_or_create_conn() {
  if (!isset($GLOBALS['DBCONN']) || $GLOBALS['DBCONN'] === null) {
    $GLOBALS['DBCONN'] = mysqli_connect($GLOBALS['DBHOST'],
                                         $GLOBALS['DBUSER'],
                                         $GLOBALS['DBPASS'],
                                         $GLOBALS['DBNAME']);
  }

  if (!$GLOBALS['DBCONN']) {
    util_except('[DBPHP ERROR] the db connection is broken', 'db');
  }

  return $GLOBALS['DBCONN'];
}

function unsafe_query($sql) {
  $conn = get_or_create_conn();
  if (isset($GLOBALS['DBVERBOSE']) && $GLOBALS['DBVERBOSE'] == true) {
    util_log('dbquery', $sql);
  }

  $result = mysqli_query($conn, $sql);

  if (mysqli_insert_id($conn) != 0) {
    if (isset($GLOBALS['DBVERBOSE']) && $GLOBALS['DBVERBOSE'] == true) {
      util_log('db insert', mysqli_insert_id($conn));
    }
  }

  if (!$result) {
    if (!$GLOBALS['ISPROD']) {
      if (isset($GLOBALS['DBVERBOSE']) && $GLOBALS['DBVERBOSE'] == true) {
        util_log('db', "Invalid query -- $sql -- " . mysqli_error($conn));
      }
      util_except("Invalid query -- $sql -- " . mysqli_error($conn), 'db');
    }
  }
  return $result;
}

function last_insert_id() {
  return mysqli_insert_id(get_or_create_conn());
}

function queryf($string) {  
  $args = func_get_args();
  array_shift($args);
  return vqueryf($string, $args);
}

function queryf_one($string) {
  $args = func_get_args();
  array_shift($args);
  $rs = vqueryf($string, $args);

  if (gettype($rs) == 'object') {
    if ($row = mysqli_fetch_assoc($rs)) {
      if (isset($GLOBALS['DBVERBOSE']) && $GLOBALS['DBVERBOSE'] == true) {
        util_log('dbresult', str_replace(array("\n", "\t", " "),
                                         '',
                                         print_r($row, true)));
      }
      return $row;
    }
  }

  if (isset($GLOBALS['DBVERBOSE']) && $GLOBALS['DBVERBOSE'] == true) {
    util_log('dbresult', 'NULL');
  }
  return NULL;
}

function queryf_all($string) {
  $args = func_get_args();
  array_shift($args);
  return vqueryf_all($string, $args);
}

function vqueryf($string, $args) {
  //$conn is needed in this function
  //. for mysqli_real_escape_string to work
  $conn = get_or_create_conn();

  $len = strlen($string);
  $sql_query = "";
  $args_i = 0;
  for($i = 0; $i < $len; $i++) {
    if($string[$i] == "%") {
      $char = $string[$i + 1];
      $i++;
      switch($char) {
      case "%":
        $sql_query .= $char;
        break;
      case "u": case "d":
        $sql_query .= "" . intval($args[$args_i]) . "";
        break;
      case "s":
        $unsafe_str = $args[$args_i];
        //$safe_str = str_replace('\'', '\\\'', $unsafe_str);
        $safe_str = mysqli_real_escape_string($conn, $args[$args_i]);
        //echo 'safe_str: ' . $safe_str . "\n";
        $sql_query .= "'" . $safe_str . "'"; 
        break;
      case "x":
        $sql_query .= "'" . dechex($args[$args_i]) . "'";
        break;
      }
      if($char != "x") {
        $args_i++;
      }
    }
    else {
      $sql_query .= $string[$i];
    }
  }
  return unsafe_query($sql_query);
}

function vqueryf_all($sql, $arr, $key = null) {
  $rv = array();
  $rs = vqueryf($sql, $arr);
  if ($rs !== null) {
    while ($row = mysqli_fetch_assoc($rs)) {
      if ($key) {
        $rv[$row[$key]] = $row;
      } else {
        $rv[] = $row;
      }
    }
  }

  if (isset($GLOBALS['DBVERBOSE']) && $GLOBALS['DBVERBOSE'] == true) {
    util_log('dbresult', count($rv) . ' rows returned');
  }

  return $rv;
}

function db_make_log_entry() {
  $page = $_SERVER['PHP_SELF'];
  if (isset($GLOBALS['DATA_PAGE'])) {
    $page .= '/' . $GLOBALS['DATA_PAGE'];
  }

  $ip = '';
  if (isset($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
  }

  $ref = '';
  if (isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
  }

  $agent = '';
  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $agent = $_SERVER['HTTP_USER_AGENT'];
  }

  $host = '';
  if (isset($GLOBALS['BROWSER_ID'])) {
    $host = $GLOBALS['BROWSER_ID'];
  }

  $nowtime = time();
  $elapsed = microtime(true) - $GLOBALS['START_TIME'];

  if (isset($GLOBALS['USER_ID'])) {
    $user = $GLOBALS['USER_ID'];
  } else {
    $user = 0;
  }

  $sql = 'INSERT INTO ' . $GLOBALS['LOGTABLE'] . ' (nowtime, ipaddr, webagent, pagename, refpage, hostname, elapsed, userid)';

  $sql .= ' VALUES (%d, %s, %s, %s, %s, %s, %s, %d)';
  queryf($sql, $nowtime, $ip, $agent, $page, $ref, $host, $elapsed, $user);

  fl('PAGE COMPLETED in ' . $elapsed . ' seconds.');
}
