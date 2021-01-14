<?php
$safetext = '';
$incat = '';
if (isset($stxt)) {
  $safetext = htmlentities($stxt);
}
if (isset($category)) {
  $incat = $category;
}
$textarea = web_get_user_flag(web_get_user(), TEXT_AREA_FLAG);
echo gen_search_form($safetext, $textarea, $incat);

$safecat = '';
if ($incat != '') {
  $safecat = htmlentities($incat);
  $safecat = '<b>[</b>' . $safecat . '<b>]</b><br>';
}

if ($safetext != '') {
  $safetext = str_replace("\n", '<br>', $safetext);
  $huttxt = $safecat . $safetext;
  echo 'Safe hut = "<br>' . $huttxt . '"';
  mod_log_search($huttxt);
} else {
  echo 'TODO generate new hug';
  if ($incat != '') {
  	echo ' from ' . $safecat;
  }
  mod_log_search('');
}
?>
