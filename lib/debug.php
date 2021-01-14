<?php
/* Copyright 2021 Tsuzy LLC.  All rights reserved. */

$g_log = '';

function get_backtrace_string($ignore_args = true) {
  if ($ignore_args) {
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
  echo $g_log;
  $g_log = '';
}

function util_log($str, $msg, $level = 1) {
  global $g_log;

  $logstr = $str . '::' . $msg . "\n";
  if ($GLOBALS['LOGLEVEL'] >= $level) {
    $g_log .= $logstr . "<br>\n";
  } else {
    $logfile = $GLOBALS['LOGPATH'] . '/errorlog.txt';
    file_put_contents($logfile, $logstr, FILE_APPEND);
  }
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
      util_except($msg, 'assert');
    } else {
      util_log('assert', $msg);      
    }
  }
}

function fl($msg) {
  util_log('fate', $msg);
}

function fd($thetime) {
  return date('H:i:s, D, M Y', $thetime);
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
