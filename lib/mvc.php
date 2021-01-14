<?php
/* Copyright 2021 Tsuzy LLC.  All rights reserved. */

define('TEMPLATE_CONTENT', '__content');
define('TEMPLATE_PAGE_MSG', '__page_msg');

function check_array_param($name, &$rv, &$p, $default=null) {
  $boolrv = check_string_param($name, $rv, $p, $default);
  if (!is_array($rv[$name])) {
    unset($rv[$name]);
    $boolrv = false;
  }
  if (!$boolrv && $GLOBALS['PARAMEX']) {
    util_except('invalid array param: ' . $name);
  }
  return $boolrv;
}

function check_int_param($name, &$rv, &$p, $default=null) {
  $boolrv = check_string_param($name, $rv, $p, $default);
  if (!is_numeric($rv[$name])) {
    unset($rv[$name]);
    $boolrv = false;
  }
  if (!$boolrv && $GLOBALS['PARAMEX']) {
    util_except('invalid int param: ' . $name);
  }
  return $boolrv;
}

function check_string_param($name, &$rv, &$p, $default=null) {
  $boolrv = false;
  if (isset($p[$name])) {
    $rv[$name] = $p[$name];
    $boolrv = true;
  } else {
    $rv[$name] = $default;
  }
  if (!$boolrv && $GLOBALS['PARAMEX']) {
    util_except('invalid string param: ' . $name);
  }
}

function util_url($rel) {
    if ($GLOBALS['RELURLS']) {
      return $rel;
    }

    //$absWebPath = parse_url($GLOBALS['WEBROOT'], PHP_URL_PATH);
    //return $absWebPath . '/' . $rel;

    return $GLOBALS['WEBROOT'] . '/' . $rel;
}

function util_show_page($vars = null) {
  if ($vars === null) {
    $vars = array();
  }

  $rv = '';
  $dpt = $GLOBALS['FATEPATH'] . '/templates/';
  $dpt .= $GLOBALS['DATA_PAGE'] . '.php';

  if (!file_exists($dpt)) {
    include('404.php');
  } else {
    $framepath = $GLOBALS['FATEPATH'] . '/frame.php';
    $rv .= util_frame_template($framepath, $dpt, $vars);
  }

  return $rv;  
}

function util_show_content($content, $vars) {
  return util_frame_content('frame.php', $content, $vars);
}

function util_frame_content($framename, $content, $vars) {
  $vars[TEMPLATE_CONTENT] = $content;
  return util_show_template($framename, $vars);  
}

function util_frame_template($framename, $filename, $vars) {
  $content = util_show_template($filename, $vars);
  return util_frame_content($framename, $content, $vars);
}

function util_show_template($filename, $vars = null) {
  //TODO put all the vars through htmlescape
  if ($vars !== null) {
    extract($vars);
  }
  ob_start();
  include($filename);
  $rv = ob_get_contents();
  ob_end_clean();
  return $rv;
}
