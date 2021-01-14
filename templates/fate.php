<?php
$heading_class = 'page_heading';
if (web_logged_in()) {

  echo gen_search_form();

  $heading_html = '';
  $link_url = 'index.php?page=settings';
  $heading_html .= gen_link($link_url, 'Current user', 'header');
  $heading_html .= gen_b(': ');
  $link_str = web_get_user_name($GLOBALS['USER_ID']);
  $heading_html .= gen_link('index.php?cmd=updateuser', $link_str);
  $heading_html .= PADDING_STR;
  $last_date_html = fd(web_get_user_lastdate(web_get_user()));
  $heading_html .= gen_i('Last updated: ' . $last_date_html);
  echo gen_p('(' . $heading_html . ')', $heading_class); 

} else {

  echo gen_login_form();
  $link_str = 'Hall of Fame';
  $hall_str = '[7]' . PADDING_STR;
  $hall_str .= gen_link('index.php?page=' . APP_PREFIX, $link_str); 
  echo gen_p($hall_str, $heading_class);

}
?>
