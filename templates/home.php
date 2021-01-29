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

$heading_class = 'page_heading';
if (web_logged_in()) {

  echo gen_search_form();

  $heading_html = '';
  $link_url = gen_url('settings');
  $heading_html .= gen_link($link_url, 'Current user', 'header');
  $heading_html .= gen_b(': ');
  $link_str = web_get_user_name(web_get_user());
  $heading_url = gen_url('home', 'updateuser');
  $heading_html .= gen_link($heading_url, $link_str);
  $heading_html .= PADDING_STR;
  $last_date_html = fd(web_get_user_lastdate(web_get_user()));
  $heading_html .= gen_i('Last updated: ' . $last_date_html);
  echo gen_p('(' . $heading_html . ')', $heading_class); 

} else { //not logged in

  $hall_url = gen_url('hall');

  echo gen_login_form();
  $link_str = 'Hall of Fame';
  $artnum = count(mod_get_hall_art());
  $hall_str = '[' . $artnum . ']' . PADDING_STR;
  $hall_str .= gen_link($hall_url, $link_str); 
  echo gen_p($hall_str, $heading_class);

}
?>
