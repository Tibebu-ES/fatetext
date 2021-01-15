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
