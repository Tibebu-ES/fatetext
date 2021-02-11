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
      <?php if (web_logged_in() && web_get_flag(INVERTEDCS_FLAG)) { ?>
        <?php if (web_logged_in() && web_get_flag(FATE_SPLASH_FLAG)) { ?>
          <link rel="stylesheet" href="css/etaf.css" />
        <?php } else { ?>
          <link rel="stylesheet" href="css/ftae.css" />
        <?php } ?>
      <?php } else { ?>
        <?php if (web_logged_in() && web_get_flag(FATE_SPLASH_FLAG)) { ?>
          <link rel="stylesheet" href="css/fate.css" />
        <?php } else { ?>
          <link rel="stylesheet" href="css/eatf.css" />
        <?php } ?>
      <?php } ?>
  </head>
<body>

<?php
$page_msg = '';
if ($data[TEMPLATE_MSG] != '') {
  $page_msg = $data[TEMPLATE_MSG];
}

$left_col = '';
if ($page != 'home') {
  $left_col .= gen_link(gen_url('home'), 'Places', 'header');
} else {
  $left_col .= 'Places';
}
$left_col .= app_get_smart_spacer($page);

if (web_logged_in()) {

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

    echo $left_col;

    $chat_data = array();
    if (isset($datestr)) {
      $chat_data['datestr'] = $datestr;
    }
    $chat_open = web_get_user_flag(web_get_user(), CHAT_OPEN_FLAG);
    $right_col = gen_chat_with_fate($page, $chat_open);
    if ($chat_open) {
      $left_col = gen_div($data[TEMPLATE_CONTENT], 'content');
    } else { //if chat is collapsed
      echo gen_div($data[TEMPLATE_CONTENT], 'content');
      $left_col = '';
    }
    $left_col .= gen_copyright_notice();
    
    echo gen_two_cols($left_col, $right_col);

  } //END logged in cases

} else { //not logged in

  echo $left_col;
  $links_arr = array('tos' => 'New',
                     'news' => 'Gems',                
                     'about' => 'About');
  echo app_get_header_links($page, $links_arr);

  if ($page == 'hall' || $page == 'art' || $page == 'date') {
    echo app_get_header_extra($page);
  }
  if ($page_msg != '') {
    echo gen_p($page_msg, 'page_msg');
  }

  echo gen_div($data[TEMPLATE_CONTENT], 'innerc');
  $add_br = (!web_logged_in() && ($page == 'home'));
  echo gen_copyright_notice($add_br);

}
?>

</body></html>
