<?php
/* Copyright 2021 Tsuzy LLC.  All rights reserved. */

date_default_timezone_set('EST');

function var_list_str($arr, $str) {
  $num = count($arr);
  $rv = '';
  if ($num > 0) {
    $rv .= $str;
    for ($i=1; $i<$num; $i++) {
      $rv .= ', ' . $str;
    }
  }
  return $rv;
}

function util_split($chars, $str) {
  $chars = addslashes($chars);
  return preg_split("/[$chars]+/", $str, -1, PREG_SPLIT_NO_EMPTY);
}

function util_strip_ident($word) {
  return preg_replace("/[^A-Za-z0-9 ]/", '', $word);
}

