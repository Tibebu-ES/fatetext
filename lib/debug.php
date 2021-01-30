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

$g_log = '';

function get_backtrace_string($ignore_args = true) {
  if ($ignore_args) {
    //this saves a lot of memory,
    //. by ignoring all the arg values
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
  } else {
    $trace = debug_backtrace();
  }
  $rtrace = array_reverse($trace);
  array_pop($rtrace);
  return print_r($rtrace, true);
}

function print_log() {
  global $g_log;

  if ($GLOBALS['ISPROD']) {
    util_log_to_file('call to print_log() ignored because ISPROD = true');
  } else {
    echo $g_log;
  }

  $g_log = '';
}

function util_log_to_file($logstr) {
  file_put_contents($GLOBALS['LOGFILE'], $logstr, FILE_APPEND);
}

function util_log($str, $msg, $level = 1) {
  global $g_log;

  $logstr = '::' . strtoupper($str) . '::' . $msg . "\n";
  if ($GLOBALS['LOGLEVEL'] >= $level) {
    $g_log .= $logstr . "<br>";
  }

  util_log_to_file($logstr);
}

function util_except($msg = 'unspecified', $str = 'web') {
  util_log('[exception]' . $str, $msg);
  if (!$GLOBALS['CATCHEX']) {
    print_log();
    exit(0);
  }
}

function util_assert($cond, $msg = 'no description was provided') {
  if (!$cond) {
    if ($GLOBALS['ASSERTEX']) {
      if ($GLOBALS['LOGLEVEL'] >= LLDEBUG) {
        bp(true);
      }
      util_except($msg, 'assert');
    } else {
      util_log('assert', $msg);      
    }
  }
}

function fl($msg = "\n") {
  util_log('fate', $msg);
}

function fd($thetime) {
  return date('H:i:s · D · M Y', $thetime);
}

function mp($msg, $var, $print = true) {
  p($var, $print, $msg);
}  

function p($var, $print = true, $msg = '') {
  $rv = '<pre>';
  if ($msg != '') {
    $rv .= $msg . ':';
  }
  if ($var === '') {
    $rv .= '[empty string]';
  } else if ($var === false) {
    $rv .= '[false]';
  } else if ($var === null) {
    $rv .= '[null]';
  } else {
    $rv .= print_r($var, true);
  }
  $rv .= '</pre>' . "\n";

  if ($print) {
    echo $rv;
  }
  return $rv;
}

function xp($var, $print = true) {
  p($var, $print);
  exit(0);
}

function bp($var = null, $print = true, $ignore_args = false) {
  $rv = '<pre>' . get_backtrace_string($ignore_args);
  if ($var !== null) {
    $rv .= print_r($var, true);
  }
  $rv .= '</pre>' . "\n";
  if ($print) {
    echo $rv;
  }
  return $rv;
}

function xbp($var, $print = true, $ignore_args = false) {
  bp($var, $print, $ignore_args);
  exit(0);
}
