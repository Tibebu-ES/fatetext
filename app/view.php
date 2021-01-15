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

define('FAME_IDENT', 'TheSuzy');
define('FAME_URL', 'http://thesuzy.com');
define('SEARCH_PLACEHOLDER', 'Empty Search = The Oracular');
define('SPACER_STR', '::');
define('SEARCH_ROWS', 40);
define('LOGIN_ROWS', 18);

function app_get_tos_page($local_page_msg) {
  $rv = gen_h(2, $GLOBALS['APPTITLE'] . ' Terms of Service');
  $rv .= '<div class="content">';
  $home_link = gen_link('index.php?cmd=nologout', 'Go back to the login page');
  $rv .= gen_p($home_link, 'page_heading');
  $c0 = 'The following langauge was derived from the MIT License without modification';
  $c1 = 'Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:';
  $c2 = 'The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.';
  $c3 = 'THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.';
  $tosmsg = 'Please agree to the following terms before proceeding.';
  $rv .= gen_p(gen_i($tosmsg), 'lastline');
  if ($local_page_msg != '') {
    $rv .= gen_p($local_page_msg, 'page_msg');
  }
  $rv .= '<div class="content">';
  $rv .= gen_tos_form();
  $rv .= gen_p(gen_u($c0) . ':');
  $rv .= gen_p($c1);
  $rv .= gen_p($c2);
  $rv .= gen_p($c3, 'lastline');
  $rv .= '</div></div>';
  $rv .= gen_copyright_notice();
  return $rv;
}

function app_get_header_extra($page, $add_el = true) {
  $css_class = 'header';
  $rv = PADDING_STR . SPACER_STR . PADDING_STR;

  if (!web_logged_in()) {

    if (!(($page == 'hall') || ($page == 'art'))) {
      util_except('getting header extra on invalid page');
    }
  }

  switch ($page) {
   case 'hall':
   case 'art':
    $link_text = FAME_IDENT;
    $link_url = FAME_URL;
    $rv .= gen_link($link_url, $link_text, $css_class, false);
    break;

   case 'home':
   case 'data':
    $link_url = 'index.php?cmd=';
    $fatesplash = web_get_user_flag(web_get_user(), FATE_SPLASH_FLAG);
    $link_str = 'splash';
    //do the opposite for 'data' and 'home'
    if ($fatesplash == ($page == 'data')) {
      $link_str = strtoupper($link_str);
    }
    $link_url .= TOGGLE_SPLASH_CMD . '&page=' . $page;
    $rv .= gen_link($link_url, $link_str, $css_class);
    break;

   case 'search':
    if (web_get_user_flag(web_get_user(), TEXT_AREA_FLAG)) {
      $link_url = 'index.php?cmd=' . TOGGLE_TEXT_CMD;
      $link_url .= '&page=' . $page;
      $rv .= gen_link($link_url, 'area--', $css_class);
    } else {
      $link_url = 'index.php?cmd=' . TOGGLE_TEXT_CMD;
      $link_url .= '&page=' . $page;
      $rv .= gen_link($link_url, 'text++', $css_class);            
    }
    break;

   case 'settings':
    $link_url = 'index.php?cmd=logout';
    $rv .= gen_link($link_url, 'LOGOUT', $css_class);
    break;
  }

  if ($add_el) $rv .= "\n";
  return $rv;
}

function app_get_header_links($page, $links_arr, $add_el = true) {
  $rv = PADDING_STR;

  $first = true;
  foreach ($links_arr as $link_str) {

    if ($first) {
      $first = false;
    } else {
      $rv .= PADDING_STR . '|' . PADDING_STR;
    }

    $lower_link = strtolower($link_str);
    if ($lower_link == $page) {
      $rv .= gen_b($link_str);
    } else {
      $link_url = 'index.php?page=' . $lower_link;
      $rv .= gen_link($link_url, $link_str, 'header');
    }
  }

  if ($add_el) $rv .= "\n";
  return $rv;
}

function app_get_smart_spacer($page, $add_el = true) {
  $rv = PADDING_STR;
  if ($page == 'hall' ||
      (!web_logged_in() &&
       $page == 'home')) {
    $rv .= gen_b(SPACER_STR);
  } else {
    $rv .= gen_link('index.php?page=hall', gen_b(SPACER_STR));
  }

  if ($add_el) $rv .= "\n";
  return $rv;
}

function app_get_page_ident($page) {
  $rv = '';
  if ($page != 'home') {
    $home_url = 'index.php?page=home';
    $rv .= gen_link($home_url, $GLOBALS['APPIDENT'], 'header');
  } else {
    $rv .= $GLOBALS['APPIDENT'];
  }
  return $rv;
}

function app_get_page_title($page) {
  $rv = $GLOBALS['APPTITLE'] . SPACER_STR . $page;
  return $rv;
}

function gen_tos_form($add_el = true) {
  $rv = '';
  $elem_arr = array();
  $elem_arr []= gen_input('submit', 'cmd', 'Proceed', $add_el);
  $elem_arr []= gen_input('checkbox', 'toscheck', '1', $add_el);
  $elem_arr []= gen_span('I agree to the following terms:');
  $rv = gen_form($elem_arr);
  return $rv;
}

function gen_search_form($safetext = '', $istextarea = false, $selcat = '', $add_el = true) {
  $rv = '';
  $elem_arr = array();
  if ($istextarea) {
    $inuser = web_get_user();
    $inrows = mod_get_user_int($inuser, USER_SEARCH_ROWS);
    $incols = mod_get_user_int($inuser, USER_SEARCH_COLS);
    $elem_arr []= gen_text_area('stxt', $safetext, $inrows, $incols,
                                SEARCH_PLACEHOLDER, $add_el);
    $elem_arr []= '<div class="textcontrols">';
    $elem_arr []= gen_input('submit', 'cmd', 'Search', $add_el);
    $option_arr = array('fate', 'data', 'docs',
                        'suzyThe', 'theBard',
                        'bibleOS', 'ancienT');
    $elem_arr []= gen_select_input('category', $option_arr, $selcat, $add_el);
    $elem_arr []= gen_input('submit', 'cmd', '&lt;=', $add_el);
    $elem_arr []= gen_input('submit', 'cmd', '=&gt;', $add_el);
    $elem_arr []= gen_input('submit', 'cmd', '/\\', $add_el);
    $elem_arr []= gen_input('submit', 'cmd', '\\/', $add_el);
    $elem_arr []= '</div>';
  } else {
    $elem_arr []= gen_input('submit', 'cmd', 'Search', $add_el);
    $insize = SEARCH_ROWS;
    $elem_arr []= gen_txt_input('stxt', $safetext, $insize,
                            SEARCH_PLACEHOLDER, $add_el);
  }
  $elem_arr []= gen_input('hidden', 'page', 'search', $add_el);
  $rv = gen_form($elem_arr);
  return $rv;
}

function gen_login_form($add_el = true) {
  $rv = '';
  $insize = LOGIN_ROWS;

  $elem_arr = array();
  $elem_arr []= gen_input('submit', 'cmd', 'Login', $add_el);

  $usertxt = gen_txt_input('username', '', $insize,
                           'Username', $add_el);
  $usertxt .= '<br>';

  $passtxt = gen_txt_input('password', '', $insize,
                           'Password', $add_el);
  $passtxt = gen_span($passtxt, 'nextline');
  
  $elem_arr []= $usertxt;
  $elem_arr []= $passtxt;

  $elem_arr []= gen_input('hidden', 'page', 'home', $add_el);

  $rv = gen_form($elem_arr);
  return $rv;
}

function gen_chat_with_fate($page, $chat_data, $is_open) {
  $rv = '';
  if ($is_open) {
    $chat_arr = array('hello fate?', 'world _______!',
                      'fate world?', 'hello _______!');
    $chat_win = gen_chat_win($chat_arr);
    $title_html = gen_chat_title($page, $chat_data, TOGGLE_CHAT_CMD, 'Collapse', '-');
    $title_bar = gen_title_bar($title_html);
    $rv = gen_title_box($title_bar, $chat_win);
  } else {
    $title_html = gen_chat_title($page, $chat_data, TOGGLE_CHAT_CMD, 'Expand', '+');
    $rv = gen_title_bar($title_html, true);
  }
  return $rv;
}

function gen_chat_title($page, $chat_data, $action_cmd, $action_word, $action_char) {
  $chat_url = 'index.php?page=' . $page;
  $chat_url .= '&cmd=' . $action_cmd;
  if (isset($chat_data['datestr'])) {
    $chat_url .= '&datestr=' . $chat_data['datestr'];
  }
  $rv = gen_link($chat_url, $action_word, 'header');
  $plain_str = ' Chat with FaTe [';
  $rv .= gen_link($chat_url, $plain_str, 'plain');
  $rv .= gen_link($chat_url, $action_char);
  $rv = $rv . ']';
  return $rv;
}

function gen_chat_win($chat_arr, $add_el = true) {
  $rv = '';
  foreach ($chat_arr as $msg) {
    $rv .= $msg . '<br><br>';
  }
  if ($add_el) $rv .= "\n";
  return $rv;
}
