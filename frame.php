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

$page = $data[TEMPLATE_PAGE];
$page_title = app_get_page_title($page);
util_assert(isset($data[TEMPLATE_CONTENT]),
            '$data[TEMPLATE_CONTENT] not set in frame.php');
?>

<html>
  <head>
    <title><?php echo $page_title; ?></title>
      <link rel="stylesheet" href="css/fate.css" />
  </head>
<body>

<?php
$page_msg = '';
if ($data[TEMPLATE_MSG] != '') {
  $page_msg = $data[TEMPLATE_MSG];
}

$left_col = '';
if (web_logged_in()) {
  if ($page != 'home') {
    $left_col .= gen_link(gen_url('home'), 'Places', 'header');
  } else {
    $left_col .= 'Places';
  }
  $left_col .= app_get_smart_spacer($page);

  $atf = web_get_user_flag(web_get_user(), AGREE_TOS_FLAG);
  if (!isset($_SESSION['AGREETOS']) && $GLOBALS['AGREEONLOGIN']) {
    $atf = false;
  }

  if (!$atf) {

    echo app_get_tos_page($page_msg);

  } else { //if tos has been agreed to

    $links_arr = array('data' => 'Data',
                       'search' => 'Gems',
                       'settings' => 'Home');
    $left_col .= app_get_header_links($page, $links_arr);
    $left_col .= app_get_header_extra($page);

    if ($page_msg != '') {
      $left_col .= gen_p($page_msg, 'page_msg');
    }

    //echo $left_col;

    $chat_data = array();
    if (isset($datestr)) {
      $chat_data['datestr'] = $datestr;
    }
    $chat_open = web_get_user_flag(web_get_user(), CHAT_OPEN_FLAG);
    $right_col = gen_chat_with_fate($page, $chat_open);
    $left_col .= gen_div($data[TEMPLATE_CONTENT], 'content');

    $chat_left = web_get_user_flag(web_get_user(), HIDETOOLTIP_FLAG);
    if ($chat_open) {
      $left_col .= gen_copyright_notice();
      $chat_left = web_get_user_flag(web_get_user(), HIDETOOLTIP_FLAG);
      $left_col = gen_div($left_col, 'content');
      if ($chat_left) {
        echo gen_two_cols($right_col, $left_col);
      } else {
        echo gen_two_cols($left_col, $right_col);
      }
    } else { //if chat is collapsed
      if ($chat_left) {
        $left_col .= gen_two_cols($right_col, gen_copyright_notice());
      } else {
        $left_col .= gen_two_cols(gen_copyright_notice(), $right_col);        
      }
      echo gen_div($left_col, 'content');
    }

  } //END logged in cases

} else { //not logged in

  /*if ($page != 'login') {
    $left_col .= gen_link(gen_url('login'), 'Login', 'header');
  } else {
    $left_col .= 'Login';
  }
  $left_col .= app_get_smart_spacer($page);*/

  $left_col .= gen_link(gen_url('login'), 'Login', 'header') . '<br>';
  $left_col .= gen_span(gen_link(gen_url('tos'), 'ToS', 'header'), 'nextline');
  $left_col .= gen_span(gen_link(gen_url('news'), 'News', 'header'), 'nextline');
  $left_col .= gen_span(gen_link(gen_url('about'), 'About', 'header'), 'nextline');
  $left_col .= gen_span(gen_link(gen_url('hall'), 'Books', 'header'), 'nextline');

  /*echo $left_col;
  $links_arr = array('tos' => 'New',
                     'news' => 'Gems',                
                     'about' => 'About');
  echo app_get_header_links($page, $links_arr);
  echo app_get_header_extra($page);

  if ($page_msg != '') {
    echo gen_p($page_msg, 'page_msg');
  }

  echo gen_div($data[TEMPLATE_CONTENT], 'innerc');
  $add_br = ($page == 'login' || $page = 'hall');
  echo gen_copyright_notice($add_br);*/

  $right_col = '';
  if ($page_msg != '') {
    $right_col .= gen_p($page_msg, 'page_msg');
  }
  $right_col .= gen_div($data[TEMPLATE_CONTENT] . gen_copyright_notice(),
                        'framec');
  echo gen_two_cols($left_col, $right_col);
}
?>

</body></html>
