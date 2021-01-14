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

$css_url = util_url('css/fate.css');
$page_title = app_get_page_title($page);
?>

<html>
  <head>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $css_url; ?>" />
  </head>
<body><?php 

$content_class = 'content';
$left_col = '';
$left_col .= app_get_page_ident($page);
$left_col .= app_get_smart_spacer($page);

if (web_logged_in()) {

  $links_arr = array('Data', 'Search', 'Settings');
  $left_col .= app_get_header_links($page, $links_arr);
  $left_col .= app_get_header_extra($page);
  if (isset(${TEMPLATE_PAGE_MSG}) && ${TEMPLATE_PAGE_MSG} != '') {
    $left_col .= gen_p(${TEMPLATE_PAGE_MSG}, 'page_msg');
  }

  echo $left_col;

  $chat_data = array();
  if (isset($datestr)) {
    $chat_data['datestr'] = $datestr;
  }
  $chat_open = web_get_user_flag(web_get_user(), CHAT_OPEN_FLAG);
  $right_col = gen_chat_with_fate($page, $chat_data, $chat_open);
  if ($chat_open) {
    $left_col = gen_div(${TEMPLATE_CONTENT}, $content_class);
  } else { //if chat is collapsed
    echo gen_div(${TEMPLATE_CONTENT}, $content_class);
    $left_col = '';
  }
  $left_col .= gen_copyright_notice();
  
  //TODO add option to use gen_two_col_table()
  echo gen_two_cols($left_col, $right_col);

} else {

  echo $left_col;
  $links_arr = array('TOS', 'News', 'About');
  echo app_get_header_links($page, $links_arr);
  if ($page == APP_PREFIX) {
    echo app_get_header_extra($page);
  }
  if (isset(${TEMPLATE_PAGE_MSG}) && ${TEMPLATE_PAGE_MSG} != '') {
    echo gen_p(${TEMPLATE_PAGE_MSG}, 'page_msg');
  }
  echo gen_div(${TEMPLATE_CONTENT}, $content_class);
  $br_page = ($page == strtolower(APP_IDENT)) || $page == strtolower(APP_PREFIX);
  $add_br = (!web_logged_in() && $br_page);
  echo gen_copyright_notice($add_br);

}
?>
