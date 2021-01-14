<?php

define('FAME_IDENT', 'TheSuzy');
define('FAME_URL', 'http://thesuzy.com');
define('SEARCH_PLACEHOLDER', 'Empty Search = The Oracular');
define('SPACER_STR', '::');
define('APP_TITLE', 'FameText');
define('APP_IDENT', 'FaTe');
define('APP_PREFIX', 'fame');
define('APP_SPLASH', 'splash');
define('SEARCH_ROWS', 40);
define('LOGIN_ROWS', 18);

function app_get_header_extra($page) {
  $css_class = 'header';
  $lower_ident = strtolower(APP_IDENT);

  $rv = PADDING_STR . SPACER_STR . PADDING_STR;

  switch ($page) {
   case $lower_ident:
   case 'data':
    $link_url = 'index.php?cmd=';
    $fatesplash = web_get_user_flag(web_get_user(), FATE_SPLASH_FLAG);
    $link_str = APP_SPLASH;
    //do the opposite for 'data' and strtolower(APP_IDENT)
    if ($fatesplash == ($page == 'data')) {
      $link_str = strtoupper($link_str);
    }
    $link_url .= TOGGLE_SPLASH_CMD . '&page=' . $page;
    $rv .= gen_link($link_url, $link_str, $css_class);
    break;

   case 'fame':
   case 'art':
    $link_text = FAME_IDENT;
    $link_url = FAME_URL;
    $rv .= gen_link($link_url, $link_text, $css_class);
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
    $lower_home = strtolower(APP_IDENT);

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

function app_get_smart_spacer() {
  $loid = strtolower(APP_IDENT);
  $rv = PADDING_STR;
  if ($GLOBALS['DATA_PAGE'] == 'fame' ||
      (!web_logged_in() &&
       $GLOBALS['DATA_PAGE'] == $loid)) {
    $rv .= gen_b(SPACER_STR);
  } else {
    $rv .= gen_link('?page=fame', gen_b(SPACER_STR));
  }
  return $rv;
}

function app_get_page_ident($page) {
  $rv = '';
  $lower_ident = strtolower(APP_IDENT);
  if ($page != $lower_ident) {
    $home_url = 'index.php?page=' . $lower_ident;
    $rv .= gen_link($home_url, APP_IDENT, 'header');
  } else {
    $rv .= gen_b(APP_IDENT);
  }
  return $rv;
}

function app_get_page_title($page) {
  util_assert($page == $GLOBALS['DATA_PAGE'], 'page != data_page');
  $rv = APP_TITLE . SPACER_STR;
  if ($page == strtolower(APP_IDENT)) {
    $rv .= 'home';
  } else {
    $rv .= $page;
  }
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
  $chat_url = '?page=' . $page;
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
