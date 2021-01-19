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
define('TEMPLATE_PAGE', 'page');
define('TEMPLATE_DOID', 'doid');
define('TEMPLATE_MSG', '__msg');

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

//this function is the entry point from index.php
function util_show_page($data = null) {
  if (!isset($data) || !isset($data[TEMPLATE_PAGE])) {
    util_except('missing data[TEMPLATE_PAGE] in util_show_page()');
  }

  $rv = '';
  $framepath = $GLOBALS['FATEPATH'] . '/frame.php';
  $ldpt = $GLOBALS['FILESDIR'] . '/templates/';
  $ldpt .= $data[TEMPLATE_PAGE] . '.php';
  if (file_exists($ldpt)) {

    util_log('debug', 'framing custom template: ' . $ldpt);
    $rv .= util_frame_template($framepath, $ldpt, $data);

  } else {   

    $dpt = $GLOBALS['FATEPATH'] . '/templates/';
    $dpt .= $data[TEMPLATE_PAGE] . '.php';
    if (!file_exists($dpt)) {

      //TODO silently log attempts to load invalid pages
      include('404.php');

    } else {

      util_log('debug', 'framing default template: ' . $ldpt);
      $rv .= util_frame_template($framepath, $dpt, $data);

    } //end if default template exists

  } //end if custom template exists

  return $rv;  
}

//get the content from a template and put it in frame.php
function util_frame_template($framename, $filename, $data) {
  //get the content from a template
  $data[TEMPLATE_CONTENT] = util_show_template($filename, $data);
  //put the content within frame.php
  return util_show_template($framename, $data);  
}

//use $filename as a ".php template," with no global variables
//. only pre-escaped $data is passed to the ".php template"
function util_show_template($filename, $data = null) {
  util_assert(isset($data[TEMPLATE_PAGE]),
              'page not set in util_show_template()');
  util_assert(isset($data[TEMPLATE_DOID]),
              'doid not set in util_show_template()');

  if (!isset($data[TEMPLATE_CONTENT])) {
    $data[TEMPLATE_CONTENT] = '';
  }
  if (!isset($data[TEMPLATE_MSG])) {
    $data[TEMPLATE_MSG] = '';
  }

  //escape all data that could be output as html
  foreach ($data as $namestr => $valuestr) {
    if ($namestr != TEMPLATE_CONTENT) {
      $data[$namestr] = htmlspecialchars($valuestr);
    }
  }

  ob_start();
  include($filename);
  $rv = ob_get_contents();
  ob_end_clean();
  return $rv;
}
